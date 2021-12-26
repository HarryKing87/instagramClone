<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class FollowsController extends Controller
{

    public function __contruct() {
        $this->middleware('auth');
    }

    public function store(User $user) {
        return auth()->user()->following()->toggle($user->profile);
    }
}
