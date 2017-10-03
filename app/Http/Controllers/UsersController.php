<?php

namespace App\Http\Controllers;

use App\Category;
use App\User;
use App\UserHoliday;
use App\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        
    	return view('users.edit', compact('user', 'categories'));
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
        $categories = $user->categories;
        $groups = $user->groups->where('enabled', 1);
        $contracts = $user->contracts;
        $holidays = $user->holidays;
        
        if(count($contracts) != 0 ){
            $usercard = $contracts->where('end_date',null)->first()->holidaysCard->first();
        }
        else{
            $usercard = new UserHoliday;
        }
        
        return view('users.show', compact('user', 'categories', 'groups', 'contracts','holidays','usercard'));
    }
}
