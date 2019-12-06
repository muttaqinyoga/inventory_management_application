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


	if(isset($_POST['addCategoryToken']))
	{
		$token = hash('sha256', 'add_category_token');
		if($token===$_POST['addCategoryToken'])
		{
				if($_POST['add_category_name']=='')
				{
					die('Failed to Add Category! Category Name field must be filled.');
				}
				$add_category_name = htmlspecialchars($_POST["add_category_name"]);
				$query = "INSERT INTO category (categoryName, categoryStatus) VALUES(:categoryName,  :categoryStatus)";
				$stmt = $conn->prepare($query);
				$stmt->execute([':categoryName' => $add_category_name , ':categoryStatus' => 'active']);
				$result = $stmt->rowCount();
				if($result > 0)
				{
					echo "New Category Added";
				}
				else
				{
					die('Failed to execute! an error occured.');
				}
		}
		else
		{
			die('Failed to execute! an error occured.');
		}
	}
	if(isset($_POST['updateCategoryToken']))
	{
		$token = hash('sha256', 'update_category_token');
		if($_POST['updateCategoryToken']===$token)
		{
			$query = "UPDATE category SET categoryName = :categoryName, categoryStatus = :categoryStatus WHERE categoryID = :categoryID";
			$stmt = $conn->prepare($query);
			$stmt->execute([':categoryName' => htmlspecialchars($_POST["categoryName"]), ':categoryStatus' => htmlspecialchars($_POST["categoryStatus"]), ':categoryID' => htmlspecialchars($_POST['categoryID'])]);
			$result = $stmt->rowCount();
			if($result > 0)
			{
				echo "Category Edited!";
			}
			else
			{
				die('Failed to execute! an error occured.');
			}
		}
		else
		{
			die('Failed to execute! an error occured.');
		}
	}
	if(isset($_POST['deleteCategoryToken']))
	{
		$token = hash('sha256', 'delete_category_token');
		if($_POST['deleteCategoryToken']===$token)
		{
			$query = "DELETE FROM category WHERE categoryID = :categoryID";
			$stmt = $conn->prepare($query);
			$stmt->execute([':categoryID' => htmlspecialchars($_POST['delete_category_id'])]);
			$result = $stmt->rowCount();
			if($result > 0)
			{
				echo $_POST["delete_category_name"]." cateogory has been deleted!";
			}
			else
			{
				die('Failed to execute! an error occured.');
			}
		}
		else
		{
			die('Failed to execute! an error occured.');
		}
	}