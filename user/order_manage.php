<?php
	require_once '../database/database_connection.php';
	require_once '../admin/helper.php';
	if(!isset($_SESSION['type']))
	{
		header("Location: ../login.php");
		die;
	}
	
	if(isset($_POST['addOrderToken'])){
		$token = hash('sha256', 'add_order_token');
		if($_POST['addOrderToken']===$token)
		{
			if($_POST['addInventoryOrderName']!='' && $_POST['addInventoryOrderDate']!='' && $_POST['addInventoryOrderAddress']!='' && $_POST['addPaymentStatus']!='')
			{
				
				$tes = htmlspecialchars($_POST['addInventoryOrderDate']);
				$addInventoryOrderDate = explode('-', $tes);
				for($i = 0; $i<count($addInventoryOrderDate); $i++)
				{
					if(!is_numeric($addInventoryOrderDate[$i]))
					{
						die('<div class="alert alert-danger" role="alert"><strong>Wrong Date Format!</strong> Please Enter a Valid Date Format (dd-mm-yyyy)</div>');
					}
				}
				if($addInventoryOrderDate[1] > 12)
				{
					die('<div class="alert alert-danger" role="alert"><strong>Wrong Month Format</strong> Please Enter a Valid Date Format (dd-mm-yyyy)</div>');
				}
				switch ($addInventoryOrderDate[1]) {
					case 4:
						if($addInventoryOrderDate[0]>30){
							die('<div class="alert alert-danger" role="alert"><strong>April Days is Not More Than 30!</strong> Please Enter a Valid Date Format (dd-mm-yyyy)</div>');
						}
						break;
					case 6:
						if($addInventoryOrderDate[0]>30){
							die('<div class="alert alert-danger" role="alert"><strong>June Days is Not More Than 30!</strong> Please Enter a Valid Date Format (dd-mm-yyyy)</div>');
						}
						break;
					case 9:
						if($addInventoryOrderDate[0]>30){
							die('<div class="alert alert-danger" role="alert"><strong>September Days is Not More Than 30!</strong> Please Enter a Valid Date Format (dd-mm-yyyy)</div>');
						}
						break;
					case 11:
						if($addInventoryOrderDate[0]>30){
							die('<div class="alert alert-danger" role="alert"><strong>November Days is Not More Than 30!</strong> Please Enter a Valid Date Format (dd-mm-yyyy)</div>');
						}
						break;
					case 2:
						if($addInventoryOrderDate[2]%4==0){
							if($addInventoryOrderDate[0] > 28){
								die('<div class="alert alert-danger" role="alert"><strong>Only February Days in Kabisat Year is More Than 28!</strong> Please Enter a Valid Date Format (dd-mm-yyyy)</div>');
							}
						} else{
							if($addInventoryOrderDate[0] > 29){
								die('<div class="alert alert-danger" role="alert"><strong>February Days in Kabisat Year is Not More Than 29!</strong> Please Enter a Valid Date Format (dd-mm-yyyy)</div>');
							}
						}
						break;
					
					default:
						if($addInventoryOrderDate[0]>31){
							die('<div class="alert alert-danger" role="alert"><strong>Failed to Add Order!</strong> Please Enter a Valid Date Format (dd-mm-yyyy)</div>');
						}
						break;
				}
				if($addInventoryOrderDate[2] < 2000 && $addInventoryOrderDate[2] > 2019)
				{
					die('<div class="alert alert-danger" role="alert"><strong>Wrong Years Input!</strong> Please Enter a Valid Date Format (dd-mm-yyyy)</div>');
				}
				$availableProduct = getAllProduct($conn);
				$productChoosen = $_POST['addproductID'];
				$productQuantity = $_POST['addProductQuantity'];
				if(!in_array($productChoosen, $availableProduct))
				{
					die('<div class="alert alert-danger" role="alert"><strong>Unknown Product! Failed to Add Order.</strong></div>');
				}
				if(!is_numeric($productQuantity) && $productQuantity < 0 )
				{
					die('<div class="alert alert-danger" role="alert"><strong>Product Quantity Must be Positive Number! Failed to Add Order.</strong></div>');
				}
				$query = "INSERT INTO inventory_order (userID, inventoryOrderTotal, inventoryOrderDate, inventoryOrderName, inventoryOrderAddress, paymentStatus, inventoryOrderStatus, inventoryOrderCreatedDate)
					 	  VALUES ('".$_SESSION["userID"]."','0','".htmlspecialchars($_POST['addInventoryOrderDate'])."', '".htmlspecialchars($_POST['addInventoryOrderName'])."', '".htmlspecialchars($_POST['addInventoryOrderAddress'])."', '".htmlspecialchars($_POST['addPaymentStatus'])."', 'active','".time()."')";
				$conn->exec($query);
				$inventoryOrderID = $conn->lastInsertId();
				$productDetails = getProductByID($productChoosen, $conn);
				$sub_query = "INSERT INTO inventory_order_product (inventoryOrderID, productID, quantity, price, tax) VALUES ( :inventoryOrderID, :productID, :quantity, :price, :tax) ";
				$stmt = $conn->prepare($sub_query);
				$productBasePrice = $productDetails[0];
				$productTax = $productDetails[1];
				$productRemain =$productDetails[2];
				$stmt->execute([
								':inventoryOrderID' => $inventoryOrderID,
								':productID' => $productChoosen,
								':quantity' => $productQuantity,
								':price' => $productBasePrice,
								':tax' => $productTax
							   ]);
				$totalAmount = 0;
				$basePrice = $productBasePrice * $productQuantity;
				$tax = $productTax * $productQuantity;
				$totalAmount = $totalAmount + ($basePrice + $tax);
				$productSold = $productDetails[2] - $productQuantity;
				$updateProductQuantityQuery = "UPDATE product SET productQuantity = '".$productSold."' WHERE productID = '".$productChoosen."' ";
				$conn->exec($updateProductQuantityQuery);
			$updateQuery = "UPDATE inventory_order SET inventoryOrderTotal = '".$totalAmount."' WHERE inventoryOrderID = '".$inventoryOrderID."' ";
				$stmt = $conn->prepare($updateQuery);
				$stmt->execute();
				$result = $stmt->rowCount();
				if($result > 0)
				{
					die('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>New Order Added!</strong></div>');
				}
				else
				{
					die('<div class="alert alert-danger" role="alert"><strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed to Add Order!</strong> Something Went Wrong :(</div>');
				}

			}
			else
			{
				die('<div class="alert alert-danger" role="alert"><strong>Please Fill All Field Order!</strong></div>');
			}
		}
		else
		{
			die('<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Failed to Add Order!</strong> An Error Occured.</div>');
		}
	}
	$productStock = 0;
	$ordered_product_id = '';
	if(isset($_POST['orderedProductID']))
	{
		$query = "SELECT inventory_order_product.productID as 'productID', inventory_order_product.quantity as 'quantity', inventory_order_product.inventoryOrderID as 'orderID', product.productName as 'productName' FROM inventory_order_product JOIN product ON product.productID = inventory_order_product.productID WHERE inventoryOrderID = '".$_POST["orderedProductID"]."' ";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();
		$data = [];
		foreach($result as $r)
		{
			$data['productID'] = $r['productID'];
			$data['quantity'] = $r['quantity'];
			$data['orderID'] = $r['orderID'];
			$data['productName'] = $r['productName'];
		}
		$_SESSION['current_ordered_product_id'] = $data['productID'];
		$_SESSION['current_ordered_product_quantity'] = $data['quantity'];
		echo json_encode($data);
	}
	if(isset($_POST['updateOrderToken'])){
		$token = hash('sha256', 'update_order_token');
		if($_POST['updateOrderToken']===$token)
		{
			if($_POST['updateInventoryOrderName']!='' && $_POST['updateInventoryOrderDate']!='' && $_POST['updateInventoryOrderAddress']!='' && $_POST['updatePaymentStatus']!='')
			{
				
				$tes = htmlspecialchars($_POST['updateInventoryOrderDate']);
				$updateInventoryOrderDate = explode('-', $tes);
				for($i = 0; $i<count($updateInventoryOrderDate); $i++)
				{
					if(!is_numeric($updateInventoryOrderDate[$i]))
					{
						die('<div class="alert alert-danger" role="alert"><strong>Wrong Date Format!</strong> Please Enter a Valid Date Format (dd-mm-yyyy)</div>');
					}
				}
				if($updateInventoryOrderDate[1] > 12)
				{
					die('<div class="alert alert-danger" role="alert"><strong>Wrong Month Format</strong> Please Enter a Valid Date Format (dd-mm-yyyy)</div>');
				}
				switch ($updateInventoryOrderDate[1]) {
					case 4:
						if($updateInventoryOrderDate[0]>30){
							die('<div class="alert alert-danger" role="alert"><strong>April Days is Not More Than 30!</strong> Please Enter a Valid Date Format (dd-mm-yyyy)</div>');
						}
						break;
					case 6:
						if($updateInventoryOrderDate[0]>30){
							die('<div class="alert alert-danger" role="alert"><strong>June Days is Not More Than 30!</strong> Please Enter a Valid Date Format (dd-mm-yyyy)</div>');
						}
						break;
					case 9:
						if($updateInventoryOrderDate[0]>30){
							die('<div class="alert alert-danger" role="alert"><strong>September Days is Not More Than 30!</strong> Please Enter a Valid Date Format (dd-mm-yyyy)</div>');
						}
						break;
					case 11:
						if($updateInventoryOrderDate[0]>30){
							die('<div class="alert alert-danger" role="alert"><strong>November Days is Not More Than 30!</strong> Please Enter a Valid Date Format (dd-mm-yyyy)</div>');
						}
						break;
					case 2:
						if($updateInventoryOrderDate[2]%4==0){
							if($updateInventoryOrderDate[0] > 28){
								die('<div class="alert alert-danger" role="alert"><strong>Only February Days in Kabisat Year is More Than 28!</strong> Please Enter a Valid Date Format (dd-mm-yyyy)</div>');
							}
						} else{
							if($updateInventoryOrderDate[0] > 29){
								die('<div class="alert alert-danger" role="alert"><strong>February Days in Kabisat Year is Not More Than 29!</strong> Please Enter a Valid Date Format (dd-mm-yyyy)</div>');
							}
						}
						break;
					
					default:
						if($updateInventoryOrderDate[0]>31){
							die('<div class="alert alert-danger" role="alert"><strong>Failed to Add Order!</strong> Please Enter a Valid Date Format (dd-mm-yyyy)</div>');
						}
						break;
				}
				if($updateInventoryOrderDate[2] < 2000 && $updateInventoryOrderDate[2] > 2019)
				{
					die('<div class="alert alert-danger" role="alert"><strong>Wrong Years Input!</strong> Please Enter a Valid Date Format (dd-mm-yyyy)</div>');
				}
				$availableProduct = getAllProduct($conn);
				$productChoosen = htmlspecialchars($_POST['updateproductID']);
				$productQuantity = htmlspecialchars($_POST['updateProductQuantity']);
				if(!in_array($productChoosen, $availableProduct))
				{
					die('<div class="alert alert-danger" role="alert"><strong>Unknown Product! Failed to Edit Order.</strong></div>');
				}
				if(!is_numeric($productQuantity) && $productQuantity < 0)
				{
					die('<div class="alert alert-danger" role="alert"><strong>Product Quantity Must be Positive Number! Failed to Edit Order.</strong></div>');
				}
				$query = "UPDATE inventory_order SET inventoryOrderName = '".htmlspecialchars($_POST["updateInventoryOrderName"])."', inventoryOrderDate = '".htmlspecialchars($_POST["updateInventoryOrderDate"])."', inventoryOrderAddress = '".htmlspecialchars($_POST["updateInventoryOrderAddress"])."', paymentStatus = '".htmlspecialchars($_POST["updatePaymentStatus"])."', inventoryOrderStatus = '".htmlspecialchars($_POST["updateInventoryOrderStatus"])."' WHERE inventoryOrderID = '".htmlspecialchars($_POST["updateInventoryOrderID"])."' ";
				$conn->exec($query);
				$sql = "SELECT productQuantity FROM product WHERE productID = '".$_SESSION["current_ordered_product_id"]."' ";
				$st = $conn->prepare($sql);
				$st->execute();
				$productCurrStock = $st->fetch(PDO::FETCH_ASSOC);
				$productStock = $productCurrStock['productQuantity'];
				$defStock = $productStock + $_SESSION['current_ordered_product_quantity'];
				$changeStock = "UPDATE product SET productQuantity = '".$defStock."'  WHERE productID = '".$_SESSION['current_ordered_product_id']."' ";
				$conn->exec($changeStock);
				$productDetails = getProductByID($productChoosen, $conn);
				$productBasePrice = $productDetails[0];
				$productTax = $productDetails[1];
				$productRemain =$productDetails[2];
				$sql = "UPDATE inventory_order_product SET productID = '".$productChoosen."', quantity = '".$productQuantity."', price = '".$productBasePrice."', tax = '".$productTax."' WHERE inventoryOrderID = '".htmlspecialchars($_POST['updateInventoryOrderID'])."' ";
				$conn->exec($sql);
				$totalAmount = 0;
				$basePrice = $productBasePrice * $productQuantity;
				$tax = $productTax * $productQuantity;
				$totalAmount = $totalAmount + ($basePrice + $tax);
				$productSold = $productRemain - $productQuantity;
				$updateProductQuantityQuery = "UPDATE product SET productQuantity = '".$productSold."' WHERE productID = '".$productChoosen."' ";
				$conn->exec($updateProductQuantityQuery);
				$updateQuery = "UPDATE inventory_order SET inventoryOrderTotal = '".$totalAmount."' WHERE inventoryOrderID = '".htmlspecialchars($_POST["updateInventoryOrderID"])."' ";
				$stmt = $conn->prepare($updateQuery);
				$result = $stmt->execute();
				if($result)
				{
					unset($_SESSION['ordered_product_id']);
					unset($_SESSION['current_ordered_product_quantity']);
					die('<div class="alert alert-success" role="alert"><strong>Order Edited!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
				}
				else
				{
					die('<div class="alert alert-danger" role="alert"><strong>Failed to Edit Order!</strong> Something Went Wrong :(. <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
				}

			}
			else
			{
				die('<div class="alert alert-danger" role="alert"><strong>Please Fill All Field Order!</strong></div>');
			}
		}
		else
		{
			die('<div class="alert alert-danger" role="alert"><strong>Failed to Edit Order!</strong> An Error Occured. <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
		}
	}
	if(isset($_POST['deleteOrderToken']))
	{
		$token = hash('sha256', 'delete_order_token');
		if($_POST['deleteOrderToken']===$token)
		{
			$currQuantityOrderQuery = "SELECT productID, quantity FROM inventory_order_product WHERE inventoryOrderID = '".$_POST['deleteOrderID']."' ";
			$resultQuantityOrderQuery = $conn->prepare($currQuantityOrderQuery);
			$resultQuantityOrderQuery->execute();
			$currQuantityOrder = $resultQuantityOrderQuery->fetch(PDO::FETCH_ASSOC);
			$currQuantity = $currQuantityOrder['quantity'];
			$currProductID = $currQuantityOrder['productID'];
			$query2 = "SELECT productQuantity FROM product WHERE productID = '".$currProductID."' ";
			$stmt2 = $conn->prepare($query2);
			$stmt2->execute();
			$result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
			$quantityProduct = $result2['productQuantity'];
			$productDef = $currQuantity + $quantityProduct;
			$queryUpdateProductQuantity = "UPDATE product SET productQuantity = '".$productDef."' WHERE productID = '".$currProductID."' ";
			$conn->exec($queryUpdateProductQuantity);
			$query = "DELETE FROM inventory_order WHERE inventoryOrderID = '".$_POST['deleteOrderID']."'";
			$stmt = $conn->prepare($query);
			$stmt->execute();
			$result = $stmt->rowCount();
			if($result > 0)
			{
				$sql = "DELETE FROM inventory_order_product WHERE inventoryOrderID ='".$_POST['deleteOrderID']."' ";
				$st = $conn->prepare($sql);
				$st->execute();
				$result2 = $st->rowCount();
				if($result2 > 0)
				{
					die('<div class="alert alert-success" role="alert">Order with ID <strong>'.$_POST['deleteOrderID'].'</strong> has been permanently delete.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
				}
				else
				{
					die('<div class="alert alert-danger" role="alert"><strong>Failed Delete Order!</strong> An Error Occured. <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
				} 
			}
			else
			{
				die('<div class="alert alert-danger" role="alert"><strong>Failed to Delete Order!</strong> An Error Occured. <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
			}
		} else
		{
			die('<div class="alert alert-danger" role="alert"><strong>Failed to Delete Order!</strong> An Error Occured. <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
		}

	}