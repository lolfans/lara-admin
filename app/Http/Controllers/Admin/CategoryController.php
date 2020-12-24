<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use App\Service\CategoryService;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * 分类列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Swoft\Http\Message\Response|\think\response\View
     * @throws \Throwable
     */
    public function index()
    {
        return view('admin.category.index');
    }

    /**
     * 分类列表数据接口
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $res = $this->categoryService->getCategoryAll();
        $data = [
            'code' => 0,
            'msg' => '正在请求中...',
            'count' => $res->count(),
            'data' => $res
        ];
        return Response::json($data);
    }

    /**
     * 添加分类
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Swoft\Http\Message\Response|\think\response\View
     * @throws \Throwable
     */
    public function create()
    {
        $categories = $this->categoryService->getCategory('asc');
        return view('admin.category.create', compact('categories'));
    }

    /**
     * 添加分类
     *
     * @param CategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CategoryRequest $request)
    {
        try {
            $data = $request->all(['name', 'sort', 'parent_id']);
            $this->categoryService->store($data);
            return Redirect::to(URL::route('admin.category'))->with(['success' => '添加成功']);
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
     * 更新分类
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Swoft\Http\Message\Response|\think\response\View
     * @throws \Throwable
     */
    public function edit($id)
    {
        $category = $this->categoryService->getOne($id);
        $categories = $this->categoryService->getCategory('asc');
        return view('admin.category.edit', compact('category', 'categories'));
    }

    /**
     * 更新分类
     *
     * @param CategoryRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CategoryRequest $request, $id)
    {
        try {
            $data = $request->all(['name', 'sort', 'parent_id']);
            $category = $this->categoryService->getOne($id);
            $category->update($data);
            return Redirect::to(URL::route('admin.category'))->with(['success' => '更新成功']);
        } catch (\Exception $exception) {
            return Redirect::back()->withErrors('更新失败');
        }
    }

    /**
     * 删除分类
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        $category = $this->categoryService->getWithChildAndArticle($ids[0]);
        if ($category == null) {
            return Response::json(['code' => 1, 'msg' => '分类不存在']);
        }
        if ($category->childs->isNotEmpty()) {
            return Response::json(['code' => 1, 'msg' => '该分类下有子分类，不能删除']);
        }
        if ($category->articles->isNotEmpty()) {
            return Response::json(['code' => 1, 'msg' => '该分类下有文章，不能删除']);
        }
        try {
            $category->delete();
            return Response::json(['code' => 0, 'msg' => '删除成功']);
        } catch (\Exception $exception) {
            return Response::json(['code' => 1, 'msg' => '删除失败']);
        }
    }
}
