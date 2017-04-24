<?php
	include('pgConnect.php');

	/*
	NOTE: we should be authenticating users with their uname ++ pasword.
	otherwise, data could be entered into our DB at random by just sending a post request.
	*/

//TESTING ONLY
/*if( !isset($_POST['username']) && !isset($_POST['fname']))
{
	$_POST['username'] = 'anmg8@mail.missouri.edu';
	//$_POST['password'] = 'password';
	$_POST['fname'] = 'Adam';
	$_POST['lname'] = 'Newland';
	$_POST['address'] = '123 Main Street';
	$_POST['city'] = 'Columbia';
	$_POST['state'] = 'Missouri';
	$_POST['zip'] = '63050';
	$_POST['gender'] = 'M';
	$_POST['ssn'] = '1234';
	$_POST['place_of_birth'] = 'St. Louis, MO';
	$_POST['phone_number'] = '5550811234';
	$_POST['registration_date'] = '4/17/2017';
	$_POST['election_district'] = 'Columbia';
	$_POST['date_of_birth'] = '9/10/1993';
	//include signature 
	$_FILES['signature']['name'] = 'testSig.png';
	//change bytea column to varchar to store file pathname
}*/

	$error; //error message set by verify();
	if(  verify() )
	{
		//////////////////
		//authenticate
		//////////////////


		pg_prepare($dbConn, "registerVoter", "UPDATE voter SET fname = $1, middle_name = $2, lname = $3, address = $4, city = $5, state = $6, zip = $7, gender = $8, registered = 't', registration_date = $9, election_district = $10, date_of_birth = $11, ssn = $12, place_of_birth = $13, phone_number = $14, license_num = $15 WHERE username = $16");

		$myName = tempnam('../images/', 'signature_');
		unlink($myName);

		$input = loadData();

		if($result = pg_execute($dbConn, "registerVoter", $input))
		{
			move_uploaded_file($_FILES['signature']['tmp_name'], $myName);
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
	}

	if( isset($error) )
	{
		$returnArr = array(
			successful => false,
			error => $error
			);
	}
	//create JSON object
	$json = json_encode($returnArr, JSON_PRETTY_PRINT);

	//return to app
	echo $json;
	

	function loadData()
	{
		$voter = array(
			fname => $_POST['fname'],
			middle_name => $_POST['middle_name'],
			lname => $_POST['lname'],
			address => $_POST['address'],
			city => $_POST['city'],
			state => $_POST['state'],
			zip => $_POST['zip'],
			gender => $_POST['gender'],
			registration_date => $_POST['registration_date'],
			election_district => $_POST['election_district'],
			date_of_birth => $_POST['date_of_birth'],
			ssn => $_POST['ssn'],
			place_of_birth => $_POST['place_of_birth'],
			phone_number => $_POST['phone_number'],
			username => $_POST['username']
			);

		return $voter;
	}

	function verify()
	{
		global $error;
		$error = 'username not set';
		if(isset($_POST['username']))
		{
			$error = 'First or Last Name not set';
			if(isset($_POST['fname']) && isset($_POST['lname']))
			{
				$error = 'address not set';
				if(isset($_POST['address']))
				{
					$error = 'city not set';
					if(isset($_POST['city']))
					{
						if(isset($_POST['state']))
						{
							$error = 'gender not set';
							if(isset($_POST['gender']))
							{
								$error = 'registration_date or date_of_birth not set';
								if(isset($_POST['registration_date']) && isset($_POST['date_of_birth']))
								{
									$error = 'election_district not set';
									if(isset($_POST['election_district']))
									{
										
										$error = 'signature not set';
										if(isset($_FILES['signature']['name']))
										{
											$error = NULL;
											return true;		
										}
									}
								}
							}
						}
					}
				}
			}
		}
	return false;
	}
?>