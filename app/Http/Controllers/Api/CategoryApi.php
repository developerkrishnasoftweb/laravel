<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryApi extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try {
            $testimonials = Category::latest()->paginate(20);
            return response()->json([
                'statusCode' => $testimonials->isNotEmpty() ? 200 : 204,
                'data' => $testimonials->items(),
                'pagination' => [
                    'currentPage' => $testimonials->currentPage(),
                    'totalPage' => $testimonials->lastPage(),
                    'totalItemCount' => $testimonials->count(),
                    'currentPageItemCount' => $testimonials->total()
                ],
                'message' => $testimonials->isNotEmpty() ? 'Data found' : 'Data not found',
            ]);
        } catch(Excepton $e) {
            return response()->json([
                'statusCode' => 500,
                'data' => [],
                'message' => 'Internal Server Error'
            ], 500);
        }
    }
}
