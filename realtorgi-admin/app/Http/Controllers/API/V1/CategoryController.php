<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Auctions\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    protected $category = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Category $category)
    {
        //$this->middleware('auth:api');
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->category->latest()->withCount('lot')->paginate(1000);

        return $this->sendResponse($categories, 'Categories list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Auctions\CategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $category = $this->category->create([
            'name' => $request->get('name'),
        ]);

        return $this->sendResponse($category, 'Category Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = $this->category->findOrFail($id);

        return $this->sendResponse($category, 'Category Details');
    }

    public function list()
    {
        $categories = Category::pluck('name', 'id');

        return $this->sendResponse($categories, 'Categories list');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = $this->category->findOrFail($id);

        $category->update($request->all());

        return $this->sendResponse($category, 'Category Information has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        //$this->authorize('isAdmin');

        $category = $this->category->findOrFail($id);

        $category->delete();

        return $this->sendResponse($category, 'Category has been Deleted');
    }

    public function upload(Request $request)
    {
        $fileName = time() . '.' . $request->file->getClientOriginalExtension();
        $request->file->move(public_path('upload'), $fileName);

        return response()->json(['success' => true]);
    }
}
