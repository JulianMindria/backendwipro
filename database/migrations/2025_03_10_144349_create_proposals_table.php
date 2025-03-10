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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->string('proposal_name');
            $table->string('proposal_objective');
            $table->timestamp('proposal_realization');
            $table->string('proposal_budget');
            $table->text('proposal_file');
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');
            $table->unsignedBigInteger('proposal_approver')->nullable();
            $table->unsignedBigInteger('proposal_initiator');
            $table->text('content');
            $table->timestamps();

            $table->foreign('proposal_approver')->references('id')->on('users')->onDelete('set null');
            $table->foreign('proposal_initiator')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
