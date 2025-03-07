<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = [
        'nom',
        'description',
        'quantite',
        'prix_unitaire',
        'sold',
    ];
    public function option(){
        return $this->belongsToMany(Option::class);
    }
     /**
     * Relation avec les images.
     */
    public function image()
    {
        return $this->hasOne(Image::class);
    }
}
