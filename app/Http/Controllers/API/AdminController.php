<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\Request;


class AdminController extends Controller
{
    public function index()
    {
        $admin = Admin::query()->where('username', 'LIKE', '%' . request('keyword') . '%')->orderBy('username')->get();
        return  response()->json(['data'=>$admin,'message'=>'Login success']);
    }

    public function store(AdminRequest $request)
    {
        $admin=Admin::create([
            'username' => $request['username'],
            'password' => bcrypt($request['password']),
        ]);
        return response()->json([
            'message'=> 'Admin register successfully',
            'data'=>$admin
        ]);
    }

    public function show($id)
    {
        $admin=Admin::where('id',$id)->first();
        return response()->json(['data'=>$admin]);
    }

    public function update(AdminRequest $request, $id)
    {
        $params = $request->validated();
        $params['password'] = bcrypt($params['password']);
        Admin::where('id',$id)->update($params);
        $admin=Admin::query()->select('*')->where('id',$id)->get();
        return response()->json( [
            'message'=> 'Admin update successfully',
            'data'=>$admin
        ]);
    }

    public function destroy($id)
    {
        Admin::query()->where('id',$id)->delete();
        return response()->json( ['message'=>'Admin delete successfully']);
    }
}
