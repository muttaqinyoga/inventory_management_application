<?php
require_once '../database/connections.php';
if (!isset($_SESSION['user_role'])) {
	echo '<script>document.location.href="../"</script>';
	die;
}
if ($_SESSION['user_role'] != 'Administrator') {
	echo '<script>document.location.href="../"</script>';
	die;
}
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
</head>

<body>
	<div id="app">
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
								<?php if ($_SESSION['user_role'] == 'Administrator') : ?>
									<ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
										<li role="presentation">
											<router-link role="menuitem" :to="{name: 'dashboard'}">Dashboard</router-link>
										</li>
										<li role="presentation">
											<router-link role="menuitem" :to="{name: 'categories'}">Data Kategori Produk</router-link>
										</li>
										<li role="presentation">
											<router-link role="menuitem" :to="{name: 'brands'}">Data Merk Produk</router-link>
										</li>
										<li role="presentation">
											<router-link role="menuitem" :to="{name: 'suppliers'}">Data Supplier</router-link>
										</li>
										<li role="presentation">
											<router-link role="menuitem" :to="{name: 'products'}">Data Produk</router-link>
										</li>
										<li role="presentation">
											<router-link role="menuitem" :to="{name: 'orders'}">Data Order</router-link>
										</li>
										<li role="presentation">
											<router-link role="menuitem" :to="{name: 'account'}">Akun</router-link>
										</li>
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
		<!-- Router View -->
		<transition name="fade">
			<router-view>

			</router-view>
		</transition>
		<!-- End Router View -->
	</div>
	<!-- Dashboard templates -->
	<template id="dashboard">
		<section>
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
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading-danger">Overview Aplikasi</div>
								<div class="panel-body">
									<div class="col-md-4">
										<div class="well-info dash-box">
											<h2 class="text-light"><span class="glyphicon glyphicon-user"></span> 0</h2>
											<h4 class="text-light">Total User</h4>
										</div>
									</div>
									<div class="col-md-4">
										<div class="well-warning dash-box">
											<h2 class="text-light"><span class="glyphicon glyphicon-list-alt"></span> 0</h2>
											<h4 class="text-light">Total pendapatan sementara hari ini</h4>
										</div>
									</div>
									<div class="col-md-4">
										<div class="well-secondary dash-box">
											<h2 class="text-light"><span class="glyphicon glyphicon-briefcase"></span> 0</h2>
											<h4 class="text-light">Produk yang sering terjual</h4>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading-success">Order Terbaru</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-striped table-hover table-bordered">

										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</section>
	</template>
	<!-- End Dashboard templates -->
	<!-- Categories templates -->
	<template id="categories">
		<section>
			<section id="breadcrumb">
				<div class="container">
					<ol class="breadcrumb">
						<li class="active">Kategori Produk</li>
					</ol>
				</div>
			</section>
			<div class="container">
				<span id="message">

				</span>
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading-purple">
								<h4 class="text-white">Daftar Kategori Produk</h4>
								<button type="button" name="btnCategoryModal" id="btnCategoryModal" data-toggle="modal" data-target="#addCategoryModal" @click="$parent.clearCategoryForm()" class="btn btn-info"><span class="glyphicon glyphicon-plus"></span> Buat kategori baru</button>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-12 table-responsive">
										<table class="table table-bordered table-striped">
											<thead>
												<tr>
													<th>No.</th>
													<th>Nama Kategori</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
												<tr v-for="(category, i) in $parent.categories" :key="i">
													<td>{{ ++i }}</td>
													<td>{{ category.category_name }}</td>
													<td>
														<button type="button" data-toggle="modal" data-target="#updateCategoryModal" class="btn btn-warning" @click="$parent.showEditCategory(category.category_id, category.category_name)">Ubah</button>
														<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteCategoryModal" @click="$parent.confirmDeleteCategory(category.category_id, category.category_name)">Hapus</button>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="addCategoryModal" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<form method="post" v-on:submit.prevent="$parent.addCategory">
							<div class="modal-header bg-lightblue">
								<button type="button" style="color: #fff;" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title text-white">Buat Kategori</h4>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<label for="categoryName">Nama Kategori</label>
									<input type="text" v-model="$parent.category.name" id="categoryName" class="form-control" placeholder="Masukkan nama kategori...">
									<span class="invalid-feedback" id="errorCategoryName"></span>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
								<button type="submit" class="btn btn-info" id="addCategoryBtn">Simpan</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="updateCategoryModal" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<form method="post" v-on:submit.prevent="$parent.updateCategory($parent.category_id)">
							<div class="modal-header bg-orange">
								<button type="button" style="color: #fff;" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title text-white">Ubah Kategori</h4>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<label for="categoryEditName">Nama Kategori</label>
									<input type="text" v-model="$parent.category.name" id="categoryUpdateName" class="form-control" placeholder="Masukkan nama kategori...">
									<span class="invalid-feedback" id="errorCategoryUpdateName"></span>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
								<button type="submit" class="btn btn-warning" id="updateCategoryBtn">Ubah</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="deleteCategoryModal" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header bg-red">
							<button type="button" style="color: #fff;" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title text-white">Hapus Kategori</h4>
						</div>
						<div class="modal-body">
							<p>Hapus Kategori</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
							<button type="button" class="btn btn-danger" id="deleteCategoryBtn" v-on:click="$parent.deleteCategory($parent.category_id)">Hapus</button>
						</div>
					</div>
				</div>
			</div>
		</section>
	</template>
	<!-- End Categories templates  -->
	<!-- Brands templates -->
	<template id="brands">
		<section>
			<section id="breadcrumb">
				<div class="container">
					<ol class="breadcrumb">
						<li class="active">Merk Produk</li>
					</ol>
				</div>
			</section>
			<div class="container">
				<span id="message">

				</span>
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading-brown">
								<h4 class="text-white">Daftar Merk Produk</h4>
								<button type="button" name="btnBrandModal" id="btnBrandModal" data-toggle="modal" data-target="#addBrandModal" class="btn btn-purple" @click="$parent.clearBrandForm()"><span class="glyphicon glyphicon-plus"></span> Buat merk baru</button>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-12 table-responsive">
										<table class="table table-bordered table-striped">
											<thead>
												<tr>
													<th>No.</th>
													<th>Nama Merk</th>
													<th>Kategori</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
												<tr v-for="(brand, i) in $parent.brands" :key="i">
													<td>{{ ++i }}</td>
													<td>{{ brand.brand_name }}</td>
													<td>{{ brand.category_name }}</td>
													<td>
														<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#updateBrandModal" @click="$parent.showEditBrand( brand.brand_id, brand.brand_name, brand.category_id)">Ubah</button>
														<button class="btn btn-danger" data-toggle="modal" data-target="#deleteBrandModal" @click="$parent.confirmDeleteBrand(brand.brand_id, brand.brand_name)">Hapus</button>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="addBrandModal" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<form method="post" v-on:submit.prevent="$parent.addBrand">
							<div class="modal-header bg-purple">
								<button type="button" style="color: #fff;" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title text-white">Buat Merk</h4>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<label for="brandName">Nama Merk</label>
									<input type="text" v-model="$parent.brand.name" id="brandName" name="name" class="form-control" placeholder="Masukkan nama merk...">
								</div>
								<div class="form-group">
									<label for="brandCategory">Kategori Merk</label>
									<select class="form-control" v-model="$parent.brand.category" id="brandCategory" name="category">
										<option value="" selected="">-- Pilih Kategori --</option>
										<option v-for="(category, i) in $parent.categories" :value="category.category_id">
											{{category.category_name}}
										</option>
									</select>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
								<button type="submit" class="btn btn-purple text-white" id="addBrandBtn">Simpan</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="updateBrandModal" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<form method="post" v-on:submit.prevent="$parent.updateBrand($parent.brand_id)">
							<div class="modal-header bg-orange">
								<button type="button" style="color: #fff;" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title text-white">Ubah Merk</h4>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<label for="brandUpdateName">Nama Merk</label>
									<input type="text" v-model="$parent.brand.name" id="brandUpdateName" name="name" class="form-control" placeholder="Masukkan nama merk...">
									<span class="invalid-feedback" id="errorBrandUpdateName"></span>
								</div>
								<div class="form-group">
									<label for="brandUpdateCategory">Kategori Merk</label>
									<select class="form-control" v-model="$parent.brand.category" id="brandUpdateCategory" name="category">
										<option v-for="(category, i) in $parent.categories" :value="category.category_id">
											{{category.category_name}}
										</option>
									</select>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
								<button type="submit" class="btn btn-warning" id="updateBrandBtn">Ubah</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="deleteBrandModal" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header bg-red">
							<button type="button" style="color: #fff;" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title text-white">Hapus Merk</h4>
						</div>
						<div class="modal-body">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
							<button type="button" class="btn btn-danger" id="deleteBrandBtn" v-on:click="$parent.deleteBrand($parent.brand_id)">Hapus</button>
						</div>
					</div>
				</div>
			</div>
		</section>
	</template>
	<!-- End Brand templates  -->
	<!-- Supplier templates -->
	<template id="suppliers">
		<section>
			<section id="breadcrumb">
				<div class="container">
					<ol class="breadcrumb">
						<li class="active">Suppliers</li>
					</ol>
				</div>
			</section>
			<div class="container">
				<span id="message">

				</span>
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading-danger">
								<h4 class="text-white">Daftar Supplier</h4>
								<button type="button" name="btnSupplierModal" id="btnSupplierModal" data-toggle="modal" data-target="#addSupplierModal" class="btn btn-success" @click="$parent.clearSupplierForm()"><span class="glyphicon glyphicon-plus"></span> Buat supplier baru</button>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-12 table-responsive">
										<table class="table table-bordered table-striped">
											<thead>
												<tr>
													<th>No.</th>
													<th>Nama Supplier</th>
													<th>Kontak</th>
													<th>Email</th>
													<th>Alamat</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
												<tr v-for="(supplier, i) in $parent.suppliers" :key="i">
													<td>{{ ++i }}</td>
													<td>{{ supplier.supplier_name }}</td>
													<td>{{ supplier.supplier_phone }}</td>
													<td>{{ supplier.supplier_email }}</td>
													<td>{{ supplier.supplier_address }}</td>
													<td>
														<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#updateSupplierModal" @click="$parent.showEditSupplier( supplier.supplier_id, supplier.supplier_name, supplier.supplier_phone, supplier.supplier_email, supplier.supplier_address)">Ubah</button>
														<button class="btn btn-danger" data-toggle="modal" data-target="#deleteSupplierModal" @click="$parent.confirmDeleteSupplier(supplier.supplier_id, supplier.supplier_name)">Hapus</button>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="addSupplierModal" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<form method="post" v-on:submit.prevent="$parent.addSupplier">
							<div class="modal-header bg-green">
								<button type="button" style="color: #fff;" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title text-white">Buat Supplier</h4>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<label for="supplierName">Nama Supplier</label>
									<input type="text" v-model="$parent.supplier.name" id="supplierName" name="name" class="form-control" placeholder="Masukkan nama supplier...">
								</div>
								<div class="form-group">
									<label for="supplierPhone">No. HP/Telepon</label>
									<input type="number"  v-model="$parent.supplier.phone" id="supplierPhone" name="phone" class="form-control" placeholder="Masukkan no. HP/telepon supplier...">
								</div>
								<div class="form-group">
									<label for="supplierEmail">Email</label>
									<input type="text" v-model="$parent.supplier.email" id="supplierEmail" name="email" class="form-control" placeholder="Masukkan email supplier...">
								</div>
								<div class="form-group">
									<label for="supplierAddress">Alamat</label>
									<input type="text" v-model="$parent.supplier.address" id="supplierAddress" name="address" class="form-control" placeholder="Masukkan alamat  supplier...">
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
								<button type="submit" class="btn btn-success text-white" id="addSupplierBtn">Simpan</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="updateSupplierModal" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<form method="post" v-on:submit.prevent="$parent.updateSupplier($parent.supplier_id)">
							<div class="modal-header bg-orange">
								<button type="button" style="color: #fff;" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title text-white">Ubah Supplier</h4>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<label for="supplierUpdateName">Nama Supplier</label>
									<input type="text" v-model="$parent.supplier.name" id="supplierUpdateName" name="name" class="form-control" placeholder="Masukkan nama supplier...">
								</div>
								<div class="form-group">
									<label for="supplierUpdatePhone">No. HP/Telepon</label>
									<input type="number"  v-model="$parent.supplier.phone" id="supplierUpdatePhone" name="phone" class="form-control" placeholder="Masukkan no. HP/telepon supplier...">
								</div>
								<div class="form-group">
									<label for="supplierUpdateEmail">Email</label>
									<input type="text" v-model="$parent.supplier.email" id="supplierUpdateEmail" name="email" class="form-control" placeholder="Masukkan email supplier...">
								</div>
								<div class="form-group">
									<label for="supplierUpdateAddress">Alamat</label>
									<input type="text" v-model="$parent.supplier.address" id="supplierUpdateAddress" name="address" class="form-control" placeholder="Masukkan alamat  supplier...">
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
								<button type="submit" class="btn btn-warning" id="updateSupplierBtn">Ubah</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="deleteSupplierModal" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header bg-red">
							<button type="button" style="color: #fff;" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title text-white">Hapus Supplier</h4>
						</div>
						<div class="modal-body">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
							<button type="button" class="btn btn-danger" id="deleteSupplierBtn" v-on:click="$parent.deleteSupplier($parent.supplier_id)">Hapus</button>
						</div>
					</div>
				</div>
			</div>
		</section>
	</template>
	<!-- End Supplier templates  -->
	<!-- Product Templates -->
	<template id="products">
		<section>
			<section id="breadcrumb">
				<div class="container">
					<ol class="breadcrumb">
						<li class="active">Data Produk</li>
					</ol>
				</div>
			</section>
			<div class="container">
				<span id="message">

				</span>
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading-pink">
								<h4 class="text-white">Daftar Produk</h4>
								<button type="button" name="btnProductModal" id="btnProductModal" data-toggle="modal" data-target="#addProductModal" class="btn btn-blue-grey" @click="$parent.clearProductForm()"><span class="glyphicon glyphicon-plus"></span> Buat produk baru</button>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-12 table-responsive">
										<table class="table table-bordered table-striped">
											<thead>
												<tr>
													<th>No.</th>
													<th>Nama Produk</th>
													<th>Kategori/Merk</th>
													<th>Harga Beli</th>
													<th>Supplier</th>
													<th>Harga Jual</th>
													<th>Stok</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
												<tr v-for="(product, i) in $parent.products" :key="i">
													<td>{{ ++i }}</td>
													<td>{{ product.stuff_name }}</td>
													<td>{{ product.category_name }} / {{product.brand_name}}</td>
													<td>Rp. {{ $parent.formatRupiah(product.stuff_buy_price) }}</td>
													<td>{{ product.supplier_name }}</td>
													<td>Rp. {{ $parent.formatRupiah(product.stuff_sale_price) }}</td>
													<td>{{ product.stuff_in_stock }}</td>
													<td>
														<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#updateProductModal" @click="$parent.showEditProduct( product.stuff_id, product.stuff_name,
														product.category_id, product.id_brand, product.stuff_buy_price,
														product.supplier_id, product.stuff_sale_price, product.stuff_in_stock)">Ubah</button>
														<button class="btn btn-danger" data-toggle="modal" data-target="#deleteProductModal" @click="$parent.confirmDeleteProduct(product.stuff_id, product.stuff_name)">Hapus</button>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="addProductModal" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<form method="post" v-on:submit.prevent="$parent.addProduct">
							<div class="modal-header bg-pink">
								<button type="button" style="color: #fff;" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title text-white">Buat produk baru</h4>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<label for="productName">Nama Produk</label>
									<input type="text" v-model="$parent.product.name" id="productName" name="name" class="form-control" placeholder="Masukkan nama produk...">
								</div>
								<div class="form-group">
									<label for="productCategory">Kategori</label>
									<select class="form-control" v-model="$parent.product.category" id="productCategory" name="category" @change="$parent.getBrandsByCategory">
										<option v-for="(category, i) in $parent.categories" :value="category.category_id">
											{{category.category_name}}
										</option>
									</select>
								</div>
								<div class="form-group">
									<label for="productBrand">Merk</label>
									<select class="form-control" v-model="$parent.product.brand" id="productBrand" name="brand" disabled>
										<option value="" selected="">-- Pilih Merk Produk --</option>
										<option v-for="(brand, i) in $parent.brands" :value="brand.brand_id">
											{{brand.brand_name}}
										</option>
									</select>
								</div>
								<div class="form-group">
									<label for="productBuyPrice">Harga Beli</label>
									<input type="number" v-model="$parent.product.buy_price" id="productBuyPrice" name="buy_price" class="form-control" placeholder="Misal: 100000" min="0">
								</div>
								<div class="form-group">
									<label for="productSupplier">Supplier</label>
									<select class="form-control" v-model="$parent.product.supplier" id="productSupplier" name="supplier">
										<option value="" selected="">-- Pilih Supplier --</option>
										<option v-for="(supplier, i) in $parent.suppliers" :value="supplier.supplier_id">
											{{supplier.supplier_name}}
										</option>
									</select>
								</div>
								<div class="form-group">
									<label for="productSalePrice">Harga Jual</label>
									<input type="number" v-model="$parent.product.sale_price" id="productSalePrice" name="sale_price" class="form-control" placeholder="Misal: 100000" min="0">
								</div>
								<div class="form-group">
									<label for="productInStock">Stok</label>
									<input type="number" v-model="$parent.product.stock" id="productInStock" name="stock" class="form-control" placeholder="Misal: 100000" min="0">
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
								<button type="submit" class="btn btn-pink text-dark" id="addProductBtn">Simpan</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="updateProductModal" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<form method="post" v-on:submit.prevent="$parent.updateProduct($parent.product_id)">
							<div class="modal-header bg-orange">
								<button type="button" style="color: #fff;" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title text-white">Ubah Produk</h4>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<label for="productName">Nama Produk</label>
									<input type="text" v-model="$parent.product.name" id="productUpdateName" name="name" class="form-control" placeholder="Masukkan nama produk...">
								</div>
								<div class="form-group">
									<label for="productCategory">Kategori</label>
									<select class="form-control" v-model="$parent.product.category" id="productUpdateCategory" name="category" @change="$parent.getBrandsByCategory">
										<option v-for="(category, i) in $parent.categories" :value="category.category_id">
											{{category.category_name}}
										</option>
									</select>
								</div>
								<div class="form-group">
									<label for="productUpdateBrand">Merk</label>
									<select class="form-control" v-model="$parent.product.brand" id="productUpdateBrand" name="brand">
										<option v-for="(brand, i) in $parent.brands" :value="brand.brand_id">
											{{brand.brand_name}}
										</option>
									</select>
								</div>
								<div class="form-group">
									<label for="productUpdateBuyPrice">Harga Beli</label>
									<input type="number" v-model="$parent.product.buy_price" id="productUpdateBuyPrice" name="buy_price" class="form-control" placeholder="Misal: 100000" min="0">
								</div>
								<div class="form-group">
									<label for="productUpdateSupplier">Supplier</label>
									<select class="form-control" v-model="$parent.product.supplier" id="productUpdateSupplier" name="supplier">
										<option v-for="(supplier, i) in $parent.suppliers" :value="supplier.supplier_id">
											{{supplier.supplier_name}}
										</option>
									</select>
								</div>
								<div class="form-group">
									<label for="productUpdateSalePrice">Harga Jual</label>
									<input type="number" v-model="$parent.product.sale_price" id="productUpdateSalePrice" name="sale_price" class="form-control" placeholder="Misal: 100000" min="0">
								</div>
								<div class="form-group">
									<label for="productUpdateInStock">Stok</label>
									<input type="number" v-model="$parent.product.stock" id="productUpdateInStock" name="stock" class="form-control" placeholder="Misal: 100000" min="0">
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
								<button type="submit" class="btn btn-warning" id="updateProductBtn">Ubah</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="deleteProductModal" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header bg-red">
							<button type="button" style="color: #fff;" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title text-white">Hapus Produk</h4>
						</div>
						<div class="modal-body">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
							<button type="button" class="btn btn-danger" id="deleteProductBtn" v-on:click="$parent.deleteProduct($parent.product_id)">Hapus</button>
						</div>
					</div>
				</div>
			</div>
		</section>
	</template>
	<!-- Products Templates -->

	<!-- End Products Templates -->
	<!-- Orders Templates -->
	<template id="orders">
		<section>
			<section id="breadcrumb">
				<div class="container">
					<ol class="breadcrumb">
						<li class="active">Data Order</li>
					</ol>
				</div>
			</section>
			<div class="container">
				<span id="message">

				</span>
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading-success">
								<h4 class="text-white">Daftar Order</h4>
								<button type="button" name="btnOrderModal" id="btnOrderModal" data-toggle="modal" data-target="#addOrderModal" class="btn btn-secondary" @click="$parent.clearOrderForm()"><span class="glyphicon glyphicon-plus"></span> Buat order baru</button>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-12 table-responsive">
										<table class="table table-bordered table-striped">
											<thead>
												<tr>
													<th>No.</th>
													<th>Nomor Order</th>
													<th>Waktu Order</th>
													<th>Total Order</th>
													<th>Metode Pembayaran</th>
													<th>Aksi</th>
												</tr>
											</thead>
											<tbody>
												<tr v-for="(order, i) in $parent.orders" :key="i">
													<td>{{ ++i }}</td>
													<td>{{ order.order_invoice_number }}</td>
													<td>{{ order.order_created_at }}</td>
													<td>{{order.order_total_price }}</td>
													<td>{{ order.order_payment_type }}</td>
													<td>
														<router-link class="btn btn-warning" :to="{name: 'orderdetails', params: {id: order.order_id} }">Detail</router-link>
														<button class="btn btn-danger" data-toggle="modal" data-target="#deleteOrderModal" @click="$parent.confirmDeleteOrder(order.order_id, order.order_invoice_number)">Hapus</button>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="addOrderModal" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<form method="post" v-on:submit.prevent="$parent.addProduct">
							<div class="modal-header bg-grey">
								<button type="button" style="color: #fff;" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title text-white">Buat order baru</h4>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<label for="orderReceiver">Nama Pelanggan</label>
									<input type="text" name="receiver" id="orderReceiver" class="form-control" v-model="$parent.order.receiver" placeholder="Masukkan nama pelanggan...">
								</div>
								<div class="form-group">
									<label for="orderReceiverEmail">Email Pelanggan</label>
									<input type="email" name="receiver_email" id="orderReceiverEmail" class="form-control" v-model="$parent.order.receiver_email" placeholder="Masukkan email pelanggan...">
								</div>
								<div class="form-group">
									<label for="orderReceiverPhone">Nama Pelanggan</label>
									<input type="number" name="receiver_phone" id="orderReceiverPhone" class="form-control" v-model="$parent.order.receiver_phone" placeholder="Masukkan nomor HP/Telepon pelanggan..." min="0">
								</div>
								<div class="form-group">
									<label for="orderReceiverAddress">Alamat Orderan</label>
									<input type="text" name="receiver_address" id="orderReceiverAddress" class="form-control" v-model="$parent.order.receiver" placeholder="Masukkan alamat pemesanan...">
								</div>
								<div class="form-group">
									<label for="orderProduct">Produk</label>
									<select class="form-control" v-model="$parent.order.product" id="orderProduct" name="product">
										<option selected value="">-- Pilih produk --</option>
										<option v-for="(product, i) in $parent.products" :value="product.product_id">
											{{product.stuff_name}} ( Rp. {{$parent.formatRupiah(product.stuff_sale_price)}} )
										</option>
									</select>
								</div>
								<div class="form-group">
									<label for="quantity_ordered">Jumlah barang yang diorder</label>
									<input type="number" v-model="$parent.order.quantity_ordered" id="quantity_ordered" name="quantity_ordered" class="form-control" placeholder="Masukkan jumlah barang yang diorder" min="0">
								</div>
								<div class="form-group">
									<label for="orderPaymentType">Metode Pembayaran</label>
									<select class="form-control" v-model="$parent.order.payment_type" id="orderPaymentType" name="">
										<option selected value="">-- Pilih metode pembayaran --</option>
										<option v-for="(p) in $parent.payment_types" :value="p">{{ p }}</option>
									</select>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
								<button type="submit" class="btn btn-secondary" id="addOrderBtn">Simpan</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			
			<!-- <div id="deleteProductModal" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header bg-red">
							<button type="button" style="color: #fff;" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title text-white">Hapus Produk</h4>
						</div>
						<div class="modal-body">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
							<button type="button" class="btn btn-danger" id="deleteProductBtn" v-on:click="$parent.deleteProduct($parent.product_id)">Hapus</button>
						</div>
					</div>
				</div>
			</div> -->
		</section>
	</template>
	<!-- End Order Templates -->
	<script type="text/javascript" src="../vendor/bootstrap/js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="../vendor/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../vendor/bootstrap/js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript" src="../vendor/bootstrap/js/bootstrap-select.min.js"></script>
	<script type="text/javascript" src="../vendor/vue/vue.js"></script>
	<script type="text/javascript" src="../vendor/vue/vue-router.js"></script>
	<script type="text/javascript" src="../vendor/vue/axios.min.js"></script>
	<script type="text/javascript" src="app.js"></script>
</body>

</html>