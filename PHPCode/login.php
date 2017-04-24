<?php
	include('pgConnect.php');
	require('password_compat-master/lib/password.php');
	
	//TESTING ONLY
	//$_POST['email'] = 'anmg8@mail.missouri.edu';
	//$_POST['password'] = 'password';

	if( isset($_POST['email']) && isset($_POST['password']))
	{

		//user input
		$username = htmlspecialchars($_POST['email']);
		$pwd = htmlspecialchars($_POST['password']);
		
		//filters invalid characters
		$username = filter_var($username, FILTER_SANITIZE_EMAIL);

		//checks if username is a valid email address
		if(!filter_var($username, FILTER_VALIDATE_EMAIL))
		{
			echo json_encode(array(loginsuccessful => false, error => 'Username is not a valid email.'), JSON_PRETTY_PRINT);
			die();
		}


		//database logic
		pg_prepare($dbConn, "login", 'SELECT * FROM voter WHERE username = $1');

		$result = pg_execute($dbConn, "login", array($username));
		$line = pg_fetch_array($result, null, PGSQL_ASSOC);

		//HASHING FOR TESTING ONLY
		//$hashedPass = password_hash($line[password], PASSWORD_DEFAULT);
		
		$hashedPass = $line[password]; //database password 
		$testPass = $pwd; //input password

		
		if (password_verify($testPass, $hashedPass)) 
		{
	   		//echo 'Password is valid!'."</br>";
	   		$returnArr = buildVoter($line);
		} 
		else 
		{
	    	//echo 'Invalid password.'."</br>";
	    	$returnArr = array(
	    		loginsuccessful => false,
	    		error => 'Invalid Username or Password'
	    		);
		}
	
		//create JSON object
		$json = json_encode($returnArr, JSON_PRETTY_PRINT);

		//return to app
		echo $json;
	}

	function buildVoter($arr) {

		$voter = array(
			loginsuccessful => true,
			user => array(
				firstname => $arr[fname],
				mname => $arr[middle_name],
				lname => $arr[lname],
				address => $arr[address],
				gender => $arr[gender],
				voterid => $arr[voter_id],
				registered => $arr[registered],
				username => $arr[username],
				election_district => $arr[election_district],
				registration_date => $arr[registration_date],
				date_of_birth => $arr[date_of_birth],
				longitude => $arr[longitude],
				latitude => $arr[latitude],
				location_id => $arr[location_id],
				city => $arr[city],
				state => $arr[state],
				zip => $arr[zip],
				ssn => $arr[ssn],
				place_of_birth => $arr[place_of_birth],
				phone_number => $arr[phone_number]
				)
			);
		return $voter;
	}
?>