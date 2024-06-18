<?php
     
    require 'database.php';

	if (isset($_FILES['proof'])) {
		// upload or move the file to our directory
		$target_dir = "./proofs/";
		$file_data = $_FILES['proof'];
	
		$value = NULL;
	
		if($file_data['tmp_name'] !== '') {
			$file_name = $file_data['name'];
			$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$file_name = 'proof-'.time().'.'.$file_ext;
	
			$check = getimagesize($file_data["tmp_name"]);
	
			# Move the file
			if ($check) {
				if (move_uploaded_file($file_data['tmp_name'], $target_dir. $file_name)) {
					// Save the file_name to the database
					$value = $file_name;
				}
			} else {
				// File upload failed, set $value to a default value or stop the execution
				$value = 'default.jpg'; // Replace this with your default value
				// Or show an error message and stop the execution
				// die('File upload failed');
			}
		} 
	}
 
    if ( !empty($_POST)) {
		// keep track post values
		$fname = $_POST['fname'];
		$bname = $_POST['bname'];
		$id = $_POST['id'];
		$gender = $_POST['gender'];
		$email = $_POST['email'];
		$mobile = $_POST['mobile'];
		$proof = $value; // Use the file name as the proof
		$address = $_POST['address'];
		
		// insert data
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO tbl_data (fname,bname,id,gender,email,mobile,proof,address) values(?, ?, ?, ?, ?, ?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($fname,$bname,$id,$gender,$email,$mobile,$proof,$address));
		Database::disconnect();
		header("Location: user data.php");
	}
?>