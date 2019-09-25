<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function download(Request $request, $link)
    {
        $file = File::where('link', $link)->get()->first();
        if ($file != null)
        {
            return Storage::download("public/".$file->link);
        }   
        //dd(File::where("link", "=", $link)->get());
        //dd($file->get($link));//$files->findByLink($link));
    }
    public function show(Request $request)
    {
        $files = new File; 
        $files = $files->all();
        /*
        collect($files)->map(function($file){
            $file->link = Storage::url("public/".$file->link);
        });
        */

        return response()->json($files);
    }
}
