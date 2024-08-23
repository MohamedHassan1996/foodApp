<?php

namespace App\Services\Product;

use App\Enums\Category\CategoryStatus;
use App\Filters\Category\FilterCategory;
use App\Models\Product\Category;
use App\Services\Upload\UploadService;
use Illuminate\Http\UploadedFile;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryService{

    private $category;
    protected $uploadService;


    public function __construct(Category $category, UploadService $uploadService)
    {
        $this->category = $category;
        $this->uploadService = $uploadService;

    }

    public function allCategories()
    {
        $user = QueryBuilder::for(Category::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterCategory()), // Add a custom search filter
            ])->get();

        return $user;

    }

    public function createCategory(array $categoryData): Category
    {

        $path = null;

        if(isset($categoryData['path']) && $categoryData['path'] instanceof UploadedFile){
            $path =  $this->uploadService->uploadFile($categoryData['path'], 'categories');
        }

        $category = Category::create([
            'name' => $categoryData['name'],
            'path' => $path,
            'status' => CategoryStatus::from($categoryData['status'])->value
        ]);

        return $category;

    }

    public function editCategory(int $categoryId)
    {
        return Category::find($categoryId);
    }

    public function updateCategory(array $categoryData): Category
    {

        $path = null;

        if(isset($categoryData['path']) && $categoryData['path'] instanceof UploadedFile){
            $path =  $this->uploadService->uploadFile($categoryData['path'], 'categories');
        }

        $category = Category::find($categoryData['categoryId']);

        $category->name = $categoryData['name'];
        $category->status = CategoryStatus::from($categoryData['status'])->value;

        if($path){
            $category->path = $path;
        }

        $category->save();


        return $category;


    }


    public function deleteCategory(int $categoryId)
    {

        return Category::find($categoryId)->delete();

    }

}
