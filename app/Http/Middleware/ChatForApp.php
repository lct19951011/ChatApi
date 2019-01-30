<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use App\Helpers\ErrorMessage;

class ChatForApp
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
        $res = $request->all();

        if(!isset($res['app_key']) || !isset($res['union_id'])  )
        {
            return response()->json(ErrorMessage::getErrorMsg('600001'));
        }
        $data = $res;
        $app =DB::table('app')->where('key',$res['app_key'])->where('union_id',$res['union_id'])->first();

        $request->attributes->add(compact('app'));

        $request->attributes->add(compact('data'));
        return $next($request);
        
    }
}
