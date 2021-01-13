axios.defaults.baseURL = 'http://localhost/aplikasi-inventori-toko/admin';
// All components
const dashboard = {
	template: '#dashboard',
	beforeRouteEnter(to, from, next){
		$('#notFound').html('');
		next();
	}
}
const categories = {
	template: '#categories',
	beforeRouteEnter(to, from, next) {
		next(vm => {
			vm.$root.category = {
				name: ''
			}
		})
	},
}
const brands = {
	template: '#brands',
	beforeRouteEnter(to, from, next) {
		$('#notFound').html('');
		next(vm => {
			vm.$root.brand = {
				name: '',
				category: ''
			}
		});
	}
}
const products = {
	template: '#products',
	beforeRouteEnter(to, from, next) {
		$('#notFound').html('');
		next(vm => {
			vm.$root.product = {
				name: '',
				category: '',
				brand: '',
				buy_price: '',
				supplier: '',
				sale_price: '',
				stock: ''
			}
		});
	}
}
const suppliers = {
	template: '#suppliers',
	beforeRouteEnter(to, from, next) {
		$('#notFound').html('');
		next(vm => {
			vm.$root.supplier = {
				name: '',
				phone: '',
				email: '',
				address: ''
			}
		});
	}
}
const orders = {
	template: '#orders',
	beforeRouteEnter(to, from, next) {
		$('#notFound').html('');
		if(from.name == 'orderdetails'){
			next(vm => {
				vm.$root.order = {
					receiver: '',
					receiver_phone: '',
					receiver_address: '',
					product: '',
					quantity_ordered: '',
					payment_type: ''
				}
				vm.$root.orders = [];
				window.setTimeout(function(){
					$('#message').html(`<div class="alert alert-warning">
										  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										  <strong>Data order berhasil diubah</strong>
										</div>`);
					vm.$root.getOrders();
				},500);
			});
		} else{
			next(vm => {
				vm.$root.order = {
					receiver: '',
					receiver_phone: '',
					receiver_address: '',
					product: '',
					quantity_ordered: '',
					payment_type: ''
				}
				vm.$root.getOrders();
			});
		}
	},
}
const orderdetails = {
	template: '#orderdetails',
	data: function(){
		return {
			orderdetails: [],
			orderdetail: {
				product: '',
				quantity_ordered: '',
				payment_type: '',
				receiver: '',
				receiver_phone: '',
				receiver_address: ''
			},
			order_id: ''
		}
	},
	created () {
	    this.getOrderDetails();
	 },
	watch: {
		'$route' : 'getOrderDetails'
	},
	methods: {
		getOrderDetails(){
			const orderdetails_id  = this.$route.params.id;
			$('#notFound').html('');
			axios.get('/order.php?detail='+orderdetails_id)
			.then( response => {
				this.orderdetails = response.data;
				this.order_id = this.orderdetails[0]['pesanan_id'];
				this.orderdetail.product = this.orderdetails[0]['stuff_id'];
				this.orderdetail.quantity_ordered = this.orderdetails[0]['jumlah_yang_diorder'];
				this.orderdetail.receiver = this.orderdetails[0]['nama_penerima'];
				this.orderdetail.receiver_phone = this.orderdetails[0]['telepon_penerima'];
				this.orderdetail.receiver_address = this.orderdetails[0]['alamat_penerima'];
				this.orderdetail.payment_type = this.orderdetails[0]['metode_pembayaran'];
			})
			.catch(error => {
				$('#notFound').html(`<h2 class="text-center">Ooops Page Not Found</h2>`);
			})
		},
		updateOrder(id){
			let vm = this;
			axios.patch('/order.php?id='+id, vm.orderdetail)
				.then(response => {
					$('.invalid-feedback').remove();
					$('.is-invalid').removeClass('is-invalid');
					$('#updateOrderBtn').html(`<span class="spinner-border"></span> Menyimpan`);
					$('#updateOrderBtn').attr('disabled', true);
					window.setTimeout(function () {
						vm.order_id = '';
						vm.orderdetail.product = '';
						vm.orderdetail.quantity_ordered = '';
						vm.orderdetail.receiver = '';
						vm.orderdetail.receiver_phone = '';
						vm.orderdetail.receiver_address = '';
						vm.orderdetail.payment_type = '';
						const message = `${response.data.message}`;
						$('#updateOrderBtn').attr('disabled', false);
						$('#updateOrderBtn').html(`Simpan`);
						vm.$router.push('/orders');
					}, 2000);
				})
				.catch(error => {
					$('.invalid-feedback').remove();
					$('.is-invalid').removeClass('is-invalid');
					let {
						response
					} = error;
					$.each(response.data.errors, function (i, err) {
						const el = $(document).find('[name="' + i + '"]');
						if (el) {
							el.addClass('is-invalid');
							el.after($('<div class="invalid-feedback">' + err + '</div>'));
						}
					});
				});
		},
	}
}
const account = {
	template: '#account'
}
// Instance App

const app = new Vue({
	el: '#app',
	data: {
		categories: [],
		category: {
			name: ''
		},
		category_id: '',
		brands: [],
		brand: {
			name: '',
			category: ''
		},
		brand_id: '',
		suppliers: [],
		supplier: {
			name: '',
			phone: '',
			email: '',
			address: ''
		},
		supplier_id: '',
		products: [],
		product: {
			name: '',
			category: '',
			brand: '',
			buy_price: '',
			supplier: '',
			sale_price: '',
			stock: ''
		},
		product_id: '',
		orders: [],
		order: {
			receiver: '',
			receiver_phone: '',
			receiver_address: '',
			product: '',
			quantity_ordered: '',
			payment_type: ''
		},
		order_id: '',
		payment_types: ['cash', 'credit'],
		users: [],
		user: {
			name: '',
			email: '',
			password: '',
			confirm_password: ''
		}
	},
	router: new VueRouter({
		routes: [{
				path: '/',
				component: dashboard,
				name: 'dashboard'
			},
			{
				path: '/categories',
				component: categories,
				name: 'categories'
			},
			{
				path: '/brands',
				component: brands,
				name: 'brands'
			},
			{
				path: '/suppliers',
				component: suppliers,
				name: 'suppliers'
			},
			{
				path: '/products',
				component: products,
				name: 'products'
			},
			{
				path: '/orders',
				component: orders,
				name: 'orders'
			},
			{
				path: '/orders/details/:id',
				component: orderdetails,
				name: 'orderdetails'
			},
			{
				path: '/account',
				component: account,
				name: 'account'
			},
		]
	}),
	methods: {
		// Categories
		getCategories(message = '') {
			axios.get('/category.php')
				.then(response => this.categories = response.data)
			if (message != '') {
				$('#message').html(message);
			}
		},
		addCategory() {
			let vm = this;
			$('#categoryName').removeClass('is-invalid');
			$('#errorCategoryName').html('');
			axios.post('/category.php', vm.category)
				.then(response => {
					$('#addCategoryBtn').attr('disabled', true);
					$('#addCategoryBtn').html(`<span class="spinner-border"></span> Menyimpan`);
					window.setTimeout(function () {
						const message = `${response.data.message}`;
						vm.category.name = '';
						$('#addCategoryModal').modal('hide');
						$('#addCategoryBtn').attr('disabled', false);
						$('#addCategoryBtn').html(`Simpan`);
						vm.getCategories(message);
					}, 2000);
				})
				.catch((error) => {
					let {
						response
					} = error;
					if (response.data.errors == 'invalid request') {
						$('#categoryName').addClass('is-invalid');
						$('#errorCategoryName').html('Nama Kategori tidak boleh kosong');
						console.log('oke');
					}
				});
		},
		confirmDeleteCategory(id, name) {
			$('#deleteCategoryModal .modal-body').html(`<p>Anda yakin ingin menghapus kategori <strong>${name}</strong> ? Barang dengan kategori tersebut otomatis akan terhapus</p>`);
			this.category_id = id;
		},
		deleteCategory(id) {
			let vm = this;
			$('#deleteCategoryBtn').html(`<span class="spinner-border"></span> Menghapus`);
			$('#deleteCategoryBtn').attr('disabled', true);
			axios.delete('/category.php?id=' + id)
				.then(response => {
					window.setTimeout(function () {
						const message = `${response.data.message}`;
						vm.category_id = '';
						$('#deleteCategoryModal').modal('hide');
						$('#deleteCategoryBtn').attr('disabled', false);
						$('#deleteCategoryBtn').html(`Hapus`);
						vm.getCategories(message);
					}, 2000);
				});
		},
		showEditCategory(id, name) {
			$('#errorCategoryName').html('');
			$('#errorCategoryUpdateName').html('');
			$('#categoryUpdateName').removeClass('is-invalid');
			this.category.name = name;
			this.category_id = id;
		},
		updateCategory(id) {
			let vm = this;
			$('#categoryUpdateName').removeClass('is-invalid');
			$('#errorCategoryUpdateName').html('');
			axios.patch('/category.php?id=' + id, vm.category)
				.then(response => {
					$('#updateCategoryBtn').html(`<span class="spinner-border"></span> Mengubah`);
					$('#updateCategoryBtn').attr('disabled', true);
					window.setTimeout(function () {
						vm.category.name = '';
						vm.category_id = '';
						const message = `${response.data.message}`;
						$('#updateCategoryModal').modal('hide');
						$('#updateCategoryBtn').attr('disabled', false);
						$('#updateCategoryBtn').html(`Ubah`);
						vm.getCategories(message);
					}, 2000);
				})
				.catch((error) => {
					let {
						response
					} = error;
					if (response.data.errors == 'invalid request') {
						$('#categoryUpdateName').addClass('is-invalid');
						$('#errorCategoryUpdateName').html('Nama Kategori tidak boleh kosong');
					}
				});
		},
		clearCategoryForm(){
			$('#errorCategoryName').html('');
			$('#errorCategoryUpdateName').html('');
			$('.is-invalid').removeClass('is-invalid');
			this.category.name = '';
			this.category_id = '';
		},
		// End Categories
		// Brands
		getBrands(message = '') {
			axios.get('/brand.php')
				.then(response => this.brands = response.data)
			if (message != '') {
				$('#message').html(message);
			}
		},
		addBrand() {
			let vm = this;
			axios.post('/brand.php', vm.brand)
				.then(response => {
					$('.invalid-feedback').remove();
					$('.is-invalid').removeClass('is-invalid');
					$('#addBrandBtn').html(`<span class="spinner-border"></span> Menyimpan`);
					$('#addBrandBtn').attr('disabled', true);
					window.setTimeout(function () {
						vm.brand.name = '';
						vm.brand.category = '';
						const message = `${response.data.message}`;
						$('#addBrandModal').modal('hide');
						$('#addBrandBtn').attr('disabled', false);
						$('#addBrandBtn').html(`Simpan`);
						vm.getBrands(message);
					}, 2000);
				})
				.catch(error => {
					$('.invalid-feedback').remove();
					$('.is-invalid').removeClass('is-invalid');
					let {
						response
					} = error;
					$.each(response.data.errors, function (i, err) {
						const el = $(document).find('[name="' + i + '"]');
						if (el) {
							el.addClass('is-invalid');
							el.after($('<div class="invalid-feedback">' + err + '</div>'));
						}
					});
				});
		},
		showEditBrand(brand_id, name, category) {
			$('.invalid-feedback').remove();
			$('.is-invalid').removeClass('is-invalid');
			this.brand_id = brand_id
			this.brand.name = name;
			this.brand.category = category;
		},
		clearBrandForm() {
			$('.invalid-feedback').remove();
			$('.is-invalid').removeClass('is-invalid');
			this.brand.name = '';
			this.brand.category = '';
			this.brand_id ='';
		},
		updateBrand(id) {
			let vm = this;
			axios.patch('/brand.php?id=' + id, vm.brand)
				.then(response => {
					$('.invalid-feedback').remove();
					$('.is-invalid').removeClass('is-invalid');
					$('#updateBrandBtn').html(`<span class="spinner-border"></span> Mengubah`);
					$('#updateBrandBtn').attr('disabled', true);
					window.setTimeout(function () {
						vm.brand_id = '';
						vm.brand.name = '';
						vm.brand.category = '';
						const message = `${response.data.message}`;
						$('#updateBrandModal').modal('hide');
						$('#updateBrandBtn').attr('disabled', false);
						$('#updateBrandBtn').html(`Mengubah`);
						vm.getBrands(message);
					}, 2000);
				})
				.catch(error => {
					$('.invalid-feedback').remove();
					$('.is-invalid').removeClass('is-invalid');
					let {
						response
					} = error;
					$.each(response.data.errors, function (i, err) {
						const el = $(document).find('[name="' + i + '"]');
						if (el) {
							el.addClass('is-invalid');
							el.after($('<div class="invalid-feedback">' + err + '</div>'));
						}
					});
				});
		},
		confirmDeleteBrand(id, name) {
			$('#deleteBrandModal .modal-body').html(`<p>Anda yakin ingin menghapus merk <strong>${name}</strong> ? </p>`);
			this.brand_id = id;
		},
		deleteBrand(id) {
			let vm = this;
			$('#deleteBrandBtn').html(`<span class="spinner-border"></span> Menghapus`);
			$('#deleteBrandBtn').attr('disabled', true);
			axios.delete('/brand.php?id=' + id)
				.then(response => {
					window.setTimeout(function () {
						const message = `${response.data.message}`;
						vm.brand_id = '';
						$('#deleteBrandModal').modal('hide');
						$('#deleteBrandBtn').attr('disabled', false);
						$('#deleteBrandBtn').html(`Hapus`);
						vm.getBrands(message);
					}, 2000);
				});
		},
		// End Brands
		// Suppliers
		getSuppliers(message = ''){
			axios.get('/supplier.php')
				.then(response => this.suppliers = response.data)
			if (message != '') {
				$('#message').html(message);
			}
		},
		addSupplier() {
			let vm = this;
			axios.post('/supplier.php', vm.supplier)
				.then(response => {
					$('.invalid-feedback').remove();
					$('.is-invalid').removeClass('is-invalid');
					$('#addSupplierBtn').html(`<span class="spinner-border"></span> Menyimpan`);
					$('#addSupplierBtn').attr('disabled', true);
					window.setTimeout(function () {
						vm.supplier.name = '';
						vm.supplier.phone = '';
						vm.supplier.email = '';
						vm.supplier.address = '';
						const message = `${response.data.message}`;
						$('#addSupplierModal').modal('hide');
						$('#addSupplierBtn').attr('disabled', false);
						$('#addSupplierBtn').html(`Simpan`);
						vm.getSuppliers(message);
					}, 2000);
				})
				.catch(error => {
					$('.invalid-feedback').remove();
					$('.is-invalid').removeClass('is-invalid');
					let {
						response
					} = error;
					$.each(response.data.errors, function (i, err) {
						const el = $(document).find('[name="' + i + '"]');
						if (el) {
							el.addClass('is-invalid');
							el.after($('<div class="invalid-feedback">' + err + '</div>'));
						}
					});
				});
		},
		clearSupplierForm(){
			$('.invalid-feedback').remove();
			$('.is-invalid').removeClass('is-invalid');
			this.supplier.name = '';
			this.supplier.email = '';
			this.supplier.phone = '';
			this.supplier.address = '';
			this.supplier_id = '';
		},
		showEditSupplier(id, name,  phone, email, address){
			$('.invalid-feedback').remove();
			$('.is-invalid').removeClass('is-invalid');
			this.supplier.name = name;
			this.supplier.email = email;
			this.supplier.phone = phone;
			this.supplier.address = address;
			this.supplier_id = id;
		},
		updateSupplier(id){
			let vm = this;
			axios.patch('/supplier.php?id='+id, vm.supplier)
				.then(response => {
					$('.invalid-feedback').remove();
					$('.is-invalid').removeClass('is-invalid');
					$('#updateSupplierBtn').html(`<span class="spinner-border"></span> Mengubah`);
					$('#updateSupplierBtn').attr('disabled', true);
					window.setTimeout(function () {
						vm.supplier_id = '';
						vm.supplier.name = '';
						vm.supplier.phone = '';
						vm.supplier.email = '';
						vm.supplier.address = '';
						const message = `${response.data.message}`;
						$('#updateSupplierModal').modal('hide');
						$('#updateSupplierBtn').attr('disabled', false);
						$('#updateSupplierBtn').html(`Ubah`);
						vm.getSuppliers(message);
					}, 2000);
				})
				.catch(error => {
					$('.invalid-feedback').remove();
					$('.is-invalid').removeClass('is-invalid');
					let {
						response
					} = error;
					$.each(response.data.errors, function (i, err) {
						const el = $(document).find('[name="' + i + '"]');
						if (el) {
							el.addClass('is-invalid');
							el.after($('<div class="invalid-feedback">' + err + '</div>'));
						}
					});
				});
		},
		confirmDeleteSupplier(id, name){
			$('#deleteSupplierModal .modal-body').html(`<p>Anda yakin ingin menghapus <strong>${name}</strong> dari daftar supplier ? Barang yang terkait dengan supplier tersebut otomatis akan terhapus</p>`);
			this.supplier_id = id;
		},
		deleteSupplier(id){
			let vm = this;
			$('#deleteSupplierBtn').html(`<span class="spinner-border"></span> Menghapus`);
			$('#deleteSupplierBtn').attr('disabled', true);
			axios.delete('/supplier.php?id=' + id)
				.then(response => {
					window.setTimeout(function () {
						const message = `${response.data.message}`;
						vm.supplier_id = '';
						$('#deleteSupplierModal').modal('hide');
						$('#deleteSupplierBtn').attr('disabled', false);
						$('#deleteSupplierBtn').html(`Hapus`);
						vm.getSuppliers(message);
					}, 2000);
				});
		},
		getProducts(message = ''){
			axios.get('/product.php')
				.then(response => this.products = response.data)
			if (message != '') {
				$('#message').html(message);
			}
		},
		clearProductForm(){
			$('.invalid-feedback').remove();
			$('.is-invalid').removeClass('is-invalid');
			this.product.name = '';
			this.product.category = '';
			this.product.brand = '';
			this.product.buy_price = '';
			this.product.supplier = '';
			this.product.sale_price = '';
			this.product.stock = '';
			this.product_id = '';
			this.brands = [];
		},
		getBrandsByCategory(){
			$('#productBrand').attr('disabled', false);
			axios.get('/brand.php?category='+this.product.category)
			.then( response => this.brands = response.data );
		},
		addProduct() {
			let vm = this;
			axios.post('/product.php', vm.product)
				.then(response => {
					$('.invalid-feedback').remove();
					$('.is-invalid').removeClass('is-invalid');
					$('#addProductBtn').html(`<span class="spinner-border"></span> Menyimpan`);
					$('#addProductBtn').attr('disabled', true);
					window.setTimeout(function () {
						vm.product.name = '';
						vm.product.category = '';
						vm.product.brand = '';
						vm.product.buy_price = '';
						vm.product.supplier = '';
						vm.product.sale_price = '';
						vm.product.stock = '';
						const message = `${response.data.message}`;
						$('#addProductModal').modal('hide');
						$('#addProductBtn').attr('disabled', false);
						$('#addProductBtn').html(`Simpan`);
						vm.getProducts(message);
					}, 2000);
				})
				.catch(error => {
					$('.invalid-feedback').remove();
					$('.is-invalid').removeClass('is-invalid');
					let {
						response
					} = error;
					$.each(response.data.errors, function (i, err) {
						const el = $(document).find('[name="' + i + '"]');
						if (el) {
							el.addClass('is-invalid');
							el.after($('<div class="invalid-feedback">' + err + '</div>'));
						}
					});
				});
		},
		showEditProduct(id, name, category, brand, buy_price, supplier, sale_price, stock) {
			$('.invalid-feedback').remove();
			$('.is-invalid').removeClass('is-invalid');
			this.product_id = id;
			this.product.name = name;
			this.product.category = category;
			this.product.brand = brand;
			this.product.buy_price = buy_price;
			this.product.supplier = supplier;
			this.product.sale_price = sale_price;
			this.product.stock = stock;
			this.getBrandsByCategory();	
		},
		updateProduct(id){
			let vm = this;
			axios.patch('/product.php?id='+id, vm.product)
				.then(response => {
					$('.invalid-feedback').remove();
					$('.is-invalid').removeClass('is-invalid');
					$('#updateProductBtn').html(`<span class="spinner-border"></span> Mengubah`);
					$('#updateProductBtn').attr('disabled', true);
					window.setTimeout(function () {
						vm.product_id = '';
						vm.product.name = '';
						vm.product.category = '';
						vm.product.brand = '';
						vm.product.buy_price = '';
						vm.product.supplier = '';
						vm.product.sale_price = '';
						vm.product.stock = '';
						const message = `${response.data.message}`;
						$('#updateProductModal').modal('hide');
						$('#updateProductBtn').attr('disabled', false);
						$('#updateProductBtn').html(`Ubah`);
						vm.getProducts(message);
					}, 2000);
				})
				.catch(error => {
					$('.invalid-feedback').remove();
					$('.is-invalid').removeClass('is-invalid');
					let {
						response
					} = error;
					$.each(response.data.errors, function (i, err) {
						const el = $(document).find('[name="' + i + '"]');
						if (el) {
							el.addClass('is-invalid');
							el.after($('<div class="invalid-feedback">' + err + '</div>'));
						}
					});
				});
		},
		confirmDeleteProduct(id, name){
			$('#deleteProductModal .modal-body').html(`<p>Anda yakin ingin menghapus <strong>${name}</strong> dari daftar produk ? Order yang memiliki barang terkait dengan produk tersebut akan menjadi kosong</p>`);
			this.product_id = id;
		},
		deleteProduct(id){
			let vm = this;
			$('#deleteProductBtn').html(`<span class="spinner-border"></span> Menghapus`);
			$('#deleteProductBtn').attr('disabled', true);
			axios.delete('/product.php?id=' + id)
				.then(response => {
					window.setTimeout(function () {
						const message = `${response.data.message}`;
						vm.product_id = '';
						$('#deleteProductModal').modal('hide');
						$('#deleteProductBtn').attr('disabled', false);
						$('#deleteProductBtn').html(`Hapus`);
						vm.getProducts(message);
					}, 2000);
				});
		},
		getOrders(message = ''){
			axios.get('/order.php')
				.then(response => this.orders = response.data)
			if (message != '') {
				$('#message').html(message);
			}
		},
		addOrder() {
			let vm = this;
			axios.post('/order.php', vm.order)
				.then(response => {
					$('.invalid-feedback').remove();
					$('.is-invalid').removeClass('is-invalid');
					$('#addOrderBtn').html(`<span class="spinner-border"></span> Menyimpan`);
					$('#addOrderBtn').attr('disabled', true);
					window.setTimeout(function () {
						vm.order.receiver = '';
						vm.order.receiver_phone = '';
						vm.order.receiver_address = '';
						vm.order.product = '';
						vm.order.quantity_ordered = '';
						vm.order.payment_type = '';
						const message = `${response.data.message}`;
						$('#addOrderModal').modal('hide');
						$('#addOrderBtn').attr('disabled', false);
						$('#addOrderBtn').html(`Simpan`);
						vm.getOrders(message);
					}, 2000);
				})
				.catch(error => {
					$('.invalid-feedback').remove();
					$('.is-invalid').removeClass('is-invalid');
					let {
						response
					} = error;
					$.each(response.data.errors, function (i, err) {
						const el = $(document).find('[name="' + i + '"]');
						if (el) {
							el.addClass('is-invalid');
							el.after($('<div class="invalid-feedback">' + err + '</div>'));
						}
					});
				});
		},
		clearOrderForm(){
			$('.invalid-feedback').remove();
			$('.is-invalid').removeClass('is-invalid');
			this.order.receiver = '';
			this.order.receiver_phone = '';
			this.order.receiver_address = '';
			this.order.product = '';
			this.order.quantity_ordered = '';
			this.order.payment_type = '';
			this.order_id = '';
		},
		confirmDeleteOrder(id, no){
			this.order_id = id;
			$('#deleteOrderModal .modal-body').html(`<p>Anda yakin ingin membatalkan order dengan no.  <strong>${no}</strong> ?</p>`);
		},
		deleteOrder(id){
			let vm = this;
			$('#deleteOrderBtn').html(`<span class="spinner-border"></span> Menghapus`);
			$('#deleteOrderBtn').attr('disabled', true);
			axios.delete('/order.php?id=' + id)
				.then(response => {
					window.setTimeout(function () {
						const message = `${response.data.message}`;
						vm.order_id = '';
						$('#deleteOrderModal').modal('hide');
						$('#deleteOrderBtn').attr('disabled', false);
						$('#deleteOrdertBtn').html(`Ya`);
						vm.getOrders(message);
					}, 2000);
				});
		},
		getAccount(message = ''){
			axios.get('/user.php')
				.then(response => this.users = response.data)
			if (message != '') {
				$('#message').html(message);
			}
		},
		updateUser(){
			let vm = this;
			axios.patch('/user.php', vm.user)
				.then(response => {
					$('.invalid-feedback').remove();
					$('.is-invalid').removeClass('is-invalid');
					$('#updateUserBtn').html(`<span class="spinner-border"></span> Menyimpan`);
					$('#updateUserBtn').attr('disabled', true);
					window.setTimeout(function () {
						vm.user.name = '';
						vm.user.email = '';
						vm.user.password = '';
						vm.user.confirm_password = '';
						const message = `${response.data.message}`;
						$('#updateUserBtn').attr('disabled', false);
						$('#updateUserBtn').html(`Simpan`);
						vm.getAccount(message);
					}, 2000);
				})
				.catch(error => {
					$('.invalid-feedback').remove();
					$('.is-invalid').removeClass('is-invalid');
					let {
						response
					} = error;
					$.each(response.data.errors, function (i, err) {
						const el = $(document).find('[name="' + i + '"]');
						if (el) {
							el.addClass('is-invalid');
							el.after($('<div class="invalid-feedback">' + err + '</div>'));
						}
					});
				});
		},
		// checkPaymentType(){
		// 	if(this.order.payment_type == 'credit'){
		// 		$('#formPayment').after(``);
		// 	}
		// },
		formatRupiah(x){
    		let parts = x.split(".");
		    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		    return parts.join(".");	
		},
		formatWaktu(d){
			const t = new Date(d);
			const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
			return `${t.getDate()} ${months[t.getMonth()]} ${t.getFullYear()} pukul ${t.getHours()}.${t.getMinutes()}.${t.getSeconds()} WIB`;
		}
	},
	created() {
		this.getCategories();
		this.getBrands();
		this.getSuppliers();
		this.getProducts();
		this.getOrders();
		this.getAccount();
	}
});
