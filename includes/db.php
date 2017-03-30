
<?php 

//create an associate array of the database connect data
$db['db_host'] = 'localhost';
$db['db_user'] = 'root';
$db['db_pass'] = 'root';
$db['db_name'] = 'cms';

//convert the array values to uppercase constants for higher security
//$key is the index of each array element
foreach($db as $key => $value){
	define(strtoupper($key), $value);
}

//use the array values to connect to the database
$connect = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

//check if the database connected
if($connect){
	//echo "were connected";
} else {
	echo "not connected";
}

?>