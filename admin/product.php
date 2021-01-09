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

$product_id = isset($_GET['id']) ? $_GET['id'] : null;
switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		$stmt = $conn->prepare('SELECT stuff_id, stuff_name, category_id, category_name, stuffs.brand_id AS id_brand, brand_name, stuff_buy_price, supplier_id, supplier_name, stuff_sale_price, stuff_in_stock FROM stuffs JOIN categories USING(category_id) JOIN suppliers USING(supplier_id) JOIN brands USING(category_id)');
		$stmt->execute();
		response($stmt->fetchAll());
		break;

	case 'POST':
		$input = json_decode(file_get_contents('php://input'), true);
		$isError = false;
		$errors = [];
		$buy_price = (int) $input['buy_price'];
		$sale_price = (int) $input['sale_price'];
		$stock = (int) $input['stock'];
		foreach ($input as $i => $v) {
			if ($input[$i] == '' || $input[$i] == null) {
				$isError = true;
				$errors[$i] = 'Kolom ini tidak boleh kosong';
			} else if ($i == 'category') {
				$stmt = $conn->prepare('SELECT * FROM categories WHERE category_id = :category_id');
				$stmt->execute([':category_id' => $input['category']]);
				if ($stmt->rowCount() == 0) {
					$isError = true;
					$errors['category'] = 'Kategori yang dipilih tidak valid';
				}
			} else if ($i == 'brand') {
				$stmt = $conn->prepare('SELECT * FROM brands WHERE brand_id = :brand_id');
				$stmt->execute([':brand_id' => $input['brand']]);
				if ($stmt->rowCount() == 0) {
					$isError = true;
					$errors['brand'] = 'Brand yang dipilih tidak valid';
				}
			}  
			else if ($i == 'supplier') {
				$stmt = $conn->prepare('SELECT * FROM suppliers WHERE supplier_id = :supplier_id');
				$stmt->execute([':supplier_id' => $input['supplier']]);
				if ($stmt->rowCount() == 0) {
					$isError = true;
					$errors['supplier'] = 'Supplier yang dipilih tidak valid';
				}
			}
			else if ($i == 'brand') {
				$stmt = $conn->prepare('SELECT * FROM brands WHERE brand_id = :brand_id');
				$stmt->execute([':brand_id' => $input['brand']]);
				if ($stmt->rowCount() == 0) {
					$isError = true;
					$errors['brand'] = 'Merk yang yang dipilih tidak valid';
				}
			}
			else if($buy_price <= 0 ){
				$isError = true;
				$errors['buy_price'] = 'Harga Beli yang dimasukkan tidak valid';
			}
			else if($sale_price <= 0 ){
				$isError = true;
				$errors['sale_price'] = 'Harga Jual yang dimasukkan tidak valid';
			}
			else if($stock < 0 ){
				$isError = true;
				$errors['stock'] = 'Stok yang dimasukkan tidak valid';
			}
		}
		if ($isError) {
			response(['errors' => $errors], 422);
		} else {
			$uid = Uuid::uuid4()->toString();
			$stmt = $conn->prepare('INSERT INTO stuffs (stuff_id, stuff_name, category_id, brand_id, stuff_buy_price, stuff_sale_price, stuff_in_stock, supplier_id) VALUES (:stuff_id, :stuff_name, :category_id, :brand_id, :stuff_buy_price, :stuff_sale_price, :stuff_in_stock, :supplier_id)');
			$stmt->execute([
				':stuff_id' => $uid, 
				':stuff_name' => $input['name'], 
				':category_id' => $input['category'],
				':brand_id' => $input['brand'],
				':stuff_buy_price' => $input['buy_price'],
				':stuff_in_stock' => $input['stock'],
				':stuff_sale_price' => $input['sale_price'],
				':supplier_id' => $input['supplier']
			]);

			response(['message' => '<div class="alert alert-success">
										  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										  <strong>Produk baru berhasil dibuat</strong>
										</div>'], 201);
		}
		break;
	case 'PATCH':
		$input = json_decode(file_get_contents('php://input'), true);
		$isError = false;
		$errors = [];
		$buy_price = (int) $input['buy_price'];
		$sale_price = (int) $input['sale_price'];
		$stock = (int) $input['stock'];
		foreach ($input as $i => $v) {
			if ($input[$i] == '' || $input[$i] == null) {
				$isError = true;
				$errors[$i] = 'Kolom ini tidak boleh kosong';
			} else if ($i == 'category') {
				$stmt = $conn->prepare('SELECT * FROM categories WHERE category_id = :category_id');
				$stmt->execute([':category_id' => $input['category']]);
				if ($stmt->rowCount() == 0) {
					$isError = true;
					$errors['category'] = 'Kategori yang dipilih tidak valid';
				}
			} else if ($i == 'brand') {
				$stmt = $conn->prepare('SELECT * FROM brands WHERE brand_id = :brand_id');
				$stmt->execute([':brand_id' => $input['brand']]);
				if ($stmt->rowCount() == 0) {
					$isError = true;
					$errors['brand'] = 'Brand yang dipilih tidak valid';
				}
			}  
			else if ($i == 'supplier') {
				$stmt = $conn->prepare('SELECT * FROM suppliers WHERE supplier_id = :supplier_id');
				$stmt->execute([':supplier_id' => $input['supplier']]);
				if ($stmt->rowCount() == 0) {
					$isError = true;
					$errors['supplier'] = 'Supplier yang dipilih tidak valid';
				}
			}
			else if ($i == 'brand') {
				$stmt = $conn->prepare('SELECT * FROM brands WHERE brand_id = :brand_id');
				$stmt->execute([':brand_id' => $input['brand']]);
				if ($stmt->rowCount() == 0) {
					$isError = true;
					$errors['brand'] = 'Merk yang yang dipilih tidak valid';
				}
			}
			else if($buy_price <= 0 ){
				$isError = true;
				$errors['buy_price'] = 'Harga Beli yang dimasukkan tidak valid';
			}
			else if($sale_price <= 0 ){
				$isError = true;
				$errors['sale_price'] = 'Harga Jual yang dimasukkan tidak valid';
			}
			else if($stock < 0 ){
				$isError = true;
				$errors['stock'] = 'Stok yang dimasukkan tidak valid';
			}
		}
		if ($isError) {
			response(['errors' => $errors], 422);
		} else {
			$input['id'] = $product_id;
			$stmt = $conn->prepare('UPDATE stuffs SET stuff_name = :stuff_name, category_id = :category_id, brand_id = :brand_id, stuff_buy_price = :stuff_buy_price, supplier_id = :supplier_id, stuff_sale_price = :stuff_sale_price, stuff_in_stock = :stuff_in_stock WHERE stuff_id = :stuff_id');
			$stmt->execute([ 
				':stuff_name' => $input['name'], 
				':category_id' => $input['category'],
				':brand_id' => $input['brand'],
				':stuff_buy_price' => $input['buy_price'],
				':supplier_id' => $input['supplier'],
				':stuff_sale_price' => $input['sale_price'],
				':stuff_in_stock' => $input['stock'],
				':stuff_id' => $product_id,
			]);
			response(['message' => '<div class="alert alert-warning">
										  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										  <strong>Data produk berhasil diubah</strong>
										</div>'], 200);
		}
		break;

	case 'DELETE':
		$stmt = $conn->prepare('DELETE FROM stuffs WHERE stuff_id = :product_id');
		$stmt->execute(compact('product_id'));

		response(['message' => '<div class="alert alert-danger">
									  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									  <strong>Produk berhasil dihapus</strong>
									</div>'], 200);
		break;

	default:
		response(['message' => 'Terjadi kesalahan dalam pengiriman request.'], 405);
}
