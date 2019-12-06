<?php
	require_once '../database/database_connection.php';
	if(!isset($_SESSION['type']))
	{
		header("Location: ../login.php");
		die;
	}
	if($_SESSION['type']!='master')
	{
		header("Location : ../user");
		die;
	}
	
	if(isset($_POST['addUserToken']))
	{
		$token = hash('sha256', 'add_user_token');
		if($_POST['addUserToken']===$token)
		{
			$email = filter_var($_POST['addUserEmail'], FILTER_SANITIZE_EMAIL);
			$userName = filter_var($_POST['addUserName'], FILTER_SANITIZE_STRING);
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)===false)
			{
				$query = "INSERT INTO user (userEmail, userPassword, userName, userType, userStatus)
						VALUES (:userEmail, :userPassword, :userName, :userType, :userStatus)";
				$stmt = $conn->prepare($query);
				$stmt->execute([
								':userEmail' => htmlspecialchars($_POST['addUserEmail']),
								':userPassword' => password_hash($_POST['addUserPassword'], PASSWORD_DEFAULT),
								':userName' => htmlspecialchars($userName),
								':userType' => 'user',
								':userStatus' => 'active'
							   ]);
				$result = $stmt->rowCount();
				if($result > 0)
				{
					echo $_POST['addUserName']." has been added.";
				}
				else
				{
					die('Failed to execute!. an error occured.');
				}	
			}
			else
			{
				die('Please enter a valid email!');
			}
				
		}
		else
		{
			die('Failed to execute!. an error occured.');	
		}

	}
	if(isset($_POST['updateUserToken']))
	{
		$token = hash('sha256', 'update_user_token');
		if($_POST['updateUserToken']===$token)
		{
			$email = filter_var($_POST['updateUserEmail'], FILTER_SANITIZE_EMAIL);
			$userName = filter_var($_POST['updateUserName'], FILTER_SANITIZE_STRING);
			$userStatus = filter_var($_POST['updateUserStatus'], FILTER_SANITIZE_STRING);
			$userStatus = strtolower($userStatus);
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)===false)
			{
					$sql = "SELECT * FROM user WHERE userID = :userID ";
					$stmt = $conn->prepare($sql);
					$stmt->bindParam(':userID', $_POST['updateUserID']);
					$stmt->execute();
					$prevResult = $stmt->setFetchMode(PDO::FETCH_ASSOC);
					$prevResult = $stmt->fetchAll();
					$prevResult = $prevResult[0];
					if($stmt->rowCount() > 0)
					{
						
						if(htmlspecialchars($_POST['updateUserPassword']) == "")
						{
							if($prevResult['userName'] == $userName && $prevResult['userEmail'] == $email && $prevResult['userStatus'] == $userStatus)
							{
								die("You don't edit any user data!");
							}
							else
							{	
								$stmnt = $conn->prepare("UPDATE user SET userName = '". $_POST["updateUserName"] ."', userEmail ='".$_POST["updateUserEmail"]."', userStatus ='".$_POST["updateUserStatus"]."' WHERE userID = ".htmlspecialchars($_POST["updateUserID"]));
								$stmnt->execute();
								if($stmnt->rowCount()>0)
								{
									echo "User Data Edited!";
								}
								else
								{
									die('Unexpected Error! Failed to Edit User.');
								}
							}
						}
						else
						{
							$stmnt = $conn->prepare("UPDATE user SET userName = '". $_POST["updateUserName"] ."', userEmail ='".$_POST["updateUserEmail"]."', userStatus ='".$_POST["updateUserStatus"]."', userPassword ='".password_hash(htmlspecialchars($_POST["updateUserStatus"]), PASSWORD_DEFAULT)."'  WHERE userID = ".htmlspecialchars($_POST["updateUserID"]));
							$stmnt->execute();
							if($stmnt->rowCount()>0)
							{
								echo "User Data Edited!";
							}
							else
							{
								die('Unexpected Error!. Failed to Edit User.');
							}
						}
					}
					else
					{
						die('User ID not found!. Failed to Edit User. ');
					}
						
			}
			else
			{
				die('Please enter a valid email!');
			}
		}
		else
		{
			die('Failed to execute!. an error occured.');	
		}
	}

	if(isset($_POST['deleteUserToken']))
	{
		$token = hash('sha256', 'delete_user_token');
		if($token===$_POST['deleteUserToken'])
		{
			$userName = filter_var($_POST['deleteUserName'], FILTER_SANITIZE_STRING);
			$userID = filter_var($_POST['deleteUserID'], FILTER_SANITIZE_STRING);
			$query = "DELETE FROM user WHERE userID = :userID";
			$stmt = $conn->prepare($query);
			$stmt->execute([':userID' => $userID]);
			$result = $stmt->rowCount();
			if($result > 0)
			{
				echo $userName." has been permanently delete";
			}
			else
			{
				die('Failed to delete user! An error occured');
			}
		}
		else
		{
			die('Failed to execute! An error occured.');
		}
	}