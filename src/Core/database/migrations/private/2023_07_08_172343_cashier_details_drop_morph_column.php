<?php

/**
 * This file is part of the "cashbox/foundation" project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Andrey Helldar <helldar@dragon-code.pro>
 * @copyright 2023 Andrey Helldar
 * @license MIT
 *
 * @see https://github.com/cashbox/foundation
 */

declare(strict_types=1);

use CashierProvider\Core\Concerns\Migrations\PrivateMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

new class extends PrivateMigration {
    public function up(): void
    {
        $this->createColumn('payment_id', 'item_id');
        $this->moveData('item_id', 'payment_id');
        $this->dropColumns('item');
    }

    protected function createColumn(string $name, string $after): void
    {
        $this->connection()->table($this->table(), function (Blueprint $table) use ($name, $after) {
            $table->foreignIdFor(static::payment()->model, $name)
                ->after($after)
                ->constrained($this->primaryTable(), $this->primaryKey())
                ->cascadeOnDelete();
        });
    }

    protected function dropColumns(string $name): void
    {
        $this->connection()->table($this->table(), function (Blueprint $table) use ($name) {
            $table->dropMorphs($name);
        });
    }

    protected function moveData(string $from, string $to): void
    {
        DB::connection(self::details()->connection)
            ->table(self::details()->table)
            ->update([$to => DB::raw($from)]);
    }
};
