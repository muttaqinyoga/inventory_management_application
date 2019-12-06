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
	require_once '../templates/header.php';
?>
	<section id="breadcrumb">
		<div class="container">
			<ol class="breadcrumb">
			  <li><a href="index.php">Dashboard</a></li>
			  <li class="active">Product</li>
			</ol>
		</div>
	</section>
	<div class="container">
		<span id="alert_action"></span>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading-pink">
						<h4 class="text-white">Product List</h4>
						<button type="button" class="btn btn-blue-grey" id="btnProductModal" data-toggle="modal" data-target="#productModal"><span class="glyphicon glyphicon-plus"></span> New Product</button>	
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-12 table-responsive">
								<table id="product_data" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Product ID</th>
											<th>Product Name</th>
											<th>Category</th>
											<th>Brand</th>
											<th>Quantity</th>
											<th>Enter By</th>
											<th>Status</th>
											<th>Details</th>
											<th>Edit</th>
											<th>Delete</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade" id="productModal">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title text-white"></h4>
	      </div>
	      <div class="modal-body">
	        <form method="post" id="productFormModal">
	        	<div class="form-group">
	        		<label for="productName">Product Name</label>
	        		<input type="text" class="form-control" name="productName" id="productName" placeholder="Enter Product Name..." required>
	        	</div>
	        	<div class="form-group">
	        		<label for="categoryID">Category</label>
	        		<select class="form-control" id="categoryID" name="categoryID" required>
	        			
	        			<?php echo selectCategoryList($conn); ?>
	        		</select>
	        	</div>
	        	<div class="form-group" id="brandList">

	        	</div>
	        	<div class="form-group">
	        		<label for="productDescription">Product Description</label>
	        		<small class="text-success"><strong>* Leave if it does not important</strong></small>
	        		<textarea class="form-control" rows="3" name="productDescription" id="productDescription" placeholder="Write Product Description..."></textarea>
	        	</div>
		        <div class="form-group">
		        	<label for="productQuantity">Product Quantity</label>
		        	<div class="input-group">
				      <input type="number" class="form-control" id="productQuantity" placeholder="Enter Product Quantity..." required>
				      <span class="input-group-addon">
				      	<select id="productUnit" name="productUnit" required>
		        			<option value="Unit">Unit</option>
		        			<option value="Pcs">Pcs</option>
		        			<option value="Box">Box</option>
		        		</select>
				      </span>
				    </div>
		        </div>
		        <div class="form-group">
	        		<label for="productBasePrice">Product Base Price</label>
	        		<div class="input-group">
	        			<span class="input-group-addon">Rp. </span>
	        			<input type="text" class="form-control" id="productBasePrice" placeholder="Enter Product Base Price..." required>
	        			<span class="input-group-addon">,00</span>
	        		</div>
	        	</div>
	        	<div class="form-group">
	        		<label for="productTax">Product Tax</label>
	        		<div class="input-group">
	        			<span class="input-group-addon">Rp. </span>
	        			 <input type="text" class="form-control" id="productTax" placeholder="Enter Product Tax..." required>
	        			 <span class="input-group-addon">,00</span>
	        		</div>
				   
	        	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancel</button>
	        <input type="submit" id="productBtnModal" class="btn btn-pink text-white" value="">
	      </div>
	      </form>
	    </div>
	  </div>
	</div>

	<!-- Modal View -->
	<div class="modal fade" id="viewProductModal" tabindex="-1" role="dialog" aria-labelledby="viewProductModalLabel">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header bg-lightblue">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title text-white">Product Details</h4>
	      </div>
	      <div class="modal-body">

	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
	      </div>
	    </div>
	  </div>
	</div>

	<div id="deleteProductModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-red">
					<button type="button" style="color: #fff;" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title text-white"><span class="glyphicon glyphicon-trash"></span> Delete Product</h4>
				</div>
				<div class="modal-body">
					
				</div>
				<div class="modal-footer">
			        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancel</button>
			        <input type="submit" class="btn btn-danger" id="deleteProductBtn" value="Delete Product">
			     </div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
			const productListData = $('#product_data').DataTable({
				"processing": true,
				"serverSide": true,
				"order" : [],
				"ajax" : {
					url : 'products_fetch.php',
					type : 'POST'
				},
				"columnDefs" : [
					{
						"target":[7,8,9]
					}
				],
				"pageLength" : 10,
				"columns":[
					{"name" : "productID", "orderable":true},
					{"name" : "productName", "orderable":true},
					{"name" : "categoryName", "orderable":true},
					{"name" : "brandName", "orderable":true},
					{"name" : "productQuantity", "orderable":true},
					{"name" : "userName", "orderable":true},
					{"name" : "productStatus", "orderable":true},
					{"name" : "Edit", "orderable":false},
					{"name" : "View", "orderable":false},
					{"name" : "Delete", "orderable":false}
				]
			});
				let hasSelectChange = false;
				let addProductToken = '';
				let updateProductToken = '';
				let deleteProductToken = '';
			$(document).on('click','#btnProductModal', function(){
				updateProductToken = '<?php echo hash('sha256', 'abcdef')  ?>';
				deleteProductToken = "<?php echo hash('sha256', '12345')  ?>";
				addProductToken = "<?php echo hash('sha256', 'add_product_token')  ?>";
				$('#productModal .modal-body div').remove('.checkbox');
				$('#productFormModal')[0].reset();
				$('#productModal .modal-header').removeClass('bg-orange');
				$('#productModal .modal-header').removeClass('bg-red');
				$('#productModal .modal-header').addClass('bg-pink');
				$('#productModal .modal-title').html('<span class="glyphicon glyphicon-plus"></span> Add New Product');
				$('#productBtnModal').removeClass('deleteProductSubmit');
				$('#productBtnModal').removeClass('btn-warning updateProductSubmit');
				$('#productBtnModal').addClass('btn-pink text-white addProductSubmit');
				$('#productBtnModal').val('Add New Product');
				$('#productBtnModal').removeAttr('update_product_id');
				$('#productBtnModal').removeAttr('delete_product_id');
				$('#productBtnModal').removeAttr('delete_product_name');
				$('#productUnit option').remove('#selectedValue');
				$('#productUnit option').remove('#disabledSelected');
				$('#productUnit').prepend(`<option value="" id="disabledSelected"  disabled selected>Select Unit</option>`);
				$('#categoryID option').remove('#categorySelectedValue');
				$('#categoryID option').remove('#categoryDisabledSelected');
				$('#categoryID').prepend(`<option  disabled selected id="categoryDisabledSelected">Select Category</option>`);
				$('#brandList').html('');
			});
			$(document).on('change', '#categoryID', function(){
				hasSelectChange = true;
				const categoryID = $('#categoryID').val();
				$.ajax({
					url : 'products_manage.php',
					method : 'POST',
					data : {'categoryID' : categoryID, 'hasSelectChange' : hasSelectChange},
					success:function(data){
						$('#brandList').html(data);
					}
				});
			});

			$(document).on('click', '.addProductSubmit', function(event){
				event.preventDefault();
				$(this).attr('disabled', 'disabled');	
				const addProductFormData = [
											{name : 'addProductToken', value : addProductToken},
											{name : 'productName', value : $('#productName').val()},
											{name : 'categoryID', value : $('#categoryID').val()},
											{name : 'brandID', value : $('#brandID').val()},
											{name : 'productDescription', value : $('#productDescription').val()},
											{name : 'productQuantity', value : $('#productQuantity').val()},
											{name : 'productUnit', value : $('#productUnit').val()},
											{name : 'productBasePrice', value : $('#productBasePrice').val()},
											{name : 'productTax', value : $('#productTax').val()}
										 ];
				$.ajax({
					url : 'products_manage.php',
					method : 'POST',
					data : addProductFormData,
					success:function(data){
						$('#productFormModal')[0].reset();
						$('#productModal').modal('hide');
						$('#alert_action').fadeIn().html(`<div class="alert alert-info">
							  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>`+ data +`</strong>
							</div>`);
						$('#productBtnModal').attr('disabled', false);
						productListData.ajax.reload();	
					}
				});
			});
			$(document).on('click', '.viewProduct', function(){
				const productViewID = $(this).attr('id');
				const productViewName = $(this).attr('productViewName');
				const productViewDescription = $(this).attr('productViewDescription');
				const productViewBrand = $(this).attr('productViewBrand');
				const productViewCategory = $(this).attr('productViewCategory');
				const productViewQuantity = $(this).attr('productViewQuantity');
				const productViewBasePrice = $(this).attr('productViewBasePrice');
				const productViewTax = $(this).attr('productViewTax');
				const productViewEnterBy = $(this).attr('productViewEnterBy');
				const productViewStatus = $(this).attr('productViewStatus');
				console.log(productViewBasePrice);
				let statusTag = '';
				let productDescriptionTag = ''
				if(productViewDescription==''){
					productDescriptionTag = '<td> - </td>'
				}else{
					productDescriptionTag = '<td>'+productViewDescription+'</td>'
				}
				if(productViewStatus=='active'){
					statusTag = '<td class="text-success" >Active</td>'
				}else
				{
					statusTag = '<td class="text-danger" >Inactive</td>'
				}
				$('#viewProductModal .modal-body').html(`
													<table class="table table-striped">
														<tr>
												      		<td>Product ID</td>
												      		<td>`+productViewID+`</td>
												      	</tr>
												      	 <tr>
												      		<td>Product Name</td>
												      		<td>`+productViewName+`</td>
												      	</tr>
												      	<tr>
												      		<td>Description</td>
												      		`+productDescriptionTag+`
												      	</tr>
												      	<tr>
												      		<td>Brand</td>
												      		<td>`+productViewBrand+`</td>
												      	</tr>
												      	<tr>
												      		<td>Category</td>
												      		<td>`+productViewCategory+`</td>
												      	</tr>
												      	<tr>
												      		<td>Product Quantity</td>
												      		<td>`+productViewQuantity+`</td>
												      	</tr>
												      	<tr>
												      		<td>Product Base Price</td>
												      		<td>Rp. `+
												      			formatRupiah(productViewBasePrice)
												      		+`,00</td>
												      	</tr>
												      	<tr>
												      		<td>Product Tax</td>
												      		<td>Rp. `+formatRupiah(productViewTax)+`,00</td>
												      	</tr>
												      	<tr>
												      		<td>Enter By</td>
												      		 <td>`+productViewEnterBy+`</td>
												      	</tr>
												      	<tr>
												      		<td>Product Status</td>
												      		`+statusTag+`
												      	</tr>
												      </table>`);
			});
			$(document).on('click', '.editProduct', function(){
				addProductToken = "<?php echo hash('sha256', 'asdfgh')  ?>";
				deleteProductToken = "<?php echo hash('sha256', '12345')  ?>";
				updateProductToken = '<?php echo hash('sha256', 'update_product_token')  ?>';
				$('#productFormModal')[0].reset();
				$('#productModal .modal-header').removeClass('bg-pink');
				$('#productModal .modal-header').removeClass('bg-red');
				$('#productModal .modal-header').addClass('bg-orange');
				$('#productModal .modal-title').html('<span class="glyphicon glyphicon-plus"></span>Edit Product');
				$('#productBtnModal').removeClass('deleteProductSubmit');
				$('#productBtnModal').addClass('btn-warning updateProductSubmit');
				$('#productBtnModal').removeClass('btn-pink text-white addProductSubmit');
				$('#productBtnModal').val('Edit Product');
				$('#productBtnModal').removeAttr('delete_product_id');
				$('#productBtnModal').removeAttr('delete_product_name');
				const productEditID = $(this).attr('id');
				const productEditName = $(this).attr('productEditName');
				const productEditDescription = $(this).attr('productEditDescription');
				const productEditBrand = $(this).attr('productEditBrand');
				const productEditCategory = $(this).attr('productEditCategory');
				const productEditQuantity = $(this).attr('productEditQuantity');
				const productEditBasePrice = $(this).attr('productEditBasePrice');
				const productEditTax = $(this).attr('productEditTax');
				const productEditEnterBy = $(this).attr('productEditEnterBy');
				const productEditStatus = $(this).attr('productEditStatus');
				const productEditBrandID = $(this).attr('productEditBrandID');
				const productEditCategoryID = $(this).attr('productEditCategoryID');
				const productEditUnit = $(this).attr('productEditUnit');
				$('#productBtnModal').attr('update_product_id', productEditID);
				$('#productModal').modal('show');
				$('#productName').val(productEditName);
				$('#categoryID option').remove('#categorySelectedValue');
				$('#categoryID option').remove('#categoryDisabledSelected');
				$('#categoryID').prepend(`<option value="`+productEditCategoryID+`" id="categorySelectedValue" selected>`+productEditCategory+`</option>`);
				$('#brandList').html(`<label for="brandID">Brand</label>
										<select class="form-control" id="brandID" name="brandID" required>
											<option class="brandList" value="`+productEditBrandID+`" selected>`+productEditBrand+`</option>
										</select>
										`);
				$('#productDescription').val(productEditDescription);
				$('#productQuantity').val(productEditQuantity);
				$('#productUnit option').remove('#selectedValue');
				$('#productUnit option').remove('#disabledSelected');
				$('#productUnit').prepend(`<option value="`+productEditUnit+`" id="selectedValue" selected>`+productEditUnit+`</option>`);
				$('#productBasePrice').val(formatRupiah(productEditBasePrice));
				$('#productTax').val(formatRupiah(productEditTax));
				let checkboxElems = '';
				if(productEditStatus== 'active'){
					 checkboxElems = `<label id="labelStatusProduct">
										<input type="checkbox" id="checkboxStatusProduct" checked> Active
									  </label>`;
				}
				else{
					 checkboxElems = `<label id="labelStatusProduct">
										<input type="checkbox" id="checkboxStatusProduct" > Inactive 
									  </label>`;
				}
				$('#productModal .modal-body div').remove('.checkbox');
				$('#productModal .modal-body').append(`<div class="checkbox">
														`+ checkboxElems +`
														</div>`);
			});
			$(document).on('click', '#checkboxStatusProduct', function(){
				let str = $("#labelStatusProduct").text();
				let str2 = str.trim();
				if(str2=='Active'){
					$("#labelStatusProduct").html(`<input type="checkbox" id="checkboxStatusProduct" > Inactive`);
				}
				else{
					$("#labelStatusProduct").html(`<input type="checkbox" id="checkboxStatusProduct" checked > Active`);
				}
			});
			$(document).on('click','.updateProductSubmit', function(event){
				event.preventDefault();
				$(this).attr('disabled', 'disabled');
				let str = $("#labelStatusProduct").text();
				let str2 = str.trim();
				let updateProductStatus = str2.toLowerCase();
				const editProductFormData = [
												{name : 'updateProductToken', value : updateProductToken},
												{name : 'productID', value : $(this).attr('update_product_id')},
												{name : 'productName', value : $('#productName').val()},
												{name : 'categoryID', value : $('#categoryID').val()},
												{name : 'brandID', value : $('#brandID').val()},
												{name : 'productDescription', value : $('#productDescription').val()},
												{name : 'productQuantity', value : $('#productQuantity').val()},
												{name : 'productUnit', value : $('#productUnit').val()},
												{name : 'productBasePrice', value : $('#productBasePrice').val()},
												{name : 'productTax', value : $('#productTax').val()},
												{name : 'productBasePrice', value : $('#productBasePrice').val()},
												{name : 'productStatus', value : updateProductStatus}
											];
				$.ajax({
					url : 'products_manage.php',
					method : 'POST',
					data : editProductFormData,
					success:function(data){
						$('#productFormModal')[0].reset();
						$('#productModal').modal('hide');
						$('#alert_action').fadeIn().html(`<div class="alert alert-warning">
							  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>`+ data +`</strong>
							</div>`);
						$('#productBtnModal').attr('disabled', false);
						productListData.ajax.reload();
					}
				});
			});
			$(document).on('click', '.deleteProduct', function(){
				addProductToken = "<?php echo hash('sha256', 'asdfgh')  ?>";
				deleteProductToken = "<?php echo hash('sha256', 'delete_product_token')  ?>";
				updateProductToken = '<?php echo hash('sha256', 'abcdef')  ?>';
				$('#productBtnModal').removeClass('btn-warning updateProductSubmit');
				$('#productBtnModal').removeClass('btn-pink text-white addProductSubmit');
				$('#deleteProductBtn').removeAttr('update_product_id');
				$('#deleteProductBtn').attr('delete_product_id', $(this).attr('id'));
				$('#deleteProductBtn').attr('delete_product_name', $(this).attr('productName'));
				$('#deleteProductBtn').addClass('deleteProductSubmit');
				$('#deleteProductModal .modal-body').html(`<h5 class="text-black"> Are You Sure Want to Permanently Delete  <strong>"`+ $(this).attr('productName') +`"</strong> Product ?</h5>`)
			});
			$(document).on('click','.deleteProductSubmit', function(event){
				event.preventDefault();
				$('#deleteProductBtn').attr('disabled', 'disabled');
				deleteProductSubmit = true;
				const deleteProductFormData = [
												{name : 'deleteProductToken', value : deleteProductToken},
												{name : 'productID', value : $(this).attr('delete_product_id')},
												{name : 'productName', value : $(this).attr('delete_product_name')}
											  ];
				$.ajax({
					url : 'products_manage.php',
					method : 'POST',
					data : deleteProductFormData,
					success:function(data){
						$('#deleteProductModal').modal('hide');
						$('#alert_action').fadeIn().html(`<div class="alert alert-danger">
							  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>`+ data +`</strong>
							</div>`);
						$('#deleteProductBtn').attr('disabled', false);
						productListData.ajax.reload();
					}
				})
			});
		});
		function formatRupiah(x){
    		let parts = x.split(".");
		    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		    return parts.join(".");	
		}
	</script>
<?php require_once '../templates/footer.php'; ?>