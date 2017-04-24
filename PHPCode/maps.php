<?php 
	include('pgConnect.php');
	require('password_compat-master/lib/password.php');

	//Database Logic
	pg_prepare($dbConn, "maps", 'SELECT * FROM ____');

	//Attempt 
	if( isset($_POST[])){
		//Get user input
		$username = htmlspecialchars($_POST['email']);
		$fname = htmlspecialchars($_POST['fname']);
		$lname = htmlspecialchars($_POST['lname']);
		$address = htmlspecialchars($_POST['address']);
		$city = htmlspecialchars($_POST['city']);
		$state = htmlspecialchars($_POST['state']);

>
