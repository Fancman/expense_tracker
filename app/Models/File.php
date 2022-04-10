<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Transaction;

class File extends Model
{
    use HasFactory;

	/**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'filename',
        'transaction_id',
    ];


    public function currency()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

	public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
