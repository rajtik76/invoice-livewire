<?php

declare(strict_types=1);

use App\Models\Contract;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Contract::class)->constrained()->cascadeOnDelete();
            $table->bigInteger('number');
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');
            $table->date('issue_date');
            $table->date('due_date');
            $table->longText('content');
            $table->float('price_per_unit');
            $table->float('total_amount');
            $table->string('currency');

            $table->unique(['contract_id', 'number']);

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
        Schema::dropIfExists('invoices');
    }
};
