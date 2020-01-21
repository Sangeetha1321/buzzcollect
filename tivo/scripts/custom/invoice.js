function add_item() {
	var items = parseInt($('#items').val());
	var nextItem = items +1;
	$('#item-table tr:last').after('<tr id="row_'+nextItem+'"><td><input type="text" name="desc_'+nextItem+'" class="form-control" /></td><td><input type="text" name="quantity_'+nextItem+'" id="quantity_'+nextItem+'" class="form-control quantitychange" value="0"></td><td><input type="text" name="amount_'+nextItem+'" id="amount_'+nextItem+'" class="form-control amountchange" value="0"></td><td><div id="total_'+nextItem+'">0.00</div></td><td><button type="button" name="remove" class="btn btn-danger btn-xs" onclick="remove_item('+nextItem+')"><span class="glyphicon glyphicon-trash"></span></button></td>');
	$('#items').val(nextItem);
}

function remove_item(num) 
{
	var items = parseInt($('#items').val());
	var nextItem = items +1;
	$('#row_' + num).remove();
	$('#items').val(nextItem);
	update_area();
}

$(document).ready(function() {
	$('.testForm').on("change",".amountchange",function() {
		var name = $(this).attr("name");
		name = name.replace("amount", "");
		name = name.replace("_","");
		var id = parseInt(name);
		var currentAmount = parseFloat($(this).val());
		currentAmount = convert_number(currentAmount);
		$('#amount_'+id).val(currentAmount);

		var quantity = parseFloat($('#quantity_'+id).val());
		quantity = convert_number(quantity);
		$('#quantity_'+id).val(quantity);

		var total = parseFloat((currentAmount * quantity));
		total = total.toFixed(2);
		$('#total_'+id).html("" + total);

		sum_sub_total();
	});
	$('.testForm').on("change",".quantitychange",function() {
		var name = $(this).attr("name");
		name = name.replace("quantity", "");
		name = name.replace("_","");
		var id = parseInt(name);
		var currentAmount = parseFloat($("#amount_"+id).val());
		currentAmount = convert_number(currentAmount);
		$('#amount_'+id).val(currentAmount);

		var quantity = parseFloat($(this).val());
		quantity = convert_number(quantity);
		$('#quantity_'+id).val(quantity);
		var total = parseFloat((currentAmount * quantity));
		total = total.toFixed(2);
		$('#total_'+id).html("" + total);
		sum_sub_total();
	});

	$('#tax_name_1').change(function() {
		$('#tax_name_1_area').text($('#tax_name_1').val());
	});
	$('#tax_name_2').change(function() {
		$('#tax_name_2_area').text($('#tax_name_2').val());
	});

	$('#tax_rate_1').change(function() {
		$('#tax_amount_1').text($('#tax_rate_1').val() + "%");
		$('#tax_name_1_area').text($('#tax_name_1').val());
		sum_sub_total();
	});

	$('#tax_rate_2').change(function() {
		$('#tax_amount_2').text($('#tax_rate_2').val() + "%");
		$('#tax_name_2_area').text($('#tax_name_2').val());
		sum_sub_total();
	});
});

function update_area() 
{
	$('#tax_amount_1').text($('#tax_rate_1').val() + "%");
	$('#tax_name_1_area').text($('#tax_name_1').val());
	$('#tax_amount_2').text($('#tax_rate_2').val() + "%");
	$('#tax_name_2_area').text($('#tax_name_2').val());
	sum_sub_total();
}

function update_tax(tax_rate,name,id) {
	var t = get_sub_total();
	var tax_rate = parseFloat(tax_rate);
	$('#tax_amount').text(tax_rate + "%");
	$('#tax_name').text(name);

	if(t> 0 && tax_rate > 0) {
		var bit = parseFloat(t/100*tax_rate);
		bit = bit.toFixed(2);
		$('#tax_total_amount_'+id).text("" + bit);
		return bit;
	}
	return 0;
}

function get_sub_total() {
	var total_sum=0.00;
	$('.quantitychange').each(function() {
		var name = $(this).attr("name");
		name = name.replace("quantity", "");
		name = name.replace("_","");
		var id = parseInt(name);
		var currentAmount = parseFloat($("#amount_"+id).val());
		var quantity = parseFloat($(this).val());
		var total = parseFloat((currentAmount * quantity));
		total_sum = total_sum + total;
	});
	return total_sum;
}


function sum_sub_total() {
	var total_sum=0.00;
	total_sum = get_sub_total();
	var tax = update_tax($('#tax_rate_1').val(), $('#tax_name_1').val(),1);
	var tax2 = update_tax($('#tax_rate_2').val(), $('#tax_name_2').val(),2);
	total_sum = total_sum.toFixed(2);
	$('#sub_total').html("" + total_sum);
	total_sum_func(tax, tax2, total_sum);
	return total_sum;
}

function total_sum_func(tax, tax2, total_sum) {
	total_sum = parseFloat(total_sum);
	var bit = parseFloat(tax);
	var bit2 = parseFloat(tax2);
	total_sum = parseFloat(total_sum + bit);
	total_sum = parseFloat(total_sum + bit2);
	total_sum = total_sum.toFixed(2);
	$('#total_payment').text("" + total_sum);
}

function editInvoice(id) {
	$.ajax({
		url: global_base_url + "accounting/edit_invoice/" + id,
		type: "GET",
		data: {
		},
		success:function(msg) {
			$('#editInvoice').html(msg);
		}
	})
}

function convert_number(digit) {
	return Number(digit.toString().match(/^\d+(?:\.\d{0,2})?/)).toFixed(2);
}