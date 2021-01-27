<?php

include_once'values.php';


class Requests {
	public $con;

	function __construct($con){
		$this->con = $con;
	}

	function get_unis(){
		$query = "select * from universities";
		$result = mysqli_query($this->con,$query);
		$resp = array();
		
		while ($row = mysqli_fetch_array($result)) {
			$temp = array();
			$temp['name'] = $row[1];
			array_push($resp,$temp);
		}
		echo json_encode($resp);
	}

	function get_col($uni) {
		$query_uni = "select id from universities where name='$uni'";
		$result_uni = mysqli_query($this->con,$query_uni);
		if ($row = mysqli_fetch_array($result_uni)) {
			$id_ = $row[0];
		}
		
		$query = "select name from colleges where uni_id=$id_";
		$result = mysqli_query($this->con,$query);
		$resp = array();
		
		while($row = mysqli_fetch_array($result)) {
			$temp = array();
			$temp['name'] = $row[0];
			array_push($resp,$temp);
		}

		echo json_encode($resp);

	}

	function register($username, $gender, $uni, $col, $pas) {
		// Checking if username is already taken
		$queryC = "select * from users where username='$username'";
		$resp = array();
		$resultC = mysqli_query($this->con, $queryC); 
		if (mysqli_fetch_array($resultC)) {
			$temp = array();
			$temp['response'] = 'taken';
			array_push($resp,$temp);
		} else {
			$query = "insert into users values (null, '$username','$gender','$uni','$col','$pas')";
			if (mysqli_query($this->con, $query)) {
				$temp = array();
				$temp['response'] = 'done';
				array_push($resp,$temp);
			}
		}

		echo json_encode($resp);
	}
}


if ($con){
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$job = $_POST['job'];
		
		$req = new Requests($con);

		if ($job == 'get_uni'){
			// Getting universities
			$req->get_unis();	
		}
		
		// Getting colleges
		else if ($job == 'get_col') {
			$uni = $_POST['uni'];
			$req->get_col($uni);
		}

		// Registering user
		else if ($job == 'register') {
			$username = $_POST['username'];
			$gender = $_POST['gender'];
			$uni = $_POST['uni'];
			$col = $_POST['col'];
			$pas = $_POST['pass'];

			$req->register($username, $gender, $uni, $col, $pas);
		}
		

	}

} else {
	echo 'Failed to connect to the Database';
}


?>
