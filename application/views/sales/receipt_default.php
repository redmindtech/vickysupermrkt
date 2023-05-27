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
			<th style="width:40%;border-style: dotted;border-left: none;border-right: none;"><?php echo $this->lang->line('sales_itemname'); ?></th>
			<th style="width:10%;border-style: dotted;border-left: none;border-right: none;"><?php echo $this->lang->line('sales_mrp'); ?></th>
			<th style="width:10%;border-style: dotted;border-left: none;border-right: none;"><?php echo $this->lang->line('sales_rate'); ?></th>
			<th style="width:20%;border-style: dotted;border-left: none;border-right: none;"><?php echo $this->lang->line('sales_qty'); ?></th>
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
		foreach($cart as $line=>$item)
		{
			if($item['print_option'] == PRINT_YES)
			{
			?>
			
			<!-- TABLE CONTENTS -->
				<tr id="table_contents">
					<td><?php echo ucfirst($item['name'] . ' ' . $item['attribute_values']); ?></td>
					<td><?php echo to_currency($item['mrp_price']); ?></td>
					<td><?php echo to_currency($item['price']);
					$saved_amt =$saved_amt + ($item['quantity'] * $item['mrp_price']) -($item['quantity'] * $item['price']);
					
					 ?></td>
					<td><?php echo to_quantity_decimals($item['quantity']);
					$items_number++; 
					$qty_count = $qty_count + $item['quantity'];
					 ?></td>
					<td class="total-value"><?php echo to_currency($item[($this->config->item('receipt_show_total_discount') ? 'total' : 'discounted_total')]); ?></td>
					<?php
					if($this->config->item('receipt_show_tax_ind'))
					{
					?>
						<td><?php echo $item['taxed_flag'] ?></td>
					<?php
					}
					?>
				</tr>

				
				<?php
				if($item['discount'] > 0)
				{
				?>


				<!-- 2222 -->
					<tr id="2">
						<?php
						if($item['discount_type'] == FIXED)
						{
						?>
							<td colspan="4" class="discount"><?php echo to_currency($item['discount']) . " " . $this->lang->line("sales_discount") ?></td>
						<?php
						}
						elseif($item['discount_type'] == PERCENT)
						{
						?>
							<td colspan="4" class="discount"><?php echo to_decimals($item['discount']) . " " . $this->lang->line("sales_discount_included") ?></td>
						<?php
						}	
						?>
						<td class="total-value"><?php echo to_currency($item['discounted_total']); ?></td>
					</tr>
				<?php
				}
			}
		}
		?>

		<?php
		if($this->config->item('receipt_show_total_discount') && $discount > 0)
		{
		?>
		<!-- 3333 -->
			<tr id="3">
				<td colspan="4" style='text-align:right;border-top:2px solid #000000;'><?php echo $this->lang->line('sales_sub_total'); ?></td>
				<td style='text-align:right;border-top:2px solid #000000;'><?php echo to_currency($prediscount_subtotal); ?></td>
			</tr>
		<!-- 4444 -->
			<tr id="4">
				<td colspan="4" class="total-value"><?php echo $this->lang->line('sales_customer_discount'); ?>:</td>
				<td class="total-value"><?php echo to_currency($discount * -1); ?></td>
			</tr>
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
		<td style="text-align: center"><b><?php echo "Tot.Qty: &nbsp;&nbsp;".$items_number; ?></b></td>
		<td style="text-align: center" colspan="2"><b><?php echo "Tot Items: &nbsp;&nbsp;".$qty_count; ?></b></td>
		<td></td>
		<td></td>
		</tr>

		<?php $border = (!$this->config->item('receipt_show_taxes') && !($this->config->item('receipt_show_total_discount') && $discount > 0)); ?>
		<!-- 7777 -->
		<tr id="7">
			<td colspan="4" style="text-align:right;border-style: dotted;border-left: none;border-right: none;font-size:26px;"><b><?php echo "TOTAL:"; ?></b></td>
			<td style="text-align:right;border-style: dotted;border-left: none;border-right: none;font-size:26px;"><b><?php echo to_currency($total); ?></b></td>
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
			<tr id="9">
				<td colspan="4" style="text-align:right;"><?php echo $splitpayment[0]; ?> </td>
				<td class="total-value"><?php echo to_currency( $payment['payment_amount'] * -1 ); ?></td>
			</tr>
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