<?php

namespace App\Http\Controllers;

use App\User;
use App\UserRepository;
use App\Http\Requests\UserFormRequest;
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

    public function index(Request $request)
    {
        $users = $this->userRepository->search($request->all(), false);
        $filter = array(    
            'name' => $request->get('name'),
        );
        
    	return view('users.index', compact('users','filter'));
    }

    public function edit($id)
    {
        $user  = User::find($id);
        $roles = config('options.roles');
		
    	return view('users.edit',compact('user','roles'));
    }

    public function show($id)
    {
        $user       = User::find($id);
        $categories = $user->categories;
        $groups     = $user->groups->where('enabled',1);
        $roles      = config('options.roles');
        
        return view('users.show',compact('user','roles','categories','groups'));
    }

    public function create()
    {
        $roles = config('options.roles');
        
        return view('users.create',compact('roles'));
    }

    public function store(UserFormRequest $request)
    {
        $user = new User;
        $user->fill($request->all());
        $user->password = bcrypt($request->get('name'));
        $user->remember_token = str_random(10);
        $user->save();

        return redirect('/users');
    }

    public function update(UserFormRequest $request,$id)
    {
        $user = User::find($id);
        $user->update($request->all());

        return redirect('/users');
    }
}
