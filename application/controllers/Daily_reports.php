<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Daily_reports extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('Daily_reports');
	}

	public function index()
	{
		 $this->load->view('daily_reports/reports');
	}

	public function supplier_details()
    {
        $startDate = $_GET['start_date'];
        $endDate = $_GET['end_date'];       
		$purchase_cash=$this->Daily_report->purchase_cash($startDate, $endDate);
		$purchase_bank=$this->Daily_report->purchase_bank($startDate, $endDate);
		// $expense=$this->Daily_report->expense_amount($startDate, $endDate);
		$response = [
			'purchase_cash' => $purchase_cash,
			'purchase_bank' => $purchase_bank
		];
		
		echo json_encode($response);
		exit;
    }

	}
?>
