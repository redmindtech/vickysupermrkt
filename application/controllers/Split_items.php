<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Split_items extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('Split_items');
	}

	public function index()
	{
		 $data['table_headers'] = $this->xss_clean(get_split_items_manage_table_headers());

		 $this->load->view('split_items/manage', $data);
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

		$filters = array(
			'start_date' => $this->input->get('start_date'),
			'end_date' => $this->input->get('end_date'));

		$filters = array_merge($filters);
		$split_items = $this->Split_item->search($search, $filters, $limit, $offset, $sort, $order);
		$total_rows = $this->Split_item->get_found_rows($search, $filters);

		//var_dump($offset);

		$count = $offset+1;

		$data_rows = array();
		foreach($split_items->result() as $split_items)
		{
			$data_rows[] = $this->xss_clean(get_split_item_data_row($split_items, $count));
			$count++;
		}
		
		// var_dump($data_row);
		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows));
	}

   //Get row
	public function get_row($row_id)
	{
		$data_row = $this->xss_clean(get_split_item_data_row($this->Split_item->get_info($row_id)));
		
        echo json_encode($data_row);
	}

     //View new form
	public function view($receiving_id = -1)
	{
		$data['split_items_info'] = $this->Split_item->get_info($receiving_id);

		$this->load->view("split_items/form", $data);
	}
	//Save new form
	public function save($id = -1)
	{

		$newdate = $this->input->post('receivings_date');

		$date_formatter = date_create_from_format($this->config->item('dateformat'), $newdate);


		$expire_date = $this->input->post('expire_date');

		$expire_date_formatter = date_create_from_format($this->config->item('dateformat'), $expire_date);


		// log_message('debug',print_r('sabefore'.$newdate,TRUE));
		// log_message('debug',print_r('savebefore'.$date_formatter,TRUE));
		$split_item_data = array(
			'receiving_id' => $this->input->post('receiving_id'),
			'item_id' => $this->input->post('old_item_id'),
			'receivings_date' => $date_formatter->format('Y-m-d'),
			'new_item_name' => $this->input->post('new_item_name'),
			'quantity_in_hand' => $this->input->post('quantity_in_hand'),
			'receivings_no_split' => $this->input->post('receivings_no_split'),
			'receivings_no_of_pack_split' => $this->input->post('receivings_no_of_pack_split'),
			'no_of_packing_split' => $this->input->post('no_of_packing_split'),
			'split_type' => $this->input->post('split_type'),
			'new_cost_price' => $this->input->post('new_cost_price'),
			'new_unit_price' => $this->input->post('new_unit_price'),
			'new_mrp_price' => $this->input->post('new_mrp_price'),
			'category' => $this->input->post('category'),
			'hsn_code' => $this->input->post('hsn_code'),
			'description' => $this->input->post('description'),
			'expire_date' => $expire_date_formatter->format('Y-m-d'),
		);

		log_message('debug',print_r($split_item_data,TRUE));

		$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		
		if($this->Split_item->save($split_item_data, $id))
		{
			$split_item_data = $this->xss_clean($split_item_data);

			// New master_category_id
			
		$item_data = array(
			'name' => $this->input->post('new_item_name'),
			'description' => $this->input->post('description'),
			'category' => $this->input->post('category'),
			'item_type' => '0',
			'stock_type' => '0',
			'supplier_id' => empty($this->input->post('supplier_id')) ? NULL : intval($this->input->post('supplier_id')),
			'item_number' => empty($this->input->post('item_number')) ? NULL : $this->input->post('item_number'),
			'cost_price' => parse_decimals($this->input->post('new_cost_price')),
			'unit_price' => parse_decimals($this->input->post('new_unit_price')),
			'mrp_price' => parse_decimals($this->input->post('new_mrp_price')),
			'batch_no' => $this->input->post('batch_no'),
			'expire_date' => $expire_date_formatter->format('Y-m-d H:i:s'),
			'reorder_level' => '1',
			'receiving_quantity' => $this->input->post('no_of_packing_split'),
			'allow_alt_description' => '0',
			'is_serialized' => '0',
			'qty_per_pack' => '1',
			'pack_name' =>'Each',
			'tax_category_id' => '0',
			'low_sell_item_id' => $this->input->post('old_item_id'),
			'deleted' => '0',
			'hsn_code' => $this->input->post('hsn_code')
		);
		log_message('debug',print_r($item_data,TRUE));

		$item_id = NEW_ITEM;

		if($this->Item->save($item_data, $item_id))
		{
			
			$success = TRUE;
			$new_item = FALSE;

			if($item_id == NEW_ITEM)
			{
				$item_id = $item_data['item_id'];
				$new_item = TRUE;
			}
		}

		$use_destination_based_tax = (boolean)$this->config->item('use_destination_based_tax');

			if(!$use_destination_based_tax)
			{
				$items_taxes_data = [];
				$tax_names = $this->input->post('tax_names');
				$tax_percents = $this->input->post('tax_percents');

				$tax_name_index = 0;

				foreach($tax_percents as $tax_percent)
				{
					$tax_percentage = parse_tax($tax_percent);

					if(is_numeric($tax_percentage))
					{
						$items_taxes_data[] = array('name' => $tax_names[$tax_name_index], 'percent' => $tax_percentage);
					}

					$tax_name_index++;
				}
				log_message('debug',print_r($items_taxes_data,TRUE));

				
				$success &= $this->Item_taxes->save($items_taxes_data, $item_id);

				$stock_locations = $this->Stock_location->get_undeleted_all()->result_array();
			foreach($stock_locations as $location)
			{
				$updated_quantity = parse_quantity($this->input->post('no_of_packing_split_' . $location['location_id']));

				if($item_data['item_type'] == ITEM_TEMP)
				{
					$updated_quantity = 0;
				}

				$location_detail = array(
						'item_id' => $item_id,
						'location_id' => $location['location_id'],
						'quantity' => $this->input->post('no_of_packing_split'),
					    'stock_qty'=> $this->input->post('no_of_packing_split'));

						log_message('debug',print_r($location_detail,TRUE));

				$item_quantity = $this->Item_quantity->get_item_quantity($item_id, $location['location_id']);

				if($item_quantity->quantity != $updated_quantity || $new_item)
				{
					$success &= $this->Item_quantity->save($location_detail, $item_id, $location['location_id']);

					$inv_data = array(
						'trans_date' => date('Y-m-d H:i:s'),
						'trans_items' => $item_id,
						'trans_user' => $employee_id,
						'trans_location' => $location['location_id'],
						'trans_comment' => $this->lang->line('items_manually_editing_of_quantity'),
						'trans_inventory' => $updated_quantity - $item_quantity->quantity
					);
					log_message('debug',print_r($inv_data,TRUE));
					$success &= $this->Inventory->insert($inv_data);
				}
			}

				if($id == -1)
			{
				echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('hsn_code_successful_adding'), 'id' => $split_item_data['id']));	
			}
		
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
		$exists = $this->Split_item->check_category_name_exists(strtolower($this->input->post('split_items')), $this->input->post('id'));

		echo !$exists ? 'true' : 'false';
	}


	public function suggest_category()
	{
		$suggestions = $this->xss_clean($this->Item->get_category_suggestions($this->input->get('term')));

		echo json_encode($suggestions);
	}

	public function suggest_item_name()
	{
		$suggestions = $this->xss_clean($this->Item->get_item_name_suggestions($this->input->get('term')));

		echo json_encode($suggestions);
	}



    //Delete data from formtable
	public function delete()
	{
		$item_hsn_code_to_delete = $this->input->post('ids');

		if($this->Split_item->delete_list($item_hsn_code_to_delete))
		{
			echo json_encode(array('success' => TRUE, 'message' => count($item_hsn_code_to_delete) . ' ' . $this->lang->line('hsn_code_successful_delete') ));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('hsn_code_cannot_be_deleted')));
		}
	}
	// public function get_percent($hsn)
	// {
	// 	// $percent=$this->Item_hsn_code->get_hsn_percent($hsn);
	// 	log_message('debug',print_r($hsn,true));
	// 	// var_dump($percent);
	// 	// return $percent;
	// }
}
?>
