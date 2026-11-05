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
        Schema::create('rollen', function (Blueprint $table) {
            $table->id();
            $table->string('rolnaam');
        });

        Schema::create('filialen', function (Blueprint $table) {
            $table->id();
            $table->string('naam');
            $table->string('adres');
            $table->string('stad');
        });

        Schema::create('producten', function (Blueprint $table) {
            $table->id();
            $table->string('productnummer')->unique();
            $table->string('naam');
            $table->decimal('verkoopprijs', 10, 2);
            $table->decimal('inkoopprijs', 10, 2);
            $table->string('afbeelding')->nullable();
            $table->text('beschrijving')->nullable();
        });

        Schema::create('distributeurs', function (Blueprint $table) {
            $table->id();
            $table->string('naam');
            $table->text('contactgegevens');
        });

        Schema::create('klanten', function (Blueprint $table) {
            $table->id();
            $table->string('naam');
            $table->text('contactgegevens');
            $table->string('email');
        });

        Schema::create('gebruikers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rol_id')->constrained('rollen');
            $table->foreignId('filiaal_id')->constrained('filialen');
            $table->string('naam');
            $table->string('email')->unique();
            $table->string('wachtwoord');
            $table->string('status');
        });

        Schema::create('voorraden', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filiaal_id')->constrained('filialen');
            $table->foreignId('product_id')->constrained('producten');
            $table->unsignedInteger('aantal');
            $table->unsignedInteger('minimum_voorraad');
            $table->string('status');
            $table->unique(['filiaal_id', 'product_id']);
        });

        Schema::create('bestellingen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filiaal_id')->constrained('filialen');
            $table->foreignId('gebruiker_id')->constrained('gebruikers');
            $table->foreignId('distributeur_id')->constrained('distributeurs');
            $table->dateTime('besteldatum');
            $table->dateTime('verzendmoment')->nullable();
            $table->string('status');
        });

        Schema::create('bestelregels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bestelling_id')->constrained('bestellingen');
            $table->foreignId('product_id')->constrained('producten');
            $table->unsignedInteger('aantal');
            $table->unique(['bestelling_id', 'product_id']);
        });

        Schema::create('klachten', function (Blueprint $table) {
            $table->id();
            $table->foreignId('klant_id')->constrained('klanten');
            $table->foreignId('filiaal_id')->constrained('filialen');
            $table->foreignId('gebruiker_id')->constrained('gebruikers');
            $table->text('omschrijving');
            $table->dateTime('datum');
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('klachten');
        Schema::dropIfExists('bestelregels');
        Schema::dropIfExists('bestellingen');
        Schema::dropIfExists('voorraden');
        Schema::dropIfExists('gebruikers');
        Schema::dropIfExists('klanten');
        Schema::dropIfExists('distributeurs');
        Schema::dropIfExists('producten');
        Schema::dropIfExists('filialen');
        Schema::dropIfExists('rollen');
    }
};
