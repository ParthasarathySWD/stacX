
format = function(num){
	var str = num.toString().replace("$", ""), parts = false, output = [], i = 1, formatted = null;
	if(str.indexOf(".") > 0) {
		parts = str.split(".");
		str = parts[0];
	}
	str = str.split("").reverse();
	for(var j = 0, len = str.length; j < len; j++) {
		if(str[j] != ",") {
			output.push(str[j]);
			if(i%3 == 0 && j < (len - 1)) {
				output.push(",");
			}
			i++;
		}
	}
	formatted = output.reverse().join("");
	return(formatted + ((parts) ? "." + parts[1].substr(0, 2) : ".00"));
};

remove = function(num){
	var str = num.toString().replace("$", ""), parts = false, output = [], i = 1, formatted = null;
	if(str.indexOf(".") > 0) {
		parts = str.split(".");
		str = parts[0];
	}
	str = str.split("").reverse();
	for(var j = 0, len = str.length; j < len; j++) {
		if(str[j] != ",") {
			output.push(str[j]);
			if(i%3 == 0 && j < (len - 1)) {
				output.push(",");
			}
			i++;
		}
	}
	formatted = output.reverse().join("");
	return(formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
};

totalvalue = function(num){
	var str = num.toString().replace("$", ""), parts = false, output = [], i = 1, formatted = null;
	if(str.indexOf(".") > 0) {
		parts = str.split(".");
		str = parts[0];
	}
	str = str.split("").reverse();
	for(var j = 0, len = str.length; j < len; j++) {
		if(str[j] != ",") {
			output.push(str[j]);
			if(i%3 == 0 && j < (len - 1)) {
				output.push(",");
			}
			i++;
		}
	}
	formatted = output.reverse().join("");
	return(formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
};

$( document ).ready(function() { 

	$(document).off('focus', '.currency').on('focus', '.currency', function(event) {
		/* Act on the event */
		$(this).val(remove($(this).val()));
		$(this).select();
	});

	$(document).off('focus', '#TotalValue').on('focus', '#TotalValue', function(event) {
		/* Act on the event */
		$(this).val(remove($(this).val()));
		$(this).select();
	});


	$(document).on('focusout', '#TotalValue', function(event) {
		var val =  $("#TotalValue").val();
		if(val==='0.00' || val==='0' || val==='$0.00'){
			$("#TotalValue").val('');
			$("#TotalValue").parent().removeClass('is-filled');
		}

	});

	$(document).on('change', '#TotalValue', function(event) {
		/* Act on the event */
		var ele=$(this);
		ele.formatCurrency();
	});


	$(document).off('blur', '.currency').on('blur', '.currency', function(event) {
		event.preventDefault();
		var ele=$(this);
		var val = $(this).val();

		if(val==='0.00' || val==='0' || val==='$0.00'){
			$(this).val('');
			$(this).parent().removeClass('is-filled');
		}

		if (val.indexOf('$.00') !== -1){

			$(this).val('$0.00');                
		}else{
			if($(this).val()!= 0){
				$(this).val($(this).val().split(" ").join(""));

				ele.formatCurrency();

			}

		}

	});

	$(".currency").keydown(function(event){
		if ((event.keyCode >= 65) &&  (event.keyCode <= 90) ||  (event.keyCode >=106 && event.keyCode <=109) || (event.keyCode >= 111) && (event.keyCode < 190) || (event.keyCode > 190) ) {
			event.preventDefault(); 
		} 
	});

	$("#TotalValue").keydown(function(event){
		if ((event.keyCode >= 65) &&  (event.keyCode <= 90) ||  (event.keyCode >=106 && event.keyCode <=109) || (event.keyCode >= 111) && (event.keyCode < 190) || (event.keyCode > 190) ) {
			event.preventDefault(); 
		} 
	});







});