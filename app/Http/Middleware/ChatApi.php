<?php

namespace App\Http\Middleware;

use Closure;

class ChatApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $url = $request->url();
        $ip = $_SERVER["REMOTE_ADDR"];
        header('Access-Control-Allow-Origin: *');
        // 响应类型
        header('Access-Control-Allow-Methods:POST,OPTIONS,GET');
        // 响应头设置
        header('Access-Control-Allow-Headers:x-requested-with,content-type');

        if (strtoupper($_SERVER['REQUEST_METHOD']) == "OPTIONS") {
            return response()->make("", 200);
        }

      

       
        return $next($request);
    }
}
