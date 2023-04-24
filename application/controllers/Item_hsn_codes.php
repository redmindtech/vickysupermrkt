<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Item_hsn_codes extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('Item_hsn_codes');
	}

	public function index()
	{
		 $data['table_headers'] = $this->xss_clean(get_hsn_codes_manage_table_headers());

		 $this->load->view('item_hsn_code/manage', $data);
	}

	/*
	Returns Item_category_manage table data rows. This will be called with AJAX.
	*/
	public function search()
	{
		$search = $this->input->get('search');
		$limit  = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$sort   = $this->input->get('sort');
		$order  = $this->input->get('order');
		$hsn_codes = $this->Item_hsn_code->search($search, $limit, $offset, $sort, $order);
		$total_rows = $this->Item_hsn_code->get_found_rows($search);

		//var_dump($offset);

		$count = $offset+1;

		$data_rows = array();
		foreach($hsn_codes->result() as $hsn_codes)
		{
			$data_rows[] = $this->xss_clean(get_hsn_codes_data_row($hsn_codes, $count));
			$count++;
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows));
	}

   //Get row
	public function get_row($row_id)
	{
		$data_row = $this->xss_clean(get_hsn_codes_data_row($this->Item_hsn_code->get_info($row_id)));
		
        echo json_encode($data_row);
	}

     //View new form
	public function view($id = -1)
	{
		$data['item_hsn_code_info'] = $this->Item_hsn_code->get_info($id);

		$this->load->view("item_hsn_code/form", $data);
	}
	//Save new form
	public function save($id = -1)
	{
		
		$hsn_code_data = array(
			'hsn_code' => $this->input->post('hsn_code'),
			'tax_percentage' => $this->input->post('tax_percentage'),
		);
		
		if($this->Item_hsn_code->save($hsn_code_data, $id))
		{
			$hsn_code_data = $this->xss_clean($hsn_code_data);

			// New master_category_id
			if($id == -1)
			{
				echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('hsn_code_successful_adding'), 'id' => $hsn_code_data['id']));	
			}
			// Existing master Category
			else 
			{
				echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('hsn_code_successful_update'), 'id' => $id));
			}
		}
		//failure
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('hsn_code_error_adding_updating') . ' ' . $hsn_code_data['hsn_code'], 'id' => -1));
		}
	}

	public function ajax_check_item_category_name()
	{
		$exists = $this->Item_hsn_code->check_category_name_exists(strtolower($this->input->post('hsn_code')), $this->input->post('id'));

		echo !$exists ? 'true' : 'false';
	}

    //Delete data from formtable
	public function delete()
	{
		$item_hsn_code_to_delete = $this->input->post('ids');

		if($this->Item_hsn_code->delete_list($item_hsn_code_to_delete))
		{
			echo json_encode(array('success' => TRUE, 'message' => count($item_hsn_code_to_delete) . ' ' . $this->lang->line('hsn_code_successful_delete') ));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('hsn_code_cannot_be_deleted')));
		}
	}
}
?>
