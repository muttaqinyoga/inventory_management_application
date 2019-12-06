<?php  
	require_once 'database/database_connection.php';
	$message = '';
	if(isset($_SESSION['type']))
	{
		if($_SESSION['type']=='master')
		{
			header("Location: admin");
		}
		else
		{
			header("Location: user");
		}
	}
	if(isset($_POST['loginButton']))
	{
		$query = "SELECT * FROM user WHERE userEmail = :userEmail";
		$stmt = $conn->prepare($query);
		$stmt->execute(['userEmail' => $_POST['userEmail']]);
		$count = $stmt->rowCount();
		if($count > 0)
		{
			$result = $stmt->fetchAll();
			foreach($result as $row)
			{
				if(password_verify($_POST['userPassword'], $row['userPassword']))
				{
					if($row['userStatus'] == 'active')
					{
						$_SESSION['type'] = $row['userType'];
						$_SESSION['userID'] = $row['userID'];
						$_SESSION['userName'] = $row['userName'];
						$_SESSION['userEmail'] = $row['userEmail'];
						if($_SESSION['type']=='master')
						{
							header("Location: admin/");
						}
						else
						{
							header("Location: user/");
						}
					}
					else
					{
						$message = '<div class="alert alert-danger">
									  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									  <strong>Your Account was Disabled!</strong> Please Contact Admin.
									</div>';						
					}
				}
				else
				{
					$message = '<div class="alert alert-danger">
									  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									  <strong>Password Incorrect!</strong>
								</div>';
				}
			}
		}
		else
		{
			$message = '<div class="alert alert-danger">
						  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						  <strong>Unknown Email!</strong> Please Contact Admin.
						</div>';
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Inventory Management</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="login-panel">
					<div class="panel panel-default">
						<div class="panel-heading-teal">
							<h2 class="text-white text-center">Inventory Management</h2>
						</div>
						<div class="panel-body">
							<h4 class="text-center">Please Login by Using Your Account</h4>
							<div class="login-icon text-center">
								<span class="glyphicon glyphicon-user"></span>
							</div>
							<form method="post" role="form">
								<div class="col-md-10 col-md-offset-1">
									<div class="form-group">
										<?php echo $message; ?>
									</div>
									<div class="form-group">
										<label for="userEmail">User Email</label>
										<input type="text" name="userEmail" id="userEmail" class="form-control" placeholder="Email..." required>
									</div>
								</div>
								<div class="col-md-10 col-md-offset-1">
									<div class="form-group">
										<label for="userPassword">User Password</label>
										<input type="password" name="userPassword" id="userPassword" class="form-control" placeholder="Password..." required>
									</div>
								</div>
								<div class="col-md-10 col-md-offset-1">
									<div class="form-group">
										<input type="submit" name="loginButton" class="form-control btn btn-primary" value="Login">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>