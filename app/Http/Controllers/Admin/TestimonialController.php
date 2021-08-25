<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $testimonials = Testimonial::latest()->paginate(20);
        return view('admin.pages.testimonial', ['testimonials' => $testimonials]);
    }

    /**
     * Filter data of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request) {
        $testimonials = Testimonial::query();
        if($request->q) {
            $testimonials = $testimonials->where('title', 'like', "%{$request->q}%");
        }
        $testimonials = $testimonials->orderBy('position')
            ->paginate(20)
            ->appends($request->query());
        return view('admin.pages.testimonial', ['testimonials' => $testimonials]);
    }

    /**
     * Get the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request) {
        //Get category
        $testimonials = Testimonial::query();
        if($request->id) {
            $testimonials = $testimonials->where('id', $request->id);
        }
        $testimonials = $testimonials->get();
        if($testimonials->isNotEmpty()) {
            $res['status'] = true;
            $res['message'] = 'Data found';
            $res['data'] = $testimonials;
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
                'testimonial_title' => 'required',
                'person_name' => 'required',
                'video_url' => 'required',
                'status' => 'required',
            ]);

            if($validator->stopOnFirstFailure()->fails()) {
                return back()->with(['error' => $validator->errors()->first()]);
            }

            $testimonial = new Testimonial();
            $testimonial->title = $request->testimonial_title;
            $testimonial->url = $request->video_url;
            $testimonial->name = $request->person_name;
            $testimonial->position = $request->position;
            $testimonial->status = $request->status;
            $testimonial->save();
            return back()->with(['success' => 'Testimonial saved successfully']);
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
                'testimonial_title' => 'required',
                'person_name' => 'required',
                'video_url' => 'required',
                'status' => 'required',
            ]);

            if($validator->stopOnFirstFailure()->fails()) {
                return back()->with(['error' => $validator->errors()->first()]);
            }

            $testimonial = Testimonial::findOrFail($request->id);
            $testimonial->title = $request->testimonial_title;
            $testimonial->url = $request->video_url;
            $testimonial->name = $request->person_name;
            $testimonial->position = $request->position;
            $testimonial->status = $request->status;
            $testimonial->save();
            return back()->with(['success' => 'Testimonial updated successfully']);
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

            $testimonial = Testimonial::findOrFail($request->id);
            $testimonial->status = $request->status;
            $testimonial->save();
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

            // Delete testimonial multiple testimonial at once
            $ids = (array) $request->id ?? [];
            foreach($ids as $id) {
                $testimonial = Testimonial::findOrFail($id)->delete();
            }
            return back()->with('success', 'Testimonial deleted successfully');
        } catch(Exception $e) {
            abort(500);
        }
    }
}
