<?php

function getDirContents($file_path, &$results = array()) {
    $files = scandir($file_path);

    foreach ($files as $key => $value) {
        $path = realpath($file_path . DIRECTORY_SEPARATOR . $value);
        if(str_contains($path, public_path().'/v1')){
            $dir = 'v1';
        }
        if(str_contains($path, public_path().'/v2')){
            $dir = 'v2';
        }
        if (!is_dir($path)) {
            if(!in_array($path, $results)) {
                $results[] = str_replace(public_path().'/'.$dir.'/', "", $path);

            }
        } else if ($value != "." && $value != "..") {
            getDirContents($path, $results);
            if(!in_array($path, $results)){
                $results[] = str_replace(public_path().'/'.$dir.'/', "", $path);
            }
        }
    }

    return $results;
}