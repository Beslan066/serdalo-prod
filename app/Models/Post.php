<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Helpers\TextHelper;
use App;



/**
 * Post
 *
 * @mixin Eloquent
 */

class Post extends Model
{
    use HasFactory;

    protected  $fillable=[
        'type',
        'description',
        'lead',
        'title',
        'type',
        'published_at',
        'category_id',
        'author_id',
        'expert_id',
        'subtitle',
        'comment',
        'thumb_id',
        'file_id',
        'tags_old',
        'user_id',
        'source_id',
        'promote',
        'promote_with_file',
        'status',
        'slug',
    ];

    protected $guarded = false;

    protected $table = 'posts';

    protected $appends = ['title_short', 'display_published_at'];


    /*public function getFileAttribute($value)
    {
        return ($value) ? Storage::disk('public')->url($value) : $value;
    }*/
    public function getTitleAttribute($value)
    {
        return (App::getLocale() !== 'ru' && $this->relationLoaded('translation')) ? $this->translation->title ?? $value : $value;
    }
    public function getTitleShortAttribute()
    {
        return TextHelper::short($this->title);
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
        $query->select(['id', 'slug', 'status', 'promote', 'promote_with_file', 'sticky', 'title', 'comment', 'file_id', 'thumb_id', 'expert_id', 'published_at']);
    }

    public function translation()
    {
        return $this->hasOne(PostTranslation::class, 'post_id', 'id');
    }
    public function file()
    {
        return $this->hasOne(MediaFile::class, 'id', 'file_id');
    }
    public function thumb()
    {
        return $this->hasOne(MediaFile::class, 'id', 'thumb_id');
    }
    public function post_tags()
    {
        return $this->hasMany(PostTag::class, 'post_id', 'id');
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
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

    public function category()
    {
    	return $this->belongsTo(Category::class, 'category_id');
    }
    public function getWebpImageAttribute()
    {
        if (!$this->file || $this->file->type !== 'image') {
            return null;
        }

        // Ищем WebP файл по пути (альтернативный подход)
        $webpPath = pathinfo($this->file->path, PATHINFO_DIRNAME) . '/' .
            pathinfo($this->file->path, PATHINFO_FILENAME) . '.webp';

        return MediaFile::where('path', $webpPath)->first();
    }

    public function getWebpImageUrl()
    {
        if (!$this->file || $this->file->type !== 'image') {
            // Если это не изображение, возвращаем оригинальный путь
            return $this->file->full_path ?? null;
        }

        // Генерируем путь к WebP файлу на основе оригинального файла
        $originalPath = $this->file->path;
        $webpPath = pathinfo($originalPath, PATHINFO_DIRNAME) . '/' .
            pathinfo($originalPath, PATHINFO_FILENAME) . '.webp';

        // Проверяем существует ли WebP файл
        if (Storage::disk('public')->exists($webpPath)) {
            return Storage::disk('public')->url($webpPath);
        }

        // Если WebP нет, возвращаем оригинальное изображение
        return $this->file->full_preview_path ?? $this->file->full_path;
    }

// Также добавьте метод для миниатюры (thumb)
    public function getWebpThumbUrl()
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

}
