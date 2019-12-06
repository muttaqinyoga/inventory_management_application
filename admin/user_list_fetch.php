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
	$tes = '';
	$query = '';
	$output = [];
	$query .= " SELECT * FROM user WHERE userType = 'user' ";
	if(isset($_POST['search']['value']))
	{
		if($_POST['search']['value']!='')
		{
			$query .= 'AND (userEmail LIKE "%'. $_POST["search"]["value"] .'%" ';
			$query .= 'OR userName LIKE "%'. $_POST["search"]["value"] .'%" ';
			$query .= 'OR userStatus LIKE "%'. $_POST["search"]["value"] .'%") ';
		}
	}
	if(isset($_POST["order"]))
	{
		$tes =  $_POST['order'][0]["column"];
		$query .= ' ORDER BY '.$_POST["columns"][$tes]["name"].' '.$_POST["order"]["0"]["dir"].'';
		
	}
	else
	{
		$query .= ' ORDER BY userID DESC ';
	}
	if($_POST["length"] != -1)
	{
		$query .= ' LIMIT '. $_POST['start'].', '. $_POST['length'];
	}
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$result = $stmt->fetchAll();

	$data = [];
	$filtered_rows = $stmt->rowCount();

	foreach($result as $row)
	{
		$status = '';
		$sub_array = [];
		$sub_array[] = $row['userID'];
		$sub_array[] = $row['userEmail'];
		$sub_array[] = $row['userName'];
		if($row['userStatus']=='active')
		{
			$status = '<p class="text-success">Active</p>';
			
		}
		else
		{
			$status = '<p class="text-danger">Inactive</p>';
		}
		$sub_array[] = $status;
		$sub_array[] = '<button type="button"  id="'.$row["userID"].'" class ="btn btn-warning btn-xs editUser" editUserStatus="'.$row["userStatus"].'" editUserName="'.$row["userName"].'" editUserEmail="'.$row["userEmail"].'" >Edit</button>';
		$sub_array[] = '<button type="button"  id="'.$row["userID"].'" class ="btn btn-danger btn-xs deleteUser" data-toggle="modal" data-target="#deleteModal" deleteUsername="'. $row["userName"] .'" >Delete</button>';
		$data[] = $sub_array;
	}
	$output = [
				"draw" => intval($_POST['draw']),
				"recordsTotal" => $filtered_rows,
				"recordsFiltered" => getTotalAllRecords($conn),
				"data" => $data
			];

	echo json_encode($output);
	function getTotalAllRecords($conn)
	{
		$stmnt = $conn->prepare("SELECT * FROM user WHERE userType = 'user' ");
		$stmnt->execute();
		return $stmnt->rowCount();
	}


?>