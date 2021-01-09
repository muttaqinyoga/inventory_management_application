<?php
require_once 'database/connections.php';
$message = '';
if (isset($_SESSION['user_role'])) {
	if ($_SESSION['user_role'] == 'Administrator') {
		header("Location: admin/");
	} else {
		header("Location: user/");
	}
}
if (isset($_POST['loginButton'])) {
	$query = "SELECT * FROM users WHERE user_email = :user_email";
	$stmt = $conn->prepare($query);
	$stmt->execute(['user_email' => $_POST['userEmail']]);
	$count = $stmt->rowCount();
	if ($count > 0) {
		$result = $stmt->fetchAll();
		foreach ($result as $row) {
			if (password_verify($_POST['userPassword'], $row['user_password'])) {
				$_SESSION['user_role'] = $row['user_role'];
				$_SESSION['user_id'] = $row['user_id'];
				$_SESSION['user_name'] = $row['user_name'];
				$_SESSION['user_email'] = $row['user_email'];
				if ($_SESSION['user_role'] == 'Administrator') {
					header("Location: admin/");
				} else {
					header("Location: user/");
				}
			} else {
				$message .= '<div class="alert alert-danger">
									  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									  <strong>Gagal login ke aplikasi. Email atau Password tidak tepat</strong>
								</div>';
			}
		}
	} else {
		$message .= '<div class="alert alert-danger">
							  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>Gagal login ke aplikasi. Email atau Password tidak tepat</strong>
						</div>';
	}
}

?>
<!DOCTYPE html>
<html>

<head>
	<title>Inventori Produk | Login</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="vendor/bootstrap/css/custom.css">
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="login-panel">
					<div class="panel panel-default">
						<div class="panel-heading-teal">
							<h2 class="text-white text-center">Inventori Produk</h2>
						</div>
						<div class="panel-body">
							<h4 class="text-center">Silahkan login menggunakan email dan password yang terdaftar</h4>
							<div class="login-icon text-center">
								<span class="glyphicon glyphicon-user"></span>
							</div>
							<form method="post" role="form">
								<div class="col-md-10 col-md-offset-1">
									<div class="form-group">
										<?php echo $message; ?>
									</div>
									<div class="form-group">
										<label for="userEmail">Email</label>
										<input type="text" name="userEmail" id="userEmail" class="form-control" placeholder="Email..." required>
									</div>
								</div>
								<div class="col-md-10 col-md-offset-1">
									<div class="form-group">
										<label for="userPassword">Password</label>
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

	<script type="text/javascript" src="vendor/bootstrap/js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>