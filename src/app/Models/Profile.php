<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'postcode', 'address', 'building', 'image'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isComplete()
    {
        return !empty($this->postcode) && !empty($this->address);
    }
}
