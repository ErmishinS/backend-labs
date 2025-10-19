<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\JsonStorageService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RecordController extends Controller
{
    protected JsonStorageService $storage;
    protected string $collection = 'records';

    public function __construct()
    {
        $this->storage = new JsonStorageService();
    }

    public function show($id): JsonResponse
    {
        $record = $this->storage->find($this->collection, $id);
        if (!$record) {
            return response()->json(['error' => 'Record not found'], 404);
        }
        return response()->json($record);
    }

    public function store(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');
        $categoryId = $request->input('category_id');
        $amount = $request->input('amount');

        if (!$userId || !$categoryId || !is_numeric($amount)) {
            return response()->json(['error' => 'user_id, category_id and numeric amount are required'], 400);
        }

        // Optionally: check existence of user/category by reading respective files
        $storage = $this->storage;
        $user = $storage->find('users', $userId);
        $cat = $storage->find('categories', $categoryId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        if (!$cat) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $record = $this->storage->create($this->collection, [
            'user_id' => (int)$userId,
            'category_id' => (int)$categoryId,
            'amount' => (float)$amount,
            'created_at' => now()->toIso8601String(),
        ]);

        return response()->json($record, 201);
    }

    public function destroy($id): JsonResponse
    {
        $deleted = $this->storage->delete($this->collection, $id);
        if (!$deleted) {
            return response()->json(['error' => 'Record not found'], 404);
        }
        return response()->json(['success' => true]);
    }

    /**
     * GET /record?user_id=&category_id=
     * Must accept user_id and/or category_id.
     * If no params -> error 400.
     */
    public function index(Request $request): JsonResponse
    {
        $userId = $request->query('user_id');
        $categoryId = $request->query('category_id');

        if ($userId === null && $categoryId === null) {
            return response()->json(['error' => 'At least one of user_id or category_id query params required'], 400);
        }

        $records = $this->storage->all($this->collection);
        $filtered = array_filter($records, function ($r) use ($userId, $categoryId) {
            if ($userId !== null && (string)$r['user_id'] !== (string)$userId) {
                return false;
            }
            if ($categoryId !== null && (string)$r['category_id'] !== (string)$categoryId) {
                return false;
            }
            return true;
        });

        // Re-index array
        $filtered = array_values($filtered);

        return response()->json($filtered);
    }
}