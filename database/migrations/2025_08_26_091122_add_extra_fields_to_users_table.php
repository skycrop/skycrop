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
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code')->nullable()->after('remember_token');
            $table->integer('reward_point')->default(0)->after('referral_code');
            
            $table->string('company_logo')->nullable()->after('reward_point');
            $table->text('company_address')->nullable()->after('company_logo');
            $table->string('company_contact')->nullable()->after('company_address');
            
            $table->unsignedInteger('total_reviews')->default(0)->after('company_contact');        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'referral_code',
                'reward_point',
                'company_logo',
                'company_address',
                'company_contact',
                'total_reviews',
            ]);
        });
    }
};
