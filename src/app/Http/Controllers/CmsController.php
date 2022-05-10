<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cms;
use Illuminate\Support\Str;
use Cocur\Slugify\Slugify;

class CmsController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ids) {
            $ids = json_decode($request->ids, true);
            $key = $ids['id'];
            $idss = array();
            for ($i = 0; $i < count($key); ++$i) {
                $idss[] = $key[$i];
            }
            $cms = Cms::whereIn("id", $idss)->get();
            return response()->json($cms);
        }

        $cms = Cms::orderBy($request->field, $request->order)
            ->when($request->search, function ($query, $value) use ($request) {
                $q = $value;
                $columns = ['email'];
                $names = explode(" ", $value);
                $query->where(function ($q) use ($columns, $request) {
                    $names = explode(" ", $request->search);
                    foreach ($columns as $column) {
                        $q->orWhere($column, 'like', "%{$request->search}%");
                    }
                });
            })
            ->paginate($request->perPage);
        return response()->json($cms);
    }

    public function create(Request $request)
    {
        $slugify = new Slugify();
        $cms = new Cms;
        $cms->name = $request->name;
        $cms->content = $request->content;
        $cms->seo_title = $request->seo_title ?? $request->name;
        $cms->seo_description = $request->seo_description;
        $cms->active = $request->active;
        $cms->seo_url = $slugify->slugify($request->name);
        $cms->save();
        return $cms;
    }

    public function edit(Request $request, $id)
    {
        $slugify = new Slugify();
        $cms = Cms::where("id", $id)->first();
        $cms->name = $request->name;
        $cms->content = $request->content;
        $cms->seo_title = $request->seo_title ?? $request->name;
        $cms->seo_description = $request->seo_description;
        $cms->active = $request->active;
        $cms->seo_url = $slugify->slugify($request->name);
        $cms->save();
        return $cms;
    }


    public function show(Request $request, $id)
    {
        $cms = Cms::where("id", $id)->first();
        return response()->json($cms, 200);
    }

    public function delete(Request $request, $id)
    {
            $ids = explode(",", $id);
            Cms::destroy($ids);
            return response()->json(["success" => "removed"], 200);
    }

}
