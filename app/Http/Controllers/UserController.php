<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Import model User untuk penggunaan eloquent
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function aboutMe()
    {
        $user = Auth::user();
        return view('about-me', compact('user'));
    }

    public function getUnapprovedUsers()
    {
        $unapprovedUsers = User::where('approved', false)->get();
        return response()->json($unapprovedUsers);
    }
}
