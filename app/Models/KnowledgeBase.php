<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KnowledgeBase extends Model
{
    use HasFactory;

    protected $table = 'knowledge_base';

    protected $fillable = [
        'title',
        'content',
        'category',
        'slug',
        'published',
    ];

    protected function casts(): array
    {
        return [
            'published' => 'boolean',
        ];
    }

    /**
     * Auto-generate slug from title when creating.
     */
    protected static function booted(): void
    {
        static::creating(function (KnowledgeBase $article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    /**
     * Scope: only published articles.
     */
    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    /**
     * Scope: filter by category.
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get the route key name for URL binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
