<?php
	
	require_once '../database/database_connection.php';
	require_once '../admin/helper.php';
	if(!isset($_SESSION['type']))
	{
		header("Location: ../login.php");
		die;
	}
	require_once '../templates/header.php';
?>
	<section id="breadcrumb">
		<div class="container">
			<ol class="breadcrumb">
			  <li><a href="index.php">Dashboard</a></li>
			  <li class="active">Order</li>
			</ol>
		</div>
	</section>
	<div class="container">
		<span id="alert_action"></span>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading-success">
						<h4 class="text-white">Order List</h4>
						<button type="button" class="btn btn-light text-black" id="btnOrderModal" data-toggle="modal" data-target="#orderModal"><span class="glyphicon glyphicon-plus"></span> Add New Order</button>
						<?php if($_SESSION['type']!='master') : ?>	
						<h5 class="text-light">Here are orders you have made. You can create an Order from your customer and the Order will showed to Admin</h5>
						<?php endif; ?>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-12 table-responsive">
								<table id="order_data" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Order ID</th>
											<th>Customer Name</th>
											<th>Total Amount</th>
											<th>Payment Status</th>
											<th>Order Status</th>
											<th>Order Date</th>
											<?php if($_SESSION['type']=='master') : ?>
											<th>Created By</th>
											<?php else : ?>
											<th></th>
											<?php endif; ?>
											<th>Details</th>
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

	<div id="orderModal" class="modal modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method="post" id="orderFormModal">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title text-white"></h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="inventoryOrderName">Receiver Name</label>
									<input type="text" id="inventoryOrderName" class="form-control" placeholder="Enter Receiver...">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="inventoryOrderDate">Order Date</label>
									<input type="text" id="inventoryOrderDate" class="form-control datepicker" placeholder="Choose Date">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="inventoryOrderAddress">Receiver Address</label>
							<textarea id="inventoryOrderAddress" class="form-control"></textarea>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-9" id="productsChoice">
									
								</div>
								<div class="col-md-3" style="margin-left: -20px;">
									<label for="order Quantity">Quantity</label>
									<input type="number" class="form-control" id="orderQuantity" placeholder="Quantity">
								</div>
						    </div>
							<hr/>
						</div>
						<div class="form-group">
							<label for="paymentStatus">Payment Method</label>
							<select id="paymentStatus" class="form-control">
							</select>
						</div>
						<div class="form-group">
							<div class="checkbox">
								
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-dark" data-dismiss="modal">Cancel</button>
						<input type="submit" id="orderSubmit" class="btn">
					</div>
				</form>
			</div>
		</div>
	</div>
	<div id="deleteOrderModal" class="modal fade">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header bg-red">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title text-white">Delete Order</h4>
	      </div>
	      <div class="modal-body">
	        <p>Are you sure want to permanently delete this order?</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancel</button>
	        <button type="button" id="deleteOrderSubmit" class="btn btn-danger">Delete</button>
	      </div>
	    </div>
	  </div>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
			const ordertListData = $('#order_data').DataTable({
				"processing": true,
				"serverSide": true,
				"order" : [],
				"ajax" : {
					url : 'order_fetch.php',
					type : 'POST'
				},
				"columnDefs" : [
					{
						"target":[7,8,9]
					}
				],
				"pageLength" : 10,
				"columns":[
					{"name" : "inventoryOrderID", "orderable":true},
					{"name" : "inventoryOrderName", "orderable":true},
					{"name" : "inventoryOrderTotal", "orderable":true},
					{"name" : "paymentStatus", "orderable":true},
					{"name" : "inventoryOrderStatus", "orderable":true},
					{"name" : "inventoryOrderDate", "orderable":true},
					{"name" : "userID", "orderable":false},
					{"name" : "View", "orderable":false},
					{"name" : "Edit", "orderable":false},
					{"name" : "Delete", "orderable":false}
				]
			});
			$(".datepicker").datepicker({
			      format: 'dd-mm-yyyy',
			      autoclose: true,
			      todayHighlight: true
			  });
			$('#inventoryOrderDate').on('click', function(){
				$(this).attr('readonly', true);
			})
			let addOrderToken = '';
			let updateOrderToken = '';
			let deleteOrderToken = '';
			$(document).on('click', '#btnOrderModal', function(){
			    addOrderToken = '<?php echo hash('sha256', 'add_order_token'); ?>';
			    updateOrderToken = '<?php echo hash('sha256', 'asdfgh'); ?>'
			    deleteOrderToken = '<?php echo hash('sha256', '123456'); ?>'
				$('#orderFormModal')[0].reset();
				$('#orderModal .modal-header').removeClass('bg-orange');
				$('#orderModal .modal-header').addClass('bg-green');
				$('#deleteOrderSubmit').removeAttr('delete_order_id');
				$('#orderModal .modal-title').html(`<span class="glyphicon glyphicon-plus"></span> Add New Order`);
				$('#productsChoice').html('');
				$('#productsChoice').prepend(`
											<label>Product Ordered</label>
											<select class="form-control selectpicker"									id="orderedProduct" data-live-search="true">
												<option disabled selected>Select Product</option>
								      			<?php echo selectProductOrder($conn); ?>	
								     		</select>`);
				$('.selectpicker').selectpicker();
				$('#orderModal .modal-body .checkbox').html('');
				$('#orderModal .modal-footer #orderSubmit').removeClass('btn-warning updateOrderSubmit');
				$('#orderModal .modal-footer #orderSubmit').removeAttr('update_inventory_order_id');
				$('#orderModal .modal-footer #orderSubmit').addClass('btn-success addOrderSubmit');
				$('#orderModal .modal-footer #orderSubmit').val('Add New Order');
				$('#paymentStatus').html('');
				$('#paymentStatus').prepend(`<option disabled selected>Select Payment Method</option>
											<option value="cash" >Cash</option>
											 <option value="credit" >Credit</option>`);
			});
			$(document).on('click', '.addOrderSubmit', function(event){
				event.preventDefault();
				$('#orderModal .modal-footer #orderSubmit').attr('disabled', 'disabled');
				const addOrderData = [
										{name : 'addOrderToken', value : addOrderToken},
										{name : 'addInventoryOrderName', value : $('#inventoryOrderName').val() },
										{name : 'addInventoryOrderDate', value : $('#inventoryOrderDate').val() },
										{name : 'addInventoryOrderAddress', value : $('#inventoryOrderAddress').val() },
										{name : 'addproductID', value : $('#orderedProduct').val() },
										{name : 'addProductQuantity', value : $('#orderQuantity').val()},
										{name : 'addPaymentStatus', value : $('#paymentStatus').val()}
									 ];
				$.ajax({
					url : 'order_manage.php',
					method : 'POST',
					data : addOrderData,
					success:function(data){
						$('#orderModal').modal('hide');
						$('#orderFormModal')[0].reset();
						$('#alert_action').html(data);
						$('#orderModal .modal-footer #orderSubmit').attr('disabled', false);
						ordertListData.ajax.reload();
					}
				});
			});
			$(document).on('click','.updateOrder', function(){
				addOrderToken = '<?php echo hash('sha256', 'asdfgh'); ?>';
			    updateOrderToken = '<?php echo hash('sha256', 'update_order_token'); ?>'
			    deleteOrderToken = '<?php echo hash('sha256', '123456'); ?>'
				$('#orderFormModal')[0].reset();
				$('#orderModal .modal-header').removeClass('bg-green');
				$('#orderModal .modal-header').addClass('bg-orange');
				$('#orderModal .modal-title').html(`<span class="glyphicon glyphicon-edit"></span> Edit Order`);
				$('#orderModal .modal-footer #orderSubmit').removeClass('btn-success addOrderSubmit');
				$('#orderModal .modal-footer #orderSubmit').addClass('btn-warning updateOrderSubmit');
				$('#orderModal .modal-footer #orderSubmit').attr('update_inventory_order_id', $(this).attr('id'));
				$('#deleteOrderSubmit').removeAttr('delete_order_id');
				$('#orderModal .modal-footer #orderSubmit').val('Edit Order');
				$('#orderModal').modal('show');
				$('#inventoryOrderName').val($(this).attr('receiverName'));
				$('#inventoryOrderDate').val($(this).attr('orderDate'));
				$('#inventoryOrderAddress').val($(this).attr('receiverAddress'));
				$('#paymentStatus').html('');
				if($(this).attr('paymentStatus')=='cash'){
					$('#paymentStatus').prepend(`<option value="cash" selected >Cash</option>
											 <option value="credit" >Credit</option>`);
				} else{
					$('#paymentStatus').prepend(`<option value="cash" >Cash</option>
											 <option value="credit" selected >Credit</option>`);
				}
				if($(this).attr('inventoryOrderStatus')=='active'){
					$('#orderModal .modal-body .checkbox').html(`
															 <label id="labelStatusOrder">
																<input type="checkbox" id="checkboxStatusOrder" checked> Active
															  </label>
														   `);
				} else{
					$('#orderModal .modal-body .checkbox').html(`
															 <label id="labelStatusOrder">
																<input type="checkbox" id="checkboxStatusOrder"> Inactive
															  </label>
														   `);
				}
				
				$.ajax({
					url : 'order_manage.php',
					method : 'POST',
					data : [{name : 'orderedProductID', value : $(this).attr('id')}],
					dataType : 'json',
					success:function(data){
						$('#productsChoice').html('');
						$('#productsChoice').prepend(`<label>Product Ordered</label><select class="form-control selectpicker" id="orderedProduct" data-live-search="true">
														<option value="`+data.productID+`" selected>`+data.productName+`</option>
										      			<?php echo selectProductOrder($conn); ?>	
										     		</select>`);
						$('#orderQuantity').val(data.quantity);
						$('.selectpicker').selectpicker();
					}
				});
			});
			$(document).on('click', '#checkboxStatusOrder', function(){
				let str = $('#labelStatusOrder').text();
				let str2 = str.trim();
				if(str2=='Active'){
					$('#labelStatusOrder').html(`<input type="checkbox" id="checkboxStatusOrder"> Inactive`);
				} else{
					$('#labelStatusOrder').html(`<input type="checkbox" id="checkboxStatusOrder" checked> Active`);
				}
			});
			$(document).on('click', '.updateOrderSubmit', function(event){
				event.preventDefault();
				$('#orderModal .modal-footer #orderSubmit').attr('disabled', 'disabled');
				let str = $('#labelStatusOrder').text();
				let str2 = str.trim();
				const orderStatus = str2.toLowerCase();
				const updateOrderData = [
										{name : 'updateOrderToken', value : updateOrderToken},
										{name : 'updateInventoryOrderID', value : $(this).attr('update_inventory_order_id') },
										{name : 'updateInventoryOrderName', value : $('#inventoryOrderName').val() },
										{name : 'updateInventoryOrderDate', value : $('#inventoryOrderDate').val() },
										{name : 'updateInventoryOrderAddress', value : $('#inventoryOrderAddress').val() },
										{name : 'updateproductID', value : $('#orderedProduct').val() },
										{name : 'updateProductQuantity', value : $('#orderQuantity').val()},
										{name : 'updatePaymentStatus', value : $('#paymentStatus').val()},
										{name : 'updateInventoryOrderStatus', value :orderStatus }
									 ];
				$.ajax({
					url : 'order_manage.php',
					method : 'POST',
					data : updateOrderData,	
					success:function(data){
						$('#orderModal').modal('hide');
						$('#orderFormModal')[0].reset();
						$('#alert_action').html(data);
						$('#orderModal .modal-footer #orderSubmit').attr('disabled', false);
						ordertListData.ajax.reload();
					}
				});
			});
			$(document).on('click', '.deleteOrder', function(){
				addOrderToken = '<?php echo hash('sha256', 'asdfgh'); ?>';
			    updateOrderToken = '<?php echo hash('sha256', '123456'); ?>'
			    deleteOrderToken = '<?php echo hash('sha256', 'delete_order_token'); ?>'
				$('#deleteOrderModal').modal('show');
				$('#deleteOrderSubmit').attr('delete_order_id',$(this).attr('id'));
				$('#orderModal .modal-footer #orderSubmit').removeAttr('update_inventory_order_id');
				$('#orderModal .modal-footer #orderSubmit').removeClass('btn-success addOrderSubmit');
				$('#orderModal .modal-footer #orderSubmit').removeClass('btn-warning updateOrderSubmit');
			});
			$(document).on('click', '#deleteOrderSubmit', function(event){
				event.preventDefault();
				const deleteOrderData = [{name :'deleteOrderToken', value : deleteOrderToken},{name : 'deleteOrderID', value: $(this).attr('delete_order_id')}];
				$.ajax({
					url : 'order_manage.php',
					method : 'POST',
					data : deleteOrderData,
					success:function(data){
						$('#deleteOrderModal').modal('hide');
						$('#alert_action').html(data);
						$(this).attr('disabled', false);
						ordertListData.ajax.reload();
					}
				});
			});
		});
	</script>

<?php require_once '../templates/footer.php'; ?>
