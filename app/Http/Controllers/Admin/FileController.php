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
      $this->validate($request, [
        'file' => 'required|mimes:pdf'
      ]);
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

    public function update(Request $request)
    {
      $this->validate($request, [
          'name' => 'required|max:255', 
      ]);

      $id = $request->id;
      $name = $request->name;

      $file = File::find($id);
      $file->name = $name;
      $file->save();

      return response()->json(['success'=>'Данные обновлены']);
      //dd(File::find());
    }

    public function delete(Request $request, $id)
    {
      $file = File::find($id);
      Storage::disk('local')->delete('public/'.$file->link); 

      $file->delete();
      return response()->json(['success'=>'Файл удален']);
    }
}
