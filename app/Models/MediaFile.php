<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MediaFile extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $appends = ['storage_path', 'full_path', 'full_preview_path', 'icon', 'show_icon', 'size_display'];

    public function getTypeAttribute($value)
    {
        if($this->source_id) {
            if($this->mime === 'image/jpeg') {
                return 'image';
            }
        }
        return $value;
    }
    public function getExtensionAttribute($value)
    {
        if($this->source_id) {
            if($this->mime === 'image/jpeg') {
                return 'jpg';
            }
        }
        return $value;
    }
    public function getPathAttribute($value)
    {
        return str_replace('/sites/deault/files/', '/sites/default/files/', $value);
    }
    public function getStoragePathAttribute()
    {
        if($this->source_id) {
            return 'https://serdalo.ru' . $this->path;
            return $this->path;
        }
        return '/storage/' . $this->path;
    }
    public function getFullPathAttribute()
    {
        if($this->source_id) {
            return 'https://serdalo.ru' . $this->path;
            return Storage::disk('public-old')->url($this->path);
        }
        return Storage::disk('public')->url($this->path);
    }
    public function getFullPreviewPathAttribute()
    {
        if($this->source_id) {
            return 'https://serdalo.ru' . $this->path;
            return Storage::disk('public-old')->url($this->path);
            return Storage::disk('public-old')->url($this->preview);
        }
        if($this->type === 'image') {
            return Storage::disk('public')->url($this->path);
            return Storage::disk('public')->url($this->preview);
        }
        if($this->type === 'application' && $this->extension === 'pdf') {
            return asset('images/file-icons/pdf.png');
        }
        return asset('images/file-icons/' . $this->type . '.png');
    }
    public function getShowIconAttribute()
    {
        if($this->type === 'image') {
            return false;
        }
        return true;
    }
    public function getIconAttribute()
    {
        if($this->show_icon) {
            if($this->type === 'application' && $this->extension === 'pdf') {
                return asset('images/file-icons/pdf.png');
            }
            return asset('images/file-icons/' . $this->type . '.png');
        }
        return null;
    }
    public function getSizeDisplayAttribute()
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        if($this->size) {
            $bytes = $this->size; 
            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);
            return round($bytes / pow(1024, $pow), 2) . ' ' . $units[$pow];
        }
        return null;
    }
}
