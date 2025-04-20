<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'name', 'condition', 'brand', 'detail', 'price', 'image', 'is_sold'];

    public function getConditionLabelAttribute()
    {
        $labels = [
            0 => '良好',
            1 => '目立った傷や汚れなし',
            2 => 'やや傷や汚れあり',
            3 => '状態が悪い'
        ];

        return $labels[$this->condition] ?? '不明';
    }

    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('brand', 'like', "%{$keyword}%")
                    ->orWhere('detail', 'like', "%{$keyword}%")
                    ->orWhereHas('categories', function ($q2) use ($keyword) {
                        $q2->where('category', 'like', "%{$keyword}%");
                    });
            });
        }

        return $query;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item', 'item_id', 'category_id')->using(CategoryItem::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
