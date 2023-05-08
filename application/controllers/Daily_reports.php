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
		$expenses_amounts=$this->Daily_report->expense_amount($startDate, $endDate);
		$total_sales=$this->Daily_report->total_sales($startDate, $endDate);
		//var_dump($total_sales);
		$response = [
			'purchase_cash' => $purchase_cash,
			'purchase_bank' => $purchase_bank,
			'expenses_amounts' => $expenses_amounts,
			'total_sales' => $total_sales
		];
		
		echo json_encode($response);
		exit;
    }

	}
?>
