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
			  <li class="active">Brand</li>
			</ol>
		</div>
	</section>
	<div class="container">
		<span id="alert_action"></span>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading-brown">
						<h4 class="text-white">Brand List</h4>
						<button type="button" name="btnBrandModal" id="btnBrandModal" data-toggle="modal" data-target="#addBrandModal" class="btn btn-purple"><span class="glyphicon glyphicon-plus"></span> New Brand</button>	
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-12 table-responsive">
								<table id="brand_data" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Brand ID</th>
											<th>Brand Name</th>
											<th>Category</th>
											<th>Status</th>
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

	<div id="addBrandModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method="post" id="addBrandModalForm">
					<div class="modal-header">
						<button type="button" style="color: #fff;" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title text-white"></h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="BrandName" id="labelBrandName">Brand Name</label>
							<input type="text" name="brandName" id="brandNameInput" class="form-control" placeholder="Enter Brand Name..." required >
						</div>
						<div class="form-group">
							<label>Category</label>
							<select name="categoryID" id="categoryID" class="form-control" required>
								
								<?php echo selectCategoryList($conn); ?>
							</select>
						</div>
						<div class="checkbox">
							
						</div>
					</div>
					<div class="modal-footer">
				        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancel</button>
				        <input type="submit" class="btn " id="addBrandBtn">
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
					<h4 class="modal-title text-white"><span class="glyphicon glyphicon-trash"></span> Delete Brand</h4>
				</div>
				<div class="modal-body">
					
				</div>
				<div class="modal-footer">
			        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancel</button>
			        <input type="submit" class="btn btn-danger" id="deleteBrandBtn" value="Delete Brand">
			     </div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			const brandListData = $('#brand_data').DataTable({
				"processing": true,
				"serverSide": true,
				"order" : [],
				"ajax" : {
					url : 'brand_fetch.php',
					type : 'POST'
				},
				"columnDefs" : [
					{
						"target":[4,5]
					}
				],
				"pageLength" : 10,
				"columns":[
					{"name" : "brandID", "orderable":true},
					{"name" : "brandName", "orderable":true},
					{"name" : "categoryName", "orderable":true},
					{"name" : "brandStatus", "orderable":true},
					{"name" : "Edit", "orderable":false},
					{"name" : "Delete", "orderable":false}
				]
			});
			let addBrandToken = '';
			let updateBrandToken = '';
			let deleteBrandToken = '';
			$(document).on('click','#btnBrandModal', function(){
				updateBrandToken = '<?php echo hash('sha256', 'abcdef')  ?>';
				deleteBrandToken = "<?php echo hash('sha256', '12345')  ?>";
				addBrandToken = "<?php echo hash('sha256', 'add_brand_token')  ?>";
				$('#addBrandModalForm')[0].reset();
				$('#addBrandModal .modal-header').removeClass('bg-orange');
				$('#addBrandModal .modal-header').removeClass('bg-red');
				$('#addBrandModal .modal-header').addClass('bg-purple');
				$('#addBrandModal .modal-title').html('<span class="glyphicon glyphicon-plus"></span> Add New Brand');
				$('#addBrandBtn').removeClass('deleteBrandSubmit');
				$('#addBrandBtn').removeClass('btn-warning updateBrandSubmit');
				$('#addBrandBtn').addClass('btn-purple text-light addBrandSubmit');
				$('#addBrandBtn').val('Add New Brand');
				$('#addBrandBtn').removeAttr('update_brand_id');
				$('#addBrandBtn').removeAttr('delete_brand_id');
				$('#addBrandBtn').removeAttr('delete_brand_name');
				$('option').remove('#selectedValue');
				$('option').remove('#disabledSelected');
				$('#categoryID').prepend(`<option value="" id="disabledSelected"  disabled selected>Select Category</option>`);
				$('#addBrandModal .modal-body .checkbox').html('');
			});
			$(document).on('click', '.addBrandSubmit', function(event){
				event.preventDefault();
				$('#addBrandBtn').attr('disabled', 'disabled');
				let addBrandFormData = [
											{name:'addBrandToken', value : addBrandToken},
											{name:'brandName', value : $('#brandNameInput').val()},
											{name:'categoryID', value:$('#categoryID').val()}
											];
				$.ajax({
					url : 'brand_manage.php',
					method : 'POST',
					data : addBrandFormData,
					success:function(data){
						$('#addBrandModalForm')[0].reset();
						$('#addBrandModal').modal('hide');
						$('#alert_action').fadeIn().html(`<div class="alert alert-info">
							  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>`+ data +`</strong>
							</div>`);
						$('#addBrandBtn').attr('disabled', false);
						brandListData.ajax.reload();
					}
				});
			});
			$(document).on('click','.editBrand', function(){
				addBrandToken = "<?php echo hash('sha256', 'asdfgh')  ?>";
				deleteBrandToken = "<?php echo hash('sha256', '12345')  ?>";
				updateBrandToken = '<?php echo hash('sha256', 'update_brand_token')  ?>';
				$('#addBrandBtn').removeAttr('delete_brand_id');
				$('#addBrandBtn').removeAttr('delete_brand_name');
				$('#addBrandModalForm')[0].reset();
				$('#addBrandModal .modal-header').removeClass('bg-purple');
				$('#addBrandModal .modal-header').removeClass('bg-red');
				$('#addBrandModal .modal-header').addClass('bg-orange');
				$('#addBrandModal .modal-title').html('<span class="glyphicon glyphicon-edit"></span> Edit Brand');
				$('#addBrandModal .modal-body').html();
				$('#addBrandBtn').removeClass('deleteBrandSubmit');
				$('#addBrandBtn').removeClass('btn-purple addBrandSubmit');
				$('#addBrandBtn').addClass('btn-warning text-light updateBrandSubmit');
				$('#addBrandBtn').val('Edit Brand');
				const brandID = $(this).attr('id');
				const brandName = $(this).attr('brand_name');
				const brandStatus = $(this).attr('brand_status');
				const categoryName = $(this).attr('category_name');
				const categoryID= $(this).attr('category_id');
				$('#addBrandBtn').attr('update_brand_id', brandID);
				$('#brandNameInput').val(brandName);
				$('option').remove('#selectedValue');
				$('option').remove('#disabledSelected');
				$('#categoryID').prepend(`<option value="`+categoryID+`" id="selectedValue" selected>`+categoryName+`</option>`);
				let checkboxElems = '';
				if(brandStatus == 'active'){
					 checkboxElems = `<label id="labelStatusBrand">
										<input type="checkbox" id="checkboxStatusBrand" checked> Active
									  </label>`;
				}
				else{
					 checkboxElems = `<label id="labelStatusBrand">
										<input type="checkbox" id="checkboxStatusBrand" > Inactive 
									  </label>`;
				}
				$('#addBrandModal .modal-body .checkbox').html('');
				$('#addBrandModal .modal-body .checkbox').html(checkboxElems);
			});
			$(document).on('click', '#checkboxStatusBrand', function(){
				let str = $("#labelStatusBrand").text();
				let str2 = str.trim();
				if(str2=='Active'){
					$("#labelStatusBrand").html(`<input type="checkbox" id="checkboxStatusBrand" > Inactive`);
				}
				else{
					$("#labelStatusBrand").html(`<input type="checkbox" id="checkboxStatusBrand" checked > Active`);
				}
			});
			$(document).on('click', '.updateBrandSubmit', function(event){
				event.preventDefault();
				$('#addBrandBtn').attr('disabled', 'disabled');
				let str = $("#labelStatusBrand").text();
				let str2 = str.trim();
				let updateBrandStatus = str2.toLowerCase();
				let updateBrandID = $(this).attr('update_brand_id');

				$('#addBrandBtn').attr('disabled', 'disabled');
				let updateBrandFormData = [
											{name:'updateBrandToken', value : updateBrandToken},
											{name:'brandName', value : $('#brandNameInput').val()},
											{name:'categoryID', value: $('#categoryID').val()},
											{name:'brandStatus', value: updateBrandStatus},
											{name:'brandID', value:updateBrandID}
											];
				$.ajax({
					url : 'brand_manage.php',
					method : 'POST',
					data : updateBrandFormData,
					success:function(data){
						$('#addBrandModalForm')[0].reset();
						$('#addBrandModal').modal('hide');
						$('#alert_action').fadeIn().html(`<div class="alert alert-warning">
							  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>`+ data +`</strong>
							</div>`);
						$('#addBrandBtn').attr('disabled', false);
						brandListData.ajax.reload();
					}
				});
			});
			$(document).on('click', '.deleteBrand', function(){
				addBrandToken = "<?php echo hash('sha256', 'asdfgh')  ?>";
				deleteBrandToken = "<?php echo hash('sha256', 'delete_brand_token')  ?>";
				updateBrandToken = '<?php echo hash('sha256', 'abcdef')  ?>';
				$('#deleteBrandBtn').removeAttr('update_brand_id');
				$('#deleteBrandBtn').addClass('deleteBrandSubmit');
				$('#deleteBrandBtn').attr('delete_brand_id', $(this).attr('id'));
				$('#deleteBrandBtn').attr('delete_brand_name', $(this).attr('brand_name'));
				$('#deleteBrandModal .modal-body').html(`<h5 class="text-black"> Are You Sure Want to Permanently Delete  <strong>"`+ $(this).attr('brand_name') +`"</strong> Brand ?</h5>`);
			});
			$(document).on('click', '.deleteBrandSubmit', function(event){
				event.preventDefault();
				let brandID = $(this).attr('delete_brand_id');
				let brandName = $(this).attr('delete_brand_name');
				let deleteBrandFormData = [
											{name : 'deleteBrandToken', value : deleteBrandToken},
											{name : 'brandID', value : brandID},
											{name : 'brandName', value : brandName }
										  ];
				$.ajax({
					url : 'brand_manage.php',
					method : 'POST',
					data : deleteBrandFormData,
					success:function(data){
						$('#deleteBrandModal').modal('hide');
						$('#alert_action').fadeIn().html(`<div class="alert alert-danger">
							  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>`+ data +`</strong>
							</div>`);
						$('#deleteBrandBtn').attr('disabled', false);
						brandListData.ajax.reload();
					}
				});
			});
		});
	</script>

<?php require_once '../templates/footer.php'; ?>