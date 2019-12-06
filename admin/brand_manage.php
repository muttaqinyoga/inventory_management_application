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
	if(isset($_POST['addBrandToken']))
	{
		$token = hash('sha256', 'add_brand_token');
		if($_POST['addBrandToken']===$token)
		{
			if($_POST['brandName']=='')
			{
				die('Failed to Add Brand! Brand Name and Category field must be filled.');
			}
			$query2 = "SELECT * FROM category WHERE categoryID = '".$_POST['categoryID']."' ";
			$stmt2 = $conn->prepare($query2);
			$stmt2->execute();
			$result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
			if($result2===false)
			{
				die('Failed to Add Brand! Unknown Category');
			}
			$query = "INSERT INTO brand (brandName, categoryID, brandStatus) VALUES(:brandName,  :categoryID, :brandStatus)";
			$stmt = $conn->prepare($query);
			$stmt->execute([':brandName' => htmlspecialchars($_POST["brandName"]), ':categoryID' => htmlspecialchars($_POST["categoryID"]), ':brandStatus' => 'active']);
			$result = $stmt->rowCount();
			if($result > 0)
			{
				echo "New Brand Added!";
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
	if(isset($_POST['updateBrandToken']))
	{
		$token = hash('sha256', 'update_brand_token');
		if($_POST['updateBrandToken']===$token)
		{
			$query = "UPDATE brand SET brandName = :brandName, brandStatus = :brandStatus, categoryID = :categoryID WHERE brandID = :brandID";
			$stmt = $conn->prepare($query);
			$stmt->execute([':brandName' => htmlspecialchars($_POST["brandName"]), ':brandStatus' => htmlspecialchars($_POST["brandStatus"]), ':categoryID' => htmlspecialchars($_POST['categoryID']), ':brandID' => htmlspecialchars($_POST['brandID'])]);
			$result = $stmt->rowCount();
			if($result > 0)
			{
				echo "Brand Edited!";
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
	if(isset($_POST['deleteBrandToken']))
	{
		$token = hash('sha256', 'delete_brand_token');
		if($_POST['deleteBrandToken']===$token)
		{
			$query = "DELETE FROM brand WHERE brandID = :brandID";
			$stmt = $conn->prepare($query);
			$stmt->execute([':brandID' => htmlspecialchars($_POST['brandID'])]);
			$result = $stmt->rowCount();
			if($result > 0)
			{
				echo $_POST['brandName']." has been deleted!";
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