<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostsController extends Controller
{

    // Previously we were able to create a post even though we were not signed in...
    // Although it was leading to an error message, now we can do it ONLY signed in.
    public function __construct() {
        $this->middleware('auth');
    }


    public function create()
    {
        return view('posts/create');
    }

    

    public function store() {
        $data = request() -> validate([
            'caption' => 'required',
            'image' => ['required', 'image'],
        ]);

        // Running this code will take the image and save it in an "Uploads"
        // file inside our system, then also into the public folder "Disk Space"
        // The file inside "Storage" is not accessible though
        // So, in order to make our images available inside the "Storage folder"
        // We need to run an artisan command.
        // Go to terminal and run php artisan storage:link
        // Storage:link creates a link to all the files that are stored within 
        // The public/storage folders, so if you type in the url of the image
        // The image itself will be shown
        $imagePath = request('image')->store('uploads', 'public');

        $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 1200);

        $image->save();
        // This will go into the auth(authenticated) user (user()) posts and then create the post.
        // We need this funcionality because inside our database migration, we assigned three values
        // For each post. These values are the caption, the image and the user_id... So, the user_id
        // has to be authenticated every time by the authentication function and THEN create the post with the data...

        auth()->user()->posts()->create([
            'caption' => $data['caption'],
            'image' => $imagePath, // caption and image have to be stored for each user separatelly
        ]); 

        // After authenticating it goes to the user's id
        return redirect('/profile/' . auth()->user()->id);

        // \App\Models\Post::create($data); We don't need it anymore

        dd(request()->all());
    }



    public function show(\App\Models\Post $post) {
        return view('posts.show', compact('post'));
    }
} 




