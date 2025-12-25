<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'video',
        'price',
        'live_class',
        'live_class_link',
        'status',
        'category_id',
    ];

    protected $casts = [
        'live_class' => 'boolean',
        'status'     => 'boolean',
        'price'      => 'decimal:2',
    ];

    /* ===============================
     |  RELATIONSHIPS
     =============================== */

    /**
     * All materials
     */
    public function materials()
    {
        return $this->hasMany(CourseMaterial::class);
    }

    /**
     * ONLY VIDEO MATERIALS
     */
    public function videoMaterials()
    {
        return $this->hasMany(CourseMaterial::class)->where('material_type', 'video');
    }

    /**
     * ONLY PDF MATERIALS
     */
    public function pdfMaterials()
    {
        return $this->hasMany(CourseMaterial::class)->where('material_type', 'pdf');
    }

    /**
     * ONLY IMAGE MATERIALS
     */
    public function imageMaterials()
    {
        return $this->hasMany(CourseMaterial::class)->where('material_type', 'image');
    }

    /**
     * ONLY URL/LINK MATERIALS
     */
    public function linkMaterials()
    {
        return $this->hasMany(CourseMaterial::class)->where('material_type', 'url');
    }

    /**
     * Lectures count accessor (for compatibility)
     */
    public function getLecturesCountAttribute()
    {
        return $this->videoMaterials()->count();
    }

    /**
     * Video count accessor
     */
    public function getVideoCountAttribute()
    {
        return $this->attributes['video_materials_count'] ?? $this->videoMaterials()->count();
    }

    /**
     * PDF count accessor
     */
    public function getPdfCountAttribute()
    {
        return $this->attributes['pdf_materials_count'] ?? $this->pdfMaterials()->count();
    }

    /**
     * Image count accessor
     */
    public function getImageCountAttribute()
    {
        return $this->attributes['image_materials_count'] ?? $this->imageMaterials()->count();
    }

    /**
     * Link count accessor
     */
    public function getLinkCountAttribute()
    {
        return $this->attributes['link_materials_count'] ?? $this->linkMaterials()->count();
    }

    /* ===============================
     |  OTHER RELATIONS
     =============================== */

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'course_user')
            ->withPivot('status', 'enrolled_at')
            ->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
