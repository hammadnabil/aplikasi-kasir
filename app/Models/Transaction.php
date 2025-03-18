<?php

namespace App\Models;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'transaction_code', 'total', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


protected static function boot()
{
    parent::boot();
    static::creating(function ($transaction) {
        $transaction->transaction_code = 'TXN-' . strtoupper(Str::random(8));
    });
}

}
