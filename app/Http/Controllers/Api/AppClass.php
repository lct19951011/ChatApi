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
use App\Helpers\ErrorMessage;
use App\Models\MeepopsGroup;


class AppClass extends Controller
{
    private $user_object;
    private $sms_code_helper;

    public function __construct()
    {
        $this->meepops_group = new MeepopsGroup();
    }
    //获取发送过来的ID
    public function getSendId(Request $request)
    {
    
        $data = $request->attributes->get('data');
        $app = $request->attributes->get('app');
      
        $message = DB::table('wb_message')->select('send_id')->where('recieve_id',$data['id'])->where('status',1)->get()->toArray();

        // $count = DB::table('wb_message_group')->where('recieve_id',$res['id'])->where('status',1)->get()->toArray();

        // $data = array(
        //             'code' => '1000',
        //             'msg'  => $message,
                  
        // );
        
        if($message)
        {
            return response()->json(ErrorMessage::getErrorMsg('1000',$message));
        }
        return response()->json(ErrorMessage::getErrorMsg('2000','传参有误'));
      
    }

    
    //查看消息
    public function lookMessage(Request $request)
    {
        $data = $request->attributes->get('data');
        $app = $request->attributes->get('app');

        $res = DB::table('wb_message')->where('send_id',$data['send_id'])->where('recieve_id',$data['my_id'])->update(['status' => 0]);

        // $count = DB::table('wb_message_group')->where('recieve_id',$res['id'])->where('status',1)->get()->toArray();

         if($res)
        {
            return response()->json(ErrorMessage::getErrorMsg('1000',$res));
        }
        return response()->json(ErrorMessage::getErrorMsg('2000','传参有误'));
      
    }

    //发起群聊或群聊加人
    public function makeGroup(Request $request)
    {
        $data = $request->attributes->get('data');
        $app = $request->attributes->get('app');

        $app_id = $app->id;
        $key = $app->key;
        $union_id = $app->union_id;
        $res = $this->meepops_group->saveData($app_id,$data);
        $group  = array(
                    'group_id' => $res
        );  

        if($res)
        {
            // $url = "http://wingschat.mdzhineng.com/index.php?controller=chat&action=group&my_user_id=5&group_id=$res&union_id=$union_id&app_key=$key";
            return response()->json(ErrorMessage::getErrorMsg('1000',$group));
        }
        return response()->json(ErrorMessage::getErrorMsg('2000','传参有误'));
    }


    /**
     * 微信版删除群聊接口
     * user_id  1,5,8,10
     * union_id 
     * app_key
     * group_id 10
     * 
     */

    public function deleteGroup(Request $request)
    {
        $data = $request->attributes->get('data');
        $app = $request->attributes->get('app');
        $app_id = $app->id;
        $res = $this->meepops_group->deleteGroup($app_id,$data);
        if($res)
        {
            return response()->json(ErrorMessage::getErrorMsg('1000',$res));
        }
        return response()->json(ErrorMessage::getErrorMsg('2000','传参有误'));
    }

    public function updateGroup(Request $request)
    {
        $data = $request->attributes->get('data');
        $app = $request->attributes->get('app');
 
        $group_id = $data['group_id'];
        $add_user_id = $data['user_id'];
        $app_id = $app->id;
        $group = DB::table('wb_meepops_group')->where('app_id',$app_id)->where('group_id',$group_id)->first();
        $old_user_id = $group->user_id;
        $new_user_id = $old_user_id.','.$add_user_id;
        $res = DB::table('wb_meepops_group')->where('id',$group->id)->update(['user_id' => $new_user_id]);
        if($res)
        {
            return response()->json(ErrorMessage::getErrorMsg('1000',$res));
        }
        return response()->json(ErrorMessage::getErrorMsg('2000','传参有误'));
       
    }

    
}
