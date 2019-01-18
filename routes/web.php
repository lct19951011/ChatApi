<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// 网址不带特有参数 跳转
Route::get('/', function () {
    //
})->middleware('token');


// 渲染，传参
Route::group(['middleware'=>['blog']],function(){
    Route::get('/', function () {
        return view('welcome', ['website' => 'Laravel']);
    });

    Route::view('/view', 'welcome', ['website' => 'Laravel学院']);
});


Route::get('post/{id}', function ($id) {
    //
})->middleware('role:editor');


//最简单的
Route::get('hello', function () {
    return 'Hello, Welcome to LaravelAcademy.org';
});

//多种
Route::match(['get', 'post'], 'foo', function () {
    return 'This is a request from get or post';
});
//所有匹配
Route::any('bar', function () {
    return 'This is a request from any HTTP verb';
});


//路由重定向
//其中 here 表示原路由，there 表示重定向之后的路由，301 是一个 HTTP 状态码，用于标识重定向。

Route::redirect('/here', '/there', 301);

//必选参数
//有时我们需要在路由中获取 URI 请求参数。例如，如果要从 URL 中获取用户ID，需要通过如下方式定义路由参数：
// Route::get('user/{id}', function ($id) {
//     return 'User ' . $id;
// });

//可以根据需要在路由中定义多个路由参数：
Route::get('posts/{post}/comments/{comment}', function ($postId, $commentId) {
    return $postId . '-' . $commentId;
});


// 可选参数  有必选参数就有可选参数，这可以通过在参数名后加一个 ? 标记来实现，这种情况下需要给相应的变量指定默认值，当对应的路由参数为空时，使用默认值：
// Route::get('user/{name?}', function ($name = null) {
//     return $name;
// });

// Route::get('user/{name?}', function ($name = 'John') {
//     return $name;
// });


//正则约束  使用正则约束还有一个好处就是避免了 user/{id} 和 user/{name} 的混淆。

// Route::get('user/{name}', function ($name) {
//     // $name 必须是字母且不能为空
// })->where('name', '[A-Za-z]+');

// Route::get('user/{id}', function ($id) {
//     // $id 必须是数字
// })->where('id', '[0-9]+');

// Route::get('user/{id}/{name}', function ($id, $name) {
//     // 同时指定 id 和 name 的数据格式
// })->where(['id' => '[0-9]+', 'name' => '[a-z]+']);

Route::get('user/profile', function () {
    // 通过路由名称生成 URL
    return 'my url: ' . route('profile');
})->name('profile');

// Route::get('user/{id}/profile', function ($id) {
//     $url = route('profile', ['id' => 1]);
//     return $url;
// })->name('profile');