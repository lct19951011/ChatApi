<?php
/**
 * 聊天室
 * @author      TTTT
 * @time        2018-10-02 03:55:31
 * @created by  Sublime Text 3
 */

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class MeepopsGroup extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'meepops_group';

    /**
     * 主键
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 模型日期列的存储格式
     *
     * @var string
     */
    // protected $dateFormat = 'U';

    /**
     * 当前model连接数据库名称
     *
     * @var string
     */
    // protected $connection = 'connection-name';

   

    /**
     * 处理数据格式 多条
     * @return [type] [description]
     */
    private function dealResults($args)
    {
        foreach ($args as $key => $value) {
            $args[$key] = $this->dealResult($value);
        }
        return $args;
    }

    /**
     * 处理数据格式 单条
     * @param  [type] $args [description]
     * @return [type]       [description]
     */
    private function dealResult($args)
    {
        $args->dateline = date('Y-m-d', $args->dateline);
        return $args;
    }


    /************************************************************************/


    public function saveData($app_id,$args)
    {
        $object = null;
        if (isset($args['group_id']) && !empty($args['group_id'])) {
            // 更新
            $object = self::find($args['group_id']);
            if (empty($object)) {
                return false;
            }
        } else {
            // 新增
            $class_name = get_class();
            $object = new $class_name;
            $object->create_time= time();
        }

       
        $object->status = 1;
        $object->app_id = $app_id;
        $user_id = $object->user_id.','.$args['user_id'];
        $object->user_id = trim($user_id,',');
        $object->save();

 
        return $object->id;
    }


    //微信版删除群聊
    public function deleteGroup($app_id, $args)
    {
        // 事务处理
        DB::beginTransaction();
        try {
            $object = self::where('id', '=', $args['group_id'])->where('app_id',$app_id)->first();

            if (empty($object)) {
                throw new \Exception("Failed");
            }
            
            $delete_user =  explode( ',',trim($args['user_id'],',') );

            $group_user =  explode( ',',trim($object->user_id ,',' ));

            $result=array_diff($group_user,$delete_user);
            
            $user_id = implode(',',$result);

            $object->user_id = $user_id;

            $result = $object->save();
           
            if (!$result) {
                throw new \Exception("Failed");
            }
           
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();//事务回滚
            return false;
        }
        return $result;
    }

   
}
