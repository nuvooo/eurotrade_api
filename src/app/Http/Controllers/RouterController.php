<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cms;

class RouterController extends Controller
{
    public function search($slug)
    {
        $router = Cms::where("seo_url", $slug)->first();
        if($router) {
            return response()->json($router);
        }
        return response(['message' => 'not found'],404);
    }
}
