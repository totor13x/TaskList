<?php

namespace App\Http\Controllers\Admin;

use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class FileController extends Controller
{
    public function show(Request $request)
    {
        $filenew = new File; 
        return response()->json($filenew->all());
    }
    public function create(Request $request)
    {
      $fileName = rand(1,50)."_".time().'.'.$request->file->getClientOriginalExtension();
      //$request->file->move(public_path('upload'), $fileName);
      Storage::disk('local')->putFileAs('public', $request->file, $fileName);
      $data = [
        'name' => $request->file->getClientOriginalName(),
        'link' => $fileName,
      ];
      $filenew = new File;
      $filenew->fill($data);
      $filenew->save();
      return response()->json(['success'=>'You have successfully upload file.']);
    }
}
