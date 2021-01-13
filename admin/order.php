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

$order_id = isset($_GET['id']) ? $_GET['id'] : null;
$orderdetails_id = isset($_GET['detail']) ? $_GET['detail'] : null;
switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		if($orderdetails_id!=null){
			$stmt = $conn->prepare('SELECT * FROM pesanan JOIN detail_pesanan USING(pesanan_id) JOIN stuffs USING (stuff_id)  WHERE pesanan_id = :detail_pesanan_id');
			$stmt->execute([':detail_pesanan_id' => $orderdetails_id]);
			if($stmt->rowCount() == 0){
				response(['errors' => 'Page Not Found'], 404);
			}
			else{
				response($stmt->fetchAll());
			}
		}
		else{
			$stmt = $conn->prepare('SELECT * FROM pesanan');
			$stmt->execute();
			response($stmt->fetchAll());
		}
		break;

	case 'POST':
		$input = json_decode(file_get_contents('php://input'), true);
		$isError = false;
		$errors = [];
		$quantity_ordered = (int) $input['quantity_ordered'];
		$currProdQty = 0;
		$product_price = 0;
		$phone = (int) $input['receiver_phone'];
		foreach ($input as $i => $v) {
			if ($input[$i] == '' || $input[$i] == null) {
				$isError = true;
				$errors[$i] = 'Kolom ini tidak boleh kosong';
			} else if ($i == 'product') {
				$stmt = $conn->prepare('SELECT * FROM stuffs WHERE stuff_id = :stuff_id');
				$stmt->execute([':stuff_id' => $input['product']]);
				if ($stmt->rowCount() == 0) {
					$isError = true;
					$errors['product'] = 'Produk yang dipilih tidak valid';
				} else{
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
					$product_price += (int) $result['stuff_sale_price'];
					$currProdQty += (int) $result['stuff_in_stock'];
				}
			} else if ($quantity_ordered <= 0) {
					$isError = true;
					$errors['quantity_ordered'] = 'Jumlah yang diorder tidak valid';
			}  
			else if(strlen($input['receiver_phone']) < 8 || strlen($input['receiver_phone']) > 12 || $input == 0 ){
				$isError = true;
				$errors['receiver_phone'] = 'No. HP/Telepon yang dimasukkan tidak valid';
			}
			else if( $i=='payment_type' ){
				$valid_payment = ['cash', 'credit'];
				if(!in_array($input['payment_type'], $valid_payment)){
					$isError = true;
					$errors['payment_type'] = 'Metode pembayaran yang dipilih tidak valid';
				}
			}
		}
		if ($isError) {
			response(['errors' => $errors], 422);
		} else {
			$uid = Uuid::uuid4()->toString();
			$stmt = $conn->prepare('INSERT INTO pesanan VALUES (:pesanan_id, :nomor_pesanan, :waktu_pemesanan, :total_harga, :metode_pembayaran) ');
			$stmt->execute([
				':pesanan_id' => $uid, 
				':nomor_pesanan' => date('YmdHis'), 
				':waktu_pemesanan' => date('Y-m-d H:i:s'),
				':total_harga' => (int) $product_price * (int) $quantity_ordered,
				':metode_pembayaran' => $input['payment_type']
			]);
			$uid2 = Uuid::uuid4()->toString();
			$stmt2 = $conn->prepare('INSERT INTO detail_pesanan VALUES (:detail_pesanan_id, :pesanan_id, :stuff_id, :nama_penerima, :telepon_penerima, :alamat_penerima, :jumlah_yang_diorder)');
			$stmt2->execute([
				':detail_pesanan_id' => $uid2, 
				':pesanan_id' => $uid, 
				':stuff_id' => $input['product'],
				':nama_penerima' => $input['receiver'],
				':telepon_penerima' => $input['receiver_phone'],
				':alamat_penerima' => $input['receiver_address'],
				':jumlah_yang_diorder' => $input['quantity_ordered']
			]);
			$stmt3 = $conn->prepare('UPDATE stuffs SET stuff_in_stock = :stuff_in_stock WHERE stuff_id = :stuff_id ');
			$stmt3->execute([
				':stuff_in_stock' => (int) $currProdQty - (int) $quantity_ordered,
				':stuff_id' => $input['product']
			]);
			response(['message' => '<div class="alert alert-success">
										  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										  <strong>Order baru berhasil dibuat</strong>
										</div>'], 201);
		}
		break;
	case 'PATCH':
		$input = json_decode(file_get_contents('php://input'), true);
		$isError = false;
		$errors = [];
		$quantity_ordered = (int) $input['quantity_ordered'];
		$currProdQty = 0;
		$product_price = 0;
		$phone = (int) $input['receiver_phone'];
		foreach ($input as $i => $v) {
			if ($input[$i] == '' || $input[$i] == null) {
				$isError = true;
				$errors[$i] = 'Kolom ini tidak boleh kosong';
			} else if ($i == 'product') {
				$stmt = $conn->prepare('SELECT * FROM stuffs WHERE stuff_id = :stuff_id');
				$stmt->execute([':stuff_id' => $input['product']]);
				if ($stmt->rowCount() == 0) {
					$isError = true;
					$errors['product'] = 'Produk yang dipilih tidak valid';
				}
			} else if ($quantity_ordered <= 0) {
					$isError = true;
					$errors['quantity_ordered'] = 'Jumlah yang diorder tidak valid';
			}  
			else if(strlen($input['receiver_phone']) < 8 || strlen($input['receiver_phone']) > 12 || $input == 0 ){
				$isError = true;
				$errors['receiver_phone'] = 'No. HP/Telepon yang dimasukkan tidak valid';
			}
			else if( $i=='payment_type' ){
				$valid_payment = ['cash', 'credit'];
				if(!in_array($input['payment_type'], $valid_payment)){
					$isError = true;
					$errors['payment_type'] = 'Metode pembayaran yang dipilih tidak valid';
				}
			}
		}
		if ($isError) {
			response(['errors' => $errors], 422);
		} else {
			$input['id'] = $order_id;
			$currOrderDataStmt = $conn->prepare('SELECT stuff_id, stuff_in_stock, jumlah_yang_diorder FROM stuffs JOIN detail_pesanan USING(stuff_id) WHERE pesanan_id = :pesanan_id ');
			$currOrderDataStmt->execute([':pesanan_id' => $order_id]);
			$currOrderData = $currOrderDataStmt->fetch(PDO::FETCH_ASSOC);
			$stmt = $conn->prepare('UPDATE detail_pesanan SET stuff_id = :stuff_id, jumlah_yang_diorder = :quantity_ordered, nama_penerima = :nama_penerima, telepon_penerima = :telepon_penerima, alamat_penerima = :alamat_penerima WHERE pesanan_id = :pesanan_id');
			$stmt->execute([ 
				':stuff_id' => $input['product'], 
				':quantity_ordered' => $quantity_ordered,
				':nama_penerima' => $input['receiver'],
				':telepon_penerima' => $input['receiver_phone'],
				':alamat_penerima' => $input['receiver_address'],
				':pesanan_id' => $order_id
			]);
			$stmt4 = $conn->prepare('UPDATE stuffs SET stuff_in_stock = :stuff_in_stock WHERE stuff_id = :stuff_id ');
			$stmt4->execute([
				':stuff_in_stock' => (int) $currOrderData['stuff_in_stock'] + (int) $currOrderData['jumlah_yang_diorder'],
				':stuff_id' => $currOrderData['stuff_id']
			]);
			$stmt5 = $conn->prepare('SELECT stuff_in_stock, stuff_sale_price FROM stuffs WHERE stuff_id = :stuff_id');
			$stmt5->execute(['stuff_id' => $input['product'] ]);
			$result = $stmt5->fetch(PDO::FETCH_ASSOC);
			$product_price += (int) $result['stuff_sale_price'];
			$currProdQty += (int) $result['stuff_in_stock'];
			$stmt2 = $conn->prepare('UPDATE pesanan SET total_harga = :total_harga, metode_pembayaran = :metode_pembayaran WHERE pesanan_id = :pesanan_id');
			$stmt2->execute([ 
				':total_harga' => (int) $product_price * (int) $quantity_ordered, 
				':metode_pembayaran' => $input['payment_type'],
				':pesanan_id' => $order_id
			]);
			$stmt3 = $conn->prepare('UPDATE stuffs SET stuff_in_stock = :stuff_in_stock WHERE stuff_id = :stuff_id ');
			$stmt3->execute([
				':stuff_in_stock' => (int) $currProdQty - (int) $quantity_ordered,
				':stuff_id' => $input['product']
			]);
			response(['message' => '<div class="alert alert-warning">
										  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										  <strong>Data order berhasil diubah</strong>
										</div>'], 200);
		}
		break;

	case 'DELETE':
		$currOrderDataStmt = $conn->prepare('SELECT stuff_id, stuff_in_stock, jumlah_yang_diorder FROM stuffs JOIN detail_pesanan USING(stuff_id) WHERE pesanan_id = :pesanan_id ');
		$currOrderDataStmt->execute([':pesanan_id' => $order_id]);
		$currOrderData = $currOrderDataStmt->fetch(PDO::FETCH_ASSOC);
		$stmt = $conn->prepare('UPDATE stuffs SET stuff_in_stock = :stuff_in_stock WHERE stuff_id = :stuff_id ');
		$stmt->execute([
			':stuff_in_stock' => (int) $currOrderData['stuff_in_stock'] + (int) $currOrderData['jumlah_yang_diorder'],
			':stuff_id' => $currOrderData['stuff_id']
		]);
		$stmt2 = $conn->prepare('DELETE FROM pesanan WHERE pesanan_id = :order_id');
		$stmt2->execute(compact('order_id'));
		$stmt3 = $conn->prepare('DELETE FROM detail_pesanan WHERE pesanan_id = :order_id');
		$stmt3->execute(compact('order_id'));
		response(['message' => '<div class="alert alert-danger">
									  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									  <strong>Order berhasil dibatalkan</strong>
									</div>'], 200);
		break;

	default:
		response(['message' => 'Terjadi kesalahan dalam pengiriman request.'], 405);
}
