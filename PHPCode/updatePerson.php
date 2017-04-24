<?php	
	include('pgConnect.php');
	

	/*
	NOTE: we should be authenticating users with their uname ++ pasword.
	otherwise, data could be entered into our DB at random by just sending a post request.
	*/

//TESTING ONLY
	/*
if( !isset($_POST['username']))
{
	$_POST['username'] = 'anmg8@mail.missouri.edu';
	$_POST['fname'] = 'Tricky';
	$_POST['lname'] = 'Bobby';
	$_POST['gender'] = 'F';
	$_POST['mname'] = 'Danger';

}*/


	if( isset($_POST['username']) )
	{
		///////////////////
		//authenticate?
		///////////////////
		
		$query = "UPDATE voter SET";
		$i = 1;
		if(isset($_POST['fname']))
		{
			$tmp = " fname = $".$i.",";
			$query = $query.$tmp;
			$i++;
			$arr['fname'] = $_POST['fname'];
		}
		if(isset($_POST['mname']))
		{
			$tmp = " middle_name = $".$i.",";
			$query = $query.$tmp;
			$i++;
			$arr['mname'] = $_POST['mname'];
		}
		if(isset($_POST['lname']))
		{
			$tmp = " lname = $".$i.",";
			$query = $query.$tmp;
			$i++;
			$arr['lname'] = $_POST['lname'];
		}
		if(isset($_POST['gender']))
		{
			$tmp = " gender = $".$i.",";
			$query = $query.$tmp;
			$i++;
			$arr['gender'] = $_POST['gender'];
		}

		$arr['username'] = $_POST['username'];
		$query = substr($query, 0, -1);
		$query = $query." WHERE username = $".$i.";";
		

		pg_prepare($dbConn, "updatePerson", $query);

	
		if($result = pg_execute($dbConn, "updatePerson", $arr))
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

	
	//create JSON object
	$json = json_encode($returnArr, JSON_PRETTY_PRINT);

	//return to app
	echo $json;
	

?>