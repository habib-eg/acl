<?php
namespace Habib\Acl\Http\Controllers;

use Habib\Acl\Models\Route as RouteModel;
use Habib\Acl\Routes;
use Illuminate\Http\Request;

class AclController extends ControllerBase
{

    public function __construct()
    {
    }

    public function index(string $name,Routes $routes)
    {
        return inertia('Acl/Index',['routes'=>$routes->models]);
    }

    public function update(RouteModel $route,Request $request)
    {

        $validated =$request->validate([
            "roles"=>['sometimes','required','array'],
            "roles.*"=>['sometimes','exists:roles,id'],
            "permissions"=>['sometimes','required','array'],
            "permissions.*"=>['sometimes','exists:permissions,id'],
            "is_public"=>['sometimes','boolean'],
            "auth"=>['sometimes','boolean'],
            "is_disable"=>['sometimes','boolean'],
        ]);

        $route->update($validated);

        return back()->with('success','updated');
    }
}
