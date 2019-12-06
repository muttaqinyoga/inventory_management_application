	<?php  
	require_once '../database/database_connection.php';
	require_once '../admin/helper.php';
	if(!isset($_SESSION['type']))
	{
		header("Location: ../login.php");
		die;
	}
	$query = '';
	$output = [];
	$query .= "SELECT * FROM inventory_order ";
	if($_SESSION['type']=='user')
	{
		$query .= " WHERE userID = '".$_SESSION['userID']."' ";
	}
	if(isset($_POST['search']['value']))
	{
		if($_POST['search']['value'] != '')
		{
			$query .= ' WHERE inventoryOrderID LIKE "%'. $_POST["search"]["value"] .'%" ';
			$query .= ' OR inventoryOrderName LIKE "%'. $_POST["search"]["value"] .'%" ';
			$query .= ' OR inventoryOrderTotal LIKE "%'. $_POST["search"]["value"] .'%" ';
			$query .= ' OR inventoryOrderDate LIKE "%'. $_POST["search"]["value"] .'%" ';
			$query .= ' OR inventoryOrderStatus LIKE "%'. $_POST["search"]["value"] .'%" ';

		}
	}
	if(isset($_POST["order"]))
	{
		$tes =  $_POST['order'][0]["column"];
		$query .= ' ORDER BY '.$_POST["columns"][$tes]["name"].' '.$_POST["order"]["0"]["dir"].'';
		
	}
	else
	{
		$query .= " ORDER BY inventoryOrderID DESC";
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
		$paymentStatus = '';
		if($row['paymentStatus']=='cash')
		{
			$paymentStatus = '<p class="label label-success">Cash</p>';
		}
		else
		{
			$paymentStatus = '<p class="label label-warning">Credit</p>';
		}
		$inventoryOrderStatus = '';

		if($row['inventoryOrderStatus']=='active')
		{
			$inventoryOrderStatus = '<p class="text-success">Active</p>';
		}
		else
		{
			$inventoryOrderStatus = '<p class="text-danger">Inactive</p>';
		}
		$sub_array = [];
		$sub_array[] = $row['inventoryOrderID'];
		$sub_array[] = $row['inventoryOrderName'];
		$sub_array[] = convertToRupiah($row['inventoryOrderTotal']);
		$sub_array[] = $paymentStatus;
		$sub_array[] = $inventoryOrderStatus;
		$sub_array[] = $row['inventoryOrderDate'];
		if($_SESSION['type']=='master')
		{
			$sub_array[] = getUserName($conn, $row['userID']);
		}
		else
		{
			$sub_array[] = '#';
		}
		$sub_array[] = '<a href="order_report.php?pdf=1&order_id='.$row["inventoryOrderID"].'" class="btn btn-info btn-xs">View PDF</a>';
		$sub_array[] =  '<button type="button" id="'.$row["inventoryOrderID"].'" class ="btn btn-warning btn-xs updateOrder" receiverName="'.$row["inventoryOrderName"].'" orderDate="'.$row["inventoryOrderDate"].'" receiverAddress="'.$row["inventoryOrderAddress"].'" inventoryOrderStatus="'.$row["inventoryOrderStatus"].'" paymentStatus="'.$row["paymentStatus"].'" paymentStatus="'.$row["paymentStatus"].'">Edit</button>';
		$sub_array[] = '<button type="button" id="'.$row["inventoryOrderID"].'" class ="btn btn-danger btn-xs deleteOrder">Delete</button>';
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
				"data" => $data,
			];

	echo json_encode($output);

	function getTotalAllRecords($conn)
	{
		$stmnt = $conn->prepare("SELECT * FROM inventory_order ");
		$stmnt->execute();
		return $stmnt->rowCount();
	}

	