<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // User input validation
        $this->validate($request, [
            'name' => ['sometimes', 'required'],
            'description' => ['sometimes', 'required'],
            'category_id' => ['sometimes', 'required'],
            'orderBy' => ['sometimes', 'in:id,name,description,category_id'],
            'orderDirection' => ['sometimes', 'in:asc,desc']
        ]);

        $input = $request->all();

        $orderBy = $input['orderBy'] ?? 'id';
        $orderDirection = $input['orderDirection'] ?? 'desc';

        $products = $this->product
            ->where(function($query) use($input) {
                if (isset($input['name'])) {
                    $q = ('%' . mb_strtolower(trim($input['name'])) . '%');
                    $query->where(function($query) use($q) {
                        $query->whereRaw('LOWER(name) LIKE ?')
                            ->setBindings([$q]);
                    });
                }
                if (isset($input['description'])) {
                    $q = ('%' . mb_strtolower(trim($input['description'])) . '%');
                    $query->where(function($query) use($q) {
                        $query->whereRaw('LOWER(description) LIKE ?')
                            ->setBindings([$q]);
                    });
                }
                if (isset($input['category_id'])) {
                    $query->where('category_id', $input['category_id']);
                }
            })
            ->orderBy($orderBy, $orderDirection)
            ->paginate(Controller::DEFAULT_PAGINATION_RESULTS);

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
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['required', 'image', 'max:128']
        ]);

        $image = $this->generateBase64ImageString($request->file('image'));

        // Everything OK
        $product = $this->product->create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'category_id' => $request->input('category_id'),
            'image' => $image
        ]);

        \Log::info('User ' . Auth::user()->id . ' has inserted a new product with id ' . $product->id . '...');

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
            'category_id' => ['sometimes', 'required', 'exists:categories,id'],
            'image' => ['sometimes', 'required', 'image', 'max:128'],
        ]);

        if ($request->hasFile('image')) {
            $image = $this->generateBase64ImageString($request->file('image'));
        } else {
            $image = null;
        }

        // Everything OK
        \Log::info('User ' . Auth::user()->id . ' is updating a product with id ' . $product->id . '...');

        $product->fill([
            'name' => $request->input('name') ?? $product->name,
            'description' => $request->input('description') ?? $product->description,
            'category_id' => $request->input('category_id') ?? $product->category_id,
            'image' => is_null($image) ? $product->image : $image,
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

        if ($product->category->products()->count() <= 3) {
            return $this->response->errorForbidden('There must be at least 3 products per category registered in the system.');
        }

        \Log::info('User ' . Auth::user()->id . ' is deleting a product with id ' . $product->id . '...');

        $product->delete();

        return $this->response->array(['deleted_id' => (int) $id]);
    }

    /**
     * Generate base64 image string
     *
     * @param $image
     * @return string
     */
    private function generateBase64ImageString($image)
    {
        return "data:{$image->getMimeType()};base64,"
            . base64_encode(file_get_contents($image->getPathname()));
    }
}
