<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('themes', function (Blueprint $table) {
            if (DB::connection()->getDriverName() === 'pgsql') {
                DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
            }
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->string('title')->index();
            $table->string('description')->nullable();
            $table->foreignUuid('user_id')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('themes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('themes');
    }
};
