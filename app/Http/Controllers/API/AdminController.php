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

        $admin = Admin::query()->where('username', 'LIKE', '%' . request('keyword') . '%')->paginate(config('constants.paginate'));
        return  response()->json($admin);
    }

    public function store(AdminRequest $request)
    {
        $admin=Admin::create([
            'username' => $request['username'],
            'password' => bcrypt($request['password']),
        ]);
        toast('Register success','success','top-right');
        return response()->json([
            'message'=> 'admin register successfully',
            'data'=>$admin
        ]);
    }

    public function show($id)
    {
        $admin=Admin::where('id',$id)->first();
        return response()->json($admin);
    }

    public function update(AdminRequest $request, $id)
    {
        $params = $request->validated();
        $params['password'] = bcrypt($params['password']);
        Admin::where('id',$id)->update($params);
        toast('Update success','success','top-right');
        return response()->json( [
            'message'=> 'admin update successfully',
            'data'=>$params
        ]);
    }

    public function destroy($id)
    {
        toast('Delete success','success','top-right');
        Admin::query()->where('id',$id)->delete();
        return response()->json( 'admin delete successfully');
    }
}
