<?php

namespace App\Http\Controllers;

use App\Category;
use App\User;
use App\UserRepository;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('auth');
        $this->middleware('checkrole');
        $this->userRepository = $userRepository;
    }

    /**
     * Show all users
     * 
     * @param  Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $users = $this->userRepository->search($request->all(), true);

        $filter = array(    
            'name' => $request->get('name'),
            'type' => $request->get('type'),
        );

    	return view('users.index', compact('users','filter'));
    }

    /**
     * User's edit view
     * 
     * @param  User
     * @return View
     */
    public function edit($id)
    {
        $user = User::find($id);
        $categories = Category::all();
        $roles = config('options.roles');
        
    	return view('users.edit', compact('user', 'roles', 'categories'));
    }

    /**
     * Show user profile
     * 
     * @param  User
     * @return View
     */
    public function show($id)
    {
        $user = User::find($id);
        $roles = config('options.roles');
        $categories = $user->categories;
        $groups = $user->groups->where('enabled', 1);
        $contracts = $user->contracts;
        
        return view('users.show', compact('user', 'roles', 'categories', 'groups', 'contracts'));
    }
}
