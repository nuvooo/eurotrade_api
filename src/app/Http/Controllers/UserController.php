<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function index(Request $request){

        if($request->ids){
            $ids = json_decode($request->ids, true);
            $key = $ids['id'];
            $idss = array();
            for($i = 0; $i < count($key); ++$i) {
              $idss[] = $key[$i];
          }
            $user = User::whereIn("id",$idss)->get();
            return response()->json($user);
        }

            $user = User::orderBy($request->field, $request->order)
            ->with("roles")
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
            return response()->json($user);
    }

    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'email|required|unique:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }
        $user = new User;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->name = $request->name;
        $user->save();
        $user->assignRole($request->role ? $request->role : "user");
        return response()->json($user);
    }

    public function edit(Request $request, $id)
    {
        $role = Role::findByName($request->roles ? $request->roles : "user", 'web');
        $user = User::where("id", $id)->with("roles")->pluck('name')->first();
        $user->syncRoles();
        $user->assignRole($role);
        $user->save();
        return response()->json($user);
    }

    public function delete(Request $request, $id)
    {
            $ids = explode(",", $id);
            User::destroy($ids);
            return response()->json(["success" => "removed"], 200);
    }

    public function show(Request $request, $id)
    {
        $user = User::where("id", $id)->with("roles")->first();
        return response()->json($user, 200);
    }
}
