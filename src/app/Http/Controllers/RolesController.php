<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
class RolesController extends Controller
{
    public function index(Request $request)
    {
        if($request->ids){
            $ids = json_decode($request->ids, true);
            $key = $ids['id'];
            $idss = array();
            for($i = 0; $i < count($key); ++$i) {
              $idss[] = $key[$i];
          }
          return $idss;
            $roles = Role::whereIn("id",$idss)->get();
            return response()->json($roles);
        }

        $roles = Role::orderBy('id','DESC')->paginate($request->perPage);
        return response()->json($roles);
    }

    public function show(Request $request, $id)
    {
        $roles = Role::where('id',$id)->first();
        return response()->json($roles);
    }
}
