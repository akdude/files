<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Jfcherng\Diff\Differ;
use Jfcherng\Diff\DiffHelper;
use Jfcherng\Diff\Factory\RendererFactory;
use Jfcherng\Diff\Renderer\RendererConstant;


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
            if(is_file($v1_file_path) && is_file($v2_file_path)){
                if( md5_file($v1_file_path) === md5_file($v2_file_path) ){
                continue;
                } else {
                    array_push($diff_in_files, $value);
                }
            }
        }

        if($action == 'difference') {
            return view('diffdata',['files' => $diff_in_files]);
        }

        if($action == 'differenceinfiles' && $request->file_name == null){
            $oldFile = $v1Path. DIRECTORY_SEPARATOR. $diff_in_files[0];
            $newFile = $v2Path. DIRECTORY_SEPARATOR. $diff_in_files[0];
        }

        if($action == 'differenceinfiles' && $request->file_name !== null){
            $oldFile = $v1Path. DIRECTORY_SEPARATOR. $request->file_name;
            $newFile = $v2Path. DIRECTORY_SEPARATOR. $request->file_name;
        }
        
        
        $rendererName = 'SideBySide';

        $differOptions = [
            'context' => 3,
            'ignoreCase' => false,
            'ignoreWhitespace' => false,
        ];

        $rendererOptions = [
            'detailLevel' => 'line',
            'language' => 'eng',
            'lineNumbers' => true,
            'separateBlock' => true,
            'showHeader' => true,
            'spacesToNbsp' => false,
            'tabSize' => 4,
            'mergeThreshold' => 0.8,
            'cliColorization' => RendererConstant::CLI_COLOR_ENABLE,
            'outputTagAsString' => false,
            'jsonEncodeFlags' => \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE,
            'wordGlues' => [' ', '-'],
            'resultForIdenticals' => null,
            'wrapperClasses' => ['diff-wrapper', 'table-bordered', 'table-responsive'],
        ];

        $jsonResult = DiffHelper::calculateFiles($oldFile, $newFile, $rendererName, $differOptions, $rendererOptions);
        return ["content" => $jsonResult , "all_files" => $diff_in_files];

    }
}


