<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;


class GitController extends Controller
{
    public function showCalc(Request $request)
    {
        $action = $request->action;
        $v1Path = public_path().'/v1';
        $v2Path = public_path().'/v2';

        $v1 = File::isDirectory($v1Path);
        $v2 = File::isDirectory($v2Path);

        if($v1){
            $v1_files = getDirContents($v1Path);
        }

        if($v2){
            $v2_files = getDirContents($v2Path);
        }

        if($action == 'v1vsv2'){
            return view('diffdata',['files' => array_diff($v1_files, $v2_files)]);
        }

        if($action == 'v2vsv1'){
            return view('diffdata',['files' => array_diff($v2_files, $v1_files)]);
        }

        $common_files =  array_intersect($v1_files, $v2_files);
        $diff_in_files = [];
        
        foreach ($common_files as $key => $value) {
            $v1_file_path =  $v1Path. DIRECTORY_SEPARATOR .$value;
            $v2_file_path =  $v2Path. DIRECTORY_SEPARATOR . $value;
            
            if( md5_file($v1_file_path) === md5_file($v2_file_path) ) {
                continue;
            } else {
                array_push($diff_in_files, $value);
            }
        }

        if($action == 'difference'){
            return view('diffdata',['files' => $diff_in_files]);
        }

        if($action == 'differenceinfiles' && $request->file_name == null){
            $string_old = file_get_contents($v1Path. DIRECTORY_SEPARATOR. $diff_in_files[0]);
            $string_new = file_get_contents($v2Path. DIRECTORY_SEPARATOR. $diff_in_files[0]);
            $diff = get_decorated_diff($string_old, $string_new, $diff_in_files[0]);
            $diff['all_files'] = $diff_in_files;
            
        }

        if($action == 'differenceinfiles' && $request->file_name !== null){
            $string_old = file_get_contents($v1Path. DIRECTORY_SEPARATOR. $request->file_name);
            $string_new = file_get_contents($v2Path. DIRECTORY_SEPARATOR. $request->file_name);
            $diff = get_decorated_diff($string_old, $string_new);
            
        }

        return $diff;
    }
}


