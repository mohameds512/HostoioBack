<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('children')->get()->transform(function($item){
            $item->img_route = route('category_image', ['img' => $item->image, 'no_cache' => Str::random(4)]);
            return $item;
        });
        return response()->json($categories);
    }

    public function store(Request $request,Category $category)
    {
        // return $request;
        if(!$category){
            $category = new Category();
        }

        if ($category->exists && $request->parent_id == $category->id) {
            return response()->json(['error' => 'Invalid parent_id. It cannot be the same as the category ID.'], 422);
        }

        $category->name = ["ar"=>$request->name_ar,"en"=>$request->name_en,];
        $category->description = $request->desc;
        $category->parent_id = $request->parent_id;

        if ($request->hasFile('image')) {
            $file_image = $request->file('image');
            $image =Str::random(6);
            saveRequestFile($file_image, "$image", "categories");
            $category->image= $image;
        }

        $category->save();
        return \response()->json($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if (!$category) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        $childCategoriesCount = $category->children()->count();
        if ($childCategoriesCount > 0) {
            return response()->json(['message' => 'Cannot delete category with child categories.'], 422);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully.']);

    }

    public function categoriesImages(Request $request, $img, $no_cache)
    {

        $paths = findFiles("categories", "$img");

        if (isset($paths[0]) && $paths[0]) {
            return responseFile($paths[0], "$img");
        }
        return response(['message' => 'not found'], 404);

    }
}
