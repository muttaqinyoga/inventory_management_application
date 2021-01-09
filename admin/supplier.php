<?php
require_once 'helper.php';
require_once '../database/connections.php';
if (!isset($_SESSION['user_role'])) {
	response(['message' => 'Terjadi kesalahan dalam pengiriman request.'], 401);
	die;
}
if ($_SESSION['user_role'] != 'Administrator') {
	response(['message' => 'Terjadi kesalahan dalam pengiriman request.'], 401);
	die;
}
use Ramsey\Uuid\Uuid;

$supplier_id = isset($_GET['id']) ? $_GET['id'] : null;
switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		$stmt = $conn->prepare('SELECT * FROM suppliers');
		$stmt->execute();
		response($stmt->fetchAll());
		break;

	case 'POST':
		$input = json_decode(file_get_contents('php://input'), true);
		$isError = false;
		$errors = [];
		$phone = (int) $input['phone'];
		foreach ($input as $i => $v) {
			if ($input[$i] == '' || $input[$i] == null) {
				$isError = true;
				$errors[$i] = 'Kolom ini tidak boleh kosong';
			} else if(!filter_var($input['email'], FILTER_VALIDATE_EMAIL)){
				$isError = true;
				$errors['email'] = 'Email yang dimasukkan tidak valid';
			}
			else if(strlen($input['phone']) < 8 || strlen($input['phone']) > 12 || $input == 0 ){
				$isError = true;
				$errors['phone'] = 'No. HP/Telepon yang dimasukkan tidak valid';
			}
		}
		if ($isError) {
			response(['errors' => $errors], 422);
		} else {
			$uid = Uuid::uuid4()->toString();
			$stmt = $conn->prepare('INSERT INTO suppliers (supplier_id, supplier_name, supplier_phone, supplier_email, supplier_address) VALUES (:supplier_id, :supplier_name, :supplier_phone, :supplier_email, :supplier_address)');
			$stmt->execute([	
					':supplier_id' => $uid,
					':supplier_name' => $input['name'],
					':supplier_phone' => $input['phone'],
					':supplier_email' => $input['email'],
					':supplier_address' => $input['address']
				]);
			response(['message' => '<div class="alert alert-success">
										  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										  <strong>Supplier baru berhasil dibuat</strong>
										</div>'], 201);
		}
		break;
	case 'PATCH':
		$input = json_decode(file_get_contents('php://input'), true);
		$isError = false;
		$errors = [];
		foreach ($input as $i => $v) {
			if ($input[$i] == '' || $input[$i] == null) {
				$isError = true;
				$errors[$i] = 'Kolom ini tidak boleh kosong';
			}else if(!filter_var($input['email'], FILTER_VALIDATE_EMAIL)){
				$isError = true;
				$errors['email'] = 'Email yang dimasukkan tidak valid';
			}
			else if(strlen($input['phone']) < 8 || strlen($input['phone']) > 12 || $input == 0 ){
				$isError = true;
				$errors['phone'] = 'No. HP/Telepon yang dimasukkan tidak valid';
			}
		}
		if ($isError) {
			response(['errors' => $errors], 422);
		} else {
			$input['id'] = $supplier_id;
			$stmt = $conn->prepare('UPDATE suppliers SET supplier_name = :supplier_name, supplier_phone = :supplier_phone, supplier_email = :supplier_email, supplier_address = :supplier_address WHERE supplier_id = :supplier_id');
			$stmt->execute([
				':supplier_name' => $input['name'],
				':supplier_phone' => $input['phone'],
				':supplier_email' => $input['email'], 
				':supplier_address' => $input['address'],
				':supplier_id' => $input['id']
			]);
			response(['message' => '<div class="alert alert-warning">
										  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										  <strong>Supplier berhasil diubah</strong>
										</div>'], 200);
		}
		break;

	case 'DELETE':
		$stmt = $conn->prepare('DELETE FROM suppliers WHERE supplier_id = :supplier_id');
		$stmt->execute(compact('supplier_id'));

		response(['message' => '<div class="alert alert-danger">
									  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									  <strong>Supplier berhasil dihapus</strong>
									</div>'], 200);
		break;

	default:
		response(['message' => 'Terjadi kesalahan dalam pengiriman request.'], 405);
}
