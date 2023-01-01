<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\Index as IndexRequest;
use App\Http\Requests\Category\Store as StoreRequest;
use App\Http\Requests\Category\Update as UpdateRequest;
use App\Http\Resources\Category as CategoryResource;
use App\Models\Category;
use App\Services\Category\Index as IndexService;
use App\Services\Category\Store as StoreService;
use App\Services\Category\Update as UpdateService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\Category\Index  $request
     * @param  \App\Services\Category\Index  $service
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request, IndexService $service)
    {
        $data = $request->validated();
        $categories = $service->execute($data);

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Category\Store  $request
     * @param  \App\Services\Category\Store  $service
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, StoreService $service)
    {
        $data = $request->validated();
        $category = $service->execute($data);

        Log::info('User ' . Auth::user()->id . ' has inserted a new category with id ' . $category->id . '...');

        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Category\Update  $request
     * @param  \App\Services\Category\Update  $service
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, UpdateService $service, Category $category)
    {
        $data = $request->validated();
        $updatedCategory = $service->execute($category, $data);

        Log::info('User ' . Auth::user()->id . ' has updated a category with id ' . $category->id . '...');

        return new CategoryResource($updatedCategory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        Gate::authorize('delete', $category);

        $category->delete();

        Log::info('User ' . Auth::user()->id . ' has deleted a category with id ' . $category->id . '...');

        return response()->noContent();
    }
}
