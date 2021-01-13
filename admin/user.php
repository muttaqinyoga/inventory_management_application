<?php
require_once 'helper.php';
require_once '../database/connections.php';
if (!isset($_SESSION['user_role'])) {
	response(['unauthorized' => 'Terjadi kesalahan dalam pengiriman request.'], 401);
	die;
}
if ($_SESSION['user_role'] != 'Administrator') {
	response(['unauthorized' => 'Terjadi kesalahan dalam pengiriman request.'], 401);
	die;
}
switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
			$stmt = $conn->prepare('SELECT user_name, user_email, user_role FROM users ');
			$stmt->execute();
			response($stmt->fetchAll());
		break;

	case 'PATCH':
		$input = json_decode(file_get_contents('php://input'), true);
		$isError = false;
		$errors = [];
		foreach ($input as $i => $v) {
			if ($input[$i] == '' || $input[$i] == null) {
				$isError = true;
				$errors[$i] = 'Kolom ini tidak boleh kosong';
			} else if(!filter_var($input['email'], FILTER_VALIDATE_EMAIL)){
				$isError = true;
				$errors['email'] = 'Email yang dimasukkan tidak valid';
			} else if($input['password'] != $input ['confirm_password']){
				$isError = true;
				$errors['password'] = 'Password tidak sama';
				$errors['confirm_password'] = 'Password tidak sama';
			} else if(strlen($input['password']) < 5){
				$errors['password'] = 'Password minimal 5 karakter';
			}
		}
		if ($isError) {
			response(['errors' => $errors], 422);
		} else {
			$stmt = $conn->prepare('UPDATE users SET user_name = :user_name, user_email = :user_email, user_password = :user_password WHERE user_id = :user_id ');
			$stmt->execute([
				'user_name' => $input['name'],
				'user_email' => $input['email'],
				'user_password' => password_hash($input['password'], PASSWORD_DEFAULT),
				'user_id' => $_SESSION['user_id']
			]);

			response(['message' => '<div class="alert alert-success">
										  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										  <strong>Data akun berhasil diupdate</strong>
										</div>'], 201);
		}
		break;
	default:
		response(['message' => 'Terjadi kesalahan dalam pengiriman request.'], 405);
}
