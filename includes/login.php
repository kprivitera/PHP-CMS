<?php include "db.php"; ?>
<?php session_start(); ?>

<?php

if(isset($_POST['login'])){
	$username =  $_POST['username'];
	$password =  $_POST['password'];

	$username = mysqli_real_escape_string($connect, $username);
	$password = mysqli_real_escape_string($connect, $password);


	$query = "SELECT * FROM users WHERE username = '{$username}' ";
	$select_user_query = mysqli_query($connect, $query);

	if(!$select_user_query){
		die("sql query didn't work");
	}

	while($row = mysqli_fetch_array($select_user_query)){
		$db_user_id = $row['user_id'];
		$db_username = $row['username'];
		$db_user_password = $row['user_password'];
		$db_user_firstname = $row['user_firstname'];
		$db_user_lastname = $row['user_lastname'];
		$db_user_role = $row['user_role'];
	}

	//check if the grabbed database details match the ones from the form submitted
	if (password_verify($password, $db_user_password)) {
		//assigning these variables to the session
		$_SESSION['username'] = $db_username;
		$_SESSION['firstname'] = $db_user_firstname;
		$_SESSION['lastname'] = $db_user_lastname;
		$_SESSION['user_role'] = $db_user_role;

		header("Location: ../admin");
	} else {
		header("Location: ../index.php");
	}
}
    
  

?>
