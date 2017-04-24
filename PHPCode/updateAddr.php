<?php	
	include('pgConnect.php');
	
	/*
	NOTE: we should be authenticating users with their uname ++ pasword.
	otherwise, data could be entered into our DB at random by just sending a post request.
	*/

//TESTING ONLY
/*if( !isset($_POST['username']) && !isset($_POST['address']))
{
	$_POST['username'] = 'anmg8@mail.missouri.edu';
	$_POST['address'] = '123 Main Street';
	$_POST['city'] = 'Columbia';
	$_POST['state'] = 'MO';
	$_POST['zip'] = '65201';
} */


	$error; //error message set by verify();
	if( verify() )
	{
		////////////////
		//authenticate
		///////////////
		pg_prepare($dbConn, "updateAddr", "UPDATE voter SET address = $1, city = $2, state = $3, zip = $4 WHERE username = $5");


		$input = loadData();

		
		if($result = pg_execute($dbConn, "updateAddr", $input))
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
			address => $_POST['address'],
			city => $_POST['city'],
			state => $_POST['state'],
			zip => $_POST['zip'],
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
			$error = 'address invalid';
			if(isset($_POST['address']))
			{
				$error = 'city invalid';
				if(isset($_POST['city']))
				{
					$error = 'state invalid';
					if(isset($_POST['state']))
					{
						$error = 'zip invalid';
						if(isset($_POST['zip']))
						{
							$error = NULL;
							return true;
						}
					}
				}
			}
		}
	return false;
	}
?>