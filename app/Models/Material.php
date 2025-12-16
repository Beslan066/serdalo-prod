<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Helpers\TextHelper;
use App;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materials';

    protected $fillable = [
        'expert_id',
        'comment',
        'thumb_id',
    ];

    protected $appends = ['title_short', 'subtitle_short', 'display_published_at'];

    public function getTitleAttribute($value)
    {
        return (App::getLocale() !== 'ru' && $this->relationLoaded('translation')) ? $this->translation->title ?? $value : $value;
    }
    public function getTitleShortAttribute()
    {
        return TextHelper::short($this->title);
    }
    public function getSubtitleAttribute($value)
    {
        $value = (App::getLocale() !== 'ru' && $this->relationLoaded('translation')) ? $this->translation->subtitle ?? $value : $value;
        $value = strip_tags($value);
        $value = str_replace("&nbsp;", ' ', $value);
        return trim($value);
    }
    public function getSubtitleShortAttribute()
    {
        return TextHelper::short($this->subtitle);
    }
    public function getLeadAttribute($value)
    {
        $value = (App::getLocale() !== 'ru' && $this->relationLoaded('translation')) ? $this->translation->lead ?? $value : $value;
        $value = strip_tags($value);
        $value = str_replace("&nbsp;", ' ', $value);
        return trim($value);
    }
    public function getDescriptionAttribute($value)
    {
        $output = (App::getLocale() !== 'ru' && $this->relationLoaded('translation')) ? $this->translation->description ?? $value : $value;
        $output = str_replace('/sites/default/files/', 'https://serdalo.ru/sites/default/files/', $output);
        return $output;
    }
    public function getDisplayPublishedAtAttribute()
    {
        $publishedAt = Carbon::parse($this->published_at);
        if($publishedAt->isToday()) {
            $value = $publishedAt->format('H:i');
        } else {
            $value = $publishedAt->translatedFormat('j F Y, H:i');
        }
        return $value;
    }

    public function scopeMinimalSelect($query)
    {
        $query->select(['id', 'slug', 'expert_id', 'status', 'promote', 'promote_with_file', 'sticky', 'popular', 'title', 'subtitle', 'file_id', 'thumb_id', 'created_at']);
    }

    public function translation()
    {
        return $this->hasOne(MaterialTranslation::class, 'material_id', 'id');
    }
    public function file()
    {
        return $this->hasOne(MediaFile::class, 'id', 'file_id');
    }
    public function thumb()
    {
        return $this->hasOne(MediaFile::class, 'id', 'thumb_id');
    }
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
    public function author()
    {
        return $this->hasOne(Author::class, 'id', 'author_id');
    }
	public function user()
    {
        return $this->hasOne(User::class, 'id', 'expert_id');
    }

    public function expert()
    {
        return $this->hasOne(Expert::class, 'id', 'expert_id');
    }

    public function getWebpImageUrlAttribute()
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

        // Если WebP нет, возвращаем оригинальное изображение
        return $this->file->full_preview_path ?? $this->file->full_path;
    }

    public function getWebpThumbUrlAttribute()
    {
        if (!$this->thumb || $this->thumb->type !== 'image') {
            return $this->thumb->full_path ?? null;
        }

        $originalPath = $this->thumb->path;
        $webpPath = pathinfo($originalPath, PATHINFO_DIRNAME) . '/' .
            pathinfo($originalPath, PATHINFO_FILENAME) . '.webp';

        if (Storage::disk('public')->exists($webpPath)) {
            return Storage::disk('public')->url($webpPath);
        }

        return $this->thumb->full_path;
    }

    // Дополнительный метод для получения объекта WebP файла
    public function getWebpImageAttribute()
    {
        if (!$this->file || $this->file->type !== 'image') {
            return null;
        }

        $webpPath = pathinfo($this->file->path, PATHINFO_DIRNAME) . '/' .
            pathinfo($this->file->path, PATHINFO_FILENAME) . '.webp';

        return MediaFile::where('path', $webpPath)->first();
    }

    public function getWebpThumbAttribute()
    {
        if (!$this->thumb || $this->thumb->type !== 'image') {
            return null;
        }

        $webpPath = pathinfo($this->thumb->path, PATHINFO_DIRNAME) . '/' .
            pathinfo($this->thumb->path, PATHINFO_FILENAME) . '.webp';

        return MediaFile::where('path', $webpPath)->first();
    }
}
