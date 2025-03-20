<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'condition', 'brand', 'detail', 'price', 'image'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categoryItem()
    {
        return $this->belongsTo(CategoryItem::class);
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
