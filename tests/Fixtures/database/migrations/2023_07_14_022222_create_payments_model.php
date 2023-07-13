<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\Fixtures\Enums\StatusEnum;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->double('price', 10, 2);

            $table->string('type');
            $table->smallInteger('status')->default(StatusEnum::new->value);

            $table->timestamps();
        });
    }
};
