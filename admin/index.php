<?php
	require_once '../database/database_connection.php';
	require_once 'helper.php';
	if(!isset($_SESSION['type']))
	{
		header("location: ../login.php");
	}
	if($_SESSION['type']!='master')
	{
		header("location: ../login.php");
	}
	require_once '../templates/header.php';
	$query = "SELECT userName From user WHERE userID != '".$_SESSION['userID']."' ";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$result = $stmt->rowCount();
	$query2 = "SELECT inventoryOrderID From inventory_order";
	$stmt2 = $conn->prepare($query2);
	$stmt2->execute();
	$result2 = $stmt2->rowCount();
	$query3 = "SELECT productID From product";
	$stmt3 = $conn->prepare($query3);
	$stmt3->execute();
	$result3 = $stmt3->rowCount();
	$query4 = "SELECT user.userName as 'user', product.productName as 'product', inventory_order.inventoryOrderTotal as 'total', inventory_order.inventoryOrderName as 'customer', inventory_order.inventoryOrderDate as 'orderDate', inventory_order.inventoryOrderCreatedDate as 'orderCreated', inventory_order_product.quantity as 'quantity' FROM inventory_order JOIN inventory_order_product ON inventory_order_product.inventoryOrderID = inventory_order.inventoryOrderID JOIN product ON product.productID = inventory_order_product.productID JOIN user ON user.userID = inventory_order.userID ORDER BY inventory_order.inventoryOrderCreatedDate  DESC LIMIT 5 ";
	$stmt4 = $conn->prepare($query4);
	$stmt4->execute();
	$result4 = $stmt4->fetchAll();

?>

	<section id="breadcrumb">
		<div class="container">
			<ol class="breadcrumb">
				<li class="active">Dashboard</li>
			</ol>
		</div>
	</section>


	<section id="main">
		<div class="container">
			<div class="row">
				<div class="col-md-8">
					<div class="panel panel-default">
					  <div class="panel-heading-danger">Website Overview</div>
					  <div class="panel-body">
					  	<div class="col-md-4">
					  		<div class="well-info dash-box">
					  			<h2 class="text-light"><span class="glyphicon glyphicon-user"></span> <?php echo $result; ?></h2>
					  			<h4 class="text-light">Total Users</h4>
					  		</div>
					  	</div>
					  	<div class="col-md-4">
					  		<div class="well-primary dash-box">
					  			<h2 class="text-light"><span class="glyphicon glyphicon-list-alt"></span> <?php echo $result2; ?></h2>
					  			<h4 class="text-light">Total Orders</h4>
					  		</div>
					  	</div>
					  	<div class="col-md-4">
					  		<div class="well-secondary dash-box">
					  			<h2 class="text-light"><span class="glyphicon glyphicon-briefcase"></span> <?php echo $result3; ?></h2>
					  			<h4 class="text-light"> Total Products</h4>
					  		</div>
					  	</div>
					  </div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
					  <div class="panel-heading-success">Latest Order</div>
					  <div class="panel-body">
					  	<table class="table table-striped table-hover table-bordered">
						    <thead>
						      <tr>
						        <th>Customer</th>
						        <th>Order Date</th>
						        <th>Order Created</th>
						        <th>Total Order</th>
						        <th>Product</th>
						        <th>Quantity</th>
						        <th>Created By</th>
						      </tr>
						    </thead>
						    <tbody>
						      <?php foreach($result4 as $r) : ?>
						      	<tr>
						      		<td><?php echo $r['customer']; ?></td>
						      		<td><?php echo date('d F Y', strtotime($r['orderDate'])); ?></td>
						      		<td><?php echo date('d F Y',$r['orderCreated']); ?></td>
						      		<td><?php echo convertToRupiah($r['total']); ?></td>
						      		<td><?php echo $r['product']; ?></td>
						      		<td><?php echo $r['quantity']; ?></td>
						      		<td><?php echo $r['user']; ?></td>
						      	</tr>
						      <?php endforeach; ?>
						    </tbody>
						  </table>
					  </div>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php require_once '../templates/footer.php'; ?>
