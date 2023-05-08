<?php $this->load->view('partial/header'); ?>
<style>
	#customer_table_cash_table thead tr,#customer_table_bank_table thead tr {
		padding:5px;
		background:#0ad;
		color:#fff;

	}
	#customer_table_cash_table, #customer_table_cash_table td, #customer_table_cash_table th
    #customer_table_bank_table, #customer_table_bank_table td, #customer_table_bank_table th{
		
		padding:5px;
		border:1px solid #999 !important;
	}
	
@media (min-width: 768px)
{
	.modal-dlg .modal-dialog
	{
		width: 750px !important;
	}
}
</style>
<div id="toolbar">
    <div class="pull-left form-inline" role="toolbar">    

        <?php  echo form_label('Date Range:', 'daterangepicker', array('style' => 'margin-right: 10px;'));
        echo form_input(array('name'=>'daterangepicker', 'class'=>'form-control input-sm', 'id'=>'daterangepicker')); ?>
          
    </div>
    </div>
    <br><br><br><h4>PURCHASE BY CASH</h4>
    <table id="customer_table_cash_table" class="table table-striped "style="width: 25%;"><thead>
		<tr bgcolor="#CCC">					
        <th style="width: 50%" ><?php echo 'supplier' ?></th>
            <th style="width:50%"><?php echo 'purchase amount' ?></th>		
		</tr>
	</thead>
	<tbody>	
</tbody>
</table>
<h4>PURCHASE BY BANK</h4>
    <table id="customer_table_bank_table" class="table table-striped "style="width: 25%;"><thead>
		<tr bgcolor="#CCC">					
        <th style="width: 50%" ><?php echo 'supplier' ?></th>
            <th style="width:50%"><?php echo 'purchase amount' ?></th>		
		</tr>
	</thead>
	<tbody>	
</tbody>
</table>
<!-- <h4>EXPENSE</h4>
    <table id="customer_table_expense_table" class="table table-striped "style="width: 25%;"><thead>
		<tr bgcolor="#CCC">					
        <th style="width: 50%" ><?php echo 'supplier' ?></th>
            <th style="width:50%"><?php echo 'purchase amount' ?></th>		
		</tr>
	</thead>
	<tbody>	
</tbody>
</table> -->

<script type="text/javascript">
$(document).ready(function()
{	// load the preset daterange picker
	<?php $this->load->view('partial/daterangepicker'); ?>
    // set the beginning of time as starting date
    $('#daterangepicker').data('daterangepicker').setStartDate("<?php echo date($this->config->item('dateformat'), mktime(0,0,0,01,01,2010));?>");
	// update the hidden inputs with the selected dates before submitting the search data
    var start_date = "<?php echo date('Y-m-d', mktime(0,0,0,01,01,2010));?>";

    $('#daterangepicker').on('apply.daterangepicker', function(ev, picker) {
  // get the selected start date
  var startDate = picker.startDate.format('YYYY-MM-DD');

  // get the selected end date
  var endDate = picker.endDate.format('YYYY-MM-DD');
  console.log(startDate);
  console.log(endDate);
var url= "<?php echo site_url("Daily_reports/supplier_details"); ?>"
alert(url);
$.ajax({
    type: 'GET',
    url:  "<?php echo site_url("Daily_reports/supplier_details"); ?>",
    data: {
        start_date: startDate,
        end_date: endDate
    },
    dataType : 'json'
}).done(function (response) {
    console.log(response);
    var purchase_cash = response.purchase_cash;
    var purchase_bank = response.purchase_bank;

    // Handle the purchase_cash data
    var tbody_cash = $('#customer_table_cash_table tbody');
    var total_cash = purchase_cash.reduce(function(sum, row) {
        return sum + parseFloat(row.purchase_amount);
    }, 0);
    tbody_cash.empty();
    $.each(purchase_cash, function(index, row) {
        var tr = '<tr>';
        tr += '<td>' + row.first_name + '</td>';
        tr += '<td>' + row.purchase_amount + '</td>';
        tr += '</tr>';
        tbody_cash.append(tr);
    });
    var tr_cash = '<tr>';
    tr_cash += '<td><b>Total<b></td>';
    tr_cash += '<td><b>' + total_cash + '<b></td>';
    tr_cash += '</tr>';
    tbody_cash.append(tr_cash);

    // Handle the purchase_bank data
    var tbody_bank = $('#customer_table_bank_table tbody');
    var total_bank = purchase_bank.reduce(function(sum, row) {
        return sum + parseFloat(row.purchase_amount);
    }, 0);
    tbody_bank.empty();
    $.each(purchase_bank, function(index, row) {
        var tr = '<tr>';
        tr += '<td>' + row.first_name + '</td>';
        tr += '<td>' + row.purchase_amount + '</td>';
        tr += '</tr>';
        tbody_bank.append(tr);
    });
    var tr_bank = '<tr>';
    tr_bank += '<td><b>Total<b></td>';
    tr_bank += '<td><b>' + total_bank + '<b></td>';
    tr_bank += '</tr>';
    tbody_bank.append(tr_bank);
}).fail((jqXHR, errorMsg) => {
    alert(jqXHR.responseText, errorMsg);
});
	});});
</script>
<?php $this->load->view('partial/footer'); ?>
