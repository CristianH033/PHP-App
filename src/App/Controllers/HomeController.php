<?php

namespace NewsApp\App\Controllers;

use NewsApp\Core\Controller;
use NewsApp\Core\Request;
use NewsApp\Core\View;

class HomeController extends Controller
{

    public function home(Request $request)
    {
        View::render('home');
    }
}
