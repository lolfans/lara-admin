<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PermissionCreateRequest;
use App\Http\Requests\PermissionUpdateRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use App\Service\PermissionService;

class PermissionController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * 权限列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Swoft\Http\Message\Response|\think\response\View
     * @throws \Throwable
     */
    public function index()
    {
        return view('admin.permission.index');
    }

    /**
     * 权限数据表格
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data()
    {
        $res = $this->permissionService->getPermission();
        $data = [
            'code' => 0,
            'msg' => '正在请求中...',
            'count' => $res->count(),
            'data' => $res
        ];
        return Response::json($data);
    }

    /**
     * 添加权限
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Swoft\Http\Message\Response|\think\response\View
     * @throws \Throwable
     */
    public function create()
    {
        $permissions = $this->permissionService->getPermissionWithChild();
        return view('admin.permission.create', compact('permissions'));
    }

    /**
     * 添加权限
     *
     * @param PermissionCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PermissionCreateRequest $request)
    {
        try {
            $data = $request->all();
            $this->permissionService->store($data);
            return Redirect::to(URL::route('admin.permission'))->with(['success' => '添加成功']);
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
     * 更新权限
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Swoft\Http\Message\Response|\think\response\View
     * @throws \Throwable
     */
    public function edit($id)
    {
        $permission = $this->permissionService->getOne($id);
        $permissions = $this->permissionService->getPermissionWithChild();
        return view('admin.permission.edit', compact('permission', 'permissions'));
    }

    /**
     * 更新权限
     *
     * @param PermissionUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PermissionUpdateRequest $request, $id)
    {
        try {
            $permission = $this->permissionService->getOne($id);
            $data = $request->all();
            $permission->update($data);
            return Redirect::to(URL::route('admin.permission'))->with(['status' => '更新成功']);
        } catch (\Exception $exception) {
            return Redirect::back()->withErrors('更新失败');
        }
    }

    /**
     * 删除权限
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
        $permission = $this->permissionService->getOneWithChildPermission($ids[0]);
        if (!$permission) {
            return Response::json(['code' => 1, 'msg' => '权限不存在']);
        }
        //如果有子权限，则禁止删除
        if ($permission->childs->isNotEmpty()) {
            return Response::json(['code' => 1, 'msg' => '存在子权限禁止删除']);
        }
        try {
            $permission->delete();
            return Response::json(['code' => 0, 'msg' => '删除成功']);
        } catch (\Exception $exception) {
            return Response::json(['code' => 0, 'msg' => '存在子权限禁止删除']);
        }
    }
}
