<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doc', function (Blueprint $table) {
            $table->integer('id',true);
            $table->text('title')->nullable(false);
            $table->text('description')->nullable(true);
            $table->string('unique_id')->nullable(false);
            $table->integer('parent')->nullable(true);
            $table->integer('left')->default(0);
            $table->integer('right')->default(0);
            $table->integer('depth')->default(0);
            $table->integer('order');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('doc_change_logs', function (Blueprint $table) {
            $table->integer('id',true);
            $table->string('version')->nullable(false);
            $table->enum('stability',['dev','alpha','beta','rc','stable']);
            $table->text('description')->nullable(true);
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->softDeletes();
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
        Schema::dropIfExists('doc');
        Schema::dropIfExists('doc_change_logs');
    }
}
