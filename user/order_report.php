<?php


if(isset($_GET["pdf"]) && isset($_GET['order_id']))
{
 require_once 'pdf.php';
 require_once '../database/database_connection.php';
 require_once '../admin/helper.php';
 if(!isset($_SESSION['type']))
 {
  header('location:login.php');
 }
 $output = '';
 $statement = $conn->prepare("
  SELECT * FROM inventory_order 
  WHERE inventoryOrderID = :inventoryOrderID
  LIMIT 1
 ");
 $statement->execute(
  array(
   ':inventoryOrderID'  =>  $_GET["order_id"]
  )
 );
 $result = $statement->fetchAll();
 foreach($result as $row)
 {
  $output .= '
  <table width="100%" border="1" cellpadding="5" cellspacing="0">
   <tr>
    <td colspan="2" align="center" style="font-size:18px"><b>Invoice</b></td>
   </tr>
   <tr>
    <td colspan="2">
    <table width="100%" cellpadding="5">
     <tr>
      <td width="65%">
       To,<br />
       <b>RECEIVER (BILL TO)</b><br />
       Name : '.$row["inventoryOrderName"].'<br /> 
       Billing Address : '.$row["inventoryOrderAddress"].'<br />
      </td>
      <td width="35%">
       Reverse Charge<br />
       Invoice No. : '.$row["inventoryOrderID"].'<br />
       Invoice Date : '.$row["inventoryOrderDate"].'<br />
      </td>
     </tr>
    </table>
    <br />
    <table width="100%" border="1" cellpadding="5" cellspacing="0">
     <tr>
      <th rowspan="2">Sr No.</th>
      <th rowspan="2">Product</th>
      <th rowspan="2">Quantity</th>
      <th rowspan="2">Price</th>
      <th colspan="2">Tax (%)</th>
      <th rowspan="2">Total</th>
     </tr>
     <tr>
      <th>Rate</th>
      <th>Amt.</th>
     </tr>
  ';
  $statement = $conn->prepare("
   SELECT * FROM inventory_order_product 
   WHERE inventoryOrderID = :inventoryOrderID
  ");
  $statement->execute(
   array(
    ':inventoryOrderID'       =>  $_GET["order_id"]
   )
  );
  $product_result = $statement->fetchAll();
  $count = 0;
  $total = 0;
  $total_actual_amount = 0;
  $total_tax_amount = 0;
  foreach($product_result as $sub_row)
  {
   $count = $count + 1;
   $product_data = getProductByID($sub_row['productID'], $conn);
   $actual_amount = $sub_row["quantity"] * $sub_row["price"];
   $tax_amount = $sub_row["tax"];
   $taxPercent = (100 * $tax_amount)/$actual_amount;
   $total_product_amount = $actual_amount + $tax_amount;
   $output .= '
    <tr>
     <td>'.$count.'</td>
     <td>'.$product_data[3].'</td>
     <td>'.$sub_row["quantity"].'</td>
     <td aling="right">'.convertToRupiah($sub_row["price"]).'</td>
     <td>'.$taxPercent.'%</td>
     <td align="right">'.convertToRupiah($tax_amount).'</td>
     <td align="right">'.convertToRupiah($total_product_amount).'</td>
    </tr>
   ';
  }
  $output .= '
      </table>
      <br />
      <br />
      <br />
      <br />
      <br />
      <br />
      <p align="right">----------------------------------------<br />Receiver Signature</p>
      <br />
      <br />
      <br />
     </td>
    </tr>
   </table>
  ';
 }
 $pdf = new Pdf();
$file_name = 'Order-'.$row["inventoryOrderName"].'-'.$row['inventoryOrderID'].'.pdf';
$pdf->loadHtml($output);
$pdf->render();
$pdf->stream($file_name, array("Attachment" => false));
}
?>
