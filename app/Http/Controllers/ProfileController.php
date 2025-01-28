<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(Request $request):RedirectResponse{

        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'avatar'=> 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $user->name = $request['name'];
        $user->email = $request['email'];

        if($request->hasFile('avatar')){
            if($user->avatar){
                Storage::delete('/public'. $user->avatar);
            }
            $avatarpath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarpath;
        }

        $user->save();
        return redirect()->route('dashboard')->with('success', 'Profile updated successfully');
    }
}
