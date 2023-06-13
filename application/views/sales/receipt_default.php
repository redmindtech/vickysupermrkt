<div id="receipt_wrapper" style="font-size:<?php echo $this->config->item('receipt_font_size');?>px">
	<div id="receipt_header">
		<?php
		if($this->config->item('company_logo') != '')
		{
		?>
			<div id="company_name">
				
			</div>
		<?php
		}
		?>

		<?php
		if($this->config->item('receipt_show_company_name'))
		{
		?>
			<div id="company_name" style="font-size:22px;"><?php echo "VICKY SUPERMARKET"; ?></div>
		<?php
		}
		?>

		<div id="company_address" style="font-size:16px;"><b><?php echo "Aranthangi Road,Thirichitrambalam"; ?></b></div>
		<div id="company_gst_no" style="font-size:16px;"><b><?php echo "GST.No:33CGBPC7978L1Z1"; ?></b></div>
		<div id="company_contact" style="font-size:16px;"><b><?php echo "Mobile:6374770571"; ?></b></div>
		
	</div>

	<div id="receipt_general_info">
		<?php 
		$date_time = explode(" ",$transaction_time);
		?>
		<div id="sale_time" style="text-align:right;"><b><?php echo $date_time[0] ?></b></div>
		<div id="sale_time" style="text-align:right;"><b><?php echo $date_time[1] ?></b></div>
		<!-- <?php
		if(isset($customer))
		{
		?>
			<div id="customer"><?php echo $this->lang->line('customers_customer').": ".$customer; ?></div>
		<?php
		}
		?> -->


		<?php
		if(!empty($invoice_number))
		{
		?>
			<div id="invoice_number"><?php echo $this->lang->line('sales_invoice_number').": ".$invoice_number; ?></div>
		<?php
		}
		?>

		<div style="display:flex;">
		<div>
		<div id="employee"><b><?php echo "User"; ?></b></div>
		<div id="sale_id"><b><?php echo "Counter &nbsp;&nbsp;&nbsp;&nbsp;"; ?></b></div>
		</div>
		<div>
		<div id="employee"><b><?php echo $employee; ?></b></div>
		<div id="sale_id"><b><?php echo $sale_id; ?></b></div>
		</div>
		<div>
			<?php
		if(isset($customer))
		{
		?>
		<div id="customer"><b><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; NAME:".$customer; ?></b></div>
		<div id="sale_id"><b><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CODE: &nbsp;&nbsp;&nbsp;"; ?></b></div>

		<?php
		}
		?>
		</div>
		</div>

		
	</div>

	<table id="receipt_items">
		<!-- TABLE HEADERS -->
		<tr id="table_headers">
			<th style="width:50%;border-style: dotted;border-left: none;border-right: none;"><?php echo $this->lang->line('sales_itemname'); ?></th>
			<th style="width:10%;border-style: dotted;border-left: none;border-right: none;text-align: center;"><?php echo $this->lang->line('sales_mrp'); ?></th>
			<th style="width:10%;border-style: dotted;border-left: none;border-right: none;text-align: center;"><?php echo $this->lang->line('sales_rate'); ?></th>
			<th style="width:10%;border-style: dotted;border-left: none;border-right: none;text-align: center;"><?php echo $this->lang->line('sales_qty'); ?></th>
			<th style="width:20%;border-style: dotted;border-left: none;border-right: none;" class="total-value"><?php echo $this->lang->line('sales_amt'); ?></th>
			<?php
			if($this->config->item('receipt_show_tax_ind'))
			{
			?>
				<th style="width:20%;"></th>
			<?php
			}
			?>
		</tr>
		<?php
		$items_number = 0;
		$qty_count = 0;
		$saved_amt = 0;
		?>


<?php

$final_counter = 0;
$i_name = array();
$i_attribute_value = array();
$i_mrp_price = array();
$i_price = array();
$i_quantity = array();
$i_amount = array();
$i_quantity_total = 0;
$i_amount_total = 0;
foreach($cart as $line=>$item)
		{
			if($item['print_option'] == PRINT_YES)
			{
			?>
			
			
					<?php 
					//echo ucfirst($item['name'] . ' ' . $item['attribute_values']); 
					//echo $item['mrp_price']; 
					//echo $item['price'];

					$saved_amt =$saved_amt + ($item['quantity'] * $item['mrp_price']) -($item['quantity'] * $item['price']);	

					//echo $item['quantity'];

					$items_number++; 
					$qty_count = $qty_count + $item['quantity'];

					//echo $item[($this->config->item('receipt_show_total_discount') ? 'total' : 'discounted_total')]; 
					array_push($i_name,$item['name']);
					array_push($i_attribute_value,$item['attribute_values']);
					array_push($i_mrp_price,$item['mrp_price']);
					array_push($i_price,$item['price']);
					array_push($i_quantity,$item['quantity']);
					array_push($i_amount,$item[($this->config->item('receipt_show_total_discount') ? 'total' : 'discounted_total')]);
					
					 ?>
					
				

				
				
					
				<?php
				
			}

			$final_counter++;
		}
		?>


<?php
// for($i=0;$i<count($i_quantity);$i++){
// echo $i_name[$i]." ";
// echo $i_attribute_value[$i]." ";
// echo $i_mrp_price[$i]." ";
// echo $i_price[$i]." ";
// echo $i_quantity[$i]." ";
// echo $i_amount[$i]." ";
// echo "<br>";
// echo "<br>";
// }
?>


<?php
//$splice_pos_arr is used to store indexes of repetitive items and prices.
$splice_pos_arr = array();
//this for loop is to iterate every item
for($i = 0 ; $i< count($i_name);$i++){
	//take one item and compare with all the remaining items	
	for($j = $i+1 ; $j <count($i_name);$j++){	
		//if item name and price match then store the index of the item name.		
		if(strcmp($i_name[$i],$i_name[$j])==0 && $i_price[$i]==$i_price[$j] && $i_mrp_price[$i]==$i_mrp_price[$j]){						
			array_push($splice_pos_arr,$j);		
		}
	}
	//add all the prices of repetitive items
	for($m = 0; $m < count($splice_pos_arr); $m++){
		$i_quantity[$i] = $i_quantity[$i] + $i_quantity[$splice_pos_arr[$m]];
		$i_amount[$i] = $i_amount[$i] + $i_amount[$splice_pos_arr[$m]];
	}
	

	//remove repetitive items from from back
	$splice_pos_arr = array_reverse($splice_pos_arr);
	if(count($splice_pos_arr) > 0){
		for($k = 0; $k < count($splice_pos_arr); $k++){
			array_splice($i_name,$splice_pos_arr[$k],1);
			array_splice($i_mrp_price,$splice_pos_arr[$k],1);
			array_splice($i_price,$splice_pos_arr[$k],1);
			array_splice($i_quantity,$splice_pos_arr[$k],1);
			array_splice($i_amount,$splice_pos_arr[$k],1);
			
        }
		//re initialize $splice_pos_arr variable
		array_splice($splice_pos_arr,0,count($splice_pos_arr));
	}	
}
//print item name and price
for($l = 0; $l < count($i_name); $l++){
	//echo $i_name[$l] ." ".$i_mrp_price[$l]." ".$i_price[$l]." ".$i_quantity[$l]." ".$i_amount[$l];
	//echo "<br>";
	$i_quantity_total = $i_quantity_total + $i_quantity[$l];
	$i_amount_total = $i_amount_total + $i_amount[$l];

}

//echo $i_quantity_total." ".$i_amount_total;
?>









		<?php
		$for_counter =0;
		foreach($cart as $line=>$item)
		{
			if($item['print_option'] == PRINT_YES)
			{
			if($for_counter<1)
			{
			for($z=0; $z <count($i_name); $z++)
			{
			?>
			<!-- TABLE CONTENTS -->
				<tr id="table_contents">
					<td><?php echo ucfirst($i_name[$z]); ?></td>
					<td style="text-align: center;"><?php echo $i_mrp_price[$z]; ?></td>
					<td style="text-align: center;"><?php echo $i_price[$z];
					
					 ?></td>
					<td style="text-align: center;"><?php echo $i_quantity[$z];
					
					 ?></td>
					<td class="total-value"><?php echo number_format((float)$i_amount[$z], 2, '.', '');
					 ?></td>
					
				</tr>	
				
				<?php
			}
			}
				
			}
			$for_counter++;
			
		}
		?>

		<?php
		if($this->config->item('receipt_show_total_discount') && $discount > 0)
		{
		?>
		<!-- 3333 -->
		
		<!-- 4444 -->
			
		<?php
		}
		?>

		

		<tr>
		<td style="border-style:dotted;border-left:none;border-right:none;border-top:none;"></td>
		<td style="border-style:dotted;border-left:none;border-right:none;border-top:none;"></td>
		<td style="border-style:dotted;border-left:none;border-right:none;border-top:none;"></td>
		<td style="border-style:dotted;border-left:none;border-right:none;border-top:none;"></td>
		<td style="border-style:dotted;border-left:none;border-right:none;border-top:none;"></td>
		</tr>
		<tr>
			<td colspan="5"></td>
		<tr>
		<tr>
		<td style="text-align: center"><b><?php echo "Tot.Qty: &nbsp;&nbsp;".$i_quantity_total; ?></b></td>
		<td style="text-align: center" colspan="3"><b><?php echo "Tot Items: &nbsp;&nbsp;".count($i_name); ?></b></td>
		
		<td></td>
		</tr>

		<?php $border = (!$this->config->item('receipt_show_taxes') && !($this->config->item('receipt_show_total_discount') && $discount > 0)); ?>
		<!-- 7777 -->
		<tr id="7">
			
			<td colspan="5" style="text-align:right;border-style: dotted;border-left: none;border-right: none;font-size:26px;"><b><?php echo "TOTAL:".to_currency($i_amount_total); ?></b></td>
		</tr>

		<!-- 8888 -->
		<tr id="8">
			<td colspan="5">&nbsp;</td>
		</tr>

		<?php
		$only_sale_check = FALSE;
		$show_giftcard_remainder = FALSE;
		foreach($payments as $payment_id=>$payment)
		{
			$only_sale_check |= $payment['payment_type'] == $this->lang->line('sales_check');
			$splitpayment = explode(':', $payment['payment_type']);
			$show_giftcard_remainder |= $splitpayment[0] == $this->lang->line('sales_giftcard');
		?>
			<!-- 9999 -->
			
		<?php
		}
		?>

		<!-- 10 -->
		<tr id="10">
			<td colspan="5">&nbsp;</td>
		</tr>

		<?php
		if(isset($cur_giftcard_value) && $show_giftcard_remainder)
		{
		?>
			<!-- 11 -->
			<tr id="11">
				<td colspan="4" style="text-align:right;"><?php echo $this->lang->line('sales_giftcard_balance'); ?></td>
				<td class="total-value"><?php echo to_currency($cur_giftcard_value); ?></td>
			</tr>
		<?php
		}
		?>
		
	</table>

	<div id="receipt_header">
		

		
		<div id="ddd" style="font-size:18px;"><b><?php echo "You have saved Rs.".$saved_amt; ?></b></div>

		<table id="receipt_items">
			<tr id="receipt_headers">
				<th style="width:100%;border-style:dotted;border-left:none;border-right:none;border-top:none;"></th>
			<tr>
			<tr id="table_contents">
				<td></td>				
			</tr>
		</table>
		

		<div id="company_address" style="font-size:22px;"><b><?php echo "..........Thank You.........."; ?></b></div>
		<div id="company_gst_no" style="font-size:22px;"><b><u><?php echo "Come Again"; ?></u></b></div>
		
	</div>



	
</div>