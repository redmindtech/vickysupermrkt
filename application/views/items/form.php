<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open('items/save/'.$item_info->item_id, array('id'=>'item_form', 'enctype'=>'multipart/form-data', 'class'=>'form-horizontal')); ?>
	<fieldset id="item_basic_info">
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_item_number'), 'item_number', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<div class="input-group">
					<span class="input-group-addon input-sm"><span class="glyphicon glyphicon-barcode"></span></span>
					<?php echo form_input(array(
							'name'=>'item_number',
							'id'=>'item_number',
							'class'=>'form-control input-sm',
							'value'=>$item_info->item_number)
							);?>
				</div>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_name'), 'name', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'name',
						'id'=>'name',
						'class'=>'form-control input-sm',
						'value'=>$item_info->name)
						);?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_category'), 'category', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<div class="input-group">
					<span class="input-group-addon input-sm"><span class="glyphicon glyphicon-tag"></span></span>
					<?php
						if($this->Appconfig->get('category_dropdown'))
						{
							echo form_dropdown('category', $categories, $selected_category, array('class'=>'form-control'));
						}
						else
						{
							echo form_input(array(
								'name'=>'category',
								'id'=>'category',
								'class'=>'form-control input-sm',
								'value'=>$item_info->category)
								);
						}
					?>
				</div>
			</div>
		</div>

		<!-- <div id="attributes">
			<script type="text/javascript">
				$('#attributes').load('<?php //echo site_url("items/attributes/$item_info->item_id");?>');
			</script>
		</div> -->

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_stock_type'), 'stock_type', !empty($basic_version) ? array('class'=>'required control-label col-xs-3') : array('class'=>'control-label col-xs-3')); ?>
			<div class="col-xs-8">
				<label class="radio-inline">
					<?php echo form_radio(array(
							'name'=>'stock_type',
							'type'=>'radio',
							'id'=>'stock_type',
							'value'=>0,
							'checked'=>$item_info->stock_type == HAS_STOCK)
					); ?> <?php echo $this->lang->line('items_stock'); ?>
				</label>
				<label class="radio-inline">
					<?php echo form_radio(array(
							'name'=>'stock_type',
							'type'=>'radio',
							'id'=>'stock_type',
							'value'=>1,
							'checked'=>$item_info->stock_type == HAS_NO_STOCK)
					); ?><?php echo $this->lang->line('items_nonstock'); ?>
				</label>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_type'), 'item_type', !empty($basic_version) ? array('class'=>'required control-label col-xs-3') : array('class'=>'control-label col-xs-3')); ?>
			<div class="col-xs-8">
				<label class="radio-inline">
					<?php
						$radio_button = array(
							'name'=>'item_type',
							'type'=>'radio',
							'id'=>'item_type',
							'value'=>0,
							'checked'=>$item_info->item_type == ITEM);
						if($standard_item_locked)
						{
							$radio_button['disabled'] = TRUE;
						}
						echo form_radio($radio_button); ?> <?php echo $this->lang->line('items_standard'); ?>
				</label>
				<label class="radio-inline">
					<?php
						$radio_button = array(
							'name'=>'item_type',
							'type'=>'radio',
							'id'=>'item_type',
							'value'=>1,
							'checked'=>$item_info->item_type == ITEM_KIT);
						if($item_kit_disabled)
						{
							$radio_button['disabled'] = TRUE;
						}
						echo form_radio($radio_button); ?> <?php echo $this->lang->line('items_kit');
					?>
				</label>
				<?php
				if($this->config->item('derive_sale_quantity') == '1')
				{
				?>
					<label class="radio-inline">
						<?php echo form_radio(array(
								'name' => 'item_type',
								'type' => 'radio',
								'id' => 'item_type',
								'value' => 2,
								'checked' => $item_info->item_type == ITEM_AMOUNT_ENTRY)
						); ?><?php echo $this->lang->line('items_amount_entry'); ?>
					</label>
				<?php
				}
				?>
				<?php
				if($allow_temp_item == 1)
				{
				?>
					<label class="radio-inline">
						<?php echo form_radio(array(
								'name'=>'item_type',
								'type'=>'radio',
								'id'=>'item_type',
								'value'=>3,
								'checked'=>$item_info->item_type == ITEM_TEMP)
						); ?> <?php echo $this->lang->line('items_temp'); ?>
					</label>
				<?php
				}
				?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_supplier'), 'supplier', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_dropdown('supplier_id', $suppliers, $selected_supplier, array('class'=>'form-control')); ?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_purchase_price'), 'cost_price', array('class'=>'required control-label col-xs-3')); ?>
			<div class="col-xs-6">
				<div class="input-group input-group-sm">
					<?php if (!currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
					<?php echo form_input(array(
							'name'=>'cost_price',
							'id'=>'cost_price',
							'min'=>'0.1',
							'class'=>'form-control input-sm',
							'onClick'=>'this.select();',
							'value'=>to_currency_no_money($item_info->cost_price))
							);?>
					<?php if (currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
				</div>
				<p>( Per Pack / Kg Purchase Price )</p>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_sales_price'), 'unit_price', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<div class="input-group input-group-sm">
					<?php if (!currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
					<?php echo form_input(array(
							'name'=>'unit_price',
							'id'=>'unit_price',
							'min'=>'0.1',
							'class'=>'form-control input-sm compare-input',
							'onClick'=>'this.select();',
							'value'=>to_currency_no_money($item_info->unit_price))
							);?>
					<?php if (currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
				</div>
				<p>( Per Pack / Kg Sales Price )</p>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_mrp_price'), 'mrp_price', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<div class="input-group input-group-sm">
					<?php if (!currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
					<?php echo form_input(array(
							'name'=>'mrp_price',
							'id'=>'mrp_price',
							'min'=>'0.1',
							'class'=>'form-control input-sm compare-input2',
							'onClick'=>'this.select();',
							'value'=>to_currency_no_money($item_info->mrp_price))
							);?>
					<?php if (currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
				</div>
				<p>( Per Pack / Kg MRP Price )</p>
			</div>
		</div>


		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_batch_no'), 'batch_no', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'batch_no',
						'id'=>'batch_no',
						'class'=>'form-control input-sm',
						'value'=>$item_info->batch_no)
						);?>
			</div>
		</div>

		<?php 
	
	

	?>
		
		<div class="form-group form-group-sm">
    <?php echo form_label($this->lang->line('items_expire_date'), 'expire_date', array('class'=>'required control-label col-xs-3')); ?>
    <div class='col-xs-1'>
        <?php echo form_checkbox(array(
            'name' => 'expire_date_show',
            'id' => 'expire_date_show',
            'value' => 1,
            'checked' => ($item_info->expire_date_show) ? true : false // Update the checked attribute based on the condition
        )); ?>
    </div>
    <div class='col-xs-6'>
        <div class="input-group" id="expire_date" <?php if (!$item_info->expire_date_show) echo 'style="display: none"'; ?>>
            <span class="input-group-addon input-sm"><span class="glyphicon glyphicon-calendar"></span></span>
            <?php echo form_input(array(
                'name' => 'expire_date',
                'id' => 'expire_date',
                'class' => 'form-control input-sm datetime',
                'value' => to_datetime(strtotime($item_info->expire_date))
            )); ?>
        </div>
    </div>
</div>


		<?php if($include_hsn): ?>
			<div class="form-group form-group-sm">
				<?php echo form_label($this->lang->line('items_hsn_code_item'), 'category', array('class'=>'required control-label col-xs-3')); ?>
				<div class='col-xs-8'>
					<div class="input-group">
						<?php echo form_input([
								'name'=>'hsn_code',
								'id'=>'hsn_code',
								'class'=>'form-control input-sm',
								'value'=>$hsn_code 
							]
						);?>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php
		if(!$use_destination_based_tax)
		{
		?>
			<div class="form-group form-group-sm">
				<?php echo form_label($this->lang->line('items_tax_1'), 'tax_percent_1', array('class'=>'control-label col-xs-3')); ?>
				<div class='col-xs-4'>
					<?php echo form_input(array(
							'name'=>'tax_names[]',
							'id'=>'tax_name_1',
							'class'=>'form-control input-sm',
							'readonly'=>'readonly',
							'value'=>isset($item_tax_info[0]['name']) ? $item_tax_info[0]['name'] : $this->config->item('default_tax_1_name'))
							);?>
				</div>
				<div class="col-xs-4">
					<div class="input-group input-group-sm">
						<?php echo form_input(array(
								'name'=>'tax_percents[]',
								'id'=>'tax_percent_name_1',
								'class'=>'form-control input-sm',
								'readonly'=>'readonly',
								'value'=>isset($item_tax_info[0]['percent']) ? to_tax_decimals($item_tax_info[0]['percent']) : to_tax_decimals($default_tax_1_rate))
								);?>
						<span class="input-group-addon input-sm"><b>%</b></span>
					</div>
				</div>
			</div>

			<div class="form-group form-group-sm">
				<?php echo form_label($this->lang->line('items_tax_2'), 'tax_percent_2', array('class'=>'control-label col-xs-3')); ?>
				<div class='col-xs-4'>
					<?php echo form_input(array(
							'name'=>'tax_names[]',
							'id'=>'tax_name_2',
							'readonly'=>'readonly',
							'class'=>'form-control input-sm',
							'value'=>isset($item_tax_info[1]['name']) ? $item_tax_info[1]['name'] : $this->config->item('default_tax_2_name'))
							);?>
				</div>
				<div class="col-xs-4">
					<div class="input-group input-group-sm">
						<?php echo form_input(array(
								'name'=>'tax_percents[]',
								'class'=>'form-control input-sm',
								'id'=>'tax_percent_name_2',
								'readonly'=>'readonly',
								'value'=>isset($item_tax_info[1]['percent']) ? to_tax_decimals($item_tax_info[1]['percent']) : to_tax_decimals($default_tax_2_rate))
								);?>
						<span class="input-group-addon input-sm"><b>%</b></span>
					</div>
				</div>
			</div>
		<?php
		}
		?>

		<?php if($use_destination_based_tax): ?>
			<div class="form-group form-group-sm">
				<?php echo form_label($this->lang->line('taxes_tax_category'), 'tax_category', array('class'=>'control-label col-xs-3')); ?>
				<div class='col-xs-8'>
					<div class="input-group input-group-sm">
						<?php echo form_input(array(
								'name'=>'tax_category',
								'id'=>'tax_category',
								'class'=>'form-control input-sm',
								'size'=>'50',
								'value'=>$tax_category)
						); ?>
						<?php echo form_hidden('tax_category_id', $tax_category_id); ?>
					</div>
				</div>
			</div>
		<?php endif; ?>

		

		<?php
		foreach($stock_locations as $key=>$location_detail)
		{
		?>
			<div class="form-group form-group-sm">
				<?php echo form_label($this->lang->line('items_quantity').' '.$location_detail['location_name'], 'quantity_' . $key, array('class'=>'required control-label col-xs-3')); ?>
				<div class='col-xs-4'>
					<?php echo form_input(array(
							'name'=>'quantity_' . $key,
							'id'=>'quantity_' . $key,
							'class'=>'required quantity form-control',
							'onClick'=>'this.select();',
							'value'=>isset($item_info->item_id) ? to_quantity_decimals($location_detail['quantity']) : to_quantity_decimals(0))
							);?>
				</div>
			</div>
		<?php
		}
		?>

		<div class="form-group form-group-sm" hidden>
			<?php echo form_label($this->lang->line('items_receiving_quantity'), 'receiving_quantity', array('class'=>'required control-label col-xs-3'));  ?>
			<div class='col-xs-4'>
				<?php echo form_input(array(
						'name'=>'receiving_quantity',
						'id'=>'receiving_quantity',
						'class'=>'required form-control input-sm',
						'onClick'=>'this.select();',
						'value'=>isset($item_info->item_id) ? to_quantity_decimals($item_info->receiving_quantity) : to_quantity_decimals(0))
						);?>
			</div>
		</div>

		<div class="form-group form-group-sm" hidden>
			<?php echo form_label($this->lang->line('items_reorder_level'), 'reorder_level', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-4'>
				<?php echo form_input(array(
						'name'=>'reorder_level',
						'id'=>'reorder_level',
						'class'=>'form-control input-sm',
						'onClick'=>'this.select();',
						'value'=>isset($item_info->item_id) ? to_quantity_decimals($item_info->reorder_level) : to_quantity_decimals(0))
						);?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_description'), 'description', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_textarea(array(
						'name'=>'description',
						'id'=>'description',
						'class'=>'form-control input-sm',
						'value'=>$item_info->description)
						);?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_image'), 'items_image', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<div class="fileinput <?php echo $logo_exists ? 'fileinput-exists' : 'fileinput-new'; ?>" data-provides="fileinput">
					<div class="fileinput-new thumbnail" style="width: 100px; height: 100px;"></div>
					<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 100px; max-height: 100px;">
						<img data-src="holder.js/100%x100%" alt="<?php echo $this->lang->line('items_image'); ?>"
							 src="<?php echo $image_path; ?>"
							 style="max-height: 100%; max-width: 100%;">
					</div>
					<div>
						<span class="btn btn-default btn-sm btn-file">
							<span class="fileinput-new"><?php echo $this->lang->line("items_select_image"); ?></span>
							<span class="fileinput-exists"><?php echo $this->lang->line("items_change_image"); ?></span>
							<input type="file" name="item_image" accept="image/*">
						</span>
						<a href="#" class="btn btn-default btn-sm fileinput-exists" data-dismiss="fileinput"><?php echo $this->lang->line("items_remove_image"); ?></a>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group form-group-sm" hidden>
			<?php echo form_label($this->lang->line('items_allow_alt_description'), 'allow_alt_description', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-1'>
				<?php echo form_checkbox(array(
						'name'=>'allow_alt_description',
						'id'=>'allow_alt_description',
						'value'=>1,
						'checked'=>($item_info->allow_alt_description) ? 1 : 0)
						);?>
			</div>
		</div>

		<div class="form-group form-group-sm" hidden >
			<?php echo form_label($this->lang->line('items_is_serialized'), 'is_serialized', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-1'>
				<?php echo form_checkbox(array(
						'name'=>'is_serialized',
						'id'=>'is_serialized',
						'value'=>1,
						'checked'=>($item_info->is_serialized) ? 1 : 0)
						);?>
			</div>
		</div>

		<?php
		if($this->config->item('multi_pack_enabled') == '1')
		{
			?>
			<div class="form-group form-group-sm">
				<?php echo form_label($this->lang->line('items_qty_per_pack'), 'qty_per_pack', array('class'=>'control-label col-xs-3')); ?>
				<div class='col-xs-4'>
					<?php echo form_input(array(
							'name'=>'qty_per_pack',
							'id'=>'qty_per_pack',
							'class'=>'form-control input-sm',
							'value'=>isset($item_info->item_id) ? to_quantity_decimals($item_info->qty_per_pack) : to_quantity_decimals(0))
					);?>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<?php echo form_label($this->lang->line('items_pack_name'), 'name', array('class'=>'control-label col-xs-3')); ?>
				<div class='col-xs-8'>
					<?php echo form_input(array(
							'name'=>'pack_name',
							'id'=>'pack_name',
							'class'=>'form-control input-sm',
							'value'=>$item_info->pack_name)
					);?>
				</div>
			</div>
			<div class="form-group  form-group-sm">
				<?php echo form_label($this->lang->line('items_low_sell_item'), 'low_sell_item_name', array('class'=>'control-label col-xs-3')); ?>
				<div class='col-xs-8'>
					<div class="input-group input-group-sm">
						<?php echo form_input(array(
								'name'=>'low_sell_item_name',
								'id'=>'low_sell_item_name',
								'class'=>'form-control input-sm',
								'value'=>$selected_low_sell_item)
						); ?>
						<?php echo form_hidden('low_sell_item_id', $selected_low_sell_item_id);?>
					</div>
				</div>
			</div>
			<?php
		}
		?>

		<div class="form-group form-group-sm" hidden>
			<?php echo form_label($this->lang->line('items_is_deleted'), 'is_deleted', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-1'>
				<?php echo form_checkbox(array(
						'name'=>'is_deleted',
						'id'=>'is_deleted',
						'value'=>1,
						'checked'=>($item_info->deleted) ? 1 : 0)
						);?>
			</div>
		</div>

	</fieldset>

	<?php
			//echo $item_customer_category_price_fetch;


			$one = 0;
			$check_name = $item_info->name;	
			$check_null_flag = 2;
			$check_item_id_null = 2;
			$f = 0;
			// $customer_category_price_fetched=array();
			if($check_name == "")
			{
				$check_null_flag = 0;//add mode
			}
			else
			{
				// if($item_customer_category_price_fetch == "null"){
					
				// 	$check_item_id_null = 1;
				// }else{
				// 	foreach($item_customer_category_price_fetch as $supplier)
				// 	{
				// 	$customer_category_price_fetched[$f] = $supplier['sales_price'];//edit		
				// 	$f++;				
				// 	}
					
				// }
					
		
				$check_null_flag =1;
				
			}

			$arg_fun = $check_null_flag."..".$check_name;

			
			
			
			


			?>

<?php echo form_close(); ?>

<script type="text/javascript">
//validation and submit handling

check_null_flag =<?php echo $check_null_flag; ?>;

$(document).ready(function()
{
	
	<?php $this->load->view('partial/datepicker_locale'); ?>
	
	$(document).ready(function() {

	if(check_null_flag == 0){
		document.getElementById("expire_date_show").checked = true;
	}
		
  // Function to show input field

  function showAlert() {
	    alert("hiii");
  }


  function showInputField() {
	    $('#expire_date').show();
  }

  // Function to hide input field
  function hideInputField() {
	
    $('#expire_date').hide();
	
  }

  // Check the initial state of the checkbox
  if ($('#expire_date_show').is(':checked')) {
    showInputField();
  } else {
	$('#expire_date').val('0');
    hideInputField();
  }

  // Trigger click event on checkbox for edit form popup
  // Replace 'your-popup-checkbox-selector' with the actual selector for the checkbox in the edit form popup
  $('.your-popup-checkbox-selector').click();

  // Handle checkbox click event
  $("#expire_date_show").click(function() {
    if ($(this).is(':checked')) {
      showInputField();
    } else {
      hideInputField();
    }
  });
});			

	$('#new').click(function() {
		stay_open = true;
		$('#item_form').submit();
	});

	$('#submit').click(function() {
		stay_open = false;
	});

	$("input[name='tax_category']").change(function() {
		!$(this).val() && $(this).val('');
	});

	var fill_value = function(event, ui) {
		event.preventDefault();
		$("input[name='tax_category_id']").val(ui.item.value);
		$("input[name='tax_category']").val(ui.item.label);
	};

	$('#tax_category').autocomplete({
		source: "<?php echo site_url('taxes/suggest_tax_categories'); ?>",
		minChars: 0,
		delay: 15,
		cacheLength: 1,
		appendTo: '.modal-content',
		select: fill_value,
		focus: fill_value
	});

	var fill_value = function(event, ui) {
		event.preventDefault();
		$("input[name='low_sell_item_id']").val(ui.item.value);
		$("input[name='low_sell_item_name']").val(ui.item.label);
	};

	$('#low_sell_item_name').autocomplete({
		source: "<?php echo site_url('items/suggest_low_sell'); ?>",
		minChars: 0,
		delay: 15,
		cacheLength: 1,
		appendTo: '.modal-content',
		select: fill_value,
		focus: fill_value
	});


	$(document).ready(function() {
        $('.compare-input').on('change', function() {
            var costPrice = parseFloat($('#cost_price').val());
            var unitPrice = parseFloat($('#unit_price').val());

            if (unitPrice < costPrice) {
                alert('Sales price cannot be lower than the Purchase price!');
                $(this).val(''); // Clear the unit price input field
            }
        });

        $('form').on('submit', function(event) {
            var costPrice = parseFloat($('#cost_price').val());
            var unitPrice = parseFloat($('#unit_price').val());

            if (unitPrice < costPrice) {
                event.preventDefault(); // Prevent form submission if unit price is lower than cost price
                alert('Sales price cannot be lower than the Purchase price!');
            }
        });
    });

	$(document).ready(function() {
        $('.compare-input2').on('change', function() {
            var unitPrice = parseFloat($('#unit_price').val());
            var mrp_price = parseFloat($('#mrp_price').val());

            if (mrp_price < unitPrice) {
                alert('MRP price cannot be lower than the Sales price!');
                $(this).val(''); // Clear the unit price input field
            }
        });

        $('form').on('submit', function(event) {
            var unitPrice = parseFloat($('#unit_price').val());
            var mrp_price = parseFloat($('#mrp_price').val());

            if (mrp_price < unitPrice) {
                event.preventDefault(); // Prevent form submission if unit price is lower than cost price
                alert('MRP price cannot be lower than the Sales price!');
            }
        });
    });


	$('#category').autocomplete({
		source: "<?php echo site_url('items/suggest_category');?>",
		delay: 10,
		appendTo: '.modal-content'
	});

	$('#hsn_code').autocomplete({
		source: "<?php echo site_url('items/suggest_hsn_code');?>",
		delay: 10,
		appendTo: '.modal-content'
	});

	$('#hsn_code').change(function()
{
		
	
	$var= $('#hsn_code').text();
    $hsn_code= $('#hsn_code').val();
    $('#hsn_code').val($hsn_code); 
	
	$.ajax({
			type: 'POST',
			url: "<?php echo site_url('Items/hsn_code_tax/'); ?>" + $hsn_code,         
            datatype : 'json',            
            }).done(function (msg) {
                
				$('#tax_percent_name_1').val(msg);
				$('#tax_percent_name_2').val(msg);                
            }).fail(function (errorMsg)
			{        
				    
			   $('#tax_percent_name_1').val('0');
			   $('#tax_percent_name_2').val('0');
        });
	
	});

	$('a.fileinput-exists').click(function() {
		$.ajax({
			type: 'GET',
			url: '<?php echo site_url("$controller_name/remove_logo/$item_info->item_id"); ?>',
			dataType: 'json'
		})
	});

	$.validator.addMethod('valid_chars', function(value, element) {
		return value.match(/(\||_)/g) == null;
	}, "<?php echo $this->lang->line('attributes_attribute_value_invalid_chars'); ?>");

	var init_validation = function() {
		$('#item_form').validate($.extend({
			submitHandler: function(form, event) {
				$(form).ajaxSubmit({
					success: function(response) {
						var stay_open = dialog_support.clicked_id() != 'submit';
						if(stay_open)
						{
							// set action of item_form to url without item id, so a new one can be created
							$('#item_form').attr('action', "<?php echo site_url('items/save/')?>");
							// use a whitelist of fields to minimize unintended side effects
							$(':text, :password, :file, #description, #item_form').not('.quantity, #reorder_level, #tax_name_1, #receiving_quantity, ' +
								'#tax_percent_name_1, #category, #reference_number, #name, #cost_price, #unit_price, #taxed_cost_price, #taxed_unit_price, #definition_name, [name^="attribute_links"]').val('');
							// de-select any checkboxes, radios and drop-down menus
							$(':input', '#item_form').removeAttr('checked').removeAttr('selected');
						}
						else
						{
						
							dialog_support.hide();
						}
						table_support.handle_submit('<?php echo site_url('items'); ?>', response, stay_open);
						init_validation();
					},
					dataType: 'json'
				});
			},

			errorLabelContainer: '#error_message_box',

			rules:
			{
				name:
			{
			required: true,
			remote: {
				url: "<?php echo site_url($controller_name . '/item_name_stringcmp')?>",
				type: 'GET',
				data: {
					'item_name' : "<?php echo $item_info->name; ?>",
					'mode' : "<?php echo $check_null_flag; ?>",
					'name' : function()
					{ 
						return $('#name').val();
					},
				}
			}
		},
				category: 'required',
				// batch_no: 'required',
				expire_date:'required',
				item_number:
				{
					required: false,
					remote:
					{
						url: "<?php echo site_url($controller_name . '/check_item_number')?>",
						type: 'POST',
						data: {
							'item_id' : "<?php echo $item_info->item_id; ?>",
							'item_number' : function()
							{
								return $('#item_number').val();
							},
						}
					}
				},
				hsn_code:
					{
					required: true,
						
					},
				mrp_price:
				{
					required: true,
					remote: "<?php echo site_url($controller_name . '/check_numeric')?>"
				},
				cost_price:
				{
					required: true,
					remote: "<?php echo site_url($controller_name . '/check_numeric')?>"
				},
				unit_price:
				{
					required: true,
					remote: "<?php echo site_url($controller_name . '/check_numeric')?>"
				},
				<?php
				foreach($stock_locations as $key=>$location_detail)
				{
				?>
				<?php echo 'quantity_' . $key ?>:
					{
						required: true,
						remote: "<?php echo site_url($controller_name . '/check_numeric')?>"
					},
				<?php
				}
				?>
				receiving_quantity:
				{
					required: true,
					remote: "<?php echo site_url($controller_name . '/check_numeric')?>"
				},
				reorder_level:
				{
					required: true,
					remote: "<?php echo site_url($controller_name . '/check_numeric')?>"
				},
				tax_percent:
				{
					required: true,
					remote: "<?php echo site_url($controller_name . '/check_numeric')?>"
				}
			},

			messages:
			{
				name:
				{ 
					required:  "<?php echo $this->lang->line('items_name_required'); ?>",
					remote: "<?php echo $this->lang->line('item_name_message'); ?>",
				},
				item_number: "<?php echo $this->lang->line('items_item_number_duplicate'); ?>",
				category: "<?php echo $this->lang->line('items_category_required'); ?>",
				// batch_no: "<?php //echo $this->lang->line('items_batch_no_required'); ?>",
				expire_date: "<?php echo $this->lang->line('items_expire_date_required'); ?>",
				hsn_code:
				{
					required: "<?php echo $this->lang->line('hsn_code_required'); ?>",
					// remote:"Your hsn code is not in HSN master please update your HSN code in HSN master"
				},
				mrp_price:
				{
					required: "<?php echo $this->lang->line('items_cost_price_required'); ?>",
					number: "<?php echo $this->lang->line('items_cost_price_number'); ?>"
				},
				cost_price:
				{
					required: "<?php echo $this->lang->line('items_cost_price_required'); ?>",
					number: "<?php echo $this->lang->line('items_cost_price_number'); ?>"
				},
				unit_price:
				{
					required: "<?php echo $this->lang->line('items_unit_price_required'); ?>",
					number: "<?php echo $this->lang->line('items_unit_price_number'); ?>"
				},
				<?php
				foreach($stock_locations as $key=>$location_detail)
				{
				?>
				<?php echo 'quantity_' . $key ?>:
					{
						required: "<?php echo $this->lang->line('items_quantity_required'); ?>",
						number: "<?php echo $this->lang->line('items_quantity_number'); ?>"
					},
				<?php
				}
				?>
				receiving_quantity:
				{
					required: "<?php echo $this->lang->line('items_quantity_required'); ?>",
					number: "<?php echo $this->lang->line('items_quantity_number'); ?>"
				},
				reorder_level:
				{
					required: "<?php echo $this->lang->line('items_reorder_level_required'); ?>",
					number: "<?php echo $this->lang->line('items_reorder_level_number'); ?>"
				},
				tax_percent:
				{
					required: "<?php echo $this->lang->line('items_tax_percent_required'); ?>",
					number: "<?php echo $this->lang->line('items_tax_percent_number'); ?>"
				}
			}
		}, form_support.error));
	};

	init_validation();
});
</script>

