<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class APIController extends Controller
{

    /* Users APIs  Start */
    
    // Register Api (POST, form data)
    public function register(Request $request)
    {
        // Check if the email already exists in the database
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'message' => 'The email is already registered. Please use a different email or log in.',
            ], 409); // 409 Conflict status code
        }

        // Validation rules including password confirmation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed during registration.', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->except(['password', 'password_confirmation']),
            ]);

            return response()->json([
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Create the user with hashed password
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                
            ]);
            
            Log::info('User registered successfully.', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return response()->json([
                'message' => 'Registration successful. Welcome!',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            Log::error('User registration failed.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Something went wrong during registration. Please try again later.',
            ], 500);
        }
    }

     // Login Api (POST, form data)
     public function login(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'email' => 'required|email',
             'password' => 'required',
         ]);
     
         if ($validator->fails()) {
             return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
         }
     
         $user = User::where("email", $request->email)->first();
     
         if($user){
             if(Hash::check($request->password, $user->password)){
                 $token = $user->createToken("myToken")->plainTextToken;
     
                 return response()->json([
                     "status" => true,
                     "message" => "Login successful",
                     "token" => $token,
                     'role' => $user->role,
                 ]);
             }
             return response()->json([
                 "status" => false,
                 "message" => "Password doesn't match"
             ]);
         }
     
         return response()->json([
             "status" => false,
             "message" => "User not found"
         ]);
     }
    
    // Profile Api (GET)
    public function profile(Request $request)
    {
        $data = auth()->user(); // Auth::user
        return response()->json([
            "status" => true,
            "message" =>"Profile data",
            "user" => $data
        ]);
    }
    
     // Profile Api (PUT)
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'address' => 'nullable|string|max:255', // Adding address
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        $user->update($request->only(['name', 'email', 'address']));

        if ($request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }

    // Logout Api (GET)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    
        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    // Password Reset API
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), ['email' => 'required|email']);
 
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }
 
        $status = Password::sendResetLink($request->only('email'));
 
        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset link sent successfully'])
            : response()->json(['message' => 'Failed to send reset link'], 500);
    }
    
    /* Users APIs  End */
    
    /* Products APIs Start */
    // Product Api (GET)
    
    /* Products APIs End */
  
 
    
}