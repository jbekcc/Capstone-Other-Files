<?php
	include('pgConnect.php');
	require('password_compat-master/lib/password.php');

	//TESTING ONLY

	//Should Fail
	//$_POST['email'] = 'anmg8@mail.missouri.edu';
	//$_POST['password'] = 'password';

	if( isset($_POST['email']) && isset($_POST['password']))
	{
		$uname = htmlspecialchars($_POST['email']);
		$pword = htmlspecialchars($_POST['password']);

		//filters invalid characters
		$uname = filter_var($uname, FILTER_SANITIZE_EMAIL);

		//checks if username is a valid email address
		if(!filter_var($uname, FILTER_VALIDATE_EMAIL))
		{
			echo json_encode(array(successful => false, error => 'Username is not a valid email.'), JSON_PRETTY_PRINT);
			die();
		}


		pg_prepare($dbConn, "addUser", "INSERT INTO voter (username, password) VALUES ($1, $2)");

		$hashedPass = password_hash($pword, PASSWORD_DEFAULT);

		if($result = pg_execute($dbConn, "addUser", array($uname, $hashedPass)))
		{
			$returnArr = array(
	    		successful => true
	    		);
		}
		else
		{
			$returnArr = array(
	    		successful => false,
	    		error => pg_last_error() 
	    		);
		}

		//create JSON object
		$json = json_encode($returnArr, JSON_PRETTY_PRINT);

		//return to app
		echo $json;
	}
?>