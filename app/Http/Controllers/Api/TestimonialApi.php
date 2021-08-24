<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;

class TestimonialApi extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try {
            $testimonials = Testimonial::latest()->paginate(20);
            return response()->json([
                'status' => $testimonials->isNotEmpty(),
                'data' => $testimonials->getCollection(),
                'message' => 'Data found'
            ]);
        } catch(Excepton $e) {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'Internal Server Error'
            ], 500);
        }
    }
}
