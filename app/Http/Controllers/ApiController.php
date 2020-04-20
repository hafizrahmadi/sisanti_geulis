<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\ModelUser;
use Illuminate\Support\Str;
use Auth;

/**
 *
 */
class ApiController extends Controller
{

  public function signin(Request $request) {
    //string tokennya
    $token = Str::random(160);

    $username = $request->username;
    $password = $request->password;
    $firebase = $request->firebase;


    $data = ModelUser::where('username',$username)->first();
    if($data){ //apakah email tersebut ada atau tidak
        // if(Hash::check($password,$data->password)){
        if ($password==$data->password) {
          $data->forceFill([
            'token' => $token,
            'firebase' => $firebase
          ])->update();

          return [
            'status' => 'Success',
            'token' => $token,
            'firebase' => $firebase
          ];

        }
        else {
            return ['status' => 'Failed'];
        }
    }
  }

  public function profile($id) {
    $data = ModelUser::where('id',$id)->first();
    return [
      'data' => $data
    ];
  }

}
