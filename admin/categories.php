<?php
	
	require_once '../database/database_connection.php';
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
			  <li class="active">Category</li>
			</ol>
		</div>
	</section>
	<div class="container">
		<span id="alert_action"></span>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading-purple">
						<h4 class="text-white">Category List</h4>
						<button type="button" name="btnCategoryModal" id="btnCategoryModal" data-toggle="modal" data-target="#addCategoryModal" class="btn btn-info"><span class="glyphicon glyphicon-plus"></span> New Category</button>	
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-12 table-responsive">
								<table id="categories_data" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>ID</th>
											<th>Category Name</th>
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

	<div id="addCategoryModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method="post" id="addCategoryModalForm">
					<div class="modal-header">
						<button type="button" style="color: #fff;" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title text-white"></h4>
					</div>
					<div class="modal-body" >
						
					</div>
					<div class="modal-footer">
				        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancel</button>
				        <input type="submit" class="btn " id="addCategoryBtn">
				     </div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
	const categoriesListData = $('#categories_data').DataTable({
		"processing": true,
		"serverSide": true,
		"order" : [],
		"ajax" : {
			url : 'categories_fetch.php',
			type : 'POST'
		},
		"columnDefs" : [
			{
				"target":[3,4]
			}
		],
		"pageLength" : 10,
		"columns":[
			{"name" : "categoryID", "orderable":true},
			{"name" : "categoryName", "orderable":true},
			{"name" : "categoryStatus", "orderable":true},
			{"name" : "Edit", "orderable":false},
			{"name" : "Delete", "orderable":false}
		]
	});
	let addCategoryToken = '';
	let updateCategoryToken = '';
	let deleteCategoryToken = '';
	$(document).on('click','#btnCategoryModal', function(){
		updateCategoryToken = '<?php echo hash('sha256', 'abcdef')  ?>';
		deleteCategoryToken = "<?php echo hash('sha256', '12345')  ?>";
		addCategoryToken = "<?php echo hash('sha256', 'add_category_token')  ?>";
		$('#addCategoryModalForm')[0].reset();
		$('#addCategoryModal .modal-header').removeClass('bg-orange');
		$('#addCategoryModal .modal-header').removeClass('bg-red');
		$('#addCategoryModal .modal-header').addClass('bg-lightblue');
		$('#addCategoryModal .modal-title').html('<span class="glyphicon glyphicon-plus"></span> Add New Category');
		$('#addCategoryModal .modal-body').html(`<div class="form-group">
													<label for="categoryName" id="labelCategoryName">Category Name</label>
													<input type="text" name="categoryName" id="categoryNameInput" class="form-control" placeholder="Enter Category Name..." required >
												</div>
												<div class="checkbox">
													
												</div>`);
		$('#addCategoryBtn').removeClass('btn-danger deleteCategorySubmit');
		$('#addCategoryBtn').removeClass('btn-warning updateCategorySubmit');
		$('#addCategoryBtn').addClass('btn-info addCategorySubmit');
		$('#addCategoryBtn').val('Add New Category');
		$('#addBrandBtn').removeAttr('update_category_id');
		$('#addBrandBtn').removeAttr('delete_category_name');
		$('#addBrandBtn').removeAttr('delete_category_id');
		$('#addCategoryModal .modal-body .checkbox').html('');
	});
	$(document).on('click', '.addCategorySubmit', function(event){
		event.preventDefault();
		$('#addCategoryBtn').attr('disabled', 'disabled');
		let addCateogryFormData = [
									{name:'addCategoryToken', value : addCategoryToken },
									{name:'add_category_name', value : $('#categoryNameInput').val()}
									];

		$.ajax({
			url : 'categories_manage.php',
			method : 'POST',
			data : addCateogryFormData,
			success:function(data){
				$('#addCategoryModalForm')[0].reset();
				$('#addCategoryModal').modal('hide');
				$('#alert_action').fadeIn().html(`<div class="alert alert-info">
					  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					  <strong>`+ data +`</strong>
					</div>`);
				$('#addCategoryBtn').attr('disabled', false);
				categoriesListData.ajax.reload();
			}
		});
	});
	$(document).on('click', '.editCategory', function(){
		addCategoryToken = "<?php echo hash('sha256', 'asdfgh')  ?>";
		deleteCategoryToken = "<?php echo hash('sha256', '12345')  ?>";
		updateCategoryToken = '<?php echo hash('sha256', 'update_category_token')  ?>';
		$('#addBrandBtn').removeAttr('delete_category_id');
		$('#addBrandBtn').removeAttr('delete_category_name');
		$('#addCategoryModalForm')[0].reset();
		$('#addCategoryModal .modal-header').removeClass('bg-lightblue');
		$('#addCategoryModal .modal-header').removeClass('bg-red');
		$('#addCategoryModal .modal-header').addClass('bg-orange');
		$('#addCategoryModal .modal-title').html('<span class="glyphicon glyphicon-edit"></span> Edit Category');
		$('#addCategoryModal .modal-body').html(`<div class="form-group">
													<label for="categoryName" id="labelCategoryName">Category Name</label>
													<input type="text" name="categoryName" id="categoryNameInput" class="form-control" placeholder="Enter Category Name..." required >
												</div>
												<div class="checkbox">
													
												</div>`);
		$('#addCategoryBtn').removeClass('btn-danger deleteCategorySubmit');
		$('#addCategoryBtn').removeClass('btn-info addCategorySubmit');
		$('#addCategoryBtn').addClass('btn-warning updateCategorySubmit');
		$('#addCategoryBtn').val('Edit Category');
		let updateCategoryID = $(this).attr('id');
		let updateCategoryStatus = $(this).attr('category_status');
		let updateCategoryName = $(this).attr('category_name');
		$('#categoryNameInput').val(updateCategoryName);
		$('#addCategoryBtn').attr('update_category_id', updateCategoryID);
		$('#addBrandBtn').removeAttr('delete_category_name');
		$('#addBrandBtn').removeAttr('delete_category_id');
		let checkboxElems = '';
		if(updateCategoryStatus == 'active'){
			 checkboxElems = `<label id="labelStatusCategory">
								<input type="checkbox" id="checkboxStatusCategory" checked> Active
							  </label>`;
		}
		else{
			 checkboxElems = `<label id="labelStatusCategory">
								<input type="checkbox" id="checkboxStatusCategory" > Inactive 
							  </label>`;
		}
		$('#addCategoryModal .modal-body .checkbox').html('');
		$('#addCategoryModal .modal-body .checkbox').html(checkboxElems);
	});
	$(document).on('click', '#checkboxStatusCategory', function(){
		let str = $("#labelStatusCategory").text();
		let str2 = str.trim();
		if(str2=='Active'){
			$("#labelStatusCategory").html(`<input type="checkbox" id="checkboxStatusCategory" > Inactive`);
		}
		else{
			$("#labelStatusCategory").html(`<input type="checkbox" id="checkboxStatusCategory" checked > Active`);
		}
	});
	$(document).on('click', '.updateCategorySubmit', function(event){
		event.preventDefault();
		let str = $("#labelStatusCategory").text();
		let str2 = str.trim();
		let updateCategoryStatus = str2.toLowerCase();
		let updateCategoryID = $(this).attr("update_category_id");
		$('#addCategoryBtn').attr('disabled', 'disabled');
		let updateCateogryFormData = [
										{name:'updateCategoryToken', value : updateCategoryToken},
										{name:'categoryName', value : $('#categoryNameInput').val()},
										{name:'categoryStatus', value: updateCategoryStatus},
										{name:'categoryID', value:updateCategoryID}
									];
		$.ajax({
			url : 'categories_manage.php',
			method : 'POST',
			data : updateCateogryFormData,
			success:function(data){
				$('#addCategoryModalForm')[0].reset();
				$('#addCategoryModal').modal('hide');
				$('#alert_action').fadeIn().html(`<div class="alert alert-warning">
					  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					  <strong>`+ data +`</strong>
					</div>`);
				$('#addCategoryBtn').attr('disabled', false);
				categoriesListData.ajax.reload();
			}
		});
	});
	$(document).on('click', '.deleteCategory', function(){
		addCategoryToken = "<?php echo hash('sha256', 'asdfgh')  ?>";
		deleteCategoryToken = "<?php echo hash('sha256', 'delete_category_token')  ?>";
		updateCategoryToken = '<?php echo hash('sha256', 'abcdef')  ?>';
		$('#addBrandBtn').removeAttr('update_category_id');
		$('#addCategoryModalForm')[0].reset();
		$('#addCategoryModal .modal-header').removeClass('bg-lightblue');
		$('#addCategoryModal .modal-header').removeClass('bg-orange');
		$('#addCategoryModal .modal-header').addClass('bg-red');
		$('#addCategoryModal .modal-title').html('<span class="glyphicon glyphicon-trash"></span> Delete Category');
		$('#addCategoryBtn').removeClass('btn-warning updateCategorySubmit');
		$('#addCategoryBtn').removeClass('btn-info addCategorySubmit');
		$('#addCategoryBtn').addClass('btn-danger deleteCategorySubmit');
		$('#addCategoryBtn').val('Delete Category');
		$('#addCategoryBtn').attr('delete_category_id', $(this).attr('id'));
		$('#addCategoryBtn').attr('delete_category_name', $(this).attr('category_name'));
		$('#addCategoryModal .modal-body').html(`<h5 class="text-black"> Are You Sure Want to Permanently Delete  <strong>"`+ $(this).attr('category_name') +`"</strong> Catgory ?</h5>`);
		$('#addBrandBtn').removeAttr('update_category_id');
	});
	$(document).on('click', '.deleteCategorySubmit', function(event){
		event.preventDefault();
		let deleteCategoryID = $(this).attr("delete_category_id");
		$('#addCategoryBtn').attr('disabled', 'disabled');
		let deleteCateogryFormData = [
									{name:'deleteCategoryToken', value : deleteCategoryToken},
									{name:'delete_category_name', value : $(this).attr('delete_category_name')},
									{name:'delete_category_id', value:deleteCategoryID}
									];
		$.ajax({
			url : 'categories_manage.php',
			method : 'POST',
			data : deleteCateogryFormData,
			success:function(data){
				$('#addCategoryModalForm')[0].reset();
				$('#addCategoryModal').modal('hide');
				$('#alert_action').fadeIn().html(`<div class="alert alert-danger">
					  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					  <strong>`+ data +`</strong>
					</div>`);
				$('#addCategoryBtn').attr('disabled', false);
				categoriesListData.ajax.reload();
			}
		});
	});
});
	</script>
<?php require_once '../templates/footer.php'; ?>