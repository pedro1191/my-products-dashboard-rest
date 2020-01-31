<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct(\App\Category $category, \App\Transformers\CategoryTransformer $transformer)
    {
        $this->category = $category;
        $this->transformer = $transformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /// User input validation
        $this->validate($request, [
            'name' => ['sometimes', 'required'],
            'orderBy' => ['sometimes', 'in:id,name'],
            'orderDirection' => ['sometimes', 'in:asc,desc']
        ]);

        $input = $request->all();

        $orderBy = $input['orderBy'] ?? 'id';
        $orderDirection = $input['orderDirection'] ?? 'desc';

        $categories = $this->category
            ->where(function($query) use($input) {
                if (isset($input['name'])) {
                    $q = ('%' . mb_strtolower(trim($input['name'])) . '%');
                    $query->where(function($query) use($q) {
                        $query->whereRaw('LOWER(name) LIKE ?')
                            ->setBindings([$q]);
                    });
                }
            })
            ->orderBy($orderBy, $orderDirection)
            ->paginate(Controller::DEFAULT_PAGINATION_RESULTS);

        return $this->response->paginator($categories, $this->transformer);
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
        ]);

        // Everything OK
        $category = $this->category->create([
            'name' => $request->input('name')
        ]);

        \Log::info('User ' . Auth::user()->id . ' has inserted a new category with id ' . $category->id . '...');

        return $this->response->item($category, $this->transformer)->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!($category = $this->category->find($id))) {
            return $this->response->errorNotFound();
        }

        return $this->response->item($category, $this->transformer);
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
        if (!($category = $this->category->find($id))) {
            return $this->response->errorNotFound();
        }

        // User input validation
        $this->validate($request, [
            'name' => ['sometimes', 'required', 'min:1', 'max:100']
        ]);

        // Everything OK
        \Log::info('User ' . Auth::user()->id . ' is updating a category with id ' . $category->id . '...');

        $category->fill([
            'name' => $request->input('name') ?? $category->name
        ]);
        $category->save();

        return $this->response->item($category, $this->transformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!($category = $this->category->find($id))) {
            return $this->response->errorNotFound();
        }

        if ($this->category->count() <= 3) {
            return $this->response->errorForbidden('There must be at least 3 categories registered in the system.');
        }

        \Log::info('User ' . Auth::user()->id . ' is deleting a category with id ' . $category->id . '...');

        $category->delete();

        return $this->response->array(['deleted_id' => (int) $id]);
    }
}
