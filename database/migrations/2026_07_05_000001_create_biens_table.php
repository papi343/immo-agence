<?php

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
        Schema::create('biens', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description');
            $table->enum('type', ['maison', 'appartement', 'terrain', 'local_commercial']);
            $table->enum('statut', ['a_vendre', 'a_louer', 'vendu', 'loue']);
            $table->decimal('prix', 15, 2);
            $table->decimal('surface', 8, 2);
            $table->integer('pieces')->nullable();
            $table->integer('chambres')->nullable();
            $table->integer('salles_de_bain')->nullable();
            $table->string('adresse');
            $table->string('ville');
            $table->string('code_postal');
            $table->json('features')->nullable(); // Ex: ["piscine", "garage", "jardin", "ascenseur"]
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('proprietaire_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biens');
    }
};
