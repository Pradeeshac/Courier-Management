<?php 
	// connect to the database
	$servername="127.0.0.1";
	$username="root";
	$password="";
	$database='c_m_s';
	$conn=new mysqli($servername,$username,$password,$database);
	// check connection
	if(!$conn){
		echo "Connection error: ". mysqli_connect_error();
	}
?>	