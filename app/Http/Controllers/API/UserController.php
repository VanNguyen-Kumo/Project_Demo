<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeAddressRequest;
use App\Http\Requests\ChangePassRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserLogin;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['index', 'destroy']]);
        $this->middleware('auth:user', ['only' => ['show', 'update', 'logout']]);
    }

    public function index()
    {
        $user = User::query()->where('email_address', 'LIKE', '%' . request('keyword') . '%')->orderBy('created_at')->get();
        return response()->json([
            'data' => $user
        ]);
    }

    public function store(UserRequest $request)
    {
        $user = User::create([
            'email_address' => $request['email_address'],
            'password' => bcrypt($request['password']),
        ]);
        toast('Register User Success', 'success', 'top-right');
        return response()->json([
            'data' => $user,
            'message' => 'Register success'
        ]);

    }

    public function show()
    {
        $id = \auth()->guard('user')->id();
        $user = User::where('id', $id)->first();
        return response()->json($user);
    }

    public function update(UpdateUserRequest $request)
    {
        $id = \auth()->guard('user')->id();
        $params = $request->validated();
        User::where('id', $id)->update($params);
        return response()->json([
            'message' => 'User update successfully',
            'data' => $params
        ]);
    }

    public function change_address(ChangeAddressRequest $request)
    {
        $id = \auth()->guard('user')->id();
        $params = $request->validated();
        User::where('id', $id)->update($params);
        return response()->json([
            'message' => 'User update successfully',
            'data' => $params
        ]);
    }

    public function change_password(ChangePassRequest $request)
    {
        $id = \auth()->guard('user')->id();
        $params = $request->only(['password']);
       $params['password'] = bcrypt($params['password']);
        User::where('id', $id)->update($params);
        return response()->json([
            'message' => 'User update successfully',
            'data' => $params
        ]);
    }

    public function update_image(UpdateImageRequest $request)
    {
        $id = \auth()->guard('user')->id();
        $file = $request->file('image_url');
        $name = $file->getClientOriginalName();
        $filebath = $name;
        Storage::disk('s3')->put('images/' . $filebath, file_get_contents($file));
        $params = $request->validated();
        $params['image_url'] = 'https://project-demo-images.s3.amazonaws.com/images/' . $name;
        User::where('id', $id)->update($params);
        return response()->json([
            'message' => 'User update successfully',
            'data' => $params
        ]);
    }

    public function destroy($id)
    {
        toast('Delete User Success', 'success', 'top-right');
        User::query()->where('id', $id)->delete();
        return response()->json('User delete successfully');
    }

    public function login(UserLogin $request)
    {
        $login_type = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL)
            ? 'email_address'
            : 'display_name';

        $request->merge([
            $login_type => $request->input('login')
        ]);

        $credentials = $request->only($login_type, 'password');
        if (!$token = auth('user')->attempt($credentials)) {
            return response()->json(['error' => 'invalid_username_or_password']);
        }
        $id = auth('user')->id();
        return $this->respondWithToken($token, $id);
    }

    protected function respondWithToken($token, $id)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 3600,
            'message' => 'Login success',
            'data' => $id,
        ]);
    }

    public function logout(Request $request)
    {
        auth('web')->logout(true);
        return response()->json(['message' => 'Logout success']);
    }
}
