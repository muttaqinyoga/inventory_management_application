<?php  
	define('base_url', 'http://localhost/inventory-management/');
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Inventory Management</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/custom.css">
    <link rel="stylesheet" href="../css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="../css/bootstrap-select.min.css">
    <script type="text/javascript" src="../js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap-select.min.js"></script>
</head>
<body>
	<nav class="navbar navbar-inverse">
	  <div class="container">
	    <div class="navbar-header">
	    	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		     </button>
	      <a class="navbar-brand">Inventory Management Application</a>
	    </div>
	    <div class="collapse navbar-collapse" id="myNavbar">
		    <ul class="nav navbar-nav navbar-right">
		    	<li><a href="#"><span class="glyphicon glyphicon-bell"></span> Nofication</a></li>
		      <li><a href="#" data-toggle="modal" data-target="#logoutModal"><span class="glyphicon glyphicon-log-out"></span> Log out</a></li>
		    </ul>
	    </div>
	  </div>
	</nav>
	<div id="logoutModal" class="modal fade">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header bg-red">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title text-white"><span class="glyphicon glyphicon-log-out"></span> Logout</h4>
	      </div>
	      <div class="modal-body">
	        <p>Are you sure want to log out?</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancel</button>
	        <a href="../logout.php" class="btn btn-danger">Logout</a>
	      </div>
	    </div>
	  </div>
	</div>

	<header id="header">
		<div class="container">
			<div class="row">
				<div class="col-md-10">
					<h2>Dashboard
						<div class="dropdown create">
						  <button class="btn btn-default dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">Menu
						  <span class="caret"></span></button>
						  <?php if($_SESSION['type']=='master') : ?>
						  <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
						    <li role="presentation"><a role="menuitem" href="<?php echo base_url; ?>admin/user_list.php">User</a></li>
						    <li role="presentation"><a role="menuitem" href="<?php echo base_url; ?>admin/categories.php">Category</a></li>
						    <li role="presentation"><a role="menuitem" href="<?php echo base_url; ?>admin/brands.php">Brand</a></li>
						    <li role="presentation"><a role="menuitem" href="<?php echo base_url; ?>admin/products.php">Product</a></li>
						    <li role="presentation"><a role="menuitem" href="<?php echo base_url; ?>user/orders.php">Order</a></li>
						    <li role="presentation"><a role="menuitem" href="<?php echo base_url; ?>user/">Profile</a></li>
						    <li role="presentation"><a role="menuitem" href="<?php echo base_url; ?>user/profile.php">Edit Account</a></li>
						  </ul>
						  <?php else : ?>
						  <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
						    <li role="presentation"><a role="menuitem" href="<?php echo base_url; ?>user/">Profile</a></li>
						    <li role="presentation"><a role="menuitem" href="<?php echo base_url; ?>user/profile.php">Edit Account</a></li>
						    <li role="presentation"><a role="menuitem" href="<?php echo base_url; ?>user/orders.php">Order</a></li>
						  </ul>
						<?php endif; ?>
						</div>
					</h2>
				</div>
			</div>
		</div>
	</header>
