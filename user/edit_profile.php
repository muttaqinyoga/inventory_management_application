<?php
	require_once '../database/database_connection.php';
	if(!isset($_SESSION['type']))
	{
		header("Location: ../login.php");
		die;
	}
	if(isset($_POST['editProfieToken']))
	{
		$token = hash('sha256', 'edit_profile_token');
		if($_POST['editProfieToken']===$token)
		{
			if($_POST['newPassword'] != '')
			{
				if($_POST['newPasswordConfirm']!=$_POST['newPassword'])
				{
					die ('<div class="alert alert-danger">
					  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					  <strong>Password did not match!</strong>
					</div>');
				}
				else
				{
					$queryGetCurrentData = "SELECT * FROM user WHERE userID = '". $_SESSION["userID"] ."' ";
					$stmtCurrentData = $conn->prepare($queryGetCurrentData);
					$stmtCurrentData->execute();
					$resultCurrentData = $stmtCurrentData->fetchAll();
					$resultCurrentData = $resultCurrentData[0];
					$currentUsername = $resultCurrentData['userName'];
					$currentUserEmail = $resultCurrentData['userEmail'];
					$query = "UPDATE user SET userPassword = '". password_hash($_POST['newPassword'], PASSWORD_DEFAULT) ."' WHERE userID = '". $_SESSION["userID"] ."' ";
						$stmt = $conn->prepare($query);
						$stmt->execute();
					if($currentUsername == $_POST['userName'] && $currentUserEmail == $_POST['userEmail'])
					{
						die('<div class="alert alert-success">
							  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>Account data edited!</strong>
							</div>');
					}

				}
			}
				$queryGetCurrentData = "SELECT * FROM user WHERE userID = '". $_SESSION["userID"] ."' ";
				$stmtCurrentData = $conn->prepare($queryGetCurrentData);
				$stmtCurrentData->execute();
				$resultCurrentData = $stmtCurrentData->fetchAll();
				$resultCurrentData = $resultCurrentData[0];
				$currentUsername = $resultCurrentData['userName'];
				$currentUserEmail = $resultCurrentData['userEmail'];
				$query2 = '';
				if($currentUsername == $_POST['userName'] && $currentUserEmail == $_POST['userEmail'])
				{
					die('<div class="alert alert-danger">
						  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						  <strong>Your name and email is same as current data!</strong>
						</div>');
				}
				else if($currentUsername == $_POST['userName'])
				{
					$query2 .= "UPDATE user SET userEmail = '". $_POST['userEmail'] ."' WHERE userID = '". $_SESSION["userID"] ."' ";
				}
				else if($currentUserEmail == $_POST['userEmail'])
					$query2 .= "UPDATE user SET userName = '". $_POST['userName'] ."' WHERE userID = '". $_SESSION["userID"] ."' ";
				else if($currentUsername != $_POST['userName'] && $currentUserEmail != $_POST['userEmail'])
				{
					$query2 .= "UPDATE user SET userName = '". $_POST['userName'] ."', userEmail = '". $_POST['userEmail'] ."'  WHERE userID = '". $_SESSION["userID"] ."' ";
				}
				$stmt2 = $conn->prepare($query2);
				$stmt2->execute();
				$result = $stmt2->rowCount();
				if($result > 0)
				{
					die('<div class="alert alert-success">
							  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>Account Edited!</strong>
						</div>');
				}
				else
				{
					die('<div class="alert alert-danger">
					  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					  <strong>Failed to Edit Account!</strong> An error occured.
				</div>');
				}
			
		}
		else
		{
			die('<div class="alert alert-danger">
					  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					  <strong>Failed to Edit Account!</strong> Something Went Wrong
				</div>');
		}
	}
	else
	{
		header("Location: profile.php");
	}