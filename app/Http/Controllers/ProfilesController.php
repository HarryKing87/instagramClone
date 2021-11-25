<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    public function index(\App\Models\User $user)
    {
        //$user = User::find($user); // We're looking for a specific user with a name id
        //$user = User::findOrFail($user); // The main difference from the above
        // is that findOrFail actually displays a 404 Error instead of the actual error code

        return view('profiles.index', compact('user'));
    }

    public function edit(\App\Models\User $user) {
        $this->authorize('update', $user->profile); // Goes along with ProfilePolicy.php
        return view('profiles.edit', compact('user'));
    }
    
    public function update(\App\Models\User $user) {

        $this->authorize('update', $user->profile);

        $data = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'url',
            'image' => '',
        ]);


        auth()->user()->profile->update($data);

        return redirect("/profile/{$user->id}");
    }
}
