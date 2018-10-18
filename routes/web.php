<?php

use Illuminate\Http\Request; 
use App\User;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/login', function (Request $request) use ($router) {

    $this->validate($request, [
       'email' => 'required',
       'password' => 'required'
    ]);
 
    $user = User::where('email', $request->input('email'))->first();

    if(password_verify($request->input('password'), $user->password)){
        $token = base64_encode(str_random(40));
        User::where('email', $request->input('email'))->update(['remember_token' => "$token"]);;
        return response()->json(['status' => 'success','remember_token' => $token]);
    }else{
         return response()->json(['status' => 'fail'],401);
    }
});



$router->group(['prefix' => 'api/'], function ($router) {  
    $router->get('listing/', 'ListingController@index');
});

