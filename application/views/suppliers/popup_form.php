<style>
	#supplier_table thead tr {
		padding:5px;
		background:#0ad;
		color:#fff;

	}
	#supplier_table, #supplier_table td, #supplier_table th{
		
		padding:5px;
		border:1px solid #999 !important;
	}
	#supplier_table{
		width:100%;
		margin-top: 20px;
	}
	#supplier_table{
		width:100%;
		margin-top: 20px;
	}

	@media (min-width: 768px)
{
	.modal-dlg .modal-dialog
	{
		width: 750px !important;
	}
}
</style>
<?php
	if ($controller_name == 'suppliers' && $supplier != NULL)
	{
	?>



<table id="supplier_table" class="table table-striped table-hover">
<thead>
		<tr bgcolor="#CCC">
			
			
			<th><?php echo $this->lang->line('supplier_date'); ?></th>
			<th><?php echo $this->lang->line('supplier_balance'); ?></th>
			<th><?php echo $this->lang->line('supplier_purchase_amount'); ?></th>
			<th><?php echo $this->lang->line('supplier_paid_amount'); ?></th>
			<!-- <th><?php //echo $this->lang->line('supplier_rate_difference'); ?></th> -->
			<th><?php echo $this->lang->line('supplier_payment_mode'); ?></th>
			<!-- <th><?php //echo $this->lang->line('supplier_purchase_return_amount'); ?></th> -->
			<!-- <th><?php //echo $this->lang->line('supplier_less'); ?></th> -->
			<th><?php echo $this->lang->line('supplier_closing_balance'); ?></th>

		</tr>
	</thead>
	<tbody>
	
	
	<?php
		foreach($supplier as $supplier)
		{
		?>
		<tr>
		
	<td><?php echo substr_replace($supplier['receiving_time'] ,"", -8); ?></td>
<td><?php echo $supplier['opening_balance']; ?></td>
<td><?php echo $supplier['purchase_amount']; ?></td>
<td><?php echo $supplier['paid_amount']; ?></td>

<td><?php echo $supplier['payment_type']; ?></td>

<!-- <td><?php //echo $supplier['discount']; ?></td> -->
<td><?php echo $supplier['closing_balance']; ?></td>



		</tr>
	<?php
		}
		?>

</table>



<div class="supplier_div">
<table id="supplier_table" >
	<thead>
		<tr>
			<td colspan="4" style="text-align: center;">Summary</td>
		</tr>
	</thead>
	<tbody>

			<?php
			foreach($supplier_summary as $supplier_summary)
			{
			?>
				<tr>
				<th><?php echo $this->lang->line('total_purchase'); ?></th>
				<td><?php echo $supplier_summary['purchase_amount'];?></td>
				
				
				</tr>
				<tr>
				<?php
				foreach($cheque as $cheque)
				{
				?>
					<th><?php echo $this->lang->line('total_card'); ?></th>
					<?php if(!empty($cheque['paid_amount'])){?>
					<td><?php echo $cheque['paid_amount'];?></td>
				<?php
					}
					else{
						?>
						<td>0.00</td>
						<?php
					}
				}
				?>
				
				<!-- <th><?php //echo $this->lang->line('total_less'); ?></th>
				<td><?php //echo $supplier_summary['discount'];?></td></tr> -->

				</tr>

				<tr>
				<th><?php echo $this->lang->line('total_return'); ?></th>
				<?php foreach($supplier_return as $supplier_return){
				
				if(!empty($supplier_return['paid_amount'])){
				?>
					<td><?php echo $supplier_return['paid_amount'];?></td>
				<?php 
			}
			else{
				?>
				<td>0.00</td>
				<?php
			}
			}?>
			
				
				</tr>

				<tr>
				<th><?php echo $this->lang->line('total_cash'); ?></th>
				<?php
				foreach($cash as $cash)
				{
					if(!empty($cash['paid_amount']))
					{
				?>
					<td><?php echo $cash['paid_amount'];?></td>
				<?php
				}
				else{
				?>
				<td>0.00</td>
				<?php
				}
				}
				?>
			<?php
			}
			?>

			<th><?php echo $this->lang->line('last_opening_balance'); ?></th>
				<?php foreach($new_supplier_open_bal as $new_supplier_open_bal){
				
				if(!empty($new_supplier_open_bal['opening_balance'])){
				?>
					<td><?php echo $new_supplier_open_bal['opening_balance'];?></td>
				<?php 
			}
			else{
				?>
				<td>0.00</td>
				<?php
			}
			}?>



			
			
		</tr>


		<tr>
				<th><?php echo $this->lang->line('total_upi'); ?></th>
				<?php foreach($upi as $upi){
				
				if(!empty($upi['paid_amount'])){
				?>
					<td><?php echo $upi['paid_amount'];?></td>
				<?php 
			}
			else{
				?>
				<td>0.00</td>
				<?php
			}
			}?>
			<th><?php echo $this->lang->line('last_closing_balance'); ?></th>
			<?php foreach($new_supplier_close_bal as $new_supplier_close_bal){
				
				if($new_supplier_close_bal['closing_balance']){
				?>
				<td><?php echo $new_supplier_close_bal['closing_balance'];?></td>
			<?php }
			else{
				?>
				<td>0.00</td>
				<?php
			}
		}?>
				
				</tr>




		



	</tbody>
</table>
</div>

<?php
	}else{
		$null_message = "This supplier has no transaction";
	?>
	
	<h3><center><?php echo $null_message; ?></center></h3>

	<?php
	}
	?>



