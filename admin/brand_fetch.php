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
	$query = '';
	$output = [];
	$query .= " SELECT * FROM brand  JOIN category ON category.categoryID = brand.categoryID ";
	if(isset($_POST['search']['value']))
	{
		if($_POST['search']['value'] != '')
		{
			$query .= 'WHERE brand.brandName LIKE "%'. $_POST["search"]["value"] .'%" ';
			$query .= 'OR category.categoryName LIKE "%'. $_POST["search"]["value"] .'%" ';
			$query .= 'OR brand.brandStatus LIKE "%'. $_POST["search"]["value"] .'%" ';
		}
	}
	if(isset($_POST["order"]))
	{
		$tes =  $_POST['order'][0]["column"];
		$query .= ' ORDER BY '.$_POST["columns"][$tes]["name"].' '.$_POST["order"]["0"]["dir"].'';
		
	}
	else
	{
		$query .= "ORDER BY brandID DESC";
	}
	if(isset($_POST['length']))
	{
		if($_POST["length"] != -1)
		{
			$query .= ' LIMIT '. $_POST['start'].', '. $_POST['length'];
		}
	}
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$result = $stmt->fetchAll();
	$data = [];
	$filtered_rows = $stmt->rowCount();
	foreach($result as $row)
	{
		$status = '';
		if($row['brandStatus']=='active')
		{
			$status = '<p class="text-success">Active</p>';
		}
		else
		{
			$status = '<p class="text-danger">Inactive</p>';
		}
		$sub_array = [];
		$sub_array[] = $row['brandID'];
		$sub_array[] = $row['brandName'];
		$sub_array[] = $row['categoryName'];
		$sub_array[] = $status;
		$sub_array[] =  '<button type="button" id="'.$row["brandID"].'" brand_name = "'.$row["brandName"].'" brand_status = "'.$row["brandStatus"].'" category_name = "'.$row["categoryName"].'" category_id = "'.$row["categoryID"].'" data-toggle="modal" data-target="#addBrandModal" class ="btn btn-warning btn-xs editBrand">Edit</button>';
		$sub_array[] = '<button type="button" id="'.$row["brandID"].'" brand_name = "'.$row["brandName"].'" brand_status = "'.$row["brandStatus"].'" data-toggle="modal" data-target="#deleteBrandModal" class ="btn btn-danger btn-xs deleteBrand">Delete</button>';
		$data[] = $sub_array;
	}
	$draw = '';
	if(isset($_POST['draw']))
	{
		$draw = $_POST['draw'];
	}
	$output = [
				"draw" => intval($draw),
				"recordsTotal" => $filtered_rows,
				"recordsFiltered" => getTotalAllRecords($conn),
				"data" => $data
			];

	echo json_encode($output);
	function getTotalAllRecords($conn)
	{
		$stmnt = $conn->prepare("SELECT * FROM brand INNER JOIN category ON category.categoryID = brand.categoryID ");
		$stmnt->execute();
		return $stmnt->rowCount();
	}

	