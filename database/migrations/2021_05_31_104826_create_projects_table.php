<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('registration_number')->unique();
            $table->string('student');
            $table->text('theme');
            $table->foreignId('supervisor_id')->constrained()
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('group_id')->constrained()
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('project_type_id')->constrained()
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->dateTime('registered_at')->default(now());
            $table->dateTime('defended_at')->default(now());
            $table->integer('grade')->default(60);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
