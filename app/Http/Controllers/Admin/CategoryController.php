<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $categories = Category::latest()->paginate(20);
        return view('admin.pages.category', ['categories' => $categories]);
    }

    /**
     * Filter data of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request) {
        $categories = Category::query();
        if($request->q) {
            $categories = $categories->where('name', 'like', "%{$request->q}%");
        }
        $categories = $categories->orderBy('position')
            ->paginate(20)
            ->appends($request->query());
        return view('admin.pages.category', ['categories' => $categories]);
    }

    /**
     * Get the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request) {
        //Get category
        $categories = Category::query();
        if($request->id) {
            $categories = $categories->where('id', $request->id);
        }
        $categories = $categories->get();
        if($categories->isNotEmpty()) {
            $res['status'] = true;
            $res['message'] = 'Data found';
            $res['data'] = $categories;
        } else {
            $res['status'] = false;
            $res['message'] = 'Data not found';
            $res['data'] = [];
        }
        return response()->json($res);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'category_name' => 'required',
                'status' => 'required',
            ]);

            if($validator->stopOnFirstFailure()->fails()) {
                return back()->with(['error' => $validator->errors()->first()]);
            }

            $category = new Category();
            $category->name = $request->category_name;
            $category->position = $request->position;
            $category->status = $request->status;
            $category->save();
            return back()->with(['success' => 'Category saved successfully']);
        } catch(Exception $e) {
            abort(500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'category_name' => 'required',
                'status' => 'required',
            ]);

            if($validator->stopOnFirstFailure()->fails()) {
                return back()->with(['error' => $validator->errors()->first()]);
            }

            $category = Category::findOrFail($request->id);
            $category->name = $request->category_name;
            $category->status = $request->status;
            $category->save();
            return back()->with(['success' => 'Category updated successfully']);
        } catch(Exception $e) {
            abort(500);
        }
    }

    /**
     * Update the status of resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'status' => 'required',
            ]);

            if($validator->stopOnFirstFailure()->fails()) {
                return back()->with(['error' => $validator->errors()->first()]);
            }

            $category = Category::findOrFail($request->id);
            $category->status = $request->status;
            $category->save();
            return response()->json([
                'status' => true,
                'data' => [],
                'message' => 'Status updated successfully'
            ]);
        } catch(Exception $e) {
            abort(500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if($validator->stopOnFirstFailure()->fails()) {
                return back()->with(['error' => $validator->errors()->first()]);
            }

            // Delete category multiple category at once
            $ids = (array) $request->id ?? [];
            foreach($ids as $id) {
                $category = Category::findOrFail($id)->delete();
            }
            return back()->with('success', 'Category deleted successfully');
        } catch(Exception $e) {
            abort(500);
        }
    }
}
