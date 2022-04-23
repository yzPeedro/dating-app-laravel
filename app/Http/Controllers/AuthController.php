<?php

namespace App\Http\Controllers;

use App\Repositories\AuthRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthRepository $repository;

    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            $response = $this->repository->login($request->all());
            return response()->json([
               'status' => 'success',
               'error' => false,
               'data' => [
                   'message' => 'User validated with success!',
                   'status' => 'authorized',
                   'user' => $response->user,
                   'tokens' => $response->tokens
               ]
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'status' => 'error',
                'error' => true,
                'data' => [
                    'message' => $ex->getMessage(),
                    'code' => $ex->getCode(),
                    'status' => 'unauthorized'
                ]
            ], $ex->getCode());
        }
    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'age' => 'required|numeric',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'phone' => 'required',
            'locale' => 'required',
            'sex' => 'required',
            'sex_interest' => 'required',
            'interests' => 'array|max:5'
        ]);

        try {
            $response = $this->repository->register($request->all());
            return response()->json([
                'status' => 'success',
                'error' => false,
                'data' => [
                    'message' => 'User registered with success!',
                    'status' => 'authorized',
                    'user' => $response->user,
                    'tokens' => $response->tokens
                ]
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'status' => 'error',
                'error' => true,
                'data' => [
                    'message' => $ex->getMessage(),
                    'code' => $ex->getCode(),
                    'status' => 'Register failed.'
                ]
            ], $ex->getCode());
        }
    }

}
