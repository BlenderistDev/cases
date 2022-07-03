<?php

namespace App\Http\Controllers;

use App\Models\Categories;

class CategoriesController extends Controller
{
    public function index()
    {
        return Categories::with('cases', 'cases.skins')->get();
    }
}
