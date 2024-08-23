<?php

namespace App\Http\Controllers\Api\Private\Product;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\Category\CreateCategoryRequest;
use App\Http\Requests\Product\Category\UpdateCategoryRequest;
use App\Http\Resources\Product\Category\AllCategoryCollection;
use App\Http\Resources\Product\Category\CategoryResource;
use App\Utils\PaginateCollection;
use App\Services\Product\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CategoryController extends Controller
{
    protected $categoryService;


    public function __construct(CategoryService $categoryService)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:all_categories', ['only' => ['allCategories']]);
        $this->middleware('permission:create_category', ['only' => ['create']]);
        $this->middleware('permission:edit_category', ['only' => ['edit']]);
        $this->middleware('permission:update_category', ['only' => ['update']]);
        $this->middleware('permission:delete_category', ['only' => ['delete']]);
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function allCategories(Request $request)
    {
        $allCategories = $this->categoryService->allCategories();

        return response()->json(
            new AllCategoryCollection(PaginateCollection::paginate($allCategories, $request->pageSize?$request->pageSize:10))
        , 200);

    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(CreateCategoryRequest $createCategoryRequest)
    {

        try {
            DB::beginTransaction();

            $this->categoryService->createCategory($createCategoryRequest->validated());

            DB::commit();

            return ResponseHelper::success([], 'category.created_success');

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Request $request)
    {
        $category  =  $this->categoryService->editCategory($request->categoryId);

        return ResponseHelper::success(new CategoryResource($category));


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $updateCategoryRequest)
    {

        try {
            DB::beginTransaction();

            $this->categoryService->updateCategory($updateCategoryRequest->validated());

            DB::commit();

            return ResponseHelper::success([], 'category.updated_success');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {

        try {
            DB::beginTransaction();
            $this->categoryService->deleteCategory($request->categoryId);
            DB::commit();

            return ResponseHelper::success([], 'category.deleted_success');

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }

}
