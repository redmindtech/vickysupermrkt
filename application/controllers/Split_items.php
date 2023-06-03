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
	public function view($unique_id = -1)
	{
		$data['split_items_info'] = $this->Split_item->get_info($unique_id);

		$this->load->view("split_items/form", $data);
	}


	//Save new form
	public function save($id = -1)
	{

		$new_item_id = $this->input->post('new_item_id');
		// log_message('debug',print_r('item_data '.$new_item_id,TRUE));

		$item_id = NEW_ITEM;

		$newdate = $this->input->post('receivings_date');

		$date_formatter = date_create_from_format($this->config->item('dateformat'), $newdate);

		
		$expire_date = $this->input->post('new_expire_date');
		$expire_date_formatter = date_create_from_format($this->config->item('dateformat') . ' ' . $this->config->item('timeformat'), $expire_date);

		
		if($new_item_id == null){
			log_message('debug',print_r('if success '.$new_item_id,TRUE));

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
			'hsn_code' => $this->input->post('hsn_code'),
			
		);

		// log_message('debug',print_r('item_data '.$item_data,TRUE));
		if($this->Item->save($item_data, $item_id))
		{
			$success = TRUE;
			$new_item = FALSE;

			if($item_id == NEW_ITEM)
			{
				$item_id = $item_data['item_id'];
				$new_item = TRUE;
			}

			// Split Item tables Split item information Save

			$split_item_data = array(
				'receiving_id' => $this->input->post('receiving_id'),
				'item_id' => $this->input->post('old_item_id'),
				'receivings_date' => $date_formatter->format('Y-m-d'),
				'new_item_name' => $item_id,
				// 'quantity_in_hand' => $this->input->post('quantity_in_hand'),
				// 'receivings_no_split' => $this->input->post('receivings_no_split'),
				'receivings_no_of_pack_split' => $this->input->post('receivings_no_of_pack_split'),
				'no_of_packing_split' => $this->input->post('no_of_packing_split'),
				'stock_qty' => $this->input->post('no_of_packing_split'),
				'split_type' => $this->input->post('split_type'),
				'new_cost_price' => $this->input->post('new_cost_price'),
				'new_unit_price' => $this->input->post('new_unit_price'),
				'new_mrp_price' => $this->input->post('new_mrp_price'),
				'category' => $this->input->post('category'),
				'hsn_code' => $this->input->post('hsn_code'),
				'description' => $this->input->post('description'),
				'expire_date' => $expire_date_formatter->format('Y-m-d H:i:s'),
				
			);
			// log_message('debug',print_r('split_item_data '.$split_item_data,TRUE));
			$success &= $this->Split_item->save($split_item_data, $id);

			$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
			
			// Inventory tables Split item information Save

			$location_id = '1';
			$inv_data = array(
				'trans_date' => date('Y-m-d H:i:s'),
				'trans_items' => $item_id,
				'trans_user' => $employee_id,
				'trans_location' => $location_id,
				'trans_comment' => 'Split Item Information',
				'trans_inventory' => parse_quantity($this->input->post('no_of_packing_split'))
			);

	
			$this->Inventory->insert($inv_data);

			// Item Quantity tables Split item information Save

			$item_quantity_data = array(
				'item_id' => $item_id,
				'location_id' => $location_id,
				'quantity' =>parse_quantity($this->input->post('no_of_packing_split')),
				'stock_qty' =>parse_quantity($this->input->post('no_of_packing_split'))
			);
	
			$this->Item_quantity->save($item_quantity_data, $item_id, $location_id);

			// Item taxes table tax information save

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

			
			$success &= $this->Item_taxes->save($items_taxes_data, $item_id);


			/* Item quantity and inventory quantity and 
			Receving quantity are updated accordingly based Split quantity based on */

			$receiving_id = $this->input->post('receiving_id');
			$old_item_id = $this->input->post('old_item_id');
			$line = $this->input->post('line');

			// $update_qty_in_hand = $this->input->post('quantity_in_hand') - $this->input->post('receivings_no_split');

			$no_pack_kg_split = $this->input->post('split_items_no_of_pack_kg') - $this->input->post('receivings_no_of_pack_split');

			$receiving_data = array(				
				// 'quantity_purchased' => $update_qty_in_hand,
				'stock_qty' => $no_pack_kg_split,			
			);
			
			$success &= $this->Split_item->update($receiving_id, $old_item_id, $line, $receiving_data,$data);
			
			$item_quantity = $this->Item_quantity->get_item_quantity($old_item_id, $location_id);
			log_message('debug',print_r('inv_data '.$item_quantity->quantity,TRUE));
			log_message('debug',print_r('old_item_id '.$old_item_id,TRUE));
			// Split item quantity minus
			$data_quantity = array(
				'item_id' => $old_item_id,
				'location_id' => $location_id,
				'quantity' => $item_quantity->quantity - parse_quantity($this->input->post('receivings_no_of_pack_split'))
			);
	
			$this->Split_item->update_stock_qty($data_quantity, $old_item_id, $location_id);


			$inv_data = array(
				'trans_date' => date('Y-m-d H:i:s'),
				'trans_items' => $old_item_id,
				'trans_user' => $employee_id,
				'trans_location' => $location_id,
				'trans_comment' => 'Items'.' '. parse_quantity($this->input->post('receivings_no_of_pack_split')) . ' '. 'Quantities Splitted',
				'trans_inventory' => -parse_quantity($this->input->post('receivings_no_of_pack_split'))
			);

			$this->Inventory->insert($inv_data);

			// New master_category_id
			if($item_id == -1)
			{
				echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('hsn_code_successful_adding'), 'id' => $item_data['item_id']));	
			}
			// Existing master Category
			else 
			{
				echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('hsn_code_successful_update'), 'id' => $item_id));
			}
		}
	
		//failure
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('hsn_code_error_adding_updating') . ' ' . $item_data['item_id'], 'id' => -1));
		}
	}

	// Exists item name id is available quantity only update

	else {
		log_message('debug',print_r('else success '.$new_item_id,TRUE));

		$new_item_id = $this->input->post('new_item_id');

		$split_item_data = array(
			'receiving_id' => $this->input->post('receiving_id'),
			'item_id' => $this->input->post('old_item_id'),
			'receivings_date' => $date_formatter->format('Y-m-d'),
			'new_item_name' => $new_item_id,
			// 'quantity_in_hand' => $this->input->post('quantity_in_hand'),
			// 'receivings_no_split' => $this->input->post('receivings_no_split'),
			'receivings_no_of_pack_split' => $this->input->post('receivings_no_of_pack_split'),
			'no_of_packing_split' => $this->input->post('no_of_packing_split'),
			'stock_qty' => $this->input->post('no_of_packing_split'),
			'split_type' => $this->input->post('split_type'),
			'new_cost_price' => $this->input->post('new_cost_price'),
			'new_unit_price' => $this->input->post('new_unit_price'),
			'new_mrp_price' => $this->input->post('new_mrp_price'),
			'category' => $this->input->post('category'),
			'hsn_code' => $this->input->post('hsn_code'),
			'description' => $this->input->post('description'),
			'expire_date' => $expire_date_formatter->format('Y-m-d H:i:s'),
			
		);
		
		if($this->Split_item->save($split_item_data, $id))
		{
			$split_item_data = $this->xss_clean($split_item_data);

			$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
			
			// Inventory tables Split item information Save

			$location_id = '1';
			$inv_data = array(
				'trans_date' => date('Y-m-d H:i:s'),
				'trans_items' => $new_item_id,
				'trans_user' => $employee_id,
				'trans_location' => $location_id,
				'trans_comment' => 'Items'.' '. parse_quantity($this->input->post('receivings_no_of_pack_split')) . ' '. 'Quantities Splitted',
				'trans_inventory' => parse_quantity($this->input->post('no_of_packing_split'))
			);

			$this->Inventory->insert($inv_data);

			// Get Item Quantity Data

			$item_quantity = $this->Item_quantity->get_item_quantity($new_item_id, $location_id);
			log_message('debug',print_r('inv_data '.$item_quantity->quantity,TRUE));
			log_message('debug',print_r('old_item_id '.$old_item_id,TRUE));
			
			
			// Split item quantity minus
			$new_data_quantity = array(
				'item_id' => $new_item_id,
				'location_id' => $location_id,
				'quantity' => $item_quantity->quantity + parse_quantity($this->input->post('no_of_packing_split'))
			);
	
			$this->Split_item->exiest_update_stock_qty($new_data_quantity, $new_item_id, $location_id);

			// Exiest update
			// Recevings tables subtracted 
			$receiving_id = $this->input->post('receiving_id');
			$old_item_id = $this->input->post('old_item_id');
			$line = $this->input->post('line');

			// $update_qty_in_hand = $this->input->post('quantity_in_hand') - $this->input->post('receivings_no_split');

			$no_pack_kg_split = $this->input->post('split_items_no_of_pack_kg') - $this->input->post('receivings_no_of_pack_split');

			$receiving_data = array(				
				// 'quantity_purchased' => $update_qty_in_hand,
				'stock_qty' => $no_pack_kg_split,			
			);
			
			$success &= $this->Split_item->update($receiving_id, $old_item_id, $line, $receiving_data,$data);


			$item_quantity = $this->Item_quantity->get_item_quantity($old_item_id, $location_id);
			log_message('debug',print_r('inv_data '.$item_quantity->quantity,TRUE));
			log_message('debug',print_r('old_item_id '.$old_item_id,TRUE));
			// Split item quantity minus
			$data_quantity = array(
				'item_id' => $old_item_id,
				'location_id' => $location_id,
				'quantity' => $item_quantity->quantity - parse_quantity($this->input->post('receivings_no_of_pack_split'))
			);
	
			$this->Split_item->update_stock_qty($data_quantity, $old_item_id, $location_id);

			$inv_data = array(
				'trans_date' => date('Y-m-d H:i:s'),
				'trans_items' => $old_item_id,
				'trans_user' => $employee_id,
				'trans_location' => $location_id,
				'trans_comment' => 'Items'.' '. parse_quantity($this->input->post('receivings_no_of_pack_split')) . ' '. 'Quantities Splitted',
				'trans_inventory' => -parse_quantity($this->input->post('receivings_no_of_pack_split'))
			);

			$this->Inventory->insert($inv_data);

			// New master_category_id
			if($id == -1)
			{
				echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('hsn_code_successful_adding'), 'id' => $split_item_data['id']));	
			}
			// Existing master Category
			else 
			{
				echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('hsn_code_successful_update'), 'id' => $id));
			}
		}
		//failure
		// else
		// {
		// 	echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('hsn_code_error_adding_updating') . ' ' . $hsn_code_data['hsn_code'], 'id' => -1));
		// }
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

	public function get_item_quantity_stocl($new_item_id)
    {

        $result = $this->Split_item->get_item_quantity_stocl($new_item_id);
        $pending = 0;
        if ($result != null) {
            foreach ($result as $row) {
               
				
				// $pending = 
				$hsn_code_tax = $row->stock_qty;
				
				// var_dumb($pending);
				
            }
        }
        if  ($hsn_code_tax == null || $result == null) {
            $hsn_code_tax = 0;
			
        }
        echo ($hsn_code_tax);
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
