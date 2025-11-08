<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Record;
use App\Services\JsonStorageService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RecordController extends Controller
{
    public function index(Request $request)
    {
        $query = Record::query();

        if ($request->has('user_id')) {
            $query->where('user_id', $request->query('user_id'));
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->query('category_id'));
        }

        if (!$request->has('user_id') && !$request->has('category_id')) {
            return response()->json(['error' => 'At least one parameter required'], 400);
        }

        return response()->json($query->get());
    }

    public function show(int $id): JsonResponse
    {
        $record = Record::with(['user', 'category'])->find($id);

        if (!$record) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        return response()->json($record);
    }

    public function store(Request $request)
    {
        // dd('123');
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01'
        ]);

        $record = Record::create($validated);

        return response()->json($record, 201);
    }

    public function destroy($id)
    {
        $record = Record::find($id);
        if (!$record) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        $record->delete();
        return response()->json(['message' => 'Record deleted']);
    }
}