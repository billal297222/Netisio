<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
 use Intervention\Image\Facades\Image;
class UserController extends Controller
{
    //
    public function index()
    {
        $data['users'] = User::where('id', '!=', 1)->get();
        return view('backend.layouts.users.index', $data);
    }

    public function create()
    {
        $data['roles'] = Role::all();
        return view('backend.layouts.users.create', $data);
    }

    public function store(Request $request)
{
    // Custom validation messages
    $messages = [
        'name.required' => 'Name is required.',
        'name.string' => 'Name must be a valid string.',
        'name.max' => 'Name cannot exceed 255 characters.',

        'email.required' => 'Email is required.',
        'email.email' => 'Email must be a valid email address.',
        'email.unique' => 'This email is already registered.',
        'email.max' => 'Email cannot exceed 255 characters.',

        'phone_number.required' => 'Phone number is required.',
        'phone_number.string' => 'Phone number must be valid.',
        'phone_number.unique' => 'This phone number is already registered.',
        'phone_number.max' => 'Phone number cannot exceed 20 characters.',

        'password.required' => 'Password is required.',
        'password.string' => 'Password must be a valid string.',
        'password.min' => 'Password must be at least 6 characters.',
        'password.confirmed' => 'Password confirmation does not match.',

        'role.required' => 'Role is required.',
        'role.string' => 'Role must be a valid string.',

        'profile.image' => 'Profile must be an image file.',
        'profile.mimes' => 'Profile image must be jpg, jpeg, png, or gif.',
        'profile.max' => 'Profile image size cannot exceed 2MB.',
    ];

    // Validate request
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'phone_number' => 'required|string|max:20|unique:users,phone_number',
        'password' => 'required|string|min:6|confirmed',
        'role' => 'required|string',
        'profile' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ], $messages);

    try {
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone_number = $validated['phone_number'];
        $user->password = Hash::make($validated['password']);

        // Handle profile image
        if ($request->hasFile('profile')) {
            $user->avatar = $this->uploadImage(
                $request->file('profile'),
                'null',
                'uploads/avatar',
                150,
                150,
                'user-'
            );
        } else {
            $user->avatar = 'default.jpg';
        }

        // Set admin flag
        $user->admin = strtolower($validated['role']) === 'admin' ? 1 : 0;

        $user->save();

        return redirect()->route('user.list')->with('success', 'User created successfully.');
    } catch (\Exception $e) {
        return back()->with('error', 'Error creating user: ' . $e->getMessage())->withInput();
    }
}



    // Toggle dynamic status
    public function status(Request $request, $id)
    {
        $dynamic =  User::findOrFail($id);
        if ($dynamic) {
            $dynamic->status = $dynamic->status === 'active' ? 'inactive' : 'active';
            $dynamic->save();
        }
        return redirect()->route('user.list')->with('success', 'User status updated successfully');
        // return response()->json(['success' => true, 'message' => 'Status updated']);
    }



    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('backend.layouts.users.edit', compact('user'));
    }
   public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $id,
        'phone_number' => 'required|string|max:20|unique:users,phone_number,' . $id,
        'password' => 'nullable|string|min:6|confirmed',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    // Update basic info
    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone_number = $request->phone_number;

    // Update password if provided
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    // Update avatar if uploaded
    if ($request->hasFile('avatar')) {
        if ($user->avatar && $user->avatar !== 'default.jpg' && file_exists(public_path('uploads/avatar/' . $user->avatar))) {
            unlink(public_path('uploads/avatar/' . $user->avatar));
        }

        $user->avatar = $this->uploadImage(
            $request->file('avatar'),
            'null',
            'uploads/avatar',
            150,
            150,
            'user-'
        );
    }

    $user->save();

    return redirect()->route('user.list')->with('success', 'User updated successfully.');
}

    public function destroy($id)
    {
        $delete = User::find($id)->delete();
        if ($delete) {
            return back()->with('success', 'Deleted Successfully');
        } else {
            return back()->with('error', 'Try Again!');
        }
    }
}
