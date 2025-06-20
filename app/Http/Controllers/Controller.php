<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //
}
class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }
}