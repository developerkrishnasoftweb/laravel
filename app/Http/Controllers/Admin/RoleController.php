<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoleController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $roles = Role::latest()->paginate(20);
        return view('admin.pages.user.role', ['roles' => $roles]);
    }

    /**
     * Filter data of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request) {
        $roles = Role::query();
        if($request->q) {
            $roles = $roles->where('role', 'like', "%{$request->q}%");
        }
        $roles = $roles->latest()
            ->paginate(20)
            ->appends($request->query());
        return view('admin.pages.user.role', ['roles' => $roles]);
    }

    /**
     * Get the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request) {
        //Get category
        $roles = Role::query();
        if($request->id) {
            $roles = $roles->where('id', $request->id);
        }
        $roles = $roles->get();
        if($roles->isNotEmpty()) {
            $res['status'] = true;
            $res['message'] = 'Data found';
            $res['data'] = $roles->toArray();
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
                'role_title' => 'required',
                'banner_image' => 'required',
                'status' => 'required',
            ]);
            $role = new Role();
            $role->role = $request->role_title;
            $role->description = $request->description;
            $role->status = $request->status;
            $role->save();
            return back()->with(['success' => 'Role saved successfully']);
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
                'role_title' => 'required',
                'status' => 'required',
            ]);
            $role = Role::findOrFail($request->id);
            $role->role = $request->role_title;
            $role->description = $request->description;
            $role->status = $request->status;
            $role->save();
            return back()->with(['success' => 'Role updated successfully']);
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
            $role = Role::findOrFail($request->id);
            $role->status = $request->status;
            $role->save();
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
            // Delete role multiple role at once
            $ids = (array) $request->id ?? [];
            foreach($ids as $id) {
                $role = Role::findOrFail($id)->delete();
            }
            return back()->with('success', 'Role deleted successfully');
        } catch(Exception $e) {
            abort(500);
        }
    }
}
