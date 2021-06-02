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

function get_decorated_diff($old, $new, $file_name=null){
    $from_start = strspn($old ^ $new, "\0");        
    $from_end = strspn(strrev($old) ^ strrev($new), "\0");

    $old_end = strlen($old) - $from_end;
    $new_end = strlen($new) - $from_end;

    $start = substr($new, 0, $from_start);
    $end = substr($new, $new_end);
    $new_diff = substr($new, $from_start, $new_end - $from_start);  
    $old_diff = substr($old, $from_start, $old_end - $from_start);

    if(strpos($old,$new_diff) == false){
        $new = "$start<ins style='background-color:#ccffcc'>$new_diff</ins>$end";
    }

    
    $old = "$start<del style='background-color:#ffcccc'>$old_diff</del>$end";
    return array("old"=>$old, "new"=>$new, "filename"=>$file_name);
}