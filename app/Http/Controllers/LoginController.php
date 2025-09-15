<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Mbranch;
use App\Models\User;

class LoginController extends Controller
{
    //

    public function index(){
        return view('login.index',[
            'title'=> 'Login',
        ]);
    }

    public function cekUsername(){ //cek username & password

    }

    public function loginSubmit(){

    }

    public function registerUser(){
        return view('login.user',[
            'title'=> 'Register',
            'users'=> User::all(),
            'mbranch'=>Mbranch::all(),
        ]);
    }

    public function auth(Request $request){
        $credentials= $request->validate([
            'username'=>'required',
            'password'=>'required'
        ]);

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->with('loginError','Login failed');

    }

    public function insertUser(Request $request){
        $validasi= $request->validate([
            'username'=>['required','min:3','max:200','unique:users'],
            'name'=>'required|max:200',
            'password'=>'required|min:5|max:255',
            'level'=>'required|max:200',
            'cabang'=>'required|max:200',
            'status'=>'required|max:200',
        ]);

        $validasi['password']=bcrypt($validasi['password']);

        User::create($validasi);

        return redirect('/register')->with('success','Pengguna berhasil disimpan');
    }

    public function updateUser(Request $request, User $user){
        $validasi= $request->validate([
            'name'=>'required|max:200',
            'level'=>'required|max:200',
            'cabang'=>'required|max:200',
            'status'=>'required|max:200',
        ]);
        if($request->password!=""){
            $validasi['password']=bcrypt($validasi['password']);
        }
        User::where('id',$request->id)
                ->update($validasi);
        return redirect('/register')->with('success','Pengguna berhasil diubah');
    }

    public function deleteUser(Request $request){
        User::destroy($request->id);
        return redirect('/register')->with('success','Pengguna berhasil dihapus');
    }

    public function logout(){
        Auth::logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect('/');
    }
}
