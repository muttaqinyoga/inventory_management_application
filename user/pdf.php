<?php  
	require_once '../database/database_connection.php';
	if(!isset($_SESSION['type']))
	{
		header("Location: ../login.php");
		die;
	}
	require_once '../admin/helper.php';
	require_once 'dompdf/autoload.inc.php';
	use Dompdf\Dompdf;

	class Pdf extends Dompdf
	{
		public function __construct()
		{
			parent::__construct();
		}
	}

	
?>