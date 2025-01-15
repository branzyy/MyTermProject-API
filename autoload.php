<?php

//Method Auto Load//gittt

function classAutoLoad($classname){
    // classname in the function is a member when in it is called it becomes an argument
    $directories = ["classes", "contents",
    "forms","processes",
    "globals","menus"];

    foreach($directories AS $dir){
        //php built in function that pulls the path of the file where it is
        //directory separatoe can be a \
        $filename = dirname(_FILE_) .
         DIRECTORY_SEPARATOR . 
         $dir . DIRECTORY_SEPARATOR .
          $classname . ".php";

          if(file_exists($filename) AND is_readable($filename)){
           //require_once; minimise the transactions by bringing everything at once then reading it means if we cannot reach the file the code stops reading and executing
            require_once($filename);
          }
    }
}
spl_autoload_register('classAutoLoad');

// Creating an instance of a class
$ObjLayout = new layout();
$ObjContent = new contents();