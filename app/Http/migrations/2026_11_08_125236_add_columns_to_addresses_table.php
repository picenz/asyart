<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
            $table->string('recipient_name')->after('user_id');
            $table->string('label', 50)->default('Home')->after('recipient_name');
            $table->string('phone', 20)->after('label');
            $table->text('address')->after('phone');
            $table->string('city', 100)->after('address');
            $table->string('province', 100)->after('city');
            $table->string('postal_code', 10)->after('province');
            $table->string('country', 100)->default('Indonesia')->after('postal_code');
            $table->boolean('is_default')->default(false)->after('country');
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'recipient_name',
                'label',
                'phone',
                'address',
                'city',
                'province',
                'postal_code',
                'country',
                'is_default',
            ]);
        });
    }
};
