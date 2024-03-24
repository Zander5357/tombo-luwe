<?php
session_start();
include "db.php";
if (isset($_POST["f_name"])) {

	$f_name = $_POST["f_name"];
	$l_name = $_POST["l_name"];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$repassword = $_POST['repassword'];
	$mobile = $_POST['mobile'];
	$address1 = $_POST['address1'];
	$address2 = $_POST['address2'];
	$name = "/^[a-zA-Z ]+$/";
	$emailValidation = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9]+(\.[a-z]{2,4})$/";
	$number = "/^[0-9]+$/";

	if (
		empty($f_name) || empty($l_name) || empty($email) || empty($password) || empty($repassword) ||
		empty($mobile) || empty($address1) || empty($address2)
	) {

		echo "
			<div class='alert alert-warning'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><b>Please fill in all fields..!</b>
			</div>
		";
		exit();
	} else {
		if (!preg_match($name, $f_name)) {
			echo "
			<div class='alert alert-warning'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>This $f_name is not valid..!</b>
			</div>
		";
			exit();
		}
		if (!preg_match($name, $l_name)) {
			echo "
			<div class='alert alert-warning'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>This $l_name is not valid..!</b>
			</div>
		";
			exit();
		}
		if (!preg_match($emailValidation, $email)) {
			echo "
			<div class='alert alert-warning'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>This $email is not valid..!</b>
			</div>
		";
			exit();
		}
		if (strlen($password) < 9) {
			echo "
			<div class='alert alert-warning'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Password is weak</b>
			</div>
		";
			exit();
		}
		if (strlen($repassword) < 9) {
			echo "
			<div class='alert alert-warning'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Password is weak</b>
			</div>
		";
			exit();
		}
		if ($password != $repassword) {
			echo "
			<div class='alert alert-warning'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Passwords do not match</b>
			</div>
		";
			exit();
		}
		if (!preg_match($number, $mobile)) {
			echo "
			<div class='alert alert-warning'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Mobile number $mobile is not valid</b>
			</div>
		";
			exit();
		}
		if (strlen($mobile) !== 10) {
			echo "
			<div class='alert alert-warning'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Mobile number must be 10 digits</b>
			</div>
		";
			exit();
		}
		// Check if email address already exists in the database
		$sql = "SELECT user_id FROM user_info WHERE email = '$email' LIMIT 1";
		$check_query = pg_query($con, $sql);
		$count_email = pg_num_rows($check_query);
		if ($count_email > 0) {
			echo "
			<div class='alert alert-danger'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Email Address is already taken. Please try another email address.</b>
			</div>
		";
			exit();
		} else {
			$sql = "INSERT INTO user_info 
			(first_name, last_name, email, password, mobile, address1, address2) 
			VALUES ('$f_name', '$l_name', '$email', '$password', '$mobile', '$address1', '$address2')";
			$run_query = pg_query($con, $sql);
			if ($run_query) {
				$sql = "UPDATE cart SET user_id = $1 WHERE ip_add = $2 AND user_id = -1";
				$run_query = pg_query_params($con, $sql, array($uid, $ip_add));
				
				$_SESSION["uid"] = $uid;
				$_SESSION["name"] = $f_name;
				$ip_add = getenv("REMOTE_ADDR");
				$sql = "UPDATE cart SET user_id = '$uid' WHERE ip_add='$ip_add' AND user_id = -1";
				$run_query = pg_query($con, $sql);
				echo "register_success";
				echo "<script> location.href='store.php'; </script>";
				exit;
			}
		}
	}
}
