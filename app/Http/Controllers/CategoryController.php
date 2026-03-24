<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'List of Categories',
            'data' => Category::all()
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $category = Category::create(['name' => $request->input('name')]);
            return response()->json([
                'status' => 'success',
                'message' => 'Category Created Successfully',
                'data' => $category
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $category = Category::find($id);
        if ($category) {
            return response()->json([
                'status' => 'success',
                'message' => "Category Details fetched successfully",
                'data' => $category
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => "No Records Found"
        ], 404);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found'
            ], 404);
        }

        try {
            $category->update(['name' => $request->input('name')]);
            return response()->json([
                'status' => 'success',
                'message' => 'Category Updated Successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found'
            ], 404);
        }

        try {
            $category->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted Successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
