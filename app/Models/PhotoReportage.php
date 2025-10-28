<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PhotoReportage extends Model
{
    use HasFactory;

    protected $table = 'photo_reportages';

    // Добавьте fillable для массового присвоения
    protected $fillable = [
        'title',
        'lead',
        'description',
        'file_id',
        'published_at'
    ];

    // Добавьте appends если нужно
    protected $appends = ['display_published_at'];

    public function minimalSelect()
    {
        return $this->select('id', 'published_at', 'title', 'file_id');
    }

    public function file()
    {
        return $this->hasOne(MediaFile::class, 'id', 'file_id');
    }

    public function translation()
    {
        return $this->hasOne(PhotoReportageTranslate::class, 'post_id', 'id');
    }

    // Добавьте методы для WebP
    public function getWebpImageUrl()
    {
        if (!$this->file || $this->file->type !== 'image') {
            return $this->file->full_path ?? null;
        }

        $originalPath = $this->file->path;
        $webpPath = pathinfo($originalPath, PATHINFO_DIRNAME) . '/' .
            pathinfo($originalPath, PATHINFO_FILENAME) . '.webp';

        if (Storage::disk('public')->exists($webpPath)) {
            return Storage::disk('public')->url($webpPath);
        }

        return $this->file->full_preview_path ?? $this->file->full_path;
    }

    public function getWebpThumbUrl()
    {
        // Если у фоторепортажей есть thumb, иначе используем основной файл
        return $this->getWebpImageUrl();
    }

    public function getDisplayPublishedAtAttribute()
    {
        if (!$this->published_at) {
            return null;
        }

        $publishedAt = Carbon::parse($this->published_at);
        if($publishedAt->isToday()) {
            $value = $publishedAt->format('H:i');
        } else {
            $value = $publishedAt->translatedFormat('j F Y, H:i');
        }
        return $value;
    }
}
