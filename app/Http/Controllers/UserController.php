<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function me(Request $request): JsonResponse
    {
        try {
            $response = $this->repository->me($request);
            return response()->json([
                'status' => 'success',
                'error' => false,
                'data' => [
                    'message' => 'User validated with success!',
                    'status' => 'authorized',
                    'user' => $response->user
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

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'interests' => 'array|max:5'
        ]);

        try {
            $response = $this->repository->update($request->all());
            return response()->json([
                'status' => 'success',
                'error' => false,
                'data' => [
                    'message' => 'User updated with success!',
                    'user' => $response->user
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

    public function feed(Request $request): JsonResponse
    {
        $request->validate([
            'limit' => 'numeric|max:20'
        ]);

        try {
            $response = $this->repository->feed($request->input('limit') ?? 10);
            return response()->json([
                'status' => 'success',
                'error' => false,
                'data' => [
                    'message' => 'Feed list.',
                    'feed' => $response->feed
                ]
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'status' => 'error',
                'error' => true,
                'data' => [
                    'message' => $ex->getMessage(),
                    'code' => $ex->getCode(),
                    'status' => 'error'
                ]
            ], $ex->getCode());
        }
    }

    public function like(Request $request): JsonResponse
    {
        $request->validate([
           'liked_id' => 'required|uuid'
        ]);

        try {
            $match = $this->repository->match($request->all());
            return response()->json([
                'status' => 'success',
                'error' => false,
                'data' => [
                    'message' => 'Like successfully!.',
                    'is_match' => $match
                ]
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'status' => 'error',
                'error' => true,
                'data' => [
                    'message' => $ex->getMessage(),
                    'code' => $ex->getCode(),
                    'status' => 'error'
                ]
            ], $ex->getCode());
        }
    }

    public function matches(): JsonResponse
    {
        try {
            $matches = $this->repository->matches();
            return response()->json([
                'status' => 'success',
                'error' => false,
                'data' => [
                    'message' => 'Matches list.',
                    'matches' => $matches
                ]
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'status' => 'error',
                'error' => true,
                'data' => [
                    'message' => $ex->getMessage(),
                    'code' => $ex->getCode(),
                    'status' => 'error'
                ]
            ], $ex->getCode());
        }
    }
}
