<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

	public function user()
    {
        return $this->hasOne(User::class);
    }

	public function category()
    {
        return $this->hasOne(Category::class);
    }
}
