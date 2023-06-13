<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open('Receivings/update_expire_date/'.$receiving_edit_info->unique_id, array('id'=>'update_expire_date_edit_form', 'class'=>'form-horizontal')); ?>
	
<fieldset id="receiving_basic_info">
		
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('receivings_date'), 'date', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
            <?php echo form_input(array(
					'name' => 'receivings_date',
					'id' => 'receivings_date',
					'class' => 'form-control input-sm',
					'readonly' => 'true',
					'value' => to_date(strtotime($receiving_edit_info->receiving_time)))
						);
				?>
			</div>
		</div>
		
        <div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('receivings_item_name'), 'item_name', array('class' => 'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name' => 'item_name',
						'id' => 'item_name',
						'class' => 'form-control input-sm',
						'readonly' => 'true',
						'value' => $receiving_edit_info->name)
					);
					echo form_input(array(
						'type' => 'hidden',
						'name' => 'old_item_id',
						'id' => 'old_item_id',
						'value' => $receiving_edit_info->item_id)
					);
					?>

			</div>
		</div>


		
		<div class="form-group form-group-sm">
  <?php echo form_label($this->lang->line('receivings_expire_date'), 'expire_date', array('class' => 'required control-label col-xs-3')); ?>
  <div class='col-xs-6'>
    <?php if ($receiving_edit_info->expire_date !== '0000-00-00'): ?>
      <div class="input-group">
        <span class="input-group-addon input-sm"><span class="glyphicon glyphicon-calendar"></span></span>
        <?php
        echo form_input(array(
          'name' => 'new_expire_date',
          'id' => 'new_expire_date',
          'class' => 'form-control input-sm datetime',
          'value' => to_datetime(strtotime($receiving_edit_info->expire_date)),
        ));
        ?>
      </div>
    <?php else: ?>
      <input type="text" name="new_expire_date" id="new_expire_date" readonly class="form-control input-sm" value="No Expire">
    <?php endif; ?>
  </div>
</div>

<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('split_items_no_of_pack_kg'), 'split_items_no_of_pack_kg', array('class' => 'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
					'name' => 'split_items_no_of_pack_kg',
					'id' => 'split_items_no_of_pack_kg',
					'class' => 'form-control input-sm',
					'readonly' => 'true',
					'value' => $receiving_edit_info->stock_qty)
				); ?>
			</div>
		</div>

	</fieldset>
<?php echo form_close(); ?>
		
<script type="text/javascript">
$(document).ready(function()
{
	<?php $this->load->view('partial/datepicker_locale');?>

  

	$('#update_expire_date_edit_form').validate($.extend({
		submitHandler: function(form) {
			$(form).ajaxSubmit({
				success: function(response)
				{
					dialog_support.hide();
					table_support.handle_submit("<?php echo site_url($controller_name); ?>", response);
				},
				dataType: 'json'
			});
		}
	}, form_support.error));
});
</script>
