<?php $this->load->view("partial/header"); ?>

<?php
if (isset($error))
{
	echo "<div class='alert alert-dismissible alert-danger'>".$error."</div>";
}

if (!empty($warning))
{
	echo "<div class='alert alert-dismissible alert-warning'>".$warning."</div>";
}

if (isset($success))
{
	echo "<div class='alert alert-dismissible alert-success'>".$success."</div>";
}
?>

<div id="register_wrapper">

<!-- Top register controls -->

	<?php echo form_open($controller_name."/change_mode", array('id'=>'mode_form', 'class'=>'form-horizontal panel panel-default')); ?>
		<div class="panel-body form-group">
			<ul>
				<li class="pull-left first_li">
					<label class="control-label"><?php echo $this->lang->line('receivings_mode'); ?></label>
				</li>
				<li class="pull-left">
					<?php echo form_dropdown('mode', $modes, $mode, array('onchange'=>"$('#mode_form').submit();", 'class'=>'selectpicker show-menu-arrow', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit')); ?>
				</li>
				<li class="pull-right">
				<?php echo anchor($controller_name."/manage", '<span class="glyphicon glyphicon-list-alt">&nbsp</span>' . 'Daily Purchases',
									array('class'=>'btn btn-primary btn-sm', 'id'=>'daily_purchases_button', 'title'=>$this->lang->line('sales_takings'))); ?>
				
				<button id='new_supplier_button' class='btn btn-info btn-sm modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url("suppliers/view"); ?>'
							title='<?php echo $this->lang->line('receivings_new_supplier'); ?>'>
						<span class="glyphicon glyphicon-user">&nbsp</span><?php echo $this->lang->line('receivings_new_supplier'); ?>
					</button>
				</li>
				<?php 
				if ($show_stock_locations)
				{
				?>
					<li class="pull-left">
						<label class="control-label"><?php echo $this->lang->line('receivings_stock_source'); ?></label>
					</li>
					<li class="pull-left">
						<?php echo form_dropdown('stock_source', $stock_locations, $stock_source, array('onchange'=>"$('#mode_form').submit();", 'class'=>'selectpicker show-menu-arrow', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit')); ?>
					</li>
					
					<?php
					if($mode=='requisition')
					{
					?>
						<li class="pull-left">
							<label class="control-label"><?php echo $this->lang->line('receivings_stock_destination'); ?></label>
						</li>
						<li class="pull-left">
							<?php echo form_dropdown('stock_destination', $stock_locations, $stock_destination, array('onchange'=>"$('#mode_form').submit();", 'class'=>'selectpicker show-menu-arrow', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit')); ?>
						</li>
				<?php
					}
				}
				?>
			</ul>
		</div>
	<?php echo form_close(); ?>

	<!-- select supplier name and view details -->

	<?php echo form_open($controller_name."/select_supplier", array('id'=>'select_supplier_form', 'class'=>'form-horizontal')); ?>
	

	<?php
		if(isset($supplier))
		{
		?>
			<table class="sales_table_100">
				<tr>
					<th style='width: 5%;'><?php echo $this->lang->line("receivings_supplier"); ?></th>
					<th style="width: 45%; text-align: left;"><?php echo $supplier; ?></th>
				</tr>
				<?php
				if(!empty($supplier_email))
				{
				?>
					<tr>
						<th style='width: 5%;'><?php echo $this->lang->line("receivings_supplier_email"); ?></th>
						<th style="width: 45%; text-align: left;"><?php echo $supplier_email; ?></th>
					</tr>
				<?php
				}
				?>
				<?php
				if(!empty($supplier_address))
				{
				?>
					<tr>
						<th style='width: 5%;'><?php echo $this->lang->line("receivings_supplier_address"); ?></th>
						<th style="width: 45%; text-align: left;"><?php echo $supplier_address; ?></th>
					</tr>
				<?php
				}
				?>
				<?php
				if(!empty($supplier_location))
				{
				?>
					<tr>
						<th style='width: 5%;'><?php echo $this->lang->line("receivings_supplier_location"); ?></th>
						<th style="width: 45%; text-align: left;"><?php echo $supplier_location; ?></th>
					</tr>
				<?php
				}
				?>
			</table>
			
			<button class="btn btn-danger btn-sm" id="remove_supplier_button" title="<?php echo $this->lang->line('common_remove').' '.$this->lang->line('suppliers_supplier')?>">
				<span class="glyphicon glyphicon-remove">&nbsp</span><?php echo $this->lang->line('common_remove').' '.$this->lang->line('suppliers_supplier') ?>
			</button>

		<?php
		}
		else
		{
		?>
				<div class="form-group" id="add_item_form">
					<label id="supplier_label" for="supplier" class="control-label pull-left" style="margin-left: 1em; margin-top: 0.5em;"><?php echo $this->lang->line('receivings_select_supplier'); ?></label>
					<div class='col-xs-6 pull-center'style=' margin-top: 1em; margin-bottom: 0.1em;'>
					<?php echo form_input(array('name'=>'supplier', 'id'=>'supplier','style'=>"margin-left: 1em; margin-bottom: 0.7em;", 'class'=>'form-control input-sm', 'value'=>$this->lang->line('receivings_start_typing_supplier_name'))); ?>
					</div>
				</div>
			
		<?php
		}
		?>
		
<?php echo form_close(); ?>

		<!-- add items -->
	<?php echo form_open($controller_name."/add", array('id'=>'add_item_form', 'name'=>'add_item_form', 'class'=>'form-horizontal panel panel-default')); ?>
		<div class="panel-body form-group">
			<ul>
				<li class="pull-left first_li">
					<label for="item", class='control-label'>
						<?php
						if($mode=='receive' or $mode=='requisition')
						{
						?>
							<?php echo $this->lang->line('receivings_find_or_scan_item'); ?>
						<?php
						}
						else
						{
						?>
							<?php echo $this->lang->line('receivings_find_or_scan_item_or_receipt'); ?>
						<?php
						}
						?>			
					</label>
				</li>
				<li class="pull-left">
					<?php echo form_input(array('name'=>'item', 'id'=>'item', 'class'=>'form-control input-sm', 'size'=>'50', 'tabindex'=>'1')); ?>
				</li>
				<li class="pull-right">
					<button id='new_item_button' class='btn btn-info btn-sm pull-right modal-dlg'
						data-btn-submit='<?php echo $this->lang->line('common_submit') ?>'
						data-btn-new='<?php echo $this->lang->line('common_new') ?>'
						data-href='<?php echo site_url("items/view"); ?>'
						title='<?php echo $this->lang->line('sales_new_item'); ?>'>
						<span class="glyphicon glyphicon-tag">&nbsp</span><?php echo $this->lang->line('sales_new_item'); ?>
					</button>
				</li>
			</ul>
		</div>
	<?php echo form_close(); ?>
	


</div>

<!-- Overall Receiving -->

<div id="overall_sale" class="panel panel-default">
<div class="panel-body" style="max-height: 200px; overflow-y: auto;">	
	

		<table class="sales_table_100">
			<tr>
				<?php
				if($mode != 'requisition')
				{
				?>
					<th style="width: 55%;"><?php echo $this->lang->line('sales_total'); ?></th>
					<th style="width: 45%; text-align: right; font-size:25px; color:red;"><?php echo to_currency($total); ?></th>
				<?php 
				}
				else
				{
				?>
					<th style="width: 55%;"></th>
					<th style="width: 45%; text-align: right;"></th>
				<?php 
				}
				?>
			</tr>
		</table>

		<?php
		if(count($cart) > 0)
		{
		?>
			<div id="finish_sale">
				<?php
				if($mode == 'requisition')
				{
				?>
					<?php echo form_open($controller_name."/requisition_complete", array('id'=>'finish_receiving_form', 'class'=>'form-horizontal')); ?>
						<div class="form-group form-group-sm">
							<label id="comment_label" for="comment"><?php echo $this->lang->line('common_comments'); ?></label>
							<?php echo form_textarea(array('name'=>'comment', 'id'=>'comment', 'class'=>'form-control input-sm', 'value'=>$comment, 'rows'=>'4')); ?>

							<div class="btn btn-sm btn-danger pull-left" id='cancel_receiving_button'><span class="glyphicon glyphicon-remove">&nbsp</span><?php echo $this->lang->line('receivings_cancel_receiving'); ?></div>
							
							<div class="btn btn-sm btn-success pull-right" id='finish_receiving_button'><span class="glyphicon glyphicon-ok">&nbsp</span><?php echo $this->lang->line('receivings_complete_receiving'); ?></div>
						</div>
					<?php echo form_close(); ?>
				<?php
				}
				else
				{
				?>
					<?php echo form_open($controller_name."/complete", array('id'=>'finish_receiving_form', 'class'=>'form-horizontal')); ?>
						<div class="form-group form-group-sm">
							<div id="payment_details" >
								<table class="sales_table_100" >
									<tr>
										<td><?php echo $this->lang->line('receivings_print_after_sale'); ?></td>
										<td>
											<?php echo form_checkbox(array('name'=>'recv_print_after_sale', 'id'=>'recv_print_after_sale', 'class'=>'checkbox', 'value'=>1, 'checked'=>$print_after_sale)); ?>
										</td>
									</tr>
									<?php
									if ($mode == "receive")
									{
									?>
										<tr>
											<td><?php echo $this->lang->line('receivings_batch_no');?></td>
											<td>
												<?php echo form_input(array('name'=>'recv_reference', 'id'=>'recv_reference', 'class'=>'form-control input-sm', 'value'=>$reference, 'size'=>'5'));?>
											</td>
										</tr>
									<?php
									}
									?>
									

									<tr>
										<td><?php echo $this->lang->line('receivings_invoice_no'); ?></td>
										<td>
										
											<?php echo form_input(array('name'=>'invoice_no', 'value'=>'', 'class'=>'form-control input-sm', 'size'=>'12','onClick'=>'this.select();')); ?>
										</td>
									</tr>

									<tr>
										<td><?php echo $this->lang->line('receivings_purchase_amt'); ?></td>
										<td>
										
											<?php echo form_input(array('name'=>'supplier_inv_amount', 'id'=>'supplier_inv_amount', 'value'=>to_currency_no_money(0.00), 'class'=>'form-control input-sm', 'size'=>'12','onClick'=>'this.select();')); ?>
										</td>
									</tr>

									<tr>
										<td><?php echo $this->lang->line('receivings_other_charges'); ?></td>
										<td>
										
											<?php echo form_input(array('name'=>'other_charges', 'id'=>'other_charges', 'value'=>to_currency_no_money(0.00), 'class'=>'form-control input-sm', 'size'=>'12','onClick'=>'this.select();')); ?>
										</td>
									</tr>

									<tr>
										<td><?php echo $this->lang->line('sales_payment'); ?></td>
										<td>
											<?php echo form_dropdown('payment_type', $payment_options, array(), array('id'=>'payment_types', 'class'=>'selectpicker show-menu-arrow', 'data-style'=>'btn-default btn-sm', 'data-width'=>'auto')); ?>
										</td>
									</tr>

									<tr>
										<td><?php echo $this->lang->line('receivings_payed_amount'); ?></td>
										<td>
										
											<?php echo form_input(array('name'=>'paid_amount', 'value'=>to_currency_no_money(0.00), 'class'=>'form-control input-sm', 'size'=>'12','onClick'=>'this.select();')); ?>
										</td>
									</tr>
									<tr>
										<td><?php echo $this->lang->line('receivings_pending_amount'); ?></td>
										<td>
											<?php echo form_input(array('name'=>'due_amount','readonly'=>true,'value'=>to_currency_no_money($new_supplier_close_bal), 'class'=>'form-control input-sm', 'size'=>'5','onClick'=>'this.select();')); ?>
										</td>
									</tr>
									<tr>
										<td><?php echo $this->lang->line('receivings_total_amount'); ?></td>
										<td>
																					
											<?php echo form_input(array('readonly'=>'readonly', 'id'=>'total', 'name'=>'amount_tendered', 'value'=>round($total, 2), 'class'=>'form-control input-sm', 'size'=>'13','onClick'=>'this.select();')); ?>
					
										</td>
									</tr>
								</table>
								<label id="comment_label" for="comment"><?php echo $this->lang->line('common_comments'); ?></label>
							<?php echo form_textarea(array('name'=>'comment', 'id'=>'comment', 'class'=>'form-control input-sm', 'value'=>$comment, 'rows'=>'2'));?>
							
							</div>

							<div class='btn btn-sm btn-danger pull-left' id='cancel_receiving_button'><span class="glyphicon glyphicon-remove">&nbsp</span><?php echo $this->lang->line('receivings_cancel_receiving') ?></div>
							
							<div class='btn btn-sm btn-success pull-right' id='finish_receiving_button'><span class="glyphicon glyphicon-ok">&nbsp</span><?php echo $this->lang->line('receivings_complete_receiving') ?></div>
						</div>
					<?php echo form_close(); ?>
				<?php
				}
				?>
			</div>
		<?php
		}
		?>
	</div>
</div>


<!-- Receiving Items List -->
<!-- <div class="row" name="cart_save"> -->
	<table class="sales_table_100" id="register" >
		<thead>
			<tr>
				
				<th style="width:3%; "><?php echo $this->lang->line('common_delete'); ?></th>
				<th style="width:5%;"><?php echo $this->lang->line('sales_item_number'); ?></th>
				<th style="width:15%;"><?php echo $this->lang->line('receivings_item_name'); ?></th>
				<th style="width:7%;"><?php echo $this->lang->line('receivings_purchase_price'); ?></th>
				<th style="width:7%;"><?php echo $this->lang->line('receivings_sale_price'); ?></th>
				<th style="width:7%;"><?php echo $this->lang->line('receivings_mrp_price'); ?></th>
				<th style="width:7%;"><?php echo $this->lang->line('receivings_roi'); ?></th>
				<th style="width:7%;"><?php echo $this->lang->line('receivings_quantity'); ?></th>
				<th style="width:7%;"><?php echo $this->lang->line('receivings_ship_pack'); ?></th>
				<th style="width:10%;"><?php echo $this->lang->line('receivings_discount'); ?></th>
				<th style="width:11%;"><?php echo $this->lang->line('receivings_expire_date'); ?></th>
				<th style="width:8%;"><?php echo $this->lang->line('receivings_hsn_code_item'); ?></th>
				<th style="width:5%;"><?php echo $this->lang->line('receivings_tax_percentage'); ?></th>
				<!-- <th style="width:6%;"><?php //echo $this->lang->line('receivings_tax_amount'); ?></th> -->
				<th style="width:10%;"><?php echo $this->lang->line('receivings_total'); ?></th>
				<th style="width:5%;"><?php echo $this->lang->line('receivings_update'); ?></th>
			</tr>
		</thead>

		<tbody id="cart_contents">
			<?php
			if(count($cart) == 0)
			{
			?>
				<tr>
					<td colspan='22'>
						<div class='alert alert-dismissible alert-info'><?php echo $this->lang->line('sales_no_items_in_cart'); ?></div>
					</td>
				</tr>
			<?php
			}
			else
			{
				foreach(array_reverse($cart, TRUE) as $line=>$item)
				{
			?>
					<?php echo form_open($controller_name."/edit_item/$line", array('class'=>'form-horizontal', 'id'=>'cart_'.$line)); ?>
					
					<tr>
							<td><span data-item-id="<?php echo $line;?>" class="delete_item_button"><span class="glyphicon glyphicon-trash"></span></span></td>
							<td><?php echo $item['item_id']; ?></td>
							<td style="align:center;">
								<?php echo $item['name'] . ' '. implode(' ', array($item['attribute_values'], $item['attribute_dtvalues'])); ?><br /> <?php echo '[' . round($item['in_stock'],2) . ' in ' . $item['stock_name'] . ']'; ?>
								<?php echo form_hidden('location', $item['item_location']); ?>
							</td>

							<?php 
							// if ($items_module_allowed && $mode !='requisition')
							// {
							?>
								<td><?php echo form_input(array('name'=>'price', 'id'=>'price', 'class'=>'form-control input-sm', 'value'=>to_currency_no_money($item['price']),'onClick'=>'this.select();'));?></td>
							<?php
							// }
							// else
							// {
							?>
								<td>
									<?php //echo $item['unit_price']; ?>
									<?php echo form_input(array('name'=>'unit_price', 'id'=>'unit_price' ,'class'=>'form-control input-sm compare-input', 'value'=>to_currency_no_money($item['unit_price']),'onClick'=>'this.select();')); ?>
								</td>
							<?php
							// }
							?>
							<!-- <td><?php //echo form_input(array('name'=>'sell_price', 'class'=>'form-control input-sm', 'value'=>to_currency_no_money($item['unit_price']),'onClick'=>'this.select();'));?></td> -->
							
							<td><?php echo form_input(array('name'=>'mrp_price', 'id'=>'mrp_price' , 'class'=>'form-control input-sm', 'value'=>to_currency_no_money($item['mrp_price']),'onClick'=>'this.select();'));?></td>
							<td><?php echo form_input(array('name'=>'roi_price',  'id'=>'roi_price' , 'readonly'=>'readonly', 'class'=>'form-control input-sm', 'value'=>to_currency_no_money($item['unit_price']-$item['price']),'onClick'=>'this.select();'));?></td>
							
							<td><?php echo form_input(array('name'=>'quantity', 'class'=>'form-control input-sm', 'value'=>to_quantity_decimals($item['quantity']),'onClick'=>'this.select();')); ?></td>
							<td><?php echo form_input(array('name'=>'receiving_quantity', 'class'=>'form-control input-sm', 'value'=>to_quantity_decimals($item['receiving_quantity']),'onClick'=>'this.select();'));?></td>

							<?php       
							if ($items_module_allowed && $mode!='requisition')
							{
							?>
								<td>
								<div class="input-group">
									<?php echo form_input(array('name'=>'discount', 'class'=>'form-control input-sm', 'value'=>$item['discount_type'] ? to_currency_no_money($item['discount']) : to_decimals($item['discount']), 'onClick'=>'this.select();')); ?>
									<span class="input-group-btn">
										<?php echo form_checkbox(array('id'=>'discount_toggle', 'name'=>'discount_toggle', 'value'=>1, 'data-toggle'=>"toggle",'data-size'=>'small', 'data-onstyle'=>'success', 'data-on'=>'<b>'.$this->config->item('currency_symbol').'</b>', 'data-off'=>'<b>%</b>', 'data-line'=>$line, 'checked'=>$item['discount_type'])); ?>
									</span>
								</div> 
							</td>
							<?php
							}
							else
							{
							?>
								<td><?php echo $item['discount'];?></td>
								<?php echo form_hidden('discount',$item['discount']); ?>
							<?php
							}
							
							?>

							
<?php if (($item['expire_date']) == "NULL" ||  ($item['expire_date'] == "No Expire")): ?>
  <td>
  <?php
    echo form_input(array(
		'readonly'=>'readonly',
      'name' => 'expire_date',
      'id' => 'expire_date',
      'class' => 'form-control input-sm',
      'placeholder' => 'No Expire',
	  'value' => 'No Expire'
    ));
    ?>
    
  </td>
<?php else: ?>
  <td>
  <?php
    echo form_input(array(
      'name' => 'expire_date',
      'id' => 'expire_date',
      'class' => 'form-control input-sm datetime',
      'value' => to_datetime(strtotime($item['expire_date'])),
      'onClick' => 'this.select();'
    ));
    ?> 
  </td>
<?php endif; ?>
					
							<td><?php echo form_input(array('name'=>'hsn_code', 'readonly'=>'readonly', 'id'=>'hsn_code', 'class'=>'form-control input-sm', 'value'=>$item['hsn_code'],'onClick'=>'this.select();'));?></td>
							<td><?php echo $item['tax_percentage']; ?></td>
							<!-- <td><?php //echo to_currency($item['price']*$item['quantity']*$item['receiving_quantity'] * $item['tax_percentage'] /100,3); //$item['tax_percentage']; ?></td> -->
							<td>
							<?php echo to_currency(($item['discount_type'] == PERCENT) ? $item['price']*$item['quantity']*$item['receiving_quantity'] - $item['price'] * $item['quantity'] * $item['receiving_quantity'] * $item['discount'] / 100 : $item['price']*$item['quantity']*$item['receiving_quantity'] - $item['discount']); ?></td> 
							<td><a href="javascript:$('#<?php echo 'cart_'.$line ?>').submit();" title=<?php echo $this->lang->line('receivings_update')?> ><span class="glyphicon glyphicon-refresh"></span></a></td>
						</tr>
						<tr>
							<?php 
							if($item['allow_alt_description']==1)
							{
							?>
								<td style="color: #2F4F4F;"><?php echo $this->lang->line('sales_description_abbrv').':';?></td>
							<?php 
							} 
							?>
							<td colspan='9' style="text-align: left;">
								<?php
								if($item['allow_alt_description']==1)
								{
									echo form_input(array('name'=>'description', 'class'=>'form-control input-sm', 'value'=>$item['description']));
								}
								else
								{
									if ($item['description']!='')
									{
										echo $item['description'];
										echo form_hidden('description',$item['description']);
									}
									else
									{
										echo "<i>".$this->lang->line('sales_no_description')."</i>";
										echo form_hidden('description','');
									}
								}
								?>
							</td>
							<td colspan='7'></td>
						</tr>
					<?php echo form_close(); ?>
			<?php
				}
			}
			?>
		</tbody>
	</table>
	<!-- </div> -->

<script type="text/javascript">
$(document).ready(function()
{

	<?php $this->load->view('partial/datepicker_locale'); ?>

	
	$('#hsn_code').autocomplete({
		source: "<?php echo site_url('items/suggest_hsn_code');?>",
		delay: 10,
		appendTo: '.modal-content'
	});

	const redirect = function() {
		window.location.href = "<?php echo site_url('receivings'); ?>";
	};

	$("#remove_supplier_button").click(function()
	{
		$.post("<?php echo site_url('receivings/remove_supplier'); ?>", redirect);
	});

	$(".delete_item_button").click(function() {
		const item_id = $(this).data('item-id');
		$.post("<?php echo site_url('receivings/delete_item/'); ?>" + item_id, redirect);
	});

	$("#item").autocomplete(
	{
		source: '<?php echo site_url($controller_name."/stock_item_search"); ?>',
		minChars:0,
		delay:10,
		autoFocus: false,
		select:	function (a, ui) {
			$(this).val(ui.item.value);
			$("#add_item_form").submit();
			return false;
		}
	});

	$('#item').focus();

	$('#item').keypress(function (e) {
		if (e.which == 13) {
			$('#add_item_form').submit();
			return false;
		}
	});

	$('#item').blur(function()
	{
		$(this).attr('value',"<?php echo $this->lang->line('sales_start_typing_item_name'); ?>");
	});

	$('#comment').keyup(function() 
	{
		$.post('<?php echo site_url($controller_name."/set_comment");?>', {comment: $('#comment').val()});
	});

	$('#recv_reference').keyup(function() 
	{
		$.post('<?php echo site_url($controller_name."/set_reference");?>', {recv_reference: $('#recv_reference').val()});
	});

	$("#recv_print_after_sale").change(function()
	{
		$.post('<?php echo site_url($controller_name."/set_print_after_sale");?>', {recv_print_after_sale: $(this).is(":checked")});
	});

	$('#item,#supplier').click(function()
	{
		$(this).attr('value','');
	});

	$("#supplier").autocomplete(
	{
		source: '<?php echo site_url("suppliers/suggest"); ?>',
		minChars:0,
		delay:10,
		select: function (a, ui) {
			$(this).val(ui.item.value);
			$("#select_supplier_form").submit();
		}
	});

	

	dialog_support.init("a.modal-dlg, button.modal-dlg");

	$('#supplier').blur(function()
	{
		$(this).attr('value',"<?php echo $this->lang->line('receivings_start_typing_supplier_name'); ?>");
	});

	$("#finish_receiving_button").click(function()
	{
		var supplier_inv_amount = $('#supplier_inv_amount').val();
		var total = $('#total').val();
		
		if(supplier_inv_amount !== total)
		{
			alert('Please Check Invoice Amount and Total Amount');
		}
		else{
		// alert(id1);
		// alert(id2);
		if (confirm("Are you sure you want to save this purchase?"))
		{
			$('#finish_receiving_form').submit();	
		}
		
		}
	});

	$("#cancel_receiving_button").click(function()
	{
		if (confirm('<?php echo $this->lang->line("receivings_confirm_cancel_receiving"); ?>'))
		{
			$('#finish_receiving_form').attr('action', '<?php echo site_url($controller_name."/cancel_receiving"); ?>');
			$('#finish_receiving_form').submit();
		}
	});

	$("#cart_contents input").keypress(function(event)
	{
		if (event.which == 13)
		{
			$(this).parents("tr").prevAll("form:first").submit();
		}
	});

	table_support.handle_submit = function(resource, response, stay_open)
	{
		if(response.success)
		{
			if (resource.match(/suppliers$/))
			{
				$("#supplier").val(response.id);
				$("#select_supplier_form").submit();
			}
			else
			{
				$("#item").val(response.id);
				if (stay_open)
				{
					$("#add_item_form").ajaxSubmit();
				}
				else
				{
					$("#add_item_form").submit();
				}
			}
		}
	}

	

	$('[name="item"]').on('click change', function() {
		// alert($('#price').val());
		var costPrice = parseFloat($('#price').val());
            var unitPrice = parseFloat($('#unit_price').val());
    
	if (unitPrice < costPrice) {
        // Handle "none" expire date case here
        alert("Sales price cannot be lower than the Purchase price!");
		$('[name="item"]').prop('disabled', true);
        $('#add_payment_button').hide();
    } else {
		$('[name="item"]').prop('disabled', false);
        $('#add_payment_button').show();
    }
});


$('[name="item"]').on('click change', function() {
		// alert($('#price').val());
		var unitPrice = parseFloat($('#unit_price').val());
        var mrp_price = parseFloat($('#mrp_price').val());
    
		if (mrp_price < unitPrice) {
        // Handle "none" expire date case here
        alert("MRP price cannot be lower than the Sales price!");
		$('[name="item"]').prop('disabled', true);
        $('#add_payment_button').hide();
    } else {
		$('[name="item"]').prop('disabled', false);
        $('#add_payment_button').show();
    }
});

	

	$('[name="price"],[name="quantity"],[name="receiving_quantity"],[name="discount"],[name="description"],[name="serialnumber"],[name="expire_date"],[name="unit_price"],[name="mrp_price"]').change(function() {
		$(this).parents("tr").prevAll("form:first").submit()
	});

	$('[name="discount_toggle"]').change(function() {
		var input = $("<input>").attr("type", "hidden").attr("name", "discount_type").val(($(this).prop('checked'))?1:0);
		$('#cart_'+ $(this).attr('data-line')).append($(input));
		$('#cart_'+ $(this).attr('data-line')).submit();
	});

});

</script>

<?php $this->load->view("partial/footer"); ?>
