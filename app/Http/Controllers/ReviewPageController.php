<?php

namespace App\Http\Controllers;

class ReviewPageController extends Controller
{
    public function index($id)
    {
        return view('product-reviews', [
            'id' => $id,
        ]);
    }
}
