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
                'statusCode' => $banners->isNotEmpty() ? 200 : 204,
                'data' => $banners->items(),
                'pagination' => [
                    'currentPage' => $banners->currentPage(),
                    'totalPage' => $banners->lastPage(),
                    'totalItemCount' => $banners->count(),
                    'currentPageItemCount' => $banners->total()
                ],
                'message' => $banners->isNotEmpty() ? 'Data found' : 'Data not found',
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
