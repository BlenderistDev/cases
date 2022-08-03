<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use Illuminate\Http\Request;

class CasesController extends Controller
{
    public function index(Cases $cases)
    {
        $cases->load('skins');
        return $cases;
    }
}
