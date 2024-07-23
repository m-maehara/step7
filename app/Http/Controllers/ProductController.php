<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use App\Models\Sale;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ProductUpdateRequest;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $companies = Company::all();
        $products = Product::query();

        if ($request->has('search')) {
            $products->where('product_name', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('manufacturer')) {
            $products->where('company_id', $request->manufacturer);
        }

        if ($request->filled('price_min')) {
            $products->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $products->where('price', '<=', $request->price_max);
        }

        if ($request->filled('stock_min')) {
            $products->where('stock', '>=', $request->stock_min);
        }

        if ($request->filled('stock_max')) {
            $products->where('stock', '<=', $request->stock_max);
        }

        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'desc');

        $products->orderBy($sort, $direction);

        $products = $products->get();

        return view('products.index', [
            'products' => $products,
            'companies' => $companies,
            'sort' => $sort,
            'direction' => $direction
        ]);
    }

    public function create()
    {
        try {
            $companies = Company::all();
            return view('products.create', compact('companies'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error occurred: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'product_name' => 'required',
                'company_id' => 'required',
                'price' => 'required',
                'stock' => 'required',
                'comment' => 'nullable',
                'img_path' => 'nullable|image|max:2048',
            ]);

            $product = new Product([
                'product_name' => $request->get('product_name'),
                'company_id' => $request->get('company_id'),
                'price' => $request->get('price'),
                'stock' => $request->get('stock'),
                'comment' => $request->get('comment'),
            ]);

            if ($request->hasFile('img_path')) {
                $filename = $request->img_path->getClientOriginalName();
                $filePath = $request->img_path->storeAs('products', $filename, 'public');
                $product->img_path = '/storage/' . $filePath;
            }

            $product->save();

            DB::commit();

            return redirect('products');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error occurred: ' . $e->getMessage());
        }
    }

    public function show(Product $product)
    {
        return view('products.show', ['product' => $product]);
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        try {
            DB::beginTransaction();

            $product->fill($request->validated());

            if ($request->hasFile('img_path')) {
                $filename = $request->img_path->getClientOriginalName();
                $filePath = $request->img_path->storeAs('products', $filename, 'public');
                $product->img_path = '/storage/' . $filePath;
            }

            $product->save();

            DB::commit();

            return redirect()->route('products.index')
                ->with('success', 'Product updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error occurred: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function edit(Product $product)
    {
        $companies = Company::all();
        return view('products.edit', compact('product', 'companies'));
    }

    public function search(Request $request)
    {
        $query = $request->get('search');
        $manufacturer = $request->get('manufacturer');

        $products = Product::query();

        if ($query) {
            $products->where('product_name', 'like', '%' . $query . '%');
        }

        if ($manufacturer) {
            $products->where('company_id', $manufacturer);
        }

        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'desc');

        $products = $products->orderBy($sort, $direction)->get();

        return view('products.partials.product_list', ['products' => $products])->render();
    }

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

            // Fetch the product with a lock for update
            $product = Product::lockForUpdate()->findOrFail($productId);

            // Check if the product has enough stock
            if ($product->stock < $quantity) {
                throw new \Exception('Not enough stock available');
            }

            // Reduce the product stock
            $product->stock -= $quantity;
            $product->save();

            // Create a new sale record
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