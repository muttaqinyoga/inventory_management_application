<?php  
	require_once '../database/database_connection.php';
	if(!isset($_SESSION['type']))
	{
		header("Location: ../login.php");
		die;
	}
	
	function selectCategoryList($conn)
	{
		if($_SESSION['type']!='master')
		{
			header("Location : ../user");
			die;
		}
		$query = "SELECT * FROM category WHERE categoryStatus ='active' ORDER BY categoryName ASC ";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();
		$output = '';
		foreach($result as $r)
		{
			$output .= '<option class="categoryList" value="'.$r['categoryID'].'">'.$r['categoryName'].'</option>';
		}
		return $output;

	}
	function selectProductList($conn, $categoryID)
	{
		if($_SESSION['type']!='master')
		{
			header("Location : ../user");
			die;
		}
		$query = "SELECT * FROM brand WHERE brandStatus = 'active' AND categoryID = '". $categoryID ."' ORDER BY brandName ASC ";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();
		$output = '';
		if(!empty($result))
		{
			$output .= '<label for="brandID">Brand</label>
						<select class="form-control" id="brandID" name="brandID" required>';
			foreach($result as $r)
			{
				$output .= '<option class="brandList" value="'.$r['brandID'].'">'.$r['brandName'		].'</option>';
			}
			$output .= '</select>';
		}
		else
		{
			$output .= '<p class="text-danger">There is no Brand for Category You'."'".'ve Select.Please Select Another Category or Add New Brand. </p>';
		}

		return $output;

	}
	function getUserName($conn, $userID)
	{
		$query = "SELECT userName FROM user WHERE userID = '".$userID."' ";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();
		 foreach($result as $r)
		 {
		 	return $r['userName'];
		 }
	}
	function convertToRupiah($num)
	{
		return "Rp. ".number_format($num,2,',','.');
	}
	function selectProductOrder($conn){
		$query = "SELECT * FROM  product WHERE productStatus='active' AND productQuantity > 0 ORDER BY productName ASC ";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();
		$output = '';
		foreach ($result as $r) {
			$output .= '<option value="'.$r['productID'].'">'.$r['productName'].'</option>';
		}
		return $output;
	}
	function getAllProduct($conn)
	{
		$query = "SELECT productID FROM  product WHERE productStatus='active' AND productQuantity > 0  ";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();
		$data = [];
		foreach ($result as $r) {
			$data[]=$r['productID'];
		}
		return $data;
	}
	function getProductByID($productID, $conn)
	{
		$query = "SELECT * FROM  product WHERE productID = '".$productID."' ";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();
		$data = [];
		foreach ($result as $r) {
			$data[] = $r['productBasePrice'];
			$data[] = $r['productTax'];
			$data[] = $r['productQuantity'];
			$data[] = $r['productName'];
		}
		return $data;
	}