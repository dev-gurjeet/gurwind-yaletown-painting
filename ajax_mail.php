<?php
	$fname = $_POST['first_name'];
	$lname = $_POST['last_name'];
	$phone = $_POST['phone_number'];
	$email = $_POST['email'];
	$message = $_POST['message'];
	
	$to = "gaurish.rathore@pixelnx.com";
	$subject = "painting template";
	$msg="Hi Admin..<p>".$fname.' '.$lname." has sent a query. User's Phone No ".$phone." email id as ".$email."</p><p>Query is : ".$message."</p>";
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: <support@painter.com>' . "\r\n";

	echo mail($to,$subject,$msg,$headers);
?>