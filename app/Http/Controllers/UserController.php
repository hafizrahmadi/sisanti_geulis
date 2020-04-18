<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\ModelUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class UserController extends Controller
{
    public function index(){
        if(!Session::get('login')){
            // return redirect('/')->with('alert','Kamu harus login dulu');
            return $this->login();
        }
        else{
            return view('user');
        }
    }

    public function login(){
        return view('login');
    }

    public function loginPost(Request $request){

        $username = $request->username;
        $password = $request->password;

        $data = ModelUser::where('username',$username)->first();
        if($data){ //apakah email tersebut ada atau tidak
            // if(Hash::check($password,$data->password)){
            if ($password==$data->password) {
                Session::put('username',$data->username);
                Session::put('role',$data->role);
                Session::put('token',$data->token);
                Session::put('login',TRUE);
                return redirect('home');
            }
            else{
                return redirect('/')->with('alert','Username atau Password, Salah !');
            }
        }
        else{
            return redirect('/')->with('alert','Username atau Password, Salah!');
        }
    }

    public function logout(){
        Session::flush();
        return redirect('/')->with('alert','Anda sudah logout');
    }

    public function masteruser(Request $request){
        return view('register');
    }

    public function getListUser(){
        // $sel = DB::connection('mysql')->select("SELECT * from tb_user");
        $sel = ModelUser::all();
        return json_encode($sel);
    }

    public function addUserPost(Request $request){
        $this->validate($request, [
            // 'name' => 'required|min:4',
            // 'email' => 'required|min:4|email|unique:users',
            'username'=>'required',
            'password' => 'required',
            'conf_password' => 'required|same:password',
            'role'=>'required',
        ]);
        if ($request->id_user == '') {
            $data =  new ModelUser();
            $data->username = $request->username;
            $data->password = $request->password;
            $data->role = $request->role;
            $data->token = "-"; //sementara
            // $data->password = bcrypt($request->password);
            // $data->password = $request->password;
            return json_encode($data->save());
        }else{
            $xx = ModelUser::where('id',$request->id_user)->update(['username'=>$request->username,'password'=>$request->password,'role'=>$request->role]);
            return json_encode($xx);
        }
        

        // return redirect('login')->with('alert-success','Kamu berhasil Register');
    }

    public function deleteUser(Request $request){
    $data = ModelUser::find($request->id);

    return json_encode($data->delete());
    }
}
