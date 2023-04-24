<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>
<?php echo form_open('Item_hsn_codes/save/'.$item_hsn_code_info->id, array('id'=>'item_hsn_codes_edit_form', 'class'=>'form-horizontal')); ?>

<fieldset id="item_hsn_code">
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('Item_hsn_codes_no'), 'hsn_code', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'hsn_code',
						'id'=>'hsn_code',
						'class'=>'form-control input-sm',
						'value'=>$item_hsn_code_info->hsn_code)
						);?>
			</div>
		</div>

        <div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('Item_hsn_codes_tax'), 'tax_percentage', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'tax_percentage',
						'id'=>'tax_percentage',
						'class'=>'form-control input-sm',
						'value'=>$item_hsn_code_info->tax_percentage)
						);?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('Item_hsn_codes_tax_description'), 'description', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_textarea(array(
						'name'=>'description',
						'id'=>'description',
						'class'=>'form-control input-sm',
						'value'=>$item_hsn_code_info->description)
						);?>
			</div>
		</div>
		
	</fieldset>
<?php echo form_close(); ?>

<script type='text/javascript'>
//validation and submit handling
$(document).ready(function()
{
	$('#item_hsn_codes_edit_form').validate($.extend({
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
            hsn_code:
			{
				required: true,
                number:true,
				remote:
				{ 
					url: "<?php echo site_url($controller_name. '/ajax_check_item_category_name') ?>",
					type: 'POST',
					data: {
						'id': "<?php echo $item_hsn_code_info->id; ?>"
						
					}
				}
			}
            
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
