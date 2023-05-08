<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Item_category class
 */

class Daily_report extends CI_Model
{
// purchase by cash
   public function purchase_cash($startDate, $endDate){
    $this->db->select('p.first_name, SUM(r.purchase_amount) as purchase_amount');
    $this->db->from('receivings r');
    $this->db->join('people p', 'r.supplier_id = p.person_id');
    $this->db->where('r.receiving_time BETWEEN "'.$startDate.'" AND "'.$endDate.'"');
    $this->db->where('r.payment_type =','cash');
    $this->db->group_by('p.person_id');
    $query = $this->db->get();
    return $query->result();    
   }
//    purchase by bank
   public function purchase_bank($startDate, $endDate){
    $this->db->select('p.first_name, SUM(r.purchase_amount) as purchase_amount');
    $this->db->from('receivings r');
    $this->db->join('people p', 'r.supplier_id = p.person_id');
    $this->db->where('r.receiving_time BETWEEN "'.$startDate.'" AND "'.$endDate.'"');
    $this->db->where_in('r.payment_type', ['upi', 'card']);
    $this->db->group_by('p.person_id');
    $query = $this->db->get();
    return $query->result();    
   }
// expense
public function expense_amount($startDate, $endDate){
   
   //  $query = $this->db->get();
   //  return $query->result();    
   }
	}
?>
