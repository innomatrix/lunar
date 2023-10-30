<?php

namespace Lunar\Database\State;

use Illuminate\Support\Facades\Schema;
use Lunar\Base\ValueObjects\Cart\TaxBreakdownAmount;
use Lunar\DataTypes\Price;
use Lunar\Models\Order;
use Lunar\Models\OrderLine;

class ConvertTaxbreakdown
{
    public function prepare()
    {
        //
    }

    public function run()
    {
        if (! $this->canRun()) {
            return;
        }

        Order::chunk(500, function ($orders) {
           foreach ($orders as $order) {
               // Get the raw tax_breakdown
               $breakdown = json_decode($order->getRawOriginal('tax_breakdown'), true);
               $amounts = collect($breakdown)->map(function ($row) use ($order) {
                  return new TaxBreakdownAmount(
                      price: new Price($row['total'], $order->currency),
                      identifier: $row['identifier'] ?? $row['description'],
                      description: $row['description'],
                      percentage: $row['percentage'],
                  );
               });

               $order->updateQuietly([
                   'tax_breakdown' => new \Lunar\Base\ValueObjects\Cart\TaxBreakdown($amounts),
               ]);
           }
        });

        OrderLine::chunk(500, function ($orderLines) {
            foreach ($orderLines as $orderLine) {
                // Get the raw tax_breakdown
                $breakdown = json_decode($orderLine->getRawOriginal('tax_breakdown'), true);
                
                $amounts = collect($breakdown)->map(function ($row) use ($orderLine) {
                    return new TaxBreakdownAmount(
                        price: new Price($row['total'], $orderLine->order->currency),
                        identifier: $row['identifier'] ?? $row['description'],
                        description: $row['description'],
                        percentage: $row['percentage'],
                    );
                });

                $orderLine->updateQuietly([
                    'tax_breakdown' => new \Lunar\Base\ValueObjects\Cart\TaxBreakdown($amounts),
                ]);
            }
        });
    }

    protected function canRun()
    {
        $prefix = config('lunar.database.table_prefix');

        $hasSchema = Schema::hasTable("{$prefix}orders") && Schema::hasTable("{$prefix}order_lines");

        if (!$hasSchema) {
            return false;
        }

        // Grab an order and determine whether the tax breakdown has already been converted.
        // This will save us having to run the command and check each order.
        $order = Order::first();

        if (!$order) {
            return false;
        }

        $breakdownItem = json_decode($order->getRawOriginal('tax_breakdown'), true)[0] ?? null;

        if (!$breakdownItem) {
            return false;
        }

        return $breakdownItem['total'] ?? false;
    }
}