<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/12/23
 * Time: 15:58
 */

namespace App\Service;

use App\Models\Article;
use App\Models\ArticleTag;

class ArticleService
{
    public function getArticleData($request)
    {
        return Article::getData($request);
    }

    public function store($data)
    {
        return Article::create($data);
    }

    public function getWithTags($id)
    {
        return Article::with('tags')->findOrFail($id);
    }

    public function destroyManyArticle($ids)
    {
        return Article::whereIn('id', $ids)->delete();
    }

    public function destroyManyArticleTags($ids)
    {
        return ArticleTag::whereIn('article_id', $ids)->delete();
    }

    public function destroyManyTagArticle($ids)
    {
        return ArticleTag::whereIn('tag_id', $ids)->delete();
    }

}