<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        // Logic để hiển thị trang tra cứu vé
        return view('about.about'); // Đảm bảo bạn có view này
    }
}
