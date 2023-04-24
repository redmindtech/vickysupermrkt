<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
$(document).ready(function()
{
	<?php $this->load->view('partial/bootstrap_tables_locale'); ?>

	table_support.init({

		resource: '<?php echo site_url($controller_name);?>',
		headers: <?php echo $table_headers; ?>,
		pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
		uniqueId: 'id',
		enableActions: function()
		{
			
			
			$('#table').find('tr').each(function(){ 
				
			
			$(this).find('td').eq(2).hide();
			$(this).find('th').eq(2).hide();
			
			//$(this).find('td').eq(1).html('<td>'+serial_no+'</td>'); 
			}); 
			var myClasses = document.querySelectorAll('.btn.btn-default.btn-sm.dropdown-toggle');
               
                myClasses[0].style.display = 'none';
			
		} 
	});
	

	// when any filter is clicked and the dropdown window is closed
	$('#filters').on('hidden.bs.select', function(e)
	{
		table_support.refresh();
	});
				$('ul li:contains(JSON)').first().remove();
                $('ul li:contains(XML)').first().remove();
                $('ul li:contains(TXT)').first().remove();
                $('ul li:contains(CSV)').first().remove();
                $('ul li:contains(SQL)').first().remove();

                $('[data-type="pdf"]').click(function(event) {
                        window.location.reload();
                        console.log('[data-type="pdf"]');
                });

});


</script>

<div id="title_bar" class="btn-toolbar">
	<button class='btn btn-info btn-sm pull-right modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/view"); ?>'
			title='<?php echo $this->lang->line($controller_name.'_new'); ?>'>
		<span class="glyphicon glyphicon-list-alt">&nbsp</span><?php echo $this->lang->line($controller_name . '_new'); ?>
	</button>
</div>

<div id="toolbar">
	<div class="pull-left form-inline" role="toolbar">
		<button id="delete" class="btn btn-default btn-sm print_hide">
			<span class="glyphicon glyphicon-trash">&nbsp</span><?php echo $this->lang->line("common_delete");?>
		</button>
	</div>
</div>

<div id="table_holder">
	<table id="table"></table>
</div>

<?php $this->load->view("partial/footer"); ?>
