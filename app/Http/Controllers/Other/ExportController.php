<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use App\Models\Post;
use League\Csv\Writer;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    public function exportPostsWithTranslations()
    {
        try {
            // Устанавливаем необходимые заголовки
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="posts_translations_'.date('Y-m-d').'.csv"',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ];

            // Создаем CSV
            $csv = Writer::createFromString('');
            $csv->setDelimiter(';');
            $csv->insertOne(['ID', 'Оригинал', 'Перевод']);

            // Обрабатываем данные порциями
            Post::with(['translation'])
                ->whereHas('translation')
                ->chunk(200, function($posts) use ($csv) {
                    foreach ($posts as $post) {
                        $original = $this->formatPostText(
                            $post->getRawOriginal('title'),
                            $post->getRawOriginal('lead'),
                            $post->getRawOriginal('description')
                        );
                        
                        $translation = $this->formatPostText(
                            $post->translation->title ?? null,
                            $post->translation->lead ?? null,
                            $post->translation->description ?? null
                        );

                        $csv->insertOne([
                            $post->id,
                            $original,
                            $translation
                        ]);
                    }
                });

            // Возвращаем ответ
            return Response::make($csv->toString(), 200, $headers);

        } catch (\Exception $e) {
            // Логируем ошибку
            \Log::error('Export error: '.$e->getMessage());
            
            // Возвращаем понятную ошибку
            return response()->json([
                'error' => 'Произошла ошибка при генерации отчета',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function formatPostText($title, $lead, $description)
    {
        $parts = array_filter([
            'Заголовок: ' . $this->cleanText($title),
            'Лид: ' . $this->cleanText($lead),
            'Описание: ' . $this->cleanText($description)
        ], function($item) {
            return !empty(trim(str_replace(['Заголовок:', 'Лид:', 'Описание:'], '', $item)));
        });

        return implode("\n\n", $parts);
    }

    private function cleanText($text)
    {
        if (empty($text)) return '';
        
        $text = html_entity_decode($text);
        $text = strip_tags($text);
        $text = str_replace(["\r\n", "\r", "\n", "\t"], ' ', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }
}
