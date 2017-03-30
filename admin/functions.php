<?php 

function escape($string){
    global $connect;
    return mysqli_real_escape_string($connect, trim($string));
}



?> 