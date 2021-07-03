<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Displays faq page
     */
    public function create()
    {
        return view('pages.faq');
    }
}
