<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DirectoryComponent
 *
 * @author uchilaka
 */
// app/Controller/Component/DirectoryComponent.php
App::uses('Component', 'Controller');
class DirectoryComponent extends Component {
    //put your code here
    //put your code here
    function make_file ($path,$name,$stuff) {
    $path = $path;
    $name = $path.$name;

    if (!file_exists($path)) {
    $md = mkdir($path, 0777);
    }

    $x=func_num_args();
            if ($x>3) {
            $mode=func_get_arg(2);
                    if ($mode==0) {
                    $k=@file_put_contents($name,$stuff);
                    return $k;
                    }
            } else {
            $try =  file_put_contents($name,$stuff);
            }
    //echo $try;

    if ($try !== FALSE) { return $name;}

    else {return 0;}
    }


    function make_log($log) {
    $x = 0;
    $text = "";
    while($x<count($log)) {$text.=$log[$x].'
    '; ++$x;}
    return $text;
    }


    function stash_records ($access,$db,$cSet,$dSet,$table,$row_count) {

    $link = mysql_connect($access[0],$access[1],$access[2]) or die("Could not connect.");

    mysql_select_db($db) or die('could not select database.');

    //ARRAY OF IDS IN CASE YOU NEED TO USE IT. FROM THE DATA ARRAY
    $id_array = $dSet[0];

    for ($a = 0 ; $a < $row_count ; $a++) {

    $content = '<table>';

    for ($b = 0; $b < count($cSet) ; $b++) {

    $content .= '<tr><td>'.$cSet[$b].': </td><td>'.$dSet[$b][$a].'</td></tr>';

    }

    $content .= '</table>';
    $cv[$a] = $content;
    $content = '';
    }

    $result[0] = $cv;
    $result[1] = $id_array;

    return $result;
    }


    function file_type($file){
    $path_chunks = explode("/", $file);
    $thefile = $path_chunks[count($path_chunks) - 1];
    $dotpos = strrpos($thefile, ".");
    //echo $dotpos;
    return strtolower(substr($thefile, $dotpos + 1));
    }



    function get_all_files($dir) {

    $x=0;

    if ($handle = opendir($dir)) {
       while (false !== ($file = readdir($handle))) {
           if ($file != "." && $file != "..") {
                       $files[$x] = $file; $types[$x] = filetype($dir.$file);
                       ++$x;
           }
       }
       closedir($handle);
    }

    $output[0] = $files;
    $output[1] = $types;

    return $output;
    }


    function scratch_file ($file) {
    $x = filetype($file);
    if ($x == 'dir') {return 0;}
    else {
    return unlink($file);
    }
    }


    function remove_dir($dir) {
    $dir_contents = scandir($dir);
    foreach ($dir_contents as $item) {
    if (is_dir($dir.$item) && $item != '.' && $item != '..') {
    remove_dir($dir.$item.'/');
    }
    elseif (file_exists($dir.$item) && $item != '.' && $item != '..') {
    unlink($dir.$item);
    }
    }
    return rmdir($dir);
    }


    public function trailblazer ($trail) {
    $set = explode('/',$trail);
    $dir = "";
    foreach ($set as $x) 
    {
    $dir .= $x.'/'; if (!is_dir($dir)) {mkdir($dir,0777);}
    }
    return is_dir($dir);
    }


    function copy_dir($srcdir, $dstdir, $verbose) {
     $num = 0;
     if (!file_exists($srcdir)) {if ($verbose) {echo 'The source directory does not exist.';} return 0;}
     $dirpath = explode('/',$dstdir);
     for ($x = 0; $x < count($dirpath); $x++) 
     {
     $path.=$dirpath[$x].'/'; if (!file_exists($path)) {mkdir($path,0777); if ($verbose) {echo 'dir created: '.$path.'<br/>';} }
     }

    // if(!file_exists($dstdir)) mkdir($dstdir);
     if($curdir = opendir($srcdir)) {
       while($file = readdir($curdir)) {
         if($file != FALSE && $file != '.' && $file != '..') {
           $srcfile = $srcdir . '/' . $file;
           $dstfile = $dstdir . '/' . $file;
           if(is_file($srcfile)) {
             if(is_file($dstfile)) $ow = filemtime($srcfile) - filemtime($dstfile); else $ow = 1;
             if($ow > 0) {
               if($verbose) echo "Copying '$srcfile' to '$dstfile'...";
               if(copy($srcfile, $dstfile)) {
                 touch($dstfile, filemtime($srcfile)); $num++;
                 if($verbose) echo "OK\n";
               }
               else echo "Error: File '$srcfile' could not be copied!\n";
             }                   
           }
           else if(is_dir($srcfile)) {
             $num += copy_dir($srcfile, $dstfile, $verbose);
           }
         }
       }
       closedir($curdir);
     }
     return $num;
    }


    function csv_to_array($input, $delimiter=',') 
    { 
        $header = null; 
        $data = array(); 
        $csvData = str_getcsv($input, "\n"); 

        foreach($csvData as $csvLine){
            // make sure line is not a comment line
            if (preg_match('/^#/', $csvLine)<1 || !preg_match('/^#/', $csvLine)) {
                if(is_null($header)) $header = explode($delimiter, $csvLine); 
                else{ 

                    $items = explode($delimiter, $csvLine); 

                    for($n = 0, $m = count($header); $n < $m; $n++){ 
                        $prepareData[$header[$n]] = $items[$n]; 
                    } 

                    $data['values'][] = $prepareData; 
                }
            }

        } 

        $data['header']=$header;
        return $data; 
    } 

}

?>
