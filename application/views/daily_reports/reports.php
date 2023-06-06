<?php $this->load->view('partial/header'); ?>
<style>
	#customer_table_cash_table thead tr,#customer_table_bank_table thead tr,#customer_table_expense_table thead tr,#customer_table_total_table thead tr{
		padding:5px;
		background:#0ad;
		color:#fff;

	}
	#customer_table_cash_table, #customer_table_cash_table td, #customer_table_cash_table th
    #customer_table_bank_table, #customer_table_bank_table td, #customer_table_bank_table th{
		
		padding:5px;
		border:1px solid #999 !important;
	}
    #customer_table_expense_table, #customer_table_expense_table td, #customer_table_expense_table th{
        padding:5px;
        border:1px solid #999!important;
    }
    #customer_table_total_table, #customer_table_total_table td, #customer_table_total_table th{
        padding:5px;
        border:1px solid #999!important;
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




<div>
    <br><br><br><h4>PURCHASE BY CASH</h4>
    <table id="customer_table_cash_table" class="table table-striped "style="width: 100%;"><thead>
		<tr bgcolor="#CCC">					
        <th style="width: 50%" ><?php echo 'supplier' ?></th>
            <th style="width:50%"><?php echo 'purchase amount' ?></th>		
		</tr>
	</thead>
	<tbody>	
    </tbody>
    </table>
</div>


<div>
<h4>CASH IN BANK</h4>
    <table id="customer_table_bank_table" class="table table-striped "style="width: 100%;"><thead>
		<tr bgcolor="#CCC">					
        <th style="width: 50%" ><?php echo 'supplier' ?></th>
            <th style="width:50%"><?php echo 'purchase amount' ?></th>		
		</tr>
	</thead>
	<tbody>	
    </tbody>
    </table>
</div>

<div>
<h4>EXPENSE</h4>
    <table id="customer_table_expense_table" class="table table-striped "style="width: 100%;"><thead>
		<tr bgcolor="#CCC">					
        <th style="width: 50%" ><?php echo 'Expense category' ?></th>
            <th style="width:50%"><?php echo 'expense amount' ?></th>		
		</tr>
	</thead>
	<tbody>	

</tbody>
</table>
<div>

<h4>TOTAL AMOUNT</h4>
    <table id="customer_table_total_table" class="table table-striped "style="width: 100%;"><thead>
		<tr bgcolor="#CCC">					
        <th style="width: 50%" ><?php echo 'Categories' ?></th>
            <th style="width:50%"><?php echo 'Amount' ?></th>		
		</tr>
	</thead>
	<tbody>	
        <!-- <tr>
            <td></td>
            <td></td>
        </tr> -->
</tbody>
</table>
<div>


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
    var expenses_amounts = response.expenses_amounts;
    var total_sales = response.total_sales;

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

    // Handle the expense_amount data



    var tbody_expense = $('#customer_table_expense_table tbody');
    var total_expense = expenses_amounts.reduce(function(sum, row) {
        return sum + parseFloat(row.expense_amount);
    }, 0);
    tbody_expense.empty();
    $.each(expenses_amounts, function(index, row) {
        var tr = '<tr>';
        tr += '<td>' + row.category_name + '</td>';
        tr += '<td>' + row.expense_amount + '</td>';
        tr += '</tr>';
        tbody_expense.append(tr);
        
    });
    var tr_expense = '<tr>';
    tr_expense += '<td><b>Total<b></td>';
    tr_expense += '<td><b>' + total_expense + '<b></td>';
    tr_expense += '</tr>';
    tbody_expense.append(tr_expense);


     
    
    



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

    // Handle the total_sales_amount data

    var tbody_sales = $('#customer_table_total_table tbody');
    
    tbody_sales.empty();
   $.each(total_sales, function(index, row) {
       var tr = '<tr>';
       tr += '<td>' + "Total Purchase By Cash"+ '</td>';
       tr += '<td>' +  total_cash + '</td>';
       tr += '</tr>';
       tbody_sales.append(tr);
       var tr = '<tr>';
       tr += '<td>' + "Total Purchase By Bank"+ '</td>';
       tr += '<td>' +  total_bank + '</td>';
       tr += '</tr>';
       tbody_sales.append(tr);
       var tr = '<tr>';
       tr += '<td>' + "Total Expenses"+ '</td>';
       tr += '<td>' +  total_expense + '</td>';
       tr += '</tr>';
       tbody_sales.append(tr);
       var tr = '<tr>';
       var row_sales_amount = row.sales_amount;
       if(row_sales_amount == null)
       {
        row_sales_amount = 0;
       }
       tr += '<td>' +"Total Sales"+  '</td>';

       tr += '<td>' + row_sales_amount + '</td>';
       tr += '</tr>';
       tbody_sales.append(tr);
       var tr = '<tr>';
       var total_final_amount = total_cash+ total_bank + total_expense - row.sales_amount;
       tr += '<td><b>' +"Total Amount"+  '</b></td>';
       tr += '<td><b>' + total_final_amount + '<b></td>';
       tr += '</tr>';
       tbody_sales.append(tr);
   });
   


}).fail((jqXHR, errorMsg) => {
    alert(jqXHR.responseText, errorMsg);
});
	});});
</script>
<?php $this->load->view('partial/footer'); ?>
