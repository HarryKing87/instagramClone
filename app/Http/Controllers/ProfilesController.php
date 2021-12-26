<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ProfilesController extends Controller
{
    public function index(\App\Models\User $user)
    {
        //$user = User::find($user); // We're looking for a specific user with a name id
        //$user = User::findOrFail($user); // The main difference from the above
        // is that findOrFail actually displays a 404 Error instead of the actual (Exception) error code

        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;

        return view('profiles.index', compact('user', 'follows'));
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



        // Upload new image from the edit section functionality
        if (request('image'))
        {
            $imagePath = request('image')->store('profile', 'public');

            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);

            $image->save();

            $imageArray = ['image' => $imagePath]; // Save profile image and 
            // if wanted to change, it will, but if we edit the page and don't upload
            // and image, the image stays
        }


        auth()->user()->profile->update(array_merge(
            $data, 
            $imageArray ?? []
        ));


        return redirect("/profile/{$user->id}");
    }
}
