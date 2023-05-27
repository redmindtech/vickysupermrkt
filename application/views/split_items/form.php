<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>
<?php echo form_open('Split_items/save/'.$split_items_info->receiving_id, array('id'=>'split_items_edit_form', 'class'=>'form-horizontal')); ?>

<fieldset id="split_items">
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('receivings_date'), 'receivings_date', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'receivings_date',
						'id'=>'receivings_date',
						'class'=>'form-control input-sm',
                        'readonly'=>'true',
						'value'=>to_date(strtotime($split_items_info->receiving_time)))
						);
						 echo form_input(array(
							'name'=>'receiving_id',
							'id'=>'receiving_id',
							'type'=>'hidden',
							'readonly'=>'true',
							'value'=>$split_items_info->receiving_id)
							);
						?>
			</div>
		</div>

        <div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('receivings_item_name'), 'item_name', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'item_name',
						'id'=>'item_name',
						'class'=>'form-control input-sm',
                        'readonly'=>'true',
						'value'=>$split_items_info->name)
						);
						echo form_input(array(
							'type'=>'hidden',
							'name'=>'old_item_id',
							'id'=>'old_item_id',
							'value'=>$split_items_info->item_id)
							);
						?>
			</div>
		</div>

        
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('receivings_new_item_name'), 'new_item_name', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'new_item_name',
						'id'=>'new_item_name',
						'class'=>'form-control input-sm',
						'value'=>'')
					);
					echo form_input(array(
						'type'=>'hidden',
						'name'=>'item_id',
						'id'=>'item_id')
						);?>
			</div>
		
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('receivings_quantity_in_hand'), 'quantity_in_hand', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'quantity_in_hand',
						'id'=>'quantity_in_hand',
						'class'=>'form-control input-sm',
                        'readonly'=>'true',
						'value'=>$split_items_info->quantity_purchased)
						);?>
			</div>
		</div>

        <div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('receivings_no_split'), 'receivings_no_split', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'receivings_no_split',
						'id'=>'receivings_no_split',
						'class'=>'form-control input-sm',
						'value'=>'')
						);?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('split_items_no_of_pack_kg'), 'split_items_no_of_pack_kg', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'split_items_no_of_pack_kg',
						'id'=>'split_items_no_of_pack_kg',
						'class'=>'form-control input-sm',
						'readonly'=>'true',
						'value'=>$split_items_info->stock_qty)
						);?>
			</div>
		</div>

        <div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('receivings_no_of_pack_split'), 'receivings_no_of_pack_split', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'receivings_no_of_pack_split',
						'id'=>'receivings_no_of_pack_split',
						'class'=>'form-control input-sm',
						'value'=>'')
						);?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('receivings_no_of_packing_split'), 'no_of_packing_split', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'no_of_packing_split',
						'id'=>'no_of_packing_split',
						'class'=>'form-control input-sm',
						'value'=>'')
						);?>
			</div>
		</div>

        <div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('receivings_split_type'), 'split_type', array('class'=>'control-label col-xs-3')); ?>
            <div class='col-xs-8'>
			<select name="split_type" id="split_type"  class='form-control'>
                <option value="" hidden>--Select Branch--</option >
                <option value="kg" >Kg</option>
                <option value="gram" >Gram</option>
               
      </select>
			</div>
		</div>

        <div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_purchase_price'), 'cost_price', array('class'=>'control-label col-xs-3')); ?>
			<div class="col-xs-4">
				<div class="input-group input-group-sm">
					<?php if (!currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
					<?php echo form_input(array(
							'name'=>'cost_price',
							'id'=>'cost_price',
							'class'=>'form-control input-sm',
							'onClick'=>'this.select();',
                            'readonly'=>'true',
							'value'=>to_currency_no_money($split_items_info->item_cost_price))
							);?>
					<?php if (currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
                    </div>
			</div>

                    <div class="col-xs-4">
				<div class="input-group input-group-sm">
					<?php if (!currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
					<?php echo form_input(array(
							'name'=>'new_cost_price',
							'id'=>'new_cost_price',
							'class'=>'form-control input-sm',
							'onClick'=>'this.select();',
                        
							'value'=>to_currency_no_money(''))
							);?>
					<?php if (currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
				
				</div>
			</div>
		</div>

        <div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_sales_price'), 'item_unit_price', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-4'>
				<div class="input-group input-group-sm">
					<?php if (!currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
					<?php echo form_input(array(
							'name'=>'item_unit_price',
							'id'=>'item_unit_price',
							
							'class'=>'form-control input-sm',
							'onClick'=>'this.select();',
                            'readonly'=>'true',
							'value'=>to_currency_no_money($split_items_info->item_unit_price))
							);?>
					<?php if (currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
				</div>
			</div>

            <div class='col-xs-4'>
				<div class="input-group input-group-sm">
					<?php if (!currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
					<?php echo form_input(array(
							'name'=>'new_unit_price',
							'id'=>'new_unit_price',							
							'class'=>'form-control input-sm',
							'onClick'=>'this.select();',
                            
							'value'=>to_currency_no_money(''))
							);?>
					<?php if (currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
				</div>
			</div>

		</div>

        <div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_mrp_price'), 'mrp_price', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-4'>
				<div class="input-group input-group-sm">
					<?php if (!currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
					<?php echo form_input(array(
							'name'=>'mrp_price',
							'id'=>'mrp_price',
							
							'class'=>'form-control input-sm',
							'onClick'=>'this.select();',
                            'readonly'=>'true',
							'value'=>to_currency_no_money($split_items_info->mrp_price))
							);?>
					<?php if (currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
				</div>
			</div>


            <div class='col-xs-4'>
				<div class="input-group input-group-sm">
					<?php if (!currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
					<?php echo form_input(array(
							'name'=>'new_mrp_price',
							'id'=>'new_mrp_price',							
							'class'=>'form-control input-sm',
							'onClick'=>'this.select();',
                            'value'=>to_currency_no_money(''))
							);?>
					<?php if (currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
				</div>
			</div>

		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('receivings_expire_date'), 'expire_date', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<div class="input-group">
					<span class="input-group-addon input-sm"><span class="glyphicon glyphicon-calendar"></span></span>
						<?php echo form_input(array(
							'name'=>'expire_date',
							'id'=>'expire_date',
							'class'=>'form-control input-sm datetime',
 							'value'=>to_date(strtotime($split_items_info->expire_date)),
                           )
							);?>
				</div>
			</div>
		</div>


		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_category'), 'category', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<div class="input-group">
					<span class="input-group-addon input-sm"><span class="glyphicon glyphicon-tag"></span></span>
					<?php						
							echo form_input(array(
								'name'=>'category',
								'id'=>'category',
								'class'=>'form-control input-sm',
								'value'=>$split_items_info->category)
								);
					
					?>
				</div>
			</div>
		</div>


        <div class="form-group form-group-sm">
				<?php echo form_label($this->lang->line('items_hsn_code_item'), 'category', array('class'=>'required control-label col-xs-3')); ?>
				<div class='col-xs-8'>
					<div class="input-group">
						<?php echo form_input([
								'name'=>'hsn_code',
								'id'=>'hsn_code',
								'class'=>'form-control input-sm',
								'value'=>'', 
							]
						);?>
					</div>
				</div>
			</div>

			<div class="form-group form-group-sm">
				<?php echo form_label($this->lang->line('items_tax_1'), 'tax_percent_1', array('class'=>'control-label col-xs-3')); ?>
				<div class='col-xs-4'>
					<?php echo form_input(array(
							'name'=>'tax_names[]',
							'id'=>'tax_name_1',
							'class'=>'form-control input-sm',
							'readonly'=>'readonly',
							'value'=>'CGST')
							);?>
				</div>
				<div class="col-xs-4">
					<div class="input-group input-group-sm">
						<?php echo form_input(array(
								'name'=>'tax_percents[]',
								'id'=>'tax_percent_name_1',
								'class'=>'form-control input-sm',
								'readonly'=>'readonly',
								'value'=>'0')
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
							'value'=>'SGST')
							);?>
				</div>
				<div class="col-xs-4">
					<div class="input-group input-group-sm">
						<?php echo form_input(array(
								'name'=>'tax_percents[]',
								'class'=>'form-control input-sm',
								'id'=>'tax_percent_name_2',
								'readonly'=>'readonly',
								'value'=>'0')
								);?>
						<span class="input-group-addon input-sm"><b>%</b></span>
					</div>
				</div>
			</div>
		
			<div class="form-group form-group-sm" hidden>
			<?php echo form_label($this->lang->line('receivings_batch_no'), 'batch_no', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<div class="input-group">
					
					<?php						
							echo form_input(array(
								'name'=>'batch_no',
								'id'=>'batch_no',
								'class'=>'form-control input-sm',
								'value'=>$split_items_info->reference)
								);
					
					?>
				</div>
			</div>
		</div>


			<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('Item_hsn_codes_tax_description'), 'description', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_textarea(array(
						'name'=>'description',
						'id'=>'description',
						'class'=>'form-control input-sm',
						'value'=>$split_items_info->description)
						);?>
			</div>
		</div>
		
	</fieldset>
<?php echo form_close(); ?>

<script type='text/javascript'>
//validation and submit handling
$(document).ready(function()
{

	
	
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

	// $('#new_item_name').autocomplete({
	// 	source: "<?php //echo site_url('Split_items/suggest_item_name');?>",
	// 	delay: 10,
	// 	appendTo: '.modal-content'
	// });

	$('#new_item_name').click(function() {
		$(this).attr('value', '');
	});

	$('#new_item_name').autocomplete({
		source: '<?php echo site_url("Split_items/suggest_item_name"); ?>',
		minChars:0,
		delay:10,
		select: function (event, ui) {
			$('#item_id').val(ui.item.value);
			$(this).val(ui.item.label);
			// $(this).attr('readonly', 'readonly');
			// $('#remove_supplier_button').css('display', 'inline-block');
			return false;
		}
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
	

	

	<?php
	if(!empty($split_items_info->expense_id))
	{
	?>
		$('#item_id').val('<?php echo $split_items_info->item_id ?>');
		$('#new_item_name').val('<?php echo $split_items_info->new_item_name ?>').attr('readonly', 'readonly');
		$('#remove_supplier_button').css('display', 'inline-block');
	<?php
	}
	?>


	$('#split_items_edit_form').validate($.extend({
		submitHandler: function(form) {
			$(form).ajaxSubmit({
				success: function(response)
				{
					window.location.reload();
					dialog_support.hide();
					table_support.handle_submit("<?php echo site_url($controller_name); ?>", response);
				},
				error: function(data) {
					dialog_support.hide();
					table_support.refresh();
					table_support.handle_submit("<?php echo site_url($controller_name); ?>", "Data saved Successfully");
				},
				dataType: 'json'
			});
		},

		errorLabelContainer: '#error_message_box',

		rules:
		{
           
			tax_percentage:{required: true,number:true,maxlength:3,range : [1, 100]},
           
            
		},

		messages:
		{
			hsn_code:
            {required: "<?php echo $this->lang->line('hsn_code_required'); ?>",
            remote:"<?php echo $this->lang->line('hsn_code_already_in_table'); ?>"
            },
            tax_percentage: "<?php echo $this->lang->line('hsn_code_percetage_required'); ?>"
		}
	}, form_support.error));
});
</script>
