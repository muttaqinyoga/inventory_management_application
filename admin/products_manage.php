<?php
	require_once '../database/database_connection.php';
	require_once 'helper.php';
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
	
	if(isset($_POST['hasSelectChange']))
	{
		if($_POST['hasSelectChange']==true)
		{
			echo selectProductList($conn, $_POST['categoryID']) ;
		}
	}
	if(isset($_POST['addProductToken']))
	{
		$token = hash('sha256', 'add_product_token');
		if($_POST['addProductToken']===$token)
		{
			if(htmlspecialchars($_POST['brandID'])!='' && htmlspecialchars($_POST['categoryID']!='') && htmlspecialchars($_POST['productName']!='') && htmlspecialchars($_POST['productQuantity']!='') && htmlspecialchars($_POST['productBasePrice']!='') && htmlspecialchars($_POST['productTax']!='') && htmlspecialchars($_POST['productUnit']!=''))
			{
				if(is_numeric(htmlspecialchars($_POST['productBasePrice'])) && is_numeric(htmlspecialchars($_POST['productTax'])) && is_numeric(htmlspecialchars($_POST['productQuantity'])) && !is_numeric(htmlspecialchars($_POST['productName'])))
				{
					$query = "INSERT INTO product (categoryID, brandID, productName, productDescription, productQuantity, productUnit, productBasePrice, productTax, productEnterBy, productStatus, productDate)
							  VALUES(:categoryID, :brandID, :productName, :productDescription, :productQuantity, :productUnit, :productBasePrice, :productTax, :productEnterBy, :productStatus, :productDate) ";
					$stmt = $conn->prepare($query);
					$stmt->execute([
										':categoryID' => htmlspecialchars($_POST['categoryID']),
										':brandID' => htmlspecialchars($_POST['brandID']),
										':productName' => htmlspecialchars($_POST['productName']),
										':productDescription' => htmlspecialchars($_POST['productDescription']),
										':productQuantity' => htmlspecialchars($_POST['productQuantity']),
										':productUnit' => htmlspecialchars($_POST['productUnit']),
										':productBasePrice' => htmlspecialchars(preg_replace('/[^0-9]/', '', $_POST['productBasePrice'])),
										':productTax' => htmlspecialchars(preg_replace('/[^0-9]/', '', $_POST['productTax'])),
										':productEnterBy' => $_SESSION['userID'],
										':productStatus' => 'active',
										':productDate' => date('Y-m-d')
								   ]);
					$result = $stmt->rowCount();
					if($result > 0)
					{
						echo "New Product Added!";
					}
					else
					{
						die('Failed to execute! an error occured.');
					}
				}
				else
				{
					die('Product Name can not filled by numeric or Product Quantity, Product Base Price, Product Quantity, and Product Tax must filled by numeric.');
				}

			}
			else
			{
				die('Please fill Product Name, Product Quantity, Product Base Price, Product Tax, and Product Unit Field.');
			}
		}
		else
		{
			die('Failed to execute! an error occured.');
		}
		
	}
	if(isset($_POST['updateProductToken']))
	{
		$token = hash('sha256', 'update_product_token');
		if($_POST['updateProductToken']===$token)
		{
			if(htmlspecialchars($_POST['brandID'])!='' && htmlspecialchars($_POST['categoryID']!='') && htmlspecialchars($_POST['productName']!='') && htmlspecialchars($_POST['productQuantity']!='') && htmlspecialchars($_POST['productBasePrice']!='') && htmlspecialchars($_POST['productTax']!='') && htmlspecialchars($_POST['productUnit']!=''))
			{
				if(is_numeric(htmlspecialchars($_POST['productBasePrice'])) && is_numeric(htmlspecialchars($_POST['productTax'])) && is_numeric(htmlspecialchars($_POST['productQuantity'])) && !is_numeric(htmlspecialchars($_POST['productName'])))
				{
					$query = "UPDATE product SET categoryID = :categoryID, brandID = :brandID, productName = :productName, productDescription = :productDescription, productBasePrice = :productBasePrice, productQuantity = :productQuantity, productUnit = :productUnit, productTax = :productTax , productStatus = :productStatus WHERE productID = :productID";
					$stmt = $conn->prepare($query);
					$stmt->execute([
										':categoryID' => htmlspecialchars($_POST['categoryID']),
										':brandID' => htmlspecialchars($_POST['brandID']),
										':productName' => htmlspecialchars($_POST['productName']),
										':productDescription' => htmlspecialchars($_POST['productDescription']),
										':productBasePrice' => htmlspecialchars(preg_replace('/[^0-9]/', '', $_POST['productBasePrice'])),
										':productQuantity' => htmlspecialchars($_POST['productQuantity']),
										':productUnit' => htmlspecialchars($_POST['productUnit']),
										':productTax' => htmlspecialchars(preg_replace('/[^0-9]/', '', $_POST['productTax'])),
										':productStatus' => htmlspecialchars($_POST['productStatus']),
										':productID' => htmlspecialchars($_POST['productID'])
								   ]);
					$result = $stmt->rowCount();
					if($result > 0)
					{
						echo "Product Edited";
					}
					else
					{
						die('Failed to edit product An error occured.');	
					}
				}
				else
				{
					die('Product Name can not filled by numeric or Product Quantity, Product Base Price, Product Quantity, and Product Tax must filled by numeric.');
				}
			}
			else
			{
				die('Please fill Product Name, Product Quantity, Product Base Price, Product Tax, and Product Unit Field.');
			}

		}
		else
		{
			die('Failed to execute! an error occured.');
		}
	}
	if(isset($_POST['deleteProductToken']))
	{
		$token = hash('sha256', 'delete_product_token');
		if($_POST['deleteProductToken']===$token)
		{
			$query = "DELETE FROM product WHERE productID = :productID";
			$stmt = $conn->prepare($query);
			$stmt->execute([':productID' => htmlspecialchars($_POST['productID'])]);
			$result = $stmt->rowCount();
			if($result > 0)
			{
				echo $_POST['productName']." has been deleted!";
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