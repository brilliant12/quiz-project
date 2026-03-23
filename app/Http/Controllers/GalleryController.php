<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
class GalleryController extends Controller
{
    public function viewd()
    {
       return view('gallery.add');
    }
    public function store(Request $request)
    {

       
        $validated = $request->validate([
            'category' => 'required|in:media,photos,team,about',
            'name' => 'required|string|max:255',
            'about_us' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            // 'status' => 'nullable|boolean',
            // 'added_by' => 'nullable|integer',
            // 'request_ip' => 'nullable|string|max:255',
        ]);
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $validatedData['image'] = $imageName;
        }
        // Create the gallery record
        $gallery = new Gallery();
        $gallery->category = $validated['category'];
        $gallery->name = $validated['name'];
        $gallery->about_us = $validated['about_us'];
        $gallery->status = $validated['status'] ?? true;
        $gallery->added_by = 1;
        $gallery->request_ip =$request->ip();
        $gallery->image= $validatedData['image'];
        $gallery->save();
    
        // Return success response as JSON
        return response()->json(['message' => 'Gallery created successfully!'], 200);
    }
    public function edit()
    {

    }
    public function update()
    {

    }
    public function delete(Request $req)
    {
      $id=$req->input('id');
      $deleted = Gallery::destroy($id);
     if($deleted)
     {
        return response()->json(['status'=>true,'message'=>'Data Deleted Successfully.'],201 );
     }
    return  response()->json(['status'=>false,'message'=>'Data Not found.'],401);

    }
    public function update_status(Request $req)
    {
        $id = $req->input('id');
    
        $updated = DB::table('galleries')
                     ->where('id', $id)
                     ->update(['status' => DB::raw('CASE WHEN status = 1 THEN 2 ELSE 1 END')]);
    
        if ($updated) {
            return response()->json(['status' => true, 'message' => 'Status changed successfully.'], 200);
        }
    
        return response()->json(['status' => false, 'message' => 'Failed to change status.'], 400);
    }
    public function list()
    {

    }
    public function fetchGalleries()
    {
        return DataTables::of(Gallery::query())
        ->addColumn('actions', function ($gallery) {
            $statusClass = $gallery->status == '1' ? 'success' : 'warning';
            $statusText = ucfirst(strtolower($gallery->status==1?'ACTIVE':'INACTIVE'));
            
            return '<button class="btn btn-primary btn-sm" onclick="editData(this)" data-id="' . $gallery->id . '"  id="edit' . $gallery->id . '">Edit</button> 
                    <button class="btn btn-danger btn-sm" onclick="deleteData(this)" data-id="' . $gallery->id . '"  id="delete' . $gallery->id . '">Delete</button>
                    <button class="btn btn-' . $statusClass . ' btn-sm" id="status' . $gallery->id . '" onclick="changeStatus(this)" data-id="' . $gallery->id . '">' . $statusText . '</button>';
        })
        ->rawColumns(['actions'])
        ->make(true);
    }
    
}
