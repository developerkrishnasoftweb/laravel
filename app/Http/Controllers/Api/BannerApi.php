<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;

class BannerApi extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try {
            $banners = Banner::latest()->paginate(20);
            return response()->json([
                'status' => $banners->isNotEmpty(),
                'data' => $banners->getCollection(),
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
