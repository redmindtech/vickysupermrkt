<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Item_category class
 */

class item_hsn_code extends CI_Model
{
	/*
	Determines if a given item_category_id is an Item_category
	*/
	public function exists($id)
	{
		$this->db->from('item_hsn_code');
		$this->db->where('id', $id);

		return ($this->db->get()->num_rows() == 1);
	}


	public function check_category_name_exists($hsn_code, $id = '')
	{
		// if the email is empty return like it is not existing
		if(empty($hsn_code))
		{
			return FALSE;
		}

		$this->db->from('item_hsn_code');
		// $this->db->join('people', 'people.person_id = customers.person_id');
		$this->db->where('item_hsn_code.hsn_code', $hsn_code);
		$this->db->where('item_hsn_code.deleted', 0);

		if(!empty($id))
		{
			$this->db->where('item_hsn_code.id !=', $id);
		}

		return ($this->db->get()->num_rows() == 1);
	}

	/*
	Gets total of rows
	*/
	public function get_total_rows()
	{
		$this->db->from('item_hsn_code');
		$this->db->where('deleted', 0);

		return $this->db->count_all_results();
	}

	/*
	Gets information about a particular category
	*/
	public function get_info($id)
	{
		$this->db->from('item_hsn_code');
		$this->db->where('id', $id);
		$this->db->where('deleted', 0);
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
			foreach($this->db->list_fields('item_hsn_code') as $field)
			{
				$item_hsn_code_obj->$field = '';
			}

			return $item_hsn_code_obj;
		}
	}

	/*
	Returns all the item_categories
	*/
	public function get_all($rows = 0, $limit_from = 0, $no_deleted = FALSE)
	{
		$this->db->from('item_hsn_code');
		if($no_deleted == TRUE)
		{
			$this->db->where('deleted', 0);
		}

		$this->db->order_by('hsn_code', 'asc');

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
		$this->db->from('item_hsn_code');
		$this->db->where_in('id', $id);
		$this->db->order_by('hsn_code', 'asc');

		return $this->db->get();
	}

	/*
	Inserts or updates an item_category
	*/
	public function save(&$hsn_code_data, $id = FALSE)
	{
		if(!$id || !$this->exists($id))
		{
			if($this->db->insert('item_hsn_code', $hsn_code_data))
			{
				$hsn_code_data['id'] = $this->db->insert_id();

				return TRUE;
			}

			return FALSE;
		}

		$this->db->where('id', $id);

		return $this->db->update('item_hsn_code', $hsn_code_data);
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
	public function get_found_rows($search)
	{
		return $this->search($search, 0, 0, 'hsn_code', 'asc', TRUE);
	}

	/*
	Perform a search on item_category
	*/
	public function search($search, $rows = 0, $limit_from = 0, $sort = 'hsn_code', $order='asc', $count_only = FALSE)
	{
		// get_found_rows case
		if($count_only == TRUE)
		{
			$this->db->select('COUNT(item_hsn_code.id) as count');
		}

		$this->db->from('item_hsn_code AS item_hsn_code');
		$this->db->group_start();
		$this->db->or_like('hsn_code', $search);
		$this->db->or_like('tax_percentage', $search);
		$this->db->group_end();
		$this->db->where('deleted', 0);

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
