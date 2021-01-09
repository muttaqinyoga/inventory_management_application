<?php  
	define('base_url', 'http://localhost/aplikasi-inventori-produk/');
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Inventori Toko</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendor/bootstrap/css/custom.css">
    <!-- <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap-select.min.css"> -->
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
	      <a class="navbar-brand">Aplikasi Inventori Toko</a>
	    </div>
	    <div class="collapse navbar-collapse" id="myNavbar">
		    <ul class="nav navbar-nav navbar-right">
		      <li><a class="text-white"><span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION['user_name']; ?></a></li>
		      <li><a href="#" data-toggle="modal" data-target="#logoutModal"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
		    </ul>
	    </div>
	  </div>
	</nav>
	<div id="logoutModal" class="modal fade">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header bg-red">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title text-white"><span class="glyphicon glyphicon-log-out"></span> Keluar Aplikasi</h4>
	      </div>
	      <div class="modal-body">
	        <p>Anda ingin keluar dari applikasi?</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
	        <a href="../logout.php" class="btn btn-danger">Ya</a>
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
						  <?php if($_SESSION['user_role']=='Administrator') : ?>
						  <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
						    <li role="presentation"><a role="menuitem" href="<?php echo base_url; ?>admin/user_list.php">Data User</a></li>
						    <li role="presentation"><a role="menuitem" href="<?php echo base_url; ?>admin/categories.php">Data Kategori</a></li>
						    <li role="presentation"><a role="menuitem" href="<?php echo base_url; ?>admin/brands.php">Data Merk Produk</a></li>
						    <li role="presentation"><a role="menuitem" href="<?php echo base_url; ?>admin/suppliers.php">Data Supplier</a></li>
						    <li role="presentation"><a role="menuitem" href="<?php echo base_url; ?>admin/products.php">Data Produk</a></li>
						    <li role="presentation"><a role="menuitem" href="<?php echo base_url; ?>admin/orders.php">Data Order</a></li>
						    <li role="presentation"><a role="menuitem" href="<?php echo base_url; ?>user/">Akun</a></li>
						  </ul>
						  <?php else : ?>
						  <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
						    <li role="presentation"><a role="menuitem" href="<?php echo base_url; ?>user/">Akun</a></li>
						    <li role="presentation"><a role="menuitem" href="<?php echo base_url; ?>user/orders.php">Data Order</a></li>
						  </ul>
						<?php endif; ?>
						</div>
					</h2>
				</div>
			</div>
		</div>
	</header>
