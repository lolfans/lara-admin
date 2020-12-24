<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RoleCreateRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Service\PermissionService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use App\Service\RoleService;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * 角色列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Swoft\Http\Message\Response|\think\response\View
     * @throws \Throwable
     */
    public function index()
    {
        return view('admin.role.index');
    }

    /**
     * 角色列表接口数据
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $res = $this->roleService->getRole($request);
        $data = [
            'code' => 0,
            'msg' => '正在请求中...',
            'count' => $res->total(),
            'data' => $res->items()
        ];
        return Response::json($data);
    }

    /**
     * 添加角色
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Swoft\Http\Message\Response|\think\response\View
     * @throws \Throwable
     */
    public function create()
    {
        return view('admin.role.create');
    }

    /**
     * 添加角色
     *
     * @param RoleCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RoleCreateRequest $request)
    {
        try {
            $this->roleService->store($request);
            return Redirect::to(URL::route('admin.role'))->with(['success' => '添加成功']);
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
     * 更新角色
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Swoft\Http\Message\Response|\think\response\View
     * @throws \Throwable
     */
    public function edit($id)
    {
        $role = $this->roleService->getOne($id);
        return view('admin.role.edit', compact('role'));
    }

    /**
     * 更新角色
     *
     * @param RoleUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RoleUpdateRequest $request, $id)
    {
        try {
            $role = $this->roleService->getOne($id);
            $data = $request->only(['name', 'display_name']);
            $role->update($data);
            return Redirect::to(URL::route('admin.role'))->with(['success' => '更新成功']);
        } catch (\Exception $exception) {
            return Redirect::back()->withErrors('更新失败');
        }
    }

    /**
     * 删除角色
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
        try {
            $this->roleService->destroy($ids);
            return Response::json(['code' => 0, 'msg' => '删除成功']);
        } catch (\Exception $exception) {
            return Response::json(['code' => 1, 'msg' => '删除失败']);
        }
    }

    /**
     * 分配权限
     *
     * @param Request $request
     * @param $id
     * @param PermissionService $permissionService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Swoft\Http\Message\Response|\think\response\View
     * @throws \Throwable
     */
    public function permission(Request $request, $id,PermissionService $permissionService)
    {
        $role = $this->roleService->getOne($id);
        $permissions = $permissionService->getPermissionWithChild();
        foreach ($permissions as $p1) {
            $p1->own = $role->hasPermissionTo($p1->id) ? 'checked' : false;
            if ($p1->childs->isNotEmpty()) {
                foreach ($p1->childs as $p2) {
                    $p2->own = $role->hasPermissionTo($p2->id) ? 'checked' : false;
                    if ($p2->childs->isNotEmpty()) {
                        foreach ($p2->childs as $p3) {
                            $p3->own = $role->hasPermissionTo($p3->id) ? 'checked' : false;
                        }
                    }
                }
            }
        }
        return view('admin.role.permission', compact('role', 'permissions'));
    }

    /**
     * 存储权限
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignPermission(Request $request, $id)
    {
        $role = $this->roleService->getOne($id);
        $permissions = $request->get('permissions', []);
        try {
            $role->syncPermissions($permissions);
            return Redirect::to(URL::route('admin.role'))->with(['success' => '更新成功']);
        } catch (\Exception $exception) {
            return Redirect::back()->withErrors('更新失败');
        }
    }
}
