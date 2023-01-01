<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\Index as IndexRequest;
use App\Http\Requests\Product\Store as StoreRequest;
use App\Http\Requests\Product\Update as UpdateRequest;
use App\Http\Resources\Product as ProductResource;
use App\Models\Product;
use App\Services\Product\Index as IndexService;
use App\Services\Product\Store as StoreService;
use App\Services\Product\Update as UpdateService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\Product\Index  $request
     * @param  \App\Services\Product\Index  $service
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request, IndexService $service)
    {
        $data = $request->validated();
        $products = $service->execute($data);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Product\Store  $request
     * @param  \App\Services\Product\Store  $service
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, StoreService $service)
    {
        $data = $request->validated();
        $product = $service->execute($data);

        Log::info('User ' . Auth::user()->id . ' has inserted a new product with id ' . $product->id . '...');

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Product\Update  $request
     * @param  \App\Services\Product\Update  $service
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, UpdateService $service, Product $product)
    {
        $data = $request->validated();
        $updatedProduct = $service->execute($product, $data);

        Log::info('User ' . Auth::user()->id . ' has updated a product with id ' . $product->id . '...');

        return new ProductResource($updatedProduct);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        Gate::authorize('delete', $product);

        $product->delete();

        Log::info('User ' . Auth::user()->id . ' has deleted a product with id ' . $product->id . '...');

        return response()->noContent();
    }
}
