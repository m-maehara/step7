<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function purchase(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        try {
            DB::beginTransaction();

            $product = Product::lockForUpdate()->findOrFail($productId);

            if ($product->stock < $quantity) {
                throw new \Exception('Not enough stock available');
            }

            $product->stock -= $quantity;
            $product->save();

            Sale::create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'sale_date' => now(),
            ]);

            DB::commit();

            return response()->json(['message' => 'Purchase successful'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}