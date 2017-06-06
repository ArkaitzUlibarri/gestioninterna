<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Category;

class CategoriesController extends Controller
{
    public function edit($user_id)
    {
    	$categories = Category::all();
        $user = User::find($user_id);
       
        return view('categories.create',compact('user','categories'));
    }
}
