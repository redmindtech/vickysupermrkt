<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Item_category class
 */

class Split_item extends CI_Model
{
	/*
	Determines if a given item_category_id is an Item_category
	*/
	public function exists($unique_id)
	{
		$this->db->from('receivings_items');
		$this->db->where('unique_id', $unique_id);

		return ($this->db->get()->num_rows() == 1);
	}


	public function check_category_name_exists($new_item_name, $id = '')
	{
		// if the email is empty return like it is not existing
		if(empty($new_item_name))
		{
			return FALSE;
		}

		$this->db->from('split_items');
		// $this->db->join('people', 'people.person_id = customers.person_id');
		$this->db->where('split_items.new_item_name', $new_item_name);
		// $this->db->where('split_items.deleted', 0);

		if(!empty($id))
		{
			$this->db->where('split_items.id !=', $id);
		}

		return ($this->db->get()->num_rows() == 1);
	}

	/*
	Gets total of rows
	*/
	public function get_total_rows()
	{
		$this->db->from('receivings_items');
		

		return $this->db->count_all_results();
	}

	/*
	Gets information about a particular category
	*/
	public function get_info($unique_id)
	{
			$this->db->select('receivings.receiving_id AS receiving_id');
			$this->db->select('receivings.receiving_time AS receiving_time');
			$this->db->select('receivings.reference AS reference');
			$this->db->select('receivings.other_charges AS other_charges');


			$this->db->select('receivings_items.unique_id AS unique_id');
			$this->db->select('receivings_items.quantity_purchased AS quantity_purchased');
			$this->db->select('receivings_items.item_cost_price AS item_cost_price');
			$this->db->select('receivings_items.item_unit_price AS item_unit_price');
			$this->db->select('receivings_items.mrp_price AS mrp_price');
			$this->db->select('receivings_items.expire_date AS expire_date');
			$this->db->select('receivings_items.stock_qty AS stock_qty');
			$this->db->select('receivings_items.line AS line');

			$this->db->select('MAX(item_hsn_code.tax_percentage) AS tax_percentage');

			$this->db->select('items.item_id AS item_id');
			$this->db->select('items.name AS name');
			$this->db->select('items.category AS category');
			$this->db->select('items.supplier_id AS supplier_id');
			$this->db->select('items.item_number AS item_number');
			$this->db->select('items.description AS description');
			$this->db->select('items.cost_price AS cost_price');
			$this->db->select('items.unit_price AS unit_price');
			$this->db->select('items.reorder_level AS reorder_level');
			$this->db->select('items.receiving_quantity AS receiving_quantity');
			$this->db->select('items.hsn_code AS hsn_code');
			$this->db->select('items.expire_date AS old_expire_date');

				$this->db->select('MAX(item_quantities.item_id) AS qty_item_id');
				$this->db->select('MAX(item_quantities.location_id) AS location_id');
				$this->db->select('MAX(item_quantities.quantity) AS quantity');
				$this->db->select('MAX(item_quantities.stock_qty) AS item_quantities_stock_qty');
			

			

		$this->db->from('receivings');
		$this->db->join('receivings_items AS receivings_items', 'receivings_items.receiving_id = receivings.receiving_id', 'inner');
		$this->db->join('items AS items', 'items.item_id = receivings_items.item_id', 'left');
		$this->db->join('item_quantities AS item_quantities', 'item_quantities.item_id = receivings_items.item_id');
		$this->db->join('item_hsn_code AS item_hsn_code', 'item_hsn_code.hsn_code = items.hsn_code');
		// $this->db->where('location_id', $filters['stock_location_id']);
		
		
		$this->db->where('receivings_items.unique_id', $unique_id);
		$this->db->group_by('receivings_items.unique_id');
		// $this->db->where('deleted', 0); split_items
		$query = $this->db->get();
		// var_dump( $item_hsn_code_obj);
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $item_kit_id is NOT an item kit
			$split_items_obj = new stdClass();

			//Get all the fields from items table
			foreach($this->db->list_fields('receivings') as $field)
			{
				$split_items_obj->$field = '';
			}
			// var_dump( $split_items_obj);

			return $split_items_obj;
		}
	}

	public function get_item_quantity_stocl($new_item_id)
		{	
			$this->db->select ('item_quantities.stock_qty') ;
			$this->db->from ('item_quantities'); 
			$this->db->where('item_quantities.item_id',$new_item_id);
			$query=$this->db->get();		
			$hsn_code_tax=$query->result();
	    	return  $hsn_code_tax;

			
		}


	public function unit_price_get($new_item_id)
		{	
			$this->db->select ('items.unit_price') ;
			$this->db->from ('items'); 
			$this->db->where('items.item_id',$new_item_id);
			$query=$this->db->get();		
			$hsn_code_tax=$query->result();

	    	return  $hsn_code_tax;	
		}

		public function old_expire_date_data_get($new_item_id)
    {
        $this->db->select('items.expire_date');
        $this->db->from('items');
        $this->db->where('items.item_id', $new_item_id);
        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }
		

	/*
	Returns all the item_categories
	*/
	public function get_all($rows = 0, $limit_from = 0, $no_deleted = FALSE)
	{
		$this->db->from('receivings_items');
		if($no_deleted == TRUE)
		{
			// $this->db->where('deleted', 0);
		}

		$this->db->order_by('receiving_time', 'desc');

		if($rows > 0)
		{
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();
	}

	/*
	Gets information about multiple item_master_id 
	*/
	public function get_multiple_info($id)
	{
		$this->db->from('receivings_items');
		$this->db->where_in('unique_id', $unique_id);
		$this->db->order_by('receiving_time', 'desc');

		return $this->db->get();
	}

	// Receving updates update stock_qty

	public function update($receiving_id, $old_item_id, $line, $receiving_data, $data)
	{
		$this->db->where_in('receiving_id', $receiving_id);
		$this->db->where_in('item_id', $old_item_id);
		$this->db->where_in('line', $line);

		
		return $this->db->update('receivings_items', $receiving_data);
 	}

	 public function update_stock_qty($data_quantity, $old_item_id, $location_id)
	 {		
		 $this->db->where_in('item_id', $old_item_id);
		
		 return $this->db->update('item_quantities', $data_quantity);
	  }


	  public function exiest_update_stock_qty($new_data_quantity, $new_item_id, $location_id)
	  {		
		  $this->db->where_in('item_id', $new_item_id);
		 
		  return $this->db->update('item_quantities', $new_data_quantity);
	   }

	  public function update_split_item_quantity($new_item_id, $new_item_update)
	  {		
		  $this->db->where_in('item_id', $new_item_id);
		 
		  return $this->db->update('items', $new_item_update);
	   }
	

	/*
	Inserts or updates an item_category
	*/
	public function save(&$split_item_data, $id = FALSE)
	{
		
		// if(!$id || !$this->exists($id))
		// {
			if($this->db->insert('split_items', $split_item_data))
			{
				$split_item_data['id'] = $this->db->insert_id();
				// log_message('debug',print_r('model '.$split_item_data,TRUE));
				return TRUE;
			}

			return FALSE;
		// }

		$this->db->where('id', $id);

		return $this->db->update('receivings_items', $receiving_data);
	}

	/*
	Deletes a list of item_category
	*/
	public function delete_list($id)
	{
		$this->db->where_in('id', $id);

		return $this->db->update('item_hsn_code', array('deleted' => 1));
 	}

	/*
	Gets rows
	*/
	public function get_found_rows($search, $filters)
	{
		return $this->search($search, $filters, 0, 0, 'receivings_items.unique_id', 'desc', TRUE);
	}

	/*
	Perform a search on item_category
	*/
	public function search($search, $filters, $rows = 0, $limit_from = 0, $sort = 'receivings_items.unique_id', $order='asc', $count_only = FALSE)
	{
		// get_found_rows case
		if($count_only == TRUE)
		{
			$this->db->select('COUNT(receivings_items.unique_id) as count');
			$this->db->select('receivings.receiving_id AS receiving_id');
			$this->db->select('receivings.receiving_time AS receiving_time');

			
			$this->db->select('receivings_items.quantity_purchased AS quantity_purchased');
			$this->db->select('receivings_items.item_cost_price AS item_cost_price');
			$this->db->select('receivings_items.item_unit_price AS item_unit_price');
			$this->db->select('receivings_items.mrp_price AS mrp_price');
			$this->db->select('receivings_items.expire_date AS expire_date');
			$this->db->select('receivings_items.stock_qty AS stock_qty');


			$this->db->select('items.item_id AS item_id');
			$this->db->select('items.name AS name');
			$this->db->select('items.category AS category');
			$this->db->select('items.supplier_id AS supplier_id');
			$this->db->select('items.item_number AS item_number');
			$this->db->select('items.description AS description');
			$this->db->select('items.cost_price AS cost_price');
			$this->db->select('items.unit_price AS unit_price');
			$this->db->select('items.reorder_level AS reorder_level');
			// $this->db->select('items.receiving_quantity AS receiving_quantity');
			$this->db->select('items.hsn_code AS hsn_code');
		}

		$this->db->from('receivings_items AS receivings_items');
		$this->db->join('receivings AS receivings', 'receivings_items.receiving_id = receivings.receiving_id', 'inner');
		$this->db->join('items AS items', 'items.item_id = receivings_items.item_id', 'left');
		$this->db->order_by('receivings_items.unique_id', 'desc');
		$this->db->group_start();
		$this->db->or_like('receivings_items.receiving_id', $search);
		$this->db->or_like('receivings_items.item_id', $search);
		$this->db->group_end();
		$this->db->where('stock_qty >', 0);

		if(empty($this->config->item('date_or_time_format')))
		{
			$this->db->where('DATE_FORMAT(receivings.receiving_time, "%Y-%m-%d") BETWEEN ' . $this->db->escape($filters['start_date']) . ' AND ' . $this->db->escape($filters['end_date']));
		}
		else
		{
			$this->db->where('receivings.receiving_time BETWEEN ' . $this->db->escape(rawurldecode($filters['start_date'])) . ' AND ' . $this->db->escape(rawurldecode($filters['end_date'])));
		}

		// get_found_rows case
		if($count_only == TRUE)
		{
			return $this->db->get()->row()->count;
		}

		$this->db->order_by($sort, $order);

		if($rows > 0)
		{
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();
	}

	/*
	Gets information about a particular category
	*/
	public function get_info_split($unique_id)
	{
			$this->db->select('receivings.receiving_id AS receiving_id');
			$this->db->select('receivings.receiving_time AS receiving_time');
			$this->db->select('receivings.reference AS reference');
			$this->db->select('receivings.other_charges AS other_charges');


			$this->db->select('receivings_items.unique_id AS unique_id');
			$this->db->select('receivings_items.quantity_purchased AS quantity_purchased');
			$this->db->select('receivings_items.item_cost_price AS item_cost_price');
			$this->db->select('receivings_items.item_unit_price AS item_unit_price');
			$this->db->select('receivings_items.mrp_price AS mrp_price');
			$this->db->select('receivings_items.expire_date AS expire_date');
			$this->db->select('receivings_items.stock_qty AS stock_qty');
			$this->db->select('receivings_items.line AS line');

			$this->db->select('MAX(item_hsn_code.tax_percentage) AS tax_percentage');

			$this->db->select('items.item_id AS item_id');
			$this->db->select('items.name AS name');
			$this->db->select('items.category AS category');
			$this->db->select('items.supplier_id AS supplier_id');
			$this->db->select('items.item_number AS item_number');
			$this->db->select('items.description AS description');
			$this->db->select('items.cost_price AS cost_price');
			$this->db->select('items.unit_price AS unit_price');
			$this->db->select('items.reorder_level AS reorder_level');
			$this->db->select('items.receiving_quantity AS receiving_quantity');
			$this->db->select('items.hsn_code AS hsn_code');
			$this->db->select('items.expire_date AS old_expire_date');
			//$this->db->select('items.deleted AS old_expire_date');

				$this->db->select('MAX(item_quantities.item_id) AS qty_item_id');
				$this->db->select('MAX(item_quantities.location_id) AS location_id');
				$this->db->select('MAX(item_quantities.quantity) AS quantity');
				$this->db->select('MAX(item_quantities.stock_qty) AS item_quantities_stock_qty');
			

			

		$this->db->from('receivings');
		$this->db->join('receivings_items AS receivings_items', 'receivings_items.receiving_id = receivings.receiving_id', 'inner');
		$this->db->join('items AS items', 'items.item_id = receivings_items.item_id', 'left');
		$this->db->join('item_quantities AS item_quantities', 'item_quantities.item_id = receivings_items.item_id');
		$this->db->join('item_hsn_code AS item_hsn_code', 'item_hsn_code.hsn_code = items.hsn_code');
		// $this->db->where('location_id', $filters['stock_location_id']);
		
		
		$this->db->where('receivings_items.unique_id', $unique_id);
		$this->db->group_by('receivings_items.unique_id');
		// $this->db->where('deleted', 0); split_items
		$query = $this->db->get();
		// var_dump( $item_hsn_code_obj);
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $item_kit_id is NOT an item kit
			$split_items_obj = new stdClass();

			//Get all the fields from items table
			foreach($this->db->list_fields('receivings') as $field)
			{
				$split_items_obj->$field = '';
			}
			// var_dump( $split_items_obj);

			return $split_items_obj;
		}
	}

		/*
	Gets rows
	*/
	public function get_found_rows_split($search, $filters)
	{
		return $this->search($search, $filters, 0, 0, 'receivings_items.unique_id', 'desc', TRUE);
	}

	/*
	Perform a search on item_category
	*/
	public function search_split($search, $filters, $rows = 0, $limit_from = 0, $sort = 'receivings_items.unique_id', $order='asc', $count_only = FALSE)
	{
		// get_found_rows case
		if($count_only == TRUE)
		{
			$this->db->select('COUNT(receivings_items.unique_id) as count');
			$this->db->select('receivings.receiving_id AS receiving_id');
			$this->db->select('receivings.receiving_time AS receiving_time');

			
			$this->db->select('receivings_items.quantity_purchased AS quantity_purchased');
			$this->db->select('receivings_items.item_cost_price AS item_cost_price');
			$this->db->select('receivings_items.item_unit_price AS item_unit_price');
			$this->db->select('receivings_items.mrp_price AS mrp_price');
			$this->db->select('receivings_items.expire_date AS expire_date');
			$this->db->select('receivings_items.stock_qty AS stock_qty');


			$this->db->select('items.item_id AS item_id');
			$this->db->select('items.name AS name');
			$this->db->select('items.category AS category');
			$this->db->select('items.supplier_id AS supplier_id');
			$this->db->select('items.item_number AS item_number');
			$this->db->select('items.description AS description');
			$this->db->select('items.cost_price AS cost_price');
			$this->db->select('items.unit_price AS unit_price');
			$this->db->select('items.reorder_level AS reorder_level');
			// $this->db->select('items.receiving_quantity AS receiving_quantity');
			$this->db->select('items.hsn_code AS hsn_code');
		}

		$this->db->from('receivings_items AS receivings_items');
		$this->db->join('receivings AS receivings', 'receivings_items.receiving_id = receivings.receiving_id', 'inner');
		$this->db->join('items AS items', 'items.item_id = receivings_items.item_id', 'left');
		$this->db->order_by('receivings_items.unique_id', 'desc');
		$this->db->group_start();
		$this->db->or_like('receivings_items.receiving_id', $search);
		$this->db->or_like('receivings_items.item_id', $search);
		$this->db->group_end();
		$this->db->where('stock_qty >', 0);

		if(empty($this->config->item('date_or_time_format')))
		{
			$this->db->where('DATE_FORMAT(receivings.receiving_time, "%Y-%m-%d") BETWEEN ' . $this->db->escape($filters['start_date']) . ' AND ' . $this->db->escape($filters['end_date']));
		}
		else
		{
			$this->db->where('receivings.receiving_time BETWEEN ' . $this->db->escape(rawurldecode($filters['start_date'])) . ' AND ' . $this->db->escape(rawurldecode($filters['end_date'])));
		}

		// get_found_rows case
		if($count_only == TRUE)
		{
			return $this->db->get()->row()->count;
		}

		$this->db->order_by($sort, $order);

		if($rows > 0)
		{
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();
	}
}
?>

