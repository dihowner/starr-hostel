<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{

    public function RegisterAccount(RegisterRequest $request) {
        $registerData = $request->validated();

        $user = User::create([
            "name" => $registerData['full_name'],
            "username" => $registerData['username'],
            "phone_number" => $registerData['phone_number'],
            "emailaddress" => $registerData['emailaddress'],
            "password" => Hash::make($registerData['password'])
        ]);

        if(!$user) {
            Alert::error("Error", "Registration failed");
            return redirect()->back();
        }

        Auth::loginUsingId($user->id);
        return redirect()->route('home');
    }

    public function LoginAccount(LoginRequest $request) {
        $loginData = $request->validated();
        
        if(Auth::attempt(['username' => $loginData['username'], 'password' => $loginData['password']])) {
            Auth::user();
            return redirect()->route('home');
        }
        Alert::error("Error", "Bad combination of username or password");
        return redirect()->back();
    }
    
    public function logOut(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('home');
    }
}