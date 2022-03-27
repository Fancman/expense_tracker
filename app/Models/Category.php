<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

	public $timestamps = false;

	protected $fillable = [
        'name',
		'user_id',
        'icon',
    ];

	public function user()
    {
        return $this->hasOne(User::class);
    }
}
