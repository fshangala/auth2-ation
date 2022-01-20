<?php

namespace Fshangala\Auth2Ation\Http\Controllers;

use App\Http\Controllers\Controller;
use Fshangala\Auth2Ation\Models\Authorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AuthorizationController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function addPermission(Request $request)
    {
        Gate::authorize('permission',[['action'=>'create','resource'=>'permissions']]);
        $res = null;

        $validData = $this->validate($request, [
            'user_id'=>'required|exists:permissions',
            'action'=>'required',
            'resource'=>'required',
            'grant'=>'required|boolean'
        ]);

        $res = Authorization::create($validData);

        return response($res, 200);
    }

    public function all()
    {
        Gate::authorize('permission',[['action'=>'read','resource'=>'permissions']]);
        $res = Authorization::all();
        return response($res);
    }

    public function deletePermission(Request $request)
    {
        Gate::authorize('permission',[['action'=>'delete','resource'=>'permissions']]);
        $res = null;
        $validData = $this->validate($request, ['permission_id'=>'required']);

        $entry = Authorization::findOrFail($validData['permission_id']);
        $entry->delete();

        return response($entry,200);
    }
}