<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //Clear table data
        DB::table('job_listings')->truncate();
        Schema::table('job_listings', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id');

            $table->integer('salary');
            $table->string('tags')->nullable();
            $table->enum('job_type', ['Full-time', 'Part-time', 'Freelance', 'Contract',
                'Temporary', 'Internship', 'On-Call'])->default('Full-time');
            $table->boolean('remote')->default(false);
            $table->text('requirements')->nullable();
            $table->text('benefits')->nullable();
            $table->string('address')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip-code')->nullable();
            $table->string('contact-email');
            $table->string('contact-phone')->nullable();
            $table->string('company-name');
            $table->string('company-description')->nullable();
            $table->string('company-logo')->nullable();
            $table->string('company-website')->nullable();

            //Add user foreign key constraint
            $table->foreign('user_id')->references('id')->
            on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropForeign(['user-id']);
            $table->dropColumn('user_id');

            $table->dropColumn('salary', 'tags', 'job_type', 'remote',
                'address', 'city', 'state', 'zip-code', 'contract-email',
            'contact-phone', 'company-name', 'company-description', 'company-logo','company-website');
        });
    }
};
