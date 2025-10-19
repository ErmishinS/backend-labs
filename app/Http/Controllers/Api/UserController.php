<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\JsonStorageService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected JsonStorageService $storage;
    protected string $collection = 'users';

    public function __construct()
    {
        $this->storage = new JsonStorageService();
    }

    public function index(): JsonResponse
    {
        $users = $this->storage->all($this->collection);
        return response()->json($users);
    }

    public function show($id): JsonResponse
    {
        $user = $this->storage->find($this->collection, $id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    public function store(Request $request): JsonResponse
    {
        $name = $request->input('name');
        if (!$name) {
            return response()->json(['error' => 'Name is required'], 400);
        }

        $user = $this->storage->create($this->collection, [
            'name' => $name,
        ]);

        return response()->json($user, 201);
    }

    public function destroy($id): JsonResponse
    {
        $deleted = $this->storage->delete($this->collection, $id);
        if (!$deleted) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json(['success' => true]);
    }
}