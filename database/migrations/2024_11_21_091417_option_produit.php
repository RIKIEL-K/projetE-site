<?php

use App\Models\Option;
use App\Models\Produit;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('option_produit', function (Blueprint $table) {
            $table->foreignIdFor(Option::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Produit::class)->constrained()->cascadeOnDelete();
            $table->primary(['option_id','produit_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
