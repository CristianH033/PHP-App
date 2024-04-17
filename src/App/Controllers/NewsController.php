<?php

namespace NewsApp\App\Controllers;

use NewsApp\Core\Controller;
use NewsApp\Core\Http\Request;
use NewsApp\Core\View;

class NewsController extends Controller
{

    public function index(Request $request)
    {
        $news = [
            ["id" => "1", "title" => "Lorem ipsum", "content" => "lorem ipsum dolor sit amet consectetur", "author" => "John Doe"],
            ["id" => "2", "title" => "Lorem ipsum", "content" => "lorem ipsum dolor sit amet consectetur", "author" => "John Doe"],
            ["id" => "3", "title" => "Lorem ipsum", "content" => "lorem ipsum dolor sit amet consectetur", "author" => "John Doe"],
            ["id" => "4", "title" => "Lorem ipsum", "content" => "lorem ipsum dolor sit amet consectetur", "author" => "John Doe"],
        ];

        View::render('news/index', ['news' => $news]);
    }

    public function show(Request $request, string $id)
    {
        View::render('news/show', ['new' => ['id' => $id, 'title' => 'Lorem ipsum', 'content' => 'lorem ipsum dolor sit amet consectetur', 'author' => 'John Doe']]);
    }
}
