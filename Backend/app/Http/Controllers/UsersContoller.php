<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersContoller extends Controller
{
    public function createUser(Request $request)
    {
        // Validate input
        // POST: Create a user
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'nullable|string|max:15',
            'role' => 'in:customer,admin',
        ]);
        // Insert into the database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Encrypt password
            'phone' => $request->phone,
            'role' => $request->role ?? 'customer', // Default role
        ]);

        return response()->json(['message' => 'User created successfully!', 'user' => $user], 201);
    }
    //fetch all data!!!!
    public function getUsers()
    {
        $users = User::all(); // Fetch all users
        return response()->json(['users' => $users]);
    }



    //create admin 
    public function registerAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:15',
            'password' => 'required|string|min:6'
        ]);

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'admin' // Ensure your users table has this column
        ]);

        return response()->json([
            'message' => 'Admin registered successfully',
            'admin' => $admin
        ], 201);
    }

    //for Login
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string',  // Changed from email to name
            'password' => 'required|string|min:6'
        ]);

        // Find user by name instead of email
        $user = User::where('name', $request->name)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Generate token using Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ]);
    }
}
//admin
// {
//     "name": "Kosal", 
//     "email": "Kosal@example.com", 
//     "phone": "09383838338",
//     "password": "111111" 
//   }

//test user 
// {
//     "name": "jann lee",
//     "email": "jane.doe@example.com",
//     "password": "222222",
//     "phone": "+1234567890",
//     "role": "customer"
//   }