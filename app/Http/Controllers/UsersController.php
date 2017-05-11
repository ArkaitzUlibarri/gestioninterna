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
    	//$users =$this->getUsers();
        $users = $this->userRepository->search($request->all(), true);
        
    	return view('users.index', compact('users'));
    }

    public function edit($id)
    {
    	$user=$this->getUser($id);

    	$roles = config('options.roles');
		
    	return view('users.edit',compact('user','roles'));
    }

    public function show($id)
    {
        $user=$this->getUser($id);

        $roles = config('options.roles');
        
        return view('users.show',compact('user','roles'));
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
        $user=User::find($id);

        $user->update($request->all());

        return redirect('/users');
    }

    private function getUser($id)
    {
        return DB::table('users')
            ->select('id','name','lastname_1','lastname_2','role','email')
            ->where('id',$id)
            ->first();
    }

    private function getUsers()
    {
        return DB::table('users')
            ->orderBy('id','asc')
            ->paginate(10);
            //->get();
    }
}
