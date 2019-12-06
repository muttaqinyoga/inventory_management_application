<?php
	require_once '../database/database_connection.php';
	if(!isset($_SESSION['type']))
	{
		header("Location: ../login.php");
	}
	$query = "SELECT * FROM user WHERE userID = '".$_SESSION["userID"]."'  ";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$result = $stmt->fetchAll();
	$name = '';
	$email = '';
	$userID = '';
	foreach($result as $row)
	{
		$name = $row['userName'];
		$email = $row['userEmail'];
		$userID = $row['userID'];
	}
	require_once '../templates/header.php';
?>
	<section id="breadcrumb">
		<div class="container">
			<ol class="breadcrumb">
				<li class="active">Edit Profile</li>
			</ol>
		</div>
	</section>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-default">
					<div class="panel-heading-green-light">
						<h3>Edit Profile</h3>
					</div>
					<div class="panel-body">
						<form method="POST" id="editProfileForm">
							<div class="form-group" id="message">

							</div>
							<div class="form-group">
								<label for="userEmail">User Email</label>
								<input type="text" name="userEmail" id="userEmail" class="form-control" placeholder="Email..." value="<?php echo $email; ?>">
							</div>
							<div class="form-group">
								<label for="userName">Full Name</label>
								<input type="text" name="userName" id="userName" class="form-control" placeholder="Full Name" value="<?php echo $name; ?>">
							</div>
							<hr />
							<label class="text-danger">Leave Password blank if you do not want to change</label>
							<div class="form-group">
								<label for="newPassword">New Password</label>
								<input type="password" name="newPassword" id="newPassword" class="form-control" placeholder="New Password..." >
							</div>
							<div class="form-group">
								<label for="newPasswordConfirm">Retype New Password</label>
								<input type="password" name="newPasswordConfirm" id="newPasswordConfirm" class="form-control" placeholder="Retype New Password..." >
							</div>
							<div class="form-group">
								<input type="submit" id="save" name="save" class="form-control btn btn-primary" value="Save">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
			$(document).on('click', '#save' , function(event){
				event.preventDefault();
				const editProfieToken = '<?php echo hash('sha256', 'edit_profile_token'); ?>';
				const editProfileData = [
					{name : 'editProfieToken', value : editProfieToken},
					{name : 'userEmail', value : $('#userEmail').val()},
					{name : 'userName', value : $('#userName').val()},
					{name : 'newPassword', value  : $('#newPassword').val()},
					{name : 'newPasswordConfirm', value : $('#newPasswordConfirm').val()}
				];
				$.ajax({
					url : 'edit_profile.php',
					method : 'post',
					data : editProfileData,
					success:function(data){
						$('#message').html(data);
					} 
				});
			})
		});
	</script>

<?php require_once '../templates/footer.php'; ?>