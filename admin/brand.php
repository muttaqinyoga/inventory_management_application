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
use Ramsey\Uuid\Uuid;

$brand_id = isset($_GET['id']) ? $_GET['id'] : null;
$category_id = isset($_GET['category']) ? $_GET['category'] : null;
switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		if($category_id != null){
			$stmt = $conn->prepare('SELECT brand_id, brand_name FROM brands WHERE category_id = :category_id');
			$stmt->execute(compact('category_id'));
			response($stmt->fetchAll());
		} else{
			$stmt = $conn->prepare('SELECT brand_id, brand_name, category_id, category_name FROM brands JOIN categories USING(category_id) ');
			$stmt->execute();
			response($stmt->fetchAll());
		}
		break;

	case 'POST':
		$input = json_decode(file_get_contents('php://input'), true);
		$isError = false;
		$errors = [];
		foreach ($input as $i => $v) {
			if ($input[$i] == '' || $input[$i] == null) {
				$isError = true;
				$errors[$i] = 'Kolom ini tidak boleh kosong';
			} else if ($i == 'category') {
				$stmt = $conn->prepare('SELECT * FROM categories WHERE category_id = :category_id');
				$stmt->execute([':category_id' => $input[$i]]);
				if ($stmt->rowCount() == 0) {
					$isError = true;
					$errors['category'] = 'Kategori yang dipilih tidak valid';
				}
			}
		}
		if ($isError) {
			response(['errors' => $errors], 422);
		} else {
			$uid = Uuid::uuid4()->toString();
			$stmt = $conn->prepare('INSERT INTO brands (brand_id, brand_name, category_id) VALUES (:brand_id, :brand_name, :category_id)');
			$stmt->execute([':brand_id' => $uid, ':brand_name' => $input['name'], ':category_id' => $input['category']]);

			response(['message' => '<div class="alert alert-success">
										  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										  <strong>Merk baru berhasil dibuat</strong>
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
			} else if ($i == 'category') {
				$stmt = $conn->prepare('SELECT * FROM categories WHERE category_id = :category_id');
				$stmt->execute([':category_id' => $input[$i]]);
				if ($stmt->rowCount() == 0) {
					$isError = true;
					$errors['category'] = 'Kategori yang dipilih tidak valid';
				}
			}
		}
		if ($isError) {
			response(['errors' => $errors], 422);
		} else {
			$input['id'] = $brand_id;
			$stmt = $conn->prepare('UPDATE brands SET brand_name = :brand_name, category_id = :category_id WHERE brand_id = :brand_id');
			$stmt->execute([':brand_name' => $input['name'], ':category_id' => $input['category'], ':brand_id' => $input['id']]);
			response(['message' => '<div class="alert alert-warning">
										  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										  <strong>Data Merk berhasil diubah</strong>
										</div>'], 200);
		}
		break;

	case 'DELETE':
		$stmt = $conn->prepare('DELETE FROM brands WHERE brand_id = :brand_id');
		$stmt->execute(compact('brand_id'));

		response(['message' => '<div class="alert alert-danger">
									  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									  <strong>Merk berhasil dihapus</strong>
									</div>'], 200);
		break;

	default:
		response(['message' => 'Terjadi kesalahan dalam pengiriman request.'], 405);
}
