<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ArticleRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Service\ArticleService;
use App\Service\CategoryService;
use App\Service\TagService;

class ArticleController extends Controller
{

    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * 资讯列表
     *
     * @param CategoryService $service
     * @return \Illuminate\Contracts\View\View
     */
    public function index(CategoryService $service)
    {
        $categories = $service->getCategory('asc');
        return View::make('admin.article.index', compact('categories'));
    }

    /**
     * 资讯数据接口
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $result = $this->articleService->getArticleData($request);
        $data = [
            'code' => 0,
            'msg' => '正在请求中...',
            'count' => $result->total(),
            'data' => $result->items(),
        ];
        return Response::json($data);
    }

    /**
     * 添加资讯
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create(CategoryService $categoryService, TagService $tagService)
    {
        //分类
        $categories = $categoryService->getCategory('desc');
        //标签
        $tags = $tagService->getTag();

        return View::make('admin.article.create', compact('tags', 'categories'));
    }

    /**
     * 添加资讯
     *
     * @param ArticleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ArticleRequest $request)
    {
        try {
            $article = $this->articleService->store($request->all());
            $article->tags()->sync($request->get('tags', []));

            return Redirect::to(URL::route('admin.article'))->with(['success' => '添加成功']);
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
     * 更新资讯
     *
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id, CategoryService $categoryService, TagService $tagService)
    {
        $article = $this->articleService->getWithTags($id);
        //分类
        $categories = $categoryService->getCategory('asc');
        //标签
        $tags = $tagService->getTag();
        foreach ($tags as $tag) {
            $tag->checked = $article->tags->contains($tag) ? 'checked' : '';
        }
        return View::make('admin.article.edit', compact('article', 'categories', 'tags'));
    }

    /**
     * 更新资讯
     *
     * @param ArticleRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ArticleRequest $request, $id)
    {
        try {
            $article = $this->articleService->getWithTags($id);
            $article->update($request->all());
            $article->tags()->sync($request->get('tags', []));

            return Redirect::to(URL::route('admin.article'))->with(['success' => '更新成功']);
        } catch (\Exception $exception) {
            return Redirect::back()->withErrors('更新失败');
        }
    }

    /**
     * 删除资讯
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $ids = $request->get('ids');
            if (!is_array($ids) || empty($ids)) {
                return Response::json(['code' => 1, 'msg' => '请选择删除项']);
            }
            //删除主表数据
            $this->articleService->destroyManyArticle($ids);
            //删除中间表article_tag
            $this->articleService->destroyManyArticleTags($ids);

            DB::commit();
            return Response::json(['code' => 0, 'msg' => '删除成功']);
        } catch (\Exception $exception) {
            DB::rollback();
            return Response::json(['code' => 1, 'msg' => '删除失败', 'data' => $exception->getMessage()]);
        }
    }
}
