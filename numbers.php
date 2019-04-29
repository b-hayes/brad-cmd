<?php
//NOTE: Only does a single file. Passing * to this from bash will cause problems.
$me = array_shift($argv);
$usage= "Usage: $me [file_name] [pad_length = 2]";
if (empty($argv)) die("Formats numbers in [file_name] to be [pad_length] digits with leading zeros.\n eg. file1name3.txt becomes file01name03.txt\n$usage");
$path = array_shift($argv);
if (!$path) die("no filename given");
if (!realpath($path)) die("ERROR: cant find file [$path]\n$usage");

$fileName = basename($path);

$pad_length = array_shift($argv) ?: 2;
if (!is_numeric($pad_length)) die("Error: 2nd param must be an integer.\n$usage");
$pad_char = 0;
$str_type = 'd'; // treats input as integer, and outputs as a (signed) decimal number

$format = "%{$pad_char}{$pad_length}{$str_type}"; // or "%04d"

$collect_ints = "";
$padded_int = "";
$formatted_file_name = "";

foreach (str_split($fileName) as $char){
    //collect ints in a row and add them later
    $intval = intval($char);
    if($char==="0" || $intval > 0 ){
        $collect_ints.=$char;
    } else {
        //not an int add collected ints as a single number with padding
        if ($collect_ints)$formatted_file_name.= sprintf($format, $collect_ints);
        //add the current char as well.
        $formatted_file_name.= $char;
        //reset
        $collect_ints = "";
    }
}
echo " >>> ",$formatted_file_name;
$new_path = str_replace($fileName,$formatted_file_name,$path);
rename($path,$new_path);
