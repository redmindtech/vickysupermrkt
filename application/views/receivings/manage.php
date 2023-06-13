<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
$(document).ready(function()
{

    // $('#generate_barcodes').click(function()
    // {
    //     window.open(
    //         'index.php/items/generate_barcodes_purchase/'+table_support.selected_ids().join(':'),
    //         '_blank' // <- This is what makes it open in a new window.
    //     );
    // });
	// when any filter is clicked and the dropdown window is closed
	$('#filters').on('hidden.bs.select', function(e) {
		table_support.refresh();
	});
	
	// load the preset datarange picker
	<?php $this->load->view('partial/daterangepicker'); ?>

	$("#daterangepicker").on('apply.daterangepicker', function(ev, picker) {
		table_support.refresh();
	});

	<?php $this->load->view('partial/bootstrap_tables_locale'); ?>

	table_support.query_params = function()
	{
		return {
			start_date: start_date,
			end_date: end_date,
			filters: $("#filters").val() || [""]
		}
	};

	table_support.init({
		resource: '<?php echo "receivings";?>',
		headers: <?php echo $table_headers; ?>,
		pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
		uniqueId: 'unique_id',
		enableActions: function()
		{
			
			
		} ,
		onLoadSuccess: function(response) {
			if($("#table tbody tr").length > 1) {
				// $("#payment_summary").html(response.payment_summary);
				// $("#table tbody tr:last td:first").html("");
				// $("#table tbody tr:last").css('font-weight', 'bold');
				// $('#table').find('tr').each(function(){ 
				
			
				// $(this).find('td').eq(2).hide();
				// $(this).find('th').eq(2).hide();
				// $(this).find('td').eq(3).hide();
				// $(this).find('th').eq(3).hide();
				
				// //$(this).find('td').eq(1).html('<td>'+serial_no+'</td>'); 
				// }); 
			
			}
		},
		queryParams: function() {
			return $.extend(arguments[0], table_support.query_params());
		},
		columns: {
			'invoice': {
				align: 'center'
			}
		}
	});
});
</script>

<?php $this->load->view('partial/print_receipt', array('print_after_sale'=>false, 'selected_printer'=>'takings_printer')); ?>

<div id="title_bar" class="print_hide btn-toolbar">
	<button onclick="javascript:printdoc()" class='btn btn-info btn-sm pull-right'>
		<span class="glyphicon glyphicon-print">&nbsp</span><?php echo $this->lang->line('common_print'); ?>
	</button>
	<?php echo anchor("receivings", '<span class="glyphicon glyphicon-shopping-cart">&nbsp</span>' . 'Purchase register', array('class'=>'btn btn-info btn-sm pull-right', 'id'=>'show_purchases_button')); ?>
</div>

<div id="toolbar">
	<div class="pull-left form-inline" role="toolbar">
    <!-- <button id="generate_barcodes" class="btn btn-default btn-sm print_hide" data-href='<?php echo site_url("items/generate_barcodes"); ?>' title='<?php echo $this->lang->line('items_generate_barcodes');?>'>
            <span class="glyphicon glyphicon-barcode">&nbsp;</span><?php echo $this->lang->line('items_generate_barcodes'); ?>
        </button> -->

		<?php echo form_input(array('name'=>'daterangepicker', 'class'=>'form-control input-sm', 'id'=>'daterangepicker')); ?>
		
	</div>
</div>

<div id="table_holder">
	<table id="table"></table>
</div>

<div id="payment_summary">
</div>

<?php $this->load->view("partial/footer"); ?>
