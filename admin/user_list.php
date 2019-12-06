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
			  <li class="active">User</li>
			</ol>
		</div>
	</section>
	<div class="container">
		<span id="alert_action"></span>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading-teal">
						<h4 class="text-white">User List</h4>
						<button type="button"  id="addUser" data-toggle="modal" data-target="#userModal" class="btn btn-secondary"><span class="glyphicon glyphicon-plus"></span> New User</button>	
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-12 table-responsive">
								<table id="user_data" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>User ID</th>
											<th>Email</th>
											<th>Name</th>
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

	<div id="userModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method="post" id="userFormModal">
					<div class="modal-header">
						<button type="button" style="color: #fff;" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title text-white"></h4>
					</div>
					<div class="modal-body" >
						
					</div>
					<div class="modal-footer">
				        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancel</button>
				        <input type="submit"  class="btn" id="addNewUserBtn">
				     </div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header bg-red">
	        <button type="button" class="close" style="color: #fff!important;" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title text-white"><span class="glyphicon glyphicon-trash"></span> Delete User</h4>
	      </div>
	      <div class="modal-body">
	        <div class="form-group">
	        	<h5 class="text-default" id="infoUser"></h5>
	        </div>
	      </div>
	      <div class="modal-footer">
	        <form method="post">
	        	<button type="button" class="btn btn-dark" data-dismiss="modal">Cancel</button>
	        	<button type="submit" id="deleteUserBtn" class="btn btn-danger">Delete User</button>
	        </form>
	      </div>
	    </div>
	  </div>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
			const userListData = $('#user_data').DataTable({
				"processing": true,
				"serverSide": true,
				"order": [],
				"ajax" : {
					url : 'user_list_fetch.php',
					type : 'POST'
				},
				"columnDefs" : [
					{
						"target":[4,5]
					}
				],
				"pageLength" : 10,
				"columns" :[
								{"name" : "userID", "orderable":true},
								{"name" : "userEmail", "orderable":true},
								{"name" : "userName", "orderable":true},
								{"name" : "userStatus", "orderable":true},
								{"name" : "Edit", "orderable":false},
								{"name" : "Delete", "orderable":false}
					       ] 
			});
			let addUserToken = '';
			let updateUserToken = '';
			let deleteUserToken = '';
			$(document).on('click', '#addUser', function(){
				addUserToken = "<?php echo hash('sha256', 'add_user_token')  ?>";
				updateUserToken = "<?php echo hash('sha256', 'asdfgh')  ?>";
				deleteUserToken = "<?php echo hash('sha256', '12345')  ?>";
				$('#userFormModal')[0].reset();
				$('#userModal .modal-header').removeClass('bg-orange');
				$('#userModal .modal-header').addClass('bg-lightblue');
				$('#userModal .modal-title').html(`<span class="glyphicon glyphicon-plus"></span> Add New User`);
				$('#userModal .modal-body').html(`<div class="form-group">
													<label for="userName">User Name</label>
													<input type="text" name="userName" id="userName" class="form-control" placeholder="User Name..." required autocomplete="off">
												</div>
												<div class="form-group">
													<label for="userEmail">User Email</label>
													<input type="email" name="userEmail" id="userEmail" class="form-control" placeholder="Email..." required>
												</div>
												<div class="form-group">
													<label for="userPassword">User Password</label>
													<input type="text" name="userPassword" id="userPassword" class="form-control" placeholder="Password..." required autocomplete="off">
												</div>`);
				$('#userModal .modal-footer #addNewUserBtn').val('Add New User');
				$('#userModal .modal-footer #addNewUserBtn').removeClass('btn-warning updateUserSubmit ');
				$('#userModal .modal-footer #addNewUserBtn').addClass('btn-info addNewUserSubmit');
				$('#userModal .modal-footer #addNewUserBtn').removeAttr('update_user_id');
				$('#deleteModal .modal-footer #deleteUserBtn').removeClass('deleteUserSubmit')
				$('#deleteModal .modal-footer #deleteUserBtn').removeAttr('delete_user_id');
				$('#deleteModal .modal-footer #deleteUserBtn').removeAttr('delete_user_name');
			});
			$(document).on('click', '.addNewUserSubmit', function(event){
				event.preventDefault();
				if($('#userName').val()!='' && $('#userPassword').val() != '' && $('#userEmail').val() != ''){
					const addNewUserData = [
										{name : 'addUserToken', value : addUserToken},
										{name : 'addUserName', value : $('#userName').val()},
										{name : 'addUserEmail', value : $('#userEmail').val()},
										{name : 'addUserPassword', value : $('#userPassword').val()}
									];
					$.ajax({
						url : 'user_manage.php',
						method : 'POST',
						data : addNewUserData,
						success:function(data){
							$('#userModal').modal('hide');
							if(data=='Please enter a valid email!'){
								$('#alert_action').html(`<div class="alert alert-danger">
								  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								  <strong>`+data+`</strong>
								</div>`);
							} else{
								$('#alert_action').html(`<div class="alert alert-info">
							  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>`+data+`</strong>
							</div>`);
							}
							userListData.ajax.reload();
						}
					});
				}
				else
				{
					$('#userModal').modal('hide');
					$('#alert_action').html(`<div class="alert alert-danger">
							  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>Please fill all fields!</strong>
							</div>`);
				}
			});
			$(document).on('click', '.editUser', function(){
				addUserToken = "<?php echo hash('sha256', 'asdfgh')  ?>";
				updateUserToken = "<?php echo hash('sha256', 'update_user_token')  ?>";
				deleteUserToken = "<?php echo hash('sha256', '12345')  ?>";
				$('#userFormModal')[0].reset();
				$('#userModal').modal('show');
				$('#userModal .modal-header').removeClass('bg-lightblue');
				$('#userModal .modal-header').addClass('bg-orange');
				$('#userModal .modal-title').html(`<span class="glyphicon glyphicon-edit"></span> Edit User`);
				$('#userModal .modal-body').html();
				if($(this).attr('editUserStatus')=='active')
				{
					$('#userModal .modal-body').html(`<div class="form-group">
													<label for="userName">User Name</label>
													<input type="text" name="userName" id="userName" class="form-control" placeholder="User Name..." value="`+$(this).attr('editUserName')+`" autocomplete="off">
												</div>
												<div class="form-group">
													<label for="userEmail">User Email</label>
													<input type="email" name="userEmail" id="userEmail" class="form-control" placeholder="Email..." value="`+$(this).attr('editUserEmail')+`">
												</div>
												<div class="form-group">
													<label for="userPassword">User Password</label>
													<small class="text-danger">Leave Blank if You Don't Change Password</small>
													<input type="text" name="userPassword" id="userPassword" class="form-control" placeholder="Password..." autocomplete="off">
												</div>
												<div class="checkbox">
													<label id="labelStatusUser">
														<input type="checkbox" id="checkboxStatusUser" checked> Active
													  </label>
												</div>`);
				}
				else
				{
					$('#userModal .modal-body').html(`<div class="form-group">
													<label for="userName">User Name</label>
													<input type="text" name="userName" id="userName" class="form-control" placeholder="User Name..." value="`+$(this).attr('editUserName')+`" autocomplete="off">
												</div>
												<div class="form-group">
													<label for="userEmail">User Email</label>
													<input type="email" name="userEmail" id="userEmail" class="form-control" placeholder="Email..." value="`+$(this).attr('editUserEmail')+`">
												</div>
												<div class="form-group">
													<label for="userPassword">User Password</label>
													<small class="text-danger">Leave Blank if You Don't Change Password</small>
													<input type="text" name="userPassword" id="userPassword" class="form-control" placeholder="Password..." autocomplete="off">
												</div>
												<div class="checkbox checkboxUser">
													<label id="labelStatusUser">
														<input type="checkbox" id="checkboxStatusUser"> Inactive
													  </label>
												</div>`)
				}
				$('#userModal .modal-footer #addNewUserBtn').val('Edit User');
				$('#userModal .modal-footer #addNewUserBtn').removeClass('btn-info addNewUserSubmit ');
				$('#userModal .modal-footer #addNewUserBtn').addClass('btn-warning editUserSubmit');
				$('#userModal .modal-footer #addNewUserBtn').attr('update_user_id', $(this).attr('id'));
				$('#deleteModal .modal-footer #deleteUserBtn').removeClass('deleteUserSubmit')
				$('#deleteModal .modal-footer #deleteUserBtn').removeAttr('delete_user_id');
				$('#deleteModal .modal-footer #deleteUserBtn').removeAttr('delete_user_name');
			});
			$(document).on('click','#checkboxStatusUser', function(){
					let str = $("#labelStatusUser").text();
					let str2 = str.trim();
					if(str2=='Active'){
						$("#labelStatusUser").html(`<input type="checkbox" id="checkboxStatusUser" > Inactive`);
					}
					else{
						$("#labelStatusUser").html(`<input type="checkbox" id="checkboxStatusUser" checked > Active`);
					}
				});
			$(document).on('click', '.editUserSubmit', function(event){
				event.preventDefault();
				let editUserStatusT = $('#labelStatusUser').text();
				const editUserStatus = editUserStatusT.trim();
				if($('#userName').val()!='' && $('#userEmail').val() != '' && editUserStatus!=''){
					const editUserData = [
										{name : 'updateUserToken', value : updateUserToken},
										{name : 'updateUserID', value : $(this).attr('update_user_id')},
										{name : 'updateUserName', value : $('#userName').val()},
										{name : 'updateUserEmail', value : $('#userEmail').val()},
										{name : 'updateUserStatus', value : editUserStatus},
										{name : 'updateUserPassword', value : $('#userPassword').val()}
									];
					$.ajax({
						url : 'user_manage.php',
						method : 'POST',
						data : editUserData,
						success:function(data){
							$('#userModal').modal('hide');
							if(data=='Please enter a valid email!'){
								$('#alert_action').html(`<div class="alert alert-danger">
								  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								  <strong>`+data+`</strong>
								</div>`);
							} else{
								$('#alert_action').html(`<div class="alert alert-warning">
							  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>`+data+`</strong>
							</div>`);
							}
							userListData.ajax.reload();
						}
					});
				}
				else
				{
					$('#userModal').modal('hide');
					$('#alert_action').html(`<div class="alert alert-danger">
							  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>Please fill all fields! Only Password field could blank.</strong>
							</div>`);
				}
			});
			$(document).on('click', '.deleteUser', function(){
				addUserToken = "<?php echo hash('sha256', 'asdfgh')  ?>";
				updateUserToken = "<?php echo hash('sha256', '12345')  ?>";
				deleteUserToken = "<?php echo hash('sha256', 'delete_user_token')  ?>";
				$('#userModal .modal-footer #addNewUserBtn').removeAttr('update_user_id');
				$('#deleteModal .modal-footer #deleteUserBtn').addClass('deleteUserSubmit')
				$('#deleteModal .modal-footer #deleteUserBtn').attr('delete_user_id', $(this).attr('id') );
				$('#deleteModal .modal-footer #deleteUserBtn').attr('delete_user_name', $(this).attr('deleteUserName'));
				$('#deleteModal .modal-body #infoUser').html(`Are you sure want to permanently delete <strong>`+$(this).attr('deleteUserName')+ `</strong> ?`);
			});
			$(document).on('click', '.deleteUserSubmit', function(event){
				event.preventDefault();
				if($(this).attr('delete_user_id')==undefined && $(this).attr('delete_user_name' == undefined)){
					$('#deleteModal').modal('hide');
					$('#alert_action').html(`<div class="alert alert-danger">
							  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>Failed to execute! An error occured.</strong>
							</div>`);
				} else{
					const deleteUserData = [
												{name : 'deleteUserToken', value : deleteUserToken},
												{name : 'deleteUserName', value : $(this).attr('delete_user_name')},
												{name : 'deleteUserID', value : $(this).attr('delete_user_id')}
										   ];
					$.ajax({
						url : 'user_manage.php',
						method : 'POST',
						data : deleteUserData,
						success:function(data){
							$('#deleteModal').modal('hide');
							if(data=='Failed to execute! An error occured.'){
								$('#alert_action').html(`<div class="alert alert-danger">
								  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								  <strong>`+data+`</strong>
								</div>`);
							} else{
								$('#alert_action').html(`<div class="alert alert-success">
							  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>`+data+`</strong>
							</div>`);
							}
							userListData.ajax.reload();
						}
					});
				}
			});
		});
	</script>
<?php require_once '../templates/footer.php'  ?>