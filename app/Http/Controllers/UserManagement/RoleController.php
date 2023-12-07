<?php

namespace App\Http\Controllers\UserManagement;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;


class RoleController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // gets the roles and permission throught the model
        $roles = Role::all();
        $permission = Permission::get();
    
        return view('User-Management.Roles.list', compact('roles','permission'));
    }

    public function store(Request $request)
    {
        //Preforming Validations
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        //Returning Error if Validation Fails
        if ($validator->fails()) {
            return redirect()->back()->with('warning', 'Validation error');
        }

        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        $role->syncPermissions($request->permission);
    
        return redirect()->back()->with('success','Role created successfully');
    }

    public function edit($id) 
    {
        $id = decrypt($id);
        $role = Role::find($id);
        $permission = Permission::get();

        // bellow will get the permissions through migrations
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
    
        return view('User-Management.Roles.edit',compact('role','permission','rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $id = decrypt($id);
        $this->validate($request, [
            'name' => 'required',
            'description' => 'nullable|string',
            'permission' => 'required',
        ]);
    
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->description = $request->input('description');
        $role->save();

        $role->syncPermissions($request->input('permission'));
    
        return redirect()->route('index.page')->with('success','Role updated successfully');
    }

    public function destroy($id)
    {
        $id = decrypt($id);
        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('index.page')->with('success','Role deleted successfully');
    }
}
