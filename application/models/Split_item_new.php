<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Item_category class
 */

class Split_item_new extends CI_Model
{
	/*
	Determines if a given item_category_id is an Item_category
	*/
	public function exists($id)
	{
		$this->db->from('split_items');
		$this->db->where('id', $id);

		return ($this->db->get()->num_rows() == 1);
	}


	// public function check_category_name_exists($hsn_code, $id = '')
	// {
	// 	// if the email is empty return like it is not existing
	// 	if(empty($hsn_code))
	// 	{
	// 		return FALSE;
	// 	}

	// 	$this->db->from('split_items');
	// 	// $this->db->join('people', 'people.person_id = customers.person_id');
	// 	$this->db->where('split_items.hsn_code', $hsn_code);
	// 	$this->db->where('split_items.deleted', 0);

	// 	if(!empty($id))
	// 	{
	// 		$this->db->where('split_items.id !=', $id);
	// 	}

	// 	return ($this->db->get()->num_rows() == 1);
	// }

	/*
	Gets total of rows
	*/
	public function get_total_rows()
	{
		$this->db->from('split_items');
		

		return $this->db->count_all_results();
	}

	/*
	Gets information about a particular category
	*/
	public function get_info($id)
	{
		$this->db->from('split_items');
		$this->db->where('id', $id);
		
		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $item_kit_id is NOT an item kit
			$item_hsn_code_obj = new stdClass();

			//Get all the fields from items table
			foreach($this->db->list_fields('split_items') as $field)
			{
				$item_hsn_code_obj->$field = '';
			}

			return $item_hsn_code_obj;
		}
	}

	/*
	Returns all the item_categories
	*/
	// public function get_all($rows = 0, $limit_from = 0, $no_deleted = FALSE)
	// {
	// 	$this->db->from('split_items');
	// 	if($no_deleted == TRUE)
	// 	{
	// 		$this->db->where('deleted', 0);
	// 	}

	// 	$this->db->order_by('hsn_code', 'asc');

	// 	if($rows > 0)
	// 	{
	// 		$this->db->limit($rows, $limit_from);
	// 	}

	// 	return $this->db->get();
	// }

	/*
	Gets information about multiple item_master_id 
	*/
	public function get_multiple_info($id)
	{
		$this->db->from('split_items');
		$this->db->where_in('id', $id);
		

		return $this->db->get();
	}

	/*
	Inserts or updates an item_category
	*/
	// public function save(&$hsn_code_data, $id = FALSE)
	// {
	// 	if(!$id || !$this->exists($id))
	// 	{
	// 		if($this->db->insert('split_items', $hsn_code_data))
	// 		{
	// 			$hsn_code_data['id'] = $this->db->insert_id();

	// 			return TRUE;
	// 		}

	// 		return FALSE;
	// 	}

	// 	$this->db->where('id', $id);

	// 	return $this->db->update('split_items', $hsn_code_data);
	// }

	/*
	Deletes a list of item_category
	// */
	// public function delete_list($id)
	// {
	// 	$this->db->where_in('id', $id);

	// 	return $this->db->update('split_items', array('deleted' => 1));
 	// }

	/*
	Gets rows
	*/
	public function get_found_rows($search, $filters)
	{
		return $this->search_split($search, $filters, 0, 0, 'new_item_name', 'asc', TRUE);
	}

	/*
	Perform a search on item_category
	*/
	public function search_split($search, $filters, $rows = 0, $limit_from = 0, $sort = 'new_item_name', $order='asc', $count_only = FALSE)
	{
		// get_found_rows case
		if($count_only == TRUE)
		{
			$this->db->select('COUNT(split_items.id) as count');
		}

		$this->db->from('split_items AS split_items');
		$this->db->group_start();
		$this->db->or_like('new_item_name', $search);
		$this->db->group_end();
		
		if(empty($this->config->item('date_or_time_format')))
		{
			$this->db->where('DATE_FORMAT(split_items.receivings_date, "%Y-%m-%d") BETWEEN ' . $this->db->escape($filters['start_date']) . ' AND ' . $this->db->escape($filters['end_date']));
		}
		else
		{
			$this->db->where('split_items.receivings_date BETWEEN ' . $this->db->escape(rawurldecode($filters['start_date'])) . ' AND ' . $this->db->escape(rawurldecode($filters['end_date'])));
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
	// public function get_hsn_percent($hsn)
	// {
	// 	$this->db->select('tax_percentage');
	// 		$this->db->from('split_items');
	// 		$this->db->where('hsn_code',$hsn);
	// 		$query = $this->db->get();			
	// 		$per_= $query->result();
	// 		return $per_;
	// }
}
?>
