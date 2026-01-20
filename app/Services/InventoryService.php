<?php

namespace App\Services;

use App\Models\ProductStock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function move(
        int $productId,
        int $warehouseId,
        float $qty,
        string $type,
        ?string $refType = null,
        ?int $refId = null,
        ?string $remarks = null
    ) {
        return DB::transaction(function () use ($productId, $warehouseId, $qty, $type, $refType, $refId, $remarks) {
            $stock = ProductStock::firstOrCreate([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
            ], [
                'quantity' => 0,
                'reserved_qty' => 0,
            ]);

            $newQty = match ($type) {
                'in', 'transfer_in' => $stock->quantity + $qty,
                'out', 'transfer_out' => $stock->quantity - $qty,
                'adjust' => $stock->quantity + $qty,
                default => throw new \Exception("Invalid stock type"),
            };

            if ($newQty < 0) {
                throw new \Exception('Insufficient stock');
            }

            StockMovement::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'type' => $type,
                'quantity' => $qty,
                'balance_after' => $newQty,
                'reference_type' => $refType,
                'reference_id' => $refId,
                'remarks' => $remarks,
            ]);

            $stock->update([
                'quantity' => $newQty,
            ]);

            return $stock;
        });
    }

    /**
     * Reserve stock by product & warehouse (legacy-compatible)
     */
    public function reserve(
        int $productId,
        int $warehouseId,
        float $qty,
        ?string $referenceType = null,
        ?int $referenceId = null
    ) {
        return DB::transaction(function () use ($productId, $warehouseId, $qty, $referenceType, $referenceId) {
            $stock = ProductStock::firstOrCreate([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
            ], [
                'quantity' => 0,
                'reserved_qty' => 0,
            ]);

            $available = $stock->quantity - $stock->reserved_qty;

            if ($available < $qty) {
                throw new \Exception('Not enough available stock');
            }

            $stock->increment('reserved_qty', $qty);

            StockMovement::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'type' => 'reserve',
                'quantity' => $qty,
                'balance_after' => $stock->quantity - $stock->reserved_qty,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'remarks' => 'Stock reserved',
            ]);

            return $stock;
        });
    }

    /**
     * Enterprise: reserve from any warehouse that has stock
     * (prevents hard-coded warehouse issues)
     */
    public function reserveFromAnyWarehouse(
        int $productId,
        float $qty,
        ?string $referenceType = null,
        ?int $referenceId = null
    ) {
        return DB::transaction(function () use ($productId, $qty, $referenceType, $referenceId) {

            $remaining = $qty;

            $stocks = ProductStock::where('product_id', $productId)
                ->whereRaw('(quantity - reserved_qty) > 0')
                ->orderBy('warehouse_id')
                ->lockForUpdate()
                ->get();

            foreach ($stocks as $stock) {
                if ($remaining <= 0)
                    break;

                $available = $stock->quantity - $stock->reserved_qty;
                $take = min($available, $remaining);

                $stock->increment('reserved_qty', $take);

                StockMovement::create([
                    'product_id' => $productId,
                    'warehouse_id' => $stock->warehouse_id,
                    'type' => 'reserve',
                    'quantity' => $take,
                    'balance_after' => $stock->quantity - $stock->reserved_qty,
                    'reference_type' => $referenceType,
                    'reference_id' => $referenceId,
                    'remarks' => 'Stock reserved',
                ]);

                $remaining -= $take;
            }

            if ($remaining > 0) {
                throw new \Exception('Not enough available stock');
            }

            return true;
        });
    }

    /**
     * Release reserved stock from any warehouse (multi-warehouse safe)
     */
    public function releaseFromAnyWarehouse(
        int $productId,
        float $qty,
        ?string $referenceType = null,
        ?int $referenceId = null
    ) {
        return DB::transaction(function () use ($productId, $qty, $referenceType, $referenceId) {

            $remaining = $qty;

            $stocks = ProductStock::where('product_id', $productId)
                ->where('reserved_qty', '>', 0)
                ->orderBy('warehouse_id')
                ->lockForUpdate()
                ->get();

            foreach ($stocks as $stock) {
                if ($remaining <= 0)
                    break;

                $take = min($stock->reserved_qty, $remaining);

                $stock->decrement('reserved_qty', $take);

                StockMovement::create([
                    'product_id' => $productId,
                    'warehouse_id' => $stock->warehouse_id,
                    'type' => 'release',
                    'quantity' => $take,
                    'balance_after' => $stock->quantity - $stock->reserved_qty,
                    'reference_type' => $referenceType,
                    'reference_id' => $referenceId,
                    'remarks' => 'Stock released',
                ]);

                $remaining -= $take;
            }

            if ($remaining > 0) {
                throw new \Exception('Not enough reserved stock to release');
            }

            return true;
        });
    }

    /**
     * Convert reserved stock into OUT movement (ship order)
     */
    public function shipFromReserved(
        int $productId,
        float $qty,
        ?string $referenceType = null,
        ?int $referenceId = null
    ) {
        return DB::transaction(function () use ($productId, $qty, $referenceType, $referenceId) {

            $remaining = $qty;

            $stocks = ProductStock::where('product_id', $productId)
                ->where('reserved_qty', '>', 0)
                ->orderBy('warehouse_id')
                ->lockForUpdate()
                ->get();

            foreach ($stocks as $stock) {
                if ($remaining <= 0)
                    break;

                $take = min($stock->reserved_qty, $remaining);

                // reduce physical stock
                $stock->decrement('quantity', $take);
                // reduce reserved stock
                $stock->decrement('reserved_qty', $take);

                StockMovement::create([
                    'product_id' => $productId,
                    'warehouse_id' => $stock->warehouse_id,
                    'type' => 'out',
                    'quantity' => $take,
                    'balance_after' => $stock->quantity,
                    'reference_type' => $referenceType,
                    'reference_id' => $referenceId,
                    'remarks' => 'Order shipped',
                ]);

                $remaining -= $take;
            }

            if ($remaining > 0) {
                throw new \Exception('Not enough reserved stock to ship');
            }

            return true;
        });
    }



    public function release(
        int $productId,
        int $warehouseId,
        float $qty,
        ?string $referenceType = null,
        ?int $referenceId = null
    ) {
        return DB::transaction(function () use ($productId, $warehouseId, $qty, $referenceType, $referenceId) {
            $stock = ProductStock::where([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
            ])->firstOrFail();

            $stock->decrement('reserved_qty', $qty);

            StockMovement::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'type' => 'release',
                'quantity' => $qty,
                'balance_after' => $stock->quantity - $stock->reserved_qty,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'remarks' => 'Stock released',
            ]);

            return $stock;
        });
    }
}
