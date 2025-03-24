<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Livewire\Auth\Login;
use Livewire\Livewire;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function show()
    {
        return view('admin.dashboard');
    }
}
