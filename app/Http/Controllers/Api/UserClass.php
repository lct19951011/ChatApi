<?php
/**
 *用户
 * @time        2017-09-26 12:26:57
 * @created by  Sublime Text 3
 */
namespace App\Http\Controllers\Api;

use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Log;



class UserClass extends Controller
{
    private $user_object;
    private $sms_code_helper;

    public function __construct()
    {
    
    }
    //登录
    public function login(Request $request)
    {
    
        $res = $request->all();

        if( !isset($res['admin_controller']) || $res['admin_controller'] != '123456')
        {
            $data = array(
                'code' => '2000',
                'msg'  => "you are shabi",
            ); 
            return response()->json($data);
        }
        // $res = json_encode($res);

        // var_dump($res);die;
        $message = DB::table('wb_message')->select('send_id')->where('recieve_id',$res['id'])->where('status',1)->get()->toArray();

        // $count = DB::table('wb_message_group')->where('recieve_id',$res['id'])->where('status',1)->get()->toArray();

        $data = array(
                    'code' => '1000',
                    'msg'  => $message,
                  
        );
        
        return response()->json($data);
      
    }

    

    public function recieve(Request $request)
    {
        $res = $request->all();

        if( !isset($res['admin_controller']) || $res['admin_controller'] != '123456')
        {
            $data = array(
                'code' => '2000',
                'msg'  => "you are shabi",
            ); 
            return response()->json($data);
        }
        // $res = json_encode($res);

        // var_dump($res);die;
        $message = DB::table('wb_message')->where('send_id',$res['send_id'])->where('recieve_id',$res['my_id'])->update(['status' => 0]);

        // $count = DB::table('wb_message_group')->where('recieve_id',$res['id'])->where('status',1)->get()->toArray();

        $data = array(
                    'code' => '1000',
                    'msg'  => $message,
                  
        );
        
        return response()->json($data);
      
    }
}
