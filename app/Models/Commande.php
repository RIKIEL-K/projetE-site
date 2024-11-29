<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commande extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'total', 'status'];

    public function items()
    {
        return $this->hasMany(CommandeProduit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
