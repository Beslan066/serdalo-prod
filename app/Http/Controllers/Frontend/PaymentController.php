<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

use App;
use App\Models\User;
use App\Models\Post;
use App\Models\Material;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Author;
use App\Models\MediaFile;
use App\Models\Payment;
use App\Models\PaymentResult;
use App\Models\Newspaper;
use Carbon\Carbon;

class PaymentController extends Controller
{
    protected $is_default_locale = true;
    
    
    public function paymentPage()
    {
        $user_id = auth()->id();
        $payments = [];
        if($user_id) {
            $payments = Payment::where('user_id', $user_id)->orderBy('id', 'desc')->get();
        }
        $active_subscribe = null;
        $next_active_subscribe = null;
        $subscribes = [];
        $now = Carbon::now();
        foreach ($payments as $payment) {
            if(!$payment->subrscribe_start_at || !$payment->subrscribe_start_at) {
                continue;
            }
            $subscribes[] = $payment;
            if($now->diffInDays($payment->subrscribe_end_at, false) < 0) {
                continue;
            }
            if($now->diffInDays($payment->subrscribe_start_at, false) <= 0) {
                $active_subscribe = $payment;
            } else {
                $next_active_subscribe = $payment;
            }
        }
        
        $newspapers = [];
        if($active_subscribe) {
            $newspapers = Newspaper::with('file')->where('status', 1)->get();
        }
        
        $tariffs = $this->tariffs();
        
        $categories = Category::all();
        
        if(session('frontend_version') != 'v1') {
            $view = 'frontend.v3.payment.payment';
        } else {
            $view = 'frontend.payment.payment';
        }
        return view($view, [
            'categories' => $categories,
            'active_subscribe' => $active_subscribe,
            'next_active_subscribe' => $next_active_subscribe,
            'subscribes' => $subscribes,
            'tariffs' => $tariffs,
            'newspapers' => $newspapers,
        ]);
    }
    public function paymentResult(Request $request)
    {
        $payment_result = new PaymentResult();
        $payment_result->data = json_encode($request->all());
        $payment_result->save();
        
        $secret = env('YOOMONEY_SECRET');
        $sha1 = sha1(
            $request->notification_type . '&' . $request->operation_id . '&' . $request->amount . '&' . 
            $request->currency . '&' . $request->datetime . '&' . $request->sender . '&' . 
            $request->codepro . '&' . $secret . '&' . $request->label
        );
        if($sha1 != $request->sha1_hash) {
            $payment_result = new PaymentResult();
            $payment_result->data = 'Верификация не пройдена. SHA1_HASH не совпадает.';
            $payment_result->save();
            return abort(403, 'Верификация не пройдена. SHA1_HASH не совпадает.');
        }
        
        $tariffs = $this->tariffs();
        
        if($request->label) {
            $tariff_key = (string)(int)($request->withdraw_amount ?? null);
            $tariff = $tariffs[$tariff_key] ?? [];
            $payment = new Payment();
            $payment->user_id = $request->label;
            $payment->amount = $request->withdraw_amount;
            $payment->tariff = $tariff['tariff'] ?? null;
            $payment->subrscribe_start_at = $tariff['db_start_at'] ?? null;
            $payment->subrscribe_end_at = $tariff['db_end_at'] ?? null;
            $payment->save();
        }
        
        return true;
    }
    public function success(Request $request)
    {
        return 'success';
    }
    protected function tariffs()
    {
        $format = 'd.m.Y';
        $now = Carbon::now();
        $tariffs_types = [
            '2' => '1',
            '3' => '3',
            '6' => '6',
            '1200' => '12',
        ];
        $tariffs = [];
        foreach ($tariffs_types as $tariff_type_amount => $tariff_type_months) {
            if($now->day <= 10) {
                $start_at = Carbon::now()->startOfMonth();
                $end_at = Carbon::now()->addMonths($tariff_type_months)->startOfMonth();
            } else {
                $start_at = Carbon::now()->addMonths(1)->startOfMonth();
                $end_at = Carbon::now()->addMonths(1)->addMonths($tariff_type_months)->startOfMonth();
            }
            $tariffs[$tariff_type_amount] = [
                'amount' => $tariff_type_amount,
                'tariff' => $tariff_type_months,
                'start_at' => $start_at->format($format),
                'db_start_at' => $start_at->format('Y-m_d'),
                'end_at' => $end_at->format($format),
                'db_end_at' => $end_at->format('Y-m_d'),
                'label' => $tariff_type_months . ' ' . trans_choice('месяц|месяца|месяцев', $tariff_type_months) 
                    . ' (' . $start_at->format($format) . ' - ' . $end_at->format($format) . ')',
            ];
        }
        return $tariffs;
    }
}
