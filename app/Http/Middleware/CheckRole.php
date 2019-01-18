<?php
// NEW
namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * 运行请求过滤器
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $role
     * @return mixed
     * translator http://laravelacademy.org
     * 
     * 中间件还可以接收额外的自定义参数，例如，如果应用需要在执行给定动作之前验证认证用户是否拥有指定的角色，可以创建一个 CheckRole 来接收角色名作为额外参数。
     * 额外的中间件参数会在 $next 参数之后传入中间件：
     */
    public function handle($request, Closure $next, $role)
    {
        var_dump($role);die;
        if (! $request->user()->hasRole($role)) {
            // Redirect...
        }

        return $next($request);
    }


    //terminate 方法将会接收请求和响应作为参数。定义了一个终端中间件之后，还需要将其加入到 app/Http/Kernel.php 文件的全局中间件列表中。

    public function terminate($request, $response)
    {
        // 存储session数据...
    }

}