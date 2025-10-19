<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\JsonStorageService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected JsonStorageService $storage;
    protected string $collection = 'categories';

    public function __construct()
    {
        $this->storage = new JsonStorageService();
    }

    public function index(): JsonResponse
    {
        $categories = $this->storage->all($this->collection);
        return response()->json($categories);
    }

    public function store(Request $request): JsonResponse
    {
        $title = $request->input('title');
        if (!$title) {
            return response()->json(['error' => 'Title is required'], 400);
        }

        $category = $this->storage->create($this->collection, [
            'title' => $title,
        ]);

        return response()->json($category, 201);
    }

    public function destroy($id): JsonResponse
    {
        $deleted = $this->storage->delete($this->collection, $id);
        if (!$deleted) {
            return response()->json(['error' => 'Category not found'], 404);
        }
        return response()->json(['success' => true]);
    }
}