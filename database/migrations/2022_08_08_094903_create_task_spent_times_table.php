<?php

declare(strict_types=1);

use App\Models\Task;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::create('task_spent_times', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Task::class)->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->float('time');
            $table->string('note')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('time_spent_times');
    }
};
