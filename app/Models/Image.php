<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;
    // Spécifiez le nom de la table
    protected $table = 'image_produit';
    /**
     * Relation avec le produit.
     */
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

}
