<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\JsonStorageService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->query('user_id');

        if ($userId) {
            $categories = Category::whereNull('user_id')
                ->orWhere('user_id', $userId)
                ->get();
        } else {
            $categories = Category::whereNull('user_id')->get();
        }

        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id'
        ]);

        $category = Category::create($validated);

        return response()->json($category, 201);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }
        $category->delete();
        return response()->json(['message' => 'Category deleted']);
    }
}