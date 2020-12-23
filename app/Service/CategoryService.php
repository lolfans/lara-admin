<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/12/23
 * Time: 16:23
 */

namespace App\Service;

use App\Models\Category;

class CategoryService
{
    public function getCategory($rank)
    {
        return Category::getCategory($rank);
    }
}