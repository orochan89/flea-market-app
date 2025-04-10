<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'name', 'condition', 'brand', 'detail', 'price', 'image', 'is_sold'];


    public function getConditionLabelAttribute()
    {
        $labels = [
            'good' => '良好',
            'better' => '良い',
            'worth' => '悪い',
            'worst' => '最悪'
        ];

        return $labels[$this->condition] ?? '不明';
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
