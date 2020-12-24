<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ConfigGroupRequest;
use App\Service\ConfigGroupService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;

class ConfigGroupController extends Controller
{
    protected $configService;

    public function __construct(ConfigGroupService $configGroupService)
    {
        $this->configService = $configGroupService;
    }

    /**
     * 标签列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Swoft\Http\Message\Response|\think\response\View
     * @throws \Throwable
     */
    public function index()
    {
        return view('admin.config_group.index');
    }

    /**
     * 标签数据接口
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $res = $this->configService->getConfigLimit($request);
        $data = [
            'code' => 0,
            'msg' => '正在请求中...',
            'count' => $res->total(),
            'data' => $res->items(),
        ];
        return Response::json($data);
    }

    /**
     * 添加标签
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Swoft\Http\Message\Response|\think\response\View
     * @throws \Throwable
     */
    public function create()
    {
        return view('admin.config_group.create');
    }

    /**
     * 添加标签
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(ConfigGroupRequest $request)
    {
        try {
            $data = $request->all(['name', 'sort']);
            $this->configService->store($data);
            return Redirect::to(URL::route('admin.config_group'))->with(['success' => '更新成功']);
        } catch (\Exception $exception) {
            return Redirect::back()->withErrors('添加失败');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * 更新标签
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Swoft\Http\Message\Response|\think\response\View
     * @throws \Throwable
     */
    public function edit($id)
    {
        $configGroup = $this->configService->getOne($id);
        return view('admin.config_group.edit', compact('configGroup'));
    }

    /**
     * 更新标签
     *
     * @param ConfigGroupRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ConfigGroupRequest $request, $id)
    {
        $configGroup = $this->configService->getOne($id);
        try {
            $data = $request->all(['name', 'sort']);
            $configGroup->update($data);
            return Redirect::to(URL::route('admin.config_group'))->with(['success' => '更新成功']);
        } catch (\Exception $exception) {
            return Redirect::back()->withErrors('更新失败');
        }
    }

    /**
     * 删除标签
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (!is_array($ids) || empty($ids)) {
            return Response::json(['code' => 1, 'msg' => '请选择删除项']);
        }
        $group = $this->configService->getOneWithConfigurations($ids[0]);
        if ($group->configurations->isNotEmpty()) {
            return Response::json(['code' => 1, 'msg' => '该组存在配置项，禁止删除']);
        }
        try {
            $group->delete();
            return Response::json(['code' => 0, 'msg' => '删除成功']);
        } catch (\Exception $exception) {
            return Response::json(['code' => 1, 'msg' => '删除失败', 'data' => $exception->getMessage()]);
        }
    }
}
