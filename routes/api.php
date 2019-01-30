<?php

use Illuminate\Http\Request;

use App\Http\Middleware\CheckAge;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->post('/user', function (Request $request) {
    
 
    return $request->user();
});




Route::group(['middleware' => ['chat']], function () {

    
Route::any('login', 'Api\UserClass@login')->name('login');

Route::any('recieve', 'Api\UserClass@recieve');


});



// 网址不带特有参数 跳转
Route::get('/', function () {
    //
})->middleware('token');


//中间件在 HTTP Kernel 中被定义后，可以使用 middleware 方法将其分配到路由：
Route::get('admin/profile', function () {
    //
})->middleware('auth');

//使用数组分配多个中间件到路由：
Route::get('/', function () {
    //
})->middleware('first', 'second');



//分配中间件的时候还可以传递完整的类名：
Route::get('admin/profile', function () {
    //
})->middleware(CheckAge::class);


//中间件组可以被分配给路由和控制器动作，使用和单个中间件分配同样的语法。再次申明，中间件组的目的只是让一次分配给路由多个中间件的实现更加方便：
Route::get('/', function () {
    //
})->middleware('web');

Route::group(['middleware' => ['web']], function () {
    //
});


Route::get('users/{user}', function (App\User $user) {
    return $user->email;
});