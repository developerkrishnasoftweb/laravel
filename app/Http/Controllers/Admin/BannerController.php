<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $banners = Banner::latest()->paginate(20);
        return view('admin.pages.banner', ['banners' => $banners]);
    }

    /**
     * Filter data of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request) {
        $banners = Banner::query();
        if($request->q) {
            $banners = $banners->where('title', 'like', "%{$request->q}%");
        }
        $banners = $banners->orderBy('position')
            ->paginate(20)
            ->appends($request->query());
        return view('admin.pages.banner', ['banners' => $banners]);
    }

    /**
     * Get the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request) {
        //Get category
        $banners = Banner::query();
        if($request->id) {
            $banners = $banners->where('id', $request->id);
        }
        $banners = $banners->get();
        if($banners->isNotEmpty()) {
            $res['status'] = true;
            $res['message'] = 'Data found';
            $res['data'] = $banners;
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
            $request->validate([
                'banner_title' => 'required',
                'banner_image' => 'required',
                'status' => 'required',
            ]);
            $banner = new Banner();
            $banner->title = $request->banner_title;
            if($request->hasFile('banner_image')) {
                // Generate new unique file name
                $newFileName = 'banner-'.rand(1000000, 9999999).'-'.$request->file('banner_image')->getClientOriginalName();
                // Store file
                $banner->image_path = $request->file('banner_image')->storeAs('images/banner', $newFileName, 'public');
            }
            $banner->position = $request->position;
            $banner->status = $request->status;
            $banner->save();
            return back()->with(['success' => 'Banner saved successfully']);
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
            $request->validate([
                'id' => 'required',
                'banner_title' => 'required',
                'status' => 'required',
            ]);
            $banner = Banner::findOrFail($request->id);
            $banner->title = $request->banner_title;
            if($request->hasFile('banner_image')) {
                // Old file path
                $oldFile = $banner->image_path;
                // Generate new unique file name
                $newFileName = 'banner-'.rand(1000000, 9999999).'-'.$request->file('banner_image')->getClientOriginalName();
                // Store file
                $banner->image_path = $request->file('banner_image')->storeAs('images/banner', $newFileName, 'public');
                // Delete old file from server
                if(!empty($banner->image_path)) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
            $banner->position = $request->position;
            $banner->status = $request->status;
            $banner->save();
            return back()->with(['success' => 'Banner updated successfully']);
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
            $request->validate([
                'id' => 'required',
                'status' => 'required',
            ]);
            $banner = Banner::findOrFail($request->id);
            $banner->status = $request->status;
            $banner->save();
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
            $request->validate([
                'id' => 'required',
            ]);
            // Delete banner multiple banner at once
            $ids = (array) $request->id ?? [];
            foreach($ids as $id) {
                $banner = Banner::findOrFail($id);
                if(!empty($banner->image_url)) {
                    // Delete banner images
                    Storage::disk('public')->delete($banner->image_path);
                }
                $banner->delete();
            }
            return back()->with('success', 'Data deleted successfully');
        } catch(Exception $e) {
            abort(500);
        }
    }
}
