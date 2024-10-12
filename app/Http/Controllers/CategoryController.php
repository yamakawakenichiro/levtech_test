<?php
use App\Models\Category;

public function create(Category $category)
{
return view('posts.create')->with(['categories' => $category->get()]);
}
