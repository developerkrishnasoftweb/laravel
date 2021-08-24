<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Navbar;

class NavbarController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function index(Request $request) {
        $navbars = Navbar::orderBy('position')->paginate(20);
        return view('admin.pages.navbar.navmenu', ['navbars' => $navbars]);
    }

    /**
     * Filter data of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function filter(Request $request) {
        $navbars = Navbar::query();
        if($request->q) {
            $navbars = $navbars->where('title', 'like', "%{$request->q}%");
        }
        $navbars = $navbars->orderBy('position')
            ->paginate(20)
            ->appends($request->query());
        return view('admin.pages.navbar.navmenu', ['navbars' => $navbars]);
    }

    /**
     * Show a spe
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    function show(Request $request, $id) {
        try {
            $navbar = Navbar::findOrFail($request->id);
            $navbars = Navbar::where('parent_nav_id', $request->id)->paginate(20);
            return view('admin.pages.navbar.submenu', ['navbars' => $navbars, 'navbar' => $navbar]);
        } catch(Exception $e) {
            abort(404);
        }
    }

    /**
     * Get the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function get(Request $request) {
        //Get category
        $navbars = Navbar::query();
        if($request->id) {
            $navbars = $navbars->where('id', $request->id);
        }
        $navbars = $navbars->get();
        if($navbars->isNotEmpty()) {
            $res['status'] = true;
            $res['message'] = 'Data found';
            $res['data'] = $navbars;
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
    function store(Request $request) {
        try {
            $request->validate([
                'nav_title' => 'required',
                'nav_url' => 'required',
                'status' => 'required',
            ]);
            $navbar = new Navbar();
            $navbar->title = $request->nav_title;
            $navbar->url = $request->nav_url;
            $navbar->icon = $request->nav_icon;
            $navbar->parent_nav_id = $request->parent_nav_id ?? 0;
            $navbar->nav_type = $request->nav_type ?? 'menu';
            $navbar->position = $request->position;
            $navbar->status = $request->status;
            $navbar->save();
            return back()->with(['success' => 'Navbar saved successfully']);
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
    function update(Request $request) {
        try {
            $request->validate([
                'id' => 'required',
                'nav_title' => 'required',
                'nav_url' => 'required',
                'status' => 'required',
            ]);
            $navbar = Navbar::findOrFail($request->id);
            $navbar->title = $request->nav_title;
            $navbar->url = $request->nav_url;
            $navbar->icon = $request->nav_icon;
            $navbar->parent_nav_id = $request->parent_nav_id ?? 0;
            $navbar->nav_type = $request->nav_type ?? 'menu';
            $navbar->position = $request->position;
            $navbar->status = $request->status;
            $navbar->save();
            return back()->with(['success' => 'Navbar updated successfully']);
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
    function updateStatus(Request $request) {
        try {
            $request->validate([
                'id' => 'required',
                'status' => 'required',
            ]);
            $navbar = Navbar::findOrFail($request->id);
            $navbar->status = $request->status;
            $navbar->save();
            return back()->with(['success' => 'Navbar updated successfully']);
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
            // Delete navbar multiple navbar
            $ids = (array) $request->id ?? [];
            foreach($ids as $id) {
                $navbar = Navbar::findOrFail($id);
                // Delete all sub menu
                Navbar::where('parent_nav_id', $id)->delete();
                $navbar->delete();
            }
            return back()->with('success', 'Data deleted successfully');
        } catch(Exception $e) {
            abort(500);
        }
    }
}
