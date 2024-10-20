<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{

    /*
    Categoryオブジェクトの$categoryを受け取り、属する投稿を取得し、投稿の一覧を表示するビューを返します。
    */
    public function index(Category $category)
    {
        return view('categories.index')->with(['posts' => $category->getByCategory()]);
    }
}
