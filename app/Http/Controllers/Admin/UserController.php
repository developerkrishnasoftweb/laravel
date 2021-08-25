<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $users = User::latest()->paginate(20);
        $roles = Role::latest()->get();
        return view('admin.pages.user.users', ['users' => $users, 'roles' => $roles]);
    }

    /**
     * Filter data of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request) {
        $users = User::query();
        if($request->q) {
            $users = $users->where('name', 'like', "%{$request->q}%");
        }
        $users = $users->latest()
            ->paginate(20)
            ->appends($request->query());
        $roles = Role::latest()->get();
        return view('admin.pages.user.users', ['users' => $users, 'roles' => $roles]);
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
            $user = User::findOrFail($request->id);
            return view('admin.pages.user.show-user', ['user' => $user]);
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
    public function get(Request $request) {
        //Get category
        $users = User::with('roles');
        if($request->id) {
            $users = $users->where('id', $request->id);
        } else if($request->email) {
            $users = $users->where('email', $request->email);
        }
        $users = $users->get();
        if($users->isNotEmpty()) {
            $res['status'] = true;
            $res['message'] = 'Data found';
            $res['data'] = $users->toArray();
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
                'full_name' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:6',
                'roles' => 'required|array',
            ]);

            if($validator->stopOnFirstFailure()->fails()) {
                return back()->with(['error' => $validator->errors()->first()]);
            }

            $user = new User();
            $user->name = $request->full_name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            if($request->hasFile('profile_image')) {
                // Old file path
                $oldFile = $user->profile_image;
                // Generate new unique file name
                $newFileName = 'user-'.rand(1000000, 9999999).'-'.$request->file('profile_image')->getClientOriginalName();
                // Store file
                $user->profile_image = $request->file('profile_image')->storeAs('images/user', $newFileName, 'public');
                // Delete old file from server
                if($user->profile_image) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
            $user->save();
            // Attach user roles
            $user->roles()->attach(Role::whereIn('id', $request->roles)->pluck('id')->toArray());
            return back()->with(['success' => 'User saved successfully']);
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
                'full_name' => 'required',
                'email' => 'required|email',
                'roles' => 'required|array',
            ]);

            if($validator->stopOnFirstFailure()->fails()) {
                return back()->with(['error' => $validator->errors()->first()]);
            }

            $user = User::findOrFail($request->id);
            $user->name = $request->full_name;
            $user->email = $request->email;
            if($request->hasFile('profile_image')) {
                // Old file path
                $oldFile = $user->profile_image;
                // Generate new unique file name
                $newFileName = 'user-'.rand(1000000, 9999999).'-'.$request->file('profile_image')->getClientOriginalName();
                // Store file
                $user->profile_image = $request->file('profile_image')->storeAs('images/user', $newFileName, 'public');
                // Delete old file from server
                if($user->profile_image) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
            $user->save();
            // Attach user roles
            $user->roles()->sync(Role::whereIn('id', $request->roles)->pluck('id')->toArray());
            return back()->with('success', 'Profile updated successfully.');
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

            $user = User::findOrFail($request->id);
            $user->status = $request->status;
            $user->save();
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

            // Delete user multiple user at once
            $ids = (array) $request->id ?? [];
            foreach($ids as $id) {
                $user = User::findOrFail($id);
                if(!empty($user->image_url)) {
                    // Delete user images
                    Storage::disk('public')->delete($user->image_path);
                }
                $user->delete();
            }
            return back()->with('success', 'Data deleted successfully');
        } catch(Exception $e) {
            abort(500);
        }
    }
}
