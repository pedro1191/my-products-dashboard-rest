<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function __construct(\App\Product $product, \App\Transformers\ProductTransformer $transformer)
    {
        $this->product = $product;
        $this->transformer = $transformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->product->orderBy('id', 'DESC')
            ->paginate(15);

        return $this->response->paginator($products, $this->transformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // User input validation
        $this->validate($request, [
            'name' => ['required', 'min:1', 'max:100'],
            'description' => ['required', 'min:1', 'max:1000'],
        ]);

        // Everything OK
        $product = $this->product->create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        return $this->response->item($product, $this->transformer)->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!($product = $this->product->find($id))) {
            return $this->response->errorNotFound();
        }

        return $this->response->item($product, $this->transformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!($product = $this->product->find($id))) {
            return $this->response->errorNotFound();
        }

        // User input validation
        $this->validate($request, [
            'name' => ['sometimes', 'required', 'min:1', 'max:100'],
            'description' => ['sometimes', 'required', 'min:1', 'max:1000'],
        ]);

        // Everything OK
        $product->fill([
            'name' => $request->input('name') ?? $product->name,
            'description' => $request->input('description') ?? $product->description,
        ]);
        $product->save();

        return $this->response->item($product, $this->transformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!($product = $this->product->find($id))) {
            return $this->response->errorNotFound();
        }

        $product->delete();

        return $this->response->array(['deleted_id' => (int) $id]);
    }
}
