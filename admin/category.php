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

$category_id = isset($_GET['id']) ? $_GET['id'] : null;
switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		$stmt = $conn->prepare('SELECT * FROM categories');
		$stmt->execute();
		response($stmt->fetchAll());
		break;

	case 'POST':
		$input = json_decode(file_get_contents('php://input'), true);
		if ($input['name'] == '' || $input['name'] == null) {
			response(['errors' => 'invalid request'], 422);
		} else {
			$uid = Uuid::uuid4()->toString();
			$stmt = $conn->prepare('INSERT INTO categories (category_id, category_name) VALUES (:category_id, :category_name)');
			$stmt->execute([':category_id' => $uid, ':category_name' => $input['name']]);

			response(['message' => '<div class="alert alert-info">
										  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										  <strong>Kategori baru berhasil dibuat</strong>
										</div>'], 201);
		}
		break;

	case 'PATCH':
		$input = json_decode(file_get_contents('php://input'), true);
		if ($input['name'] == '' || $input['name'] == null) {
			response(['errors' => 'invalid request'], 422);
		} else {
			$input['id'] = $category_id;
			$stmt = $conn->prepare('UPDATE categories SET category_name = :category_name WHERE category_id = :category_id');
			$stmt->execute([':category_name' => $input['name'], ':category_id' => $input['id']]);
			response(['message' => '<div class="alert alert-warning">
										  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										  <strong>Data Kategori berhasil diubah</strong>
										</div>'], 200);
		}
		break;

	case 'DELETE':
		$stmt = $conn->prepare('DELETE FROM categories WHERE category_id = :category_id');
		$stmt->execute(compact('category_id'));
		$stmt = $conn->prepare('DELETE FROM brands WHERE category_id = :category_id');
		$stmt->execute(compact('category_id'));

		response(['message' => '<div class="alert alert-danger">
									  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									  <strong>Kategori berhasil dihapus</strong>
									</div>'], 200);
		break;

	default:
		response(['message' => 'Terjadi kesalahan dalam pengiriman request.'], 405);
}
