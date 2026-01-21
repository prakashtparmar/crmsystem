<?php

namespace App\Services;

use App\Models\ProductStock;
use App\Models\StockMovement;

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
        $stock = ProductStock::firstOrCreate(
            ['product_id' => $productId, 'warehouse_id' => $warehouseId],
            ['quantity' => 0, 'reserved_qty' => 0]
        );

        $newQty = match ($type) {
            'in', 'transfer_in'   => $stock->quantity + $qty,
            'out', 'transfer_out' => $stock->quantity - $qty,
            'adjust'              => $stock->quantity + $qty,
            default               => throw new \Exception("Invalid stock type"),
        };

        if ($newQty < 0) {
            throw new \Exception('Insufficient stock');
        }

        $stock->quantity = $newQty;
        $stock->save();

        StockMovement::create([
            'product_id'     => $productId,
            'warehouse_id'   => $warehouseId,
            'type'           => $type,
            'quantity'       => $qty,
            'balance_after'  => $newQty,
            'reference_type' => $refType,
            'reference_id'   => $refId,
            'remarks'        => $remarks,
        ]);

        return $stock;
    }

    public function reserveFromAnyWarehouse(
        int $productId,
        float $qty,
        ?string $referenceType = null,
        ?int $referenceId = null
    ) {
        $remaining = $qty;

        $stocks = ProductStock::where('product_id', $productId)
            ->whereRaw('(quantity - reserved_qty) > 0')
            ->orderBy('warehouse_id')
            ->lockForUpdate()
            ->get();

        foreach ($stocks as $stock) {
            if ($remaining <= 0) break;

            $available = $stock->quantity - $stock->reserved_qty;
            $take = min($available, $remaining);

            $stock->reserved_qty += $take;
            $stock->save();

            StockMovement::create([
                'product_id'     => $productId,
                'warehouse_id'   => $stock->warehouse_id,
                'type'           => 'reserve',
                'quantity'       => $take,
                'balance_after'  => $stock->quantity - $stock->reserved_qty,
                'reference_type' => $referenceType,
                'reference_id'   => $referenceId,
                'remarks'        => 'Stock reserved',
            ]);

            $remaining -= $take;
        }

        if ($remaining > 0) {
            throw new \Exception('Not enough available stock');
        }

        return true;
    }

    public function releaseFromAnyWarehouse(
        int $productId,
        float $qty,
        ?string $referenceType = null,
        ?int $referenceId = null
    ) {
        $remaining = $qty;

        $stocks = ProductStock::where('product_id', $productId)
            ->where('reserved_qty', '>', 0)
            ->orderBy('warehouse_id')
            ->lockForUpdate()
            ->get();

        foreach ($stocks as $stock) {
            if ($remaining <= 0) break;

            $take = min($stock->reserved_qty, $remaining);

            $stock->reserved_qty -= $take;
            $stock->save();

            StockMovement::create([
                'product_id'     => $productId,
                'warehouse_id'   => $stock->warehouse_id,
                'type'           => 'release',
                'quantity'       => $take,
                'balance_after'  => $stock->quantity - $stock->reserved_qty,
                'reference_type' => $referenceType,
                'reference_id'   => $referenceId,
                'remarks'        => 'Stock released',
            ]);

            $remaining -= $take;
        }

        if ($remaining > 0) {
            throw new \Exception('Not enough reserved stock to release');
        }

        return true;
    }

    public function shipFromReserved(
        int $productId,
        float $qty,
        ?string $referenceType = null,
        ?int $referenceId = null
    ) {
        $remaining = $qty;

        $stocks = ProductStock::where('product_id', $productId)
            ->where('reserved_qty', '>', 0)
            ->orderBy('warehouse_id')
            ->lockForUpdate()
            ->get();

        foreach ($stocks as $stock) {
            if ($remaining <= 0) break;

            $take = min($stock->reserved_qty, $remaining);

            $stock->quantity     -= $take;
            $stock->reserved_qty -= $take;
            $stock->save();

            StockMovement::create([
                'product_id'     => $productId,
                'warehouse_id'   => $stock->warehouse_id,
                'type'           => 'out',
                'quantity'       => $take,
                'balance_after'  => $stock->quantity,
                'reference_type' => $referenceType,
                'reference_id'   => $referenceId,
                'remarks'        => 'Order shipped',
            ]);

            $remaining -= $take;
        }

        if ($remaining > 0) {
            throw new \Exception('Not enough reserved stock to ship');
        }

        return true;
    }
}
