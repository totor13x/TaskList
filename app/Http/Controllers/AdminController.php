<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
  public function index()
  {
    return view('admin');
  }
  public function login()
  {
    return view('admin.login');
  }
  public function upload(Request $request)
  {
    $fileName = time().'.'.$request->file->getClientOriginalExtension();
    $request->file->move(public_path('upload'), $fileName);
          
    return response()->json(['success'=>'You have successfully upload file.']);
  }
}
