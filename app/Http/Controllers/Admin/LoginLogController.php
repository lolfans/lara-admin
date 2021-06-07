<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Service\Contract\LoginLogServiceInterface;
use App\Service\Contract\ConfigGroupServiceInterface;

class LoginLogController extends Controller
{
    protected $loginLogService;

    public function __construct(LoginLogServiceInterface $loginLogService)
    {
        $this->loginLogService = $loginLogService;
    }

    /**
     * 登录日志主页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Swoft\Http\Message\Response|\think\response\View
     * @throws \Throwable
     */
    public function index()
    {
        return view('admin.log.login');
    }

    /**
     * 数据接口
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $res = $this->loginLogService->getLoginLog($request);
        $data = [
            'code' => 0,
            'msg' => '正在请求中...',
            'count' => $res->total(),
            'data' => $res->items(),
        ];
        return Response::json($data);
    }

    /**
     * 删除
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, ConfigGroupServiceInterface $configGroupService)
    {
        $ids = $request->get('ids');
        if (!is_array($ids) || empty($ids)) {
            return Response::json(['code' => 1, 'msg' => '请选择删除项']);
        }
        //查询配置是否允许删除 0-禁止，1-允许
        $configuration = $configGroupService->getCanDeleteConfiguration('delete_login_log');
        if ($configuration == null) {
            return Response::json(['code' => 1, 'msg' => '系统已设置禁止删除登录日志']);
        }
        try {
            $this->loginLogService->destroy($ids);
            return Response::json(['code' => 0, 'msg' => '删除成功']);
        } catch (\Exception $exception) {
            return Response::json(['code' => 1, 'msg' => '删除失败', 'data' => $exception->getMessage()]);
        }
    }

}
