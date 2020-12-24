<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TagRequest;
use App\Service\ArticleService;
use App\Service\TagService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    /**
     * 标签列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Swoft\Http\Message\Response|\think\response\View
     * @throws \Throwable
     */
    public function index()
    {
        return view('admin.tag.index');
    }

    /**
     * 标签数据接口
     *
     * @param Request $request
     * @param TagService $tagService
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request,TagService $tagService)
    {
        $res = $tagService->getTagLimit($request);
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
        return view('admin.tag.create');
    }

    /**
     * 添加标签
     *
     * @param TagRequest $request
     * @param TagService $tagService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TagRequest $request, TagService $tagService)
    {
        try {
            $data = $request->all(['name', 'sort']);
            $tagService->store($data);
            return Redirect::to(URL::route('admin.tag'))->with(['success' => '更新成功']);
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
     * @param TagService $tagService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Swoft\Http\Message\Response|\think\response\View
     * @throws \Throwable
     */
    public function edit($id,TagService $tagService)
    {
        $tag = $tagService->getOne($id);
        return view('admin.tag.edit', compact('tag'));
    }

    /**
     * 更新标签
     *
     * @param TagRequest $request
     * @param $id
     * @param TagService $tagService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TagRequest $request, $id, TagService $tagService)
    {
        try {
            $tag = $tagService->getOne($id);
            $data = $request->all(['name', 'sort']);
            $tag->update($data);
            return Redirect::to(URL::route('admin.tag'))->with(['success' => '更新成功']);
        } catch (\Exception $exception) {
            return Redirect::back()->withErrors('更新失败');
        }
    }

    /**
     * 删除标签
     *
     * @param Request $request
     * @param TagService $tagService
     * @param ArticleService $articleService
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request,TagService $tagService,ArticleService $articleService)
    {
        $ids = $request->get('ids');
        if (!is_array($ids) || empty($ids)) {
            return Response::json(['code' => 1, 'msg' => '请选择删除项']);
        }
        DB::beginTransaction();
        try {
            //删除中间表article_tag
            $articleService->destroyManyTagArticle($ids);
            //删除主表tag
            $tagService->destroyManyTags($ids);
            DB::commit();
            return Response::json(['code' => 0, 'msg' => '删除成功']);
        } catch (\Exception $exception) {
            DB::rollback();
            return Response::json(['code' => 1, 'msg' => '删除失败', 'data' => $exception->getMessage()]);
        }
    }
}
