<?php

declare(strict_types=1);

use App\Models\Address;
use App\Models\CompanyDetail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(CompanyDetail::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Address::class)->constrained()->cascadeOnDelete();
            $table->string('name');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
