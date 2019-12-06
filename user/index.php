<?php
	require_once '../database/database_connection.php';
	require_once '../admin/helper.php';
	if(!isset($_SESSION['type']))
	{
		header("location: ../login.php");
	}
	require_once '../templates/header.php';
	$query4 = "SELECT product.productName as 'product', inventory_order.inventoryOrderTotal as 'total', inventory_order.inventoryOrderName as 'customer', inventory_order.inventoryOrderDate as 'orderDate', inventory_order.inventoryOrderCreatedDate as 'orderCreated', inventory_order_product.quantity as 'quantity' FROM inventory_order JOIN inventory_order_product ON inventory_order_product.inventoryOrderID = inventory_order.inventoryOrderID JOIN product ON product.productID = inventory_order_product.productID WHERE inventory_order.userID = '".$_SESSION['userID']."' ORDER BY inventory_order.inventoryOrderCreatedDate  DESC LIMIT 5 ";
	$stmt4 = $conn->prepare($query4);
	$stmt4->execute();
	$result4 = $stmt4->fetchAll();
?>

	<section id="breadcrumb">
		<div class="container">
			<ol class="breadcrumb">
				<li class="active">My Profile</li>
			</ol>
		</div>
	</section>
	<div class="container">
		<div class="row">
			<div class="col-md-5">
				<div class="panel panel-default">
					<div class="panel-heading-success">
						<h3 class="text-white">My Profile</h3>
					</div>
					<div class="panel-body">
						<div class="col-xs-4">
							<img src="../img/default.png" class="img-responsive">
						</div>
						<div class="col-xs-8">
							<h4 class="text-black"><span class="glyphicon glyphicon-file"></span> <?php echo $_SESSION['userID']; ?></h4>
							<h4 class="text-black"><span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION['userName']; ?></h4>
							<h4 class="text-black"><span class="glyphicon glyphicon-envelope"></span> <?php echo $_SESSION['userEmail']; ?></h4>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
				  <div class="panel-heading-danger">Latest Order</div>
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
					      	</tr>
					      <?php endforeach; ?>
					    </tbody>
					  </table>
				  </div>
				</div>
			</div>
		</div>
	</div>

<?php require_once '../templates/footer.php'; ?>