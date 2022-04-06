<?php

namespace App\Http\Controllers;


use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller
{

    public function createAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'mobil_phone' => ['required', 'numeric', 'min:10'],
            'password' => ['required', 'min:8'],
            'c_password' => ['required', 'same:password'],
        ]);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $request['password'] = Hash::make($request['password']);

        $user = User::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'mobil_phone' => $request->mobil_phone,
            'url_facebook' => $request->url_facebook,
            'password' => $request->password,

        ]);

        $tokenResult = $user->createToken('PersonalAccessToken');

        $data["user"] = $user;
        $data["token_type"] = 'Bearer';
        $data["access_token"] = $tokenResult->accessToken;

        return response()->json($data, 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all(), Response::HTTP_UNPROCESSABLE_ENTITY);

        }

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {

            throw new AuthenticationException();
        }
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        $data["user"] = $user;
        $data["token_type"] = 'Bearer';
        $data["access_token"] = $tokenResult->accessToken;
        return response()->json($data, Response::HTTP_OK);

    }

    public function logout(Request $request)
    {
        $result = $request->user()->token()->revoke();
        if ($result) {
            $response = response()->json(['message' => 'User logout successfully.'], 200);
        } else {
            $response = response()->json(['message' => 'Something is wrong.'], 400);
        }
        return $response;
    }
}

