<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $brands = Brand::latest()->paginate(20);
        return view('admin.pages.brand', ['brands' => $brands]);
    }

    /**
     * Filter data of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request) {
        $brands = Brand::query();
        if($request->q) {
            $brands = $brands->where('name', 'like', "%{$request->q}%");
        }
        $brands = $brands->orderBy('position')
            ->paginate(20)
            ->appends($request->query());
        return view('admin.pages.brand', ['brands' => $brands]);
    }

    /**
     * Get the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request) {
        //Get category
        $brands = Brand::query();
        if($request->id) {
            $brands = $brands->where('id', $request->id);
        }
        $brands = $brands->get();
        if($brands->isNotEmpty()) {
            $res['status'] = true;
            $res['message'] = 'Data found';
            $res['data'] = $brands;
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
                'brand_name' => 'required',
                'logo' => 'required',
                'status' => 'required',
            ]);

            if($validator->stopOnFirstFailure()->fails()) {
                return back()->with(['error' => $validator->errors()->first()]);
            }

            $brand = new Brand();
            $brand->name = $request->brand_name;
            if($request->hasFile('logo')) {
                // Generate new unique file name
                $newFileName = 'brand-'.rand(1000000, 9999999).'-'.$request->file('logo')->getClientOriginalName();
                // Store file
                $brand->logo = $request->file('logo')->storeAs('images/brand', $newFileName, 'public');
            }
            $brand->position = $request->position;
            $brand->status = $request->status;
            $brand->save();
            return back()->with(['success' => 'Brand saved successfully']);
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
                'brand_name' => 'required',
                'status' => 'required',
            ]);

            if($validator->stopOnFirstFailure()->fails()) {
                return back()->with(['error' => $validator->errors()->first()]);
            }

            $brand = Brand::findOrFail($request->id);
            $brand->name = $request->brand_name;
            if($request->hasFile('logo')) {
                // Old file path
                $oldFile = $brand->logo;
                // Generate new unique file name
                $newFileName = 'brand-'.rand(1000000, 9999999).'-'.$request->file('logo')->getClientOriginalName();
                // Store file
                $brand->logo = $request->file('logo')->storeAs('images/brand', $newFileName, 'public');
                // Delete old file from server
                if(!empty($brand->logo)) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
            $brand->status = $request->status;
            $brand->save();
            return back()->with(['success' => 'Brand updated successfully']);
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

            $brand = Brand::findOrFail($request->id);
            $brand->status = $request->status;
            $brand->save();
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

            // Delete brand multiple brand at once
            $ids = (array) $request->id ?? [];
            foreach($ids as $id) {
                $brand = Brand::findOrFail($id);
                if(!empty($brand->image_url)) {
                    // Delete brand logo
                    Storage::disk('public')->delete($brand->logo);
                }
                $brand->delete();
            }
            return back()->with('success', 'Brand deleted successfully');
        } catch(Exception $e) {
            abort(500);
        }
    }
}
