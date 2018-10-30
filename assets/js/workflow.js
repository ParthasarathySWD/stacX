$(document).ready(function() {

  $("[data-toggle=popover]").popover({
    html: true, 
    content: function() {
      return $('#popover-content').html();
    }
  }); 
  $("[data-toggle=onholdpopover]").popover({
    html: true, 
    content: function() {
      return $('#onholdpopover-content').html();
    }
  });   

  $("[data-toggle=reviewonholdpopover]").popover({
    html: true, 
    content: function() {
      return $('#reviewonholdpopover-content').html();
    }
  }); 

  $("[data-toggle=reverse_workflowpopover]").popover({
    html: true, 
    content: function() {
      return $('#reverse_workflowpopover-content').html();
    },
  }); 

  $("[data-toggle=revoke_workflowpopover]").popover({
    html: true, 
    content: function() {
      return $('#revoke_workflowpopover-content').html();
    },
  });


  $("[data-toggle=exceptionclear_popover]").popover({
    html: true, 
    content: function() {
      return $('#exceptionclear_popover_content').html();
    },
  }); 

  $("[data-toggle=reverse_workflowpopover]").click(function(e) {
    if ($('.selectpopover').data('select2')) {

     $('.selectpopover').select2('destroy');
   }

   $('.selectpopover').select2({
    theme: "bootstrap",
    width:"100px",
    containerCssClass: "my-select2-container",
    dropdownCssClass: "my-select2-dropdown",
  });
 })


  $("[data-toggle=qcpopover]").popover({
   html: true, 
   content: function() {
    return $('#qcpopover-content').html();
  }
}); 




});
function get_parameter_by_name(name, url) {
  if (!url) url = window.location.href;
  name = name.replace(/[\[\]]/g, "\\$&");
  var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
  results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return '';
  return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function menuurl()
{
  return MENU_URL;
}

function baseurl(){
  var first = window.location.origin;
  return first;
}


$('.revoke-checkbox input:checkbox').on('click', function(e) {
  var selector = '.' + $(this).attr('class');
  var isChecked = $(selector).prop('checked');
  if(isChecked){
    var ss = true;
  }
  else{
    var ss = false;
  }
  $(selector).prop('checked', ss);
});


$(document).off('submit','#onholdform').on('submit','#onholdform',function(event){
  event.preventDefault();

  var button = $('.onholdsubmit');
  var MenuUrl = menuurl();
  var remarkstext = $(".popover .remarkstext").val();
  var OrderUID = $(".OrderUID").val();
  var reason =  $("[name='selectreason']:visible").val();
  var Type =  $("[name='selectonholdtype']:visible").val();
  if(remarkstext == '' || MenuUrl == '' || OrderUID == ''){
    
      $.notify({icon:"icon-bell-check",message:'Fields Required'},{type:"danger",delay:1000 });

  }else{
    $('.spinnerclass').addClass("be-loading-active");
    button.attr('disabled',true);
    button.html('<i class="fa fa-spinner fa-spin"></i>');

    $.ajax({
     type: "POST",
     url: base_url+'/Onhold_Orders/submitholdorder',
     data: $('#onholdform').serialize() + "&" + $.param({'MenuUrl': MenuUrl})+ "&" + $.param({"remarkstext": remarkstext}) + "&" + $.param({'OrderUID': OrderUID})+ "&" + $.param({"reason": reason}) +"&" + $.param({"Type": Type}),
     dataType:'json',
     success: function(data)
     {
      $('.spinnerclass').removeClass("be-loading-active");

      if(data['Error'] == '0'){
        
      $.notify({icon:"icon-bell-check",message:data['message']},{type:"success",delay:1000 });
      $('[data-toggle=onholdpopover]').popover('dispose');
      triggerpage(window.location.href);

      }else{
        
      $.notify({icon:"icon-bell-check",message:data['message']},{type:"danger",delay:1000 });

      }
    }
  });

  }
});

$('.Reviewunholdorder').off('click').click(function(){
  var MenuUrl = menuurl();

  $('.spinnerclass').addClass("be-loading-active");
  var button = $('.Reviewunholdorder');
  var OrderUID = $(this).attr('name');
  $.ajax({
    type: "POST",
    url: base_url+'/Onhold_Orders/changeReviewholdorder',
      //url: 'http://localhost/d2t.development/Onhold_Orders/changeReviewholdorder',
      dataType:'json',

      data:{'OrderUID':OrderUID},
      success: function(data)
      {
        button.attr('disabled',true);
        button.html('<i class="fa fa-spinner fa-spin"></i> loading..');
        $('.spinnerclass').removeClass("be-loading-active");

        if(data['Error'] == '0'){
          
      $.notify({icon:"icon-bell-check",message:data['message']},{type:"success",delay:1000 });
      triggerpage(window.location.href);

        }else{
          
      $.notify({icon:"icon-bell-check",message:data['message']},{type:"danger",delay:1000 });
        }
      }
    })
});

$(document).off('submit','#reviewonholdform').on('submit','#reviewonholdform',function(event){
  event.preventDefault();
  var OrderUID = $(".OrderUID").val();
  var button = $('.reviewonholdsubmit');
  var MenuUrl = menuurl();
  var remarkstext = $(".popover .remarkstext").val();
  var reason =  $("[name='selectreason']:visible").val();
  var Type =  $("[name='selectonholdtype']:visible").val();
  if(remarkstext == '' || reason == '' || Type == ''){
    
      $.notify({icon:"icon-bell-check",message:'Fields Required'},{type:"danger",delay:1000 });

  }
  else{
    $('.spinnerclass').addClass("be-loading-active");
    button.attr('disabled',true);
    button.html('<i class="fa fa-spinner fa-spin"></i> Loading');
    $.ajax({
      type: "POST",
      url: base_url+'/Onhold_Orders/reviewonhold',
      //url : 'http://localhost/d2t.development/Onhold_Orders/reviewonhold',
      //data : {'OrderUID':OrderUID}, 
      data: $('#reviewonholdform').serialize() + "&" + $.param({'MenuUrl': MenuUrl})+ "&" + $.param({"remarkstext": remarkstext}) + "&" + $.param({'OrderUID': OrderUID})+ "&" + $.param({"reason": reason}) +"&" + $.param({"Type": Type}),
      dataType:'json',
      cache: false,
      success: function(data)
      {
        $('.spinnerclass').removeClass("be-loading-active");
        if(data['Error'] == '0'){
          
      $.notify({icon:"icon-bell-check",message:data['message']},{type:"success",delay:1000 });
      triggerpage(window.location.href);

      $('[data-toggle=reviewonholdpopover]').popover('dispose');
      triggerpage(base_url + 'MyOrders');

        }else{
          
      $.notify({icon:"icon-bell-check",message:data['message']},{type:"danger",delay:1000 });

        }
      }
    });    
  }
  return false;
});


$('.unholdorder').off('click').click(function(){

  var MenuUrl = menuurl();

  $('.spinnerclass').addClass("be-loading-active");
  var button = $('.unholdorder');
  var OrderUID = $(this).attr('name');
  $.ajax({
   type: "POST",
   url: base_url+'/Onhold_Orders/changestatusholdorder',
   dataType:'json',

   data:{'MenuUrl':MenuUrl,'OrderUID':OrderUID},
   success: function(data)
   {
    button.attr('disabled',true);
    button.html('<i class="fa fa-spinner fa-spin"></i>');
    $('.spinnerclass').removeClass("be-loading-active");

    if(data['Error'] == '0'){
      
      $.notify({icon:"icon-bell-check",message:data['message']},{type:"success",delay:1000 });

      triggerpage(window.location.href);

    }else{
      
      $.notify({icon:"icon-bell-check",message:data['message']},{type:"danger",delay:1000 });

    }
  }
})
});


$(document).off('submit','#Frm_ExceptionRaise').on('submit','#Frm_ExceptionRaise',function(event)
{
  var MenuUrl = menuurl();

  event.preventDefault();
  var OrderUID = get_parameter_by_name('OrderUID').replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, ' ');
  var button = $('.onRaiseExceptionsubmit');
  var remarkstext = $(".popover .remarkstext").val();
  var selectreason = $(".popover .selectreason").val();
  //var baseurl='https://staging.direct2title.com/exception_raise/';

  if(remarkstext == ''){
    $('.remarkstext').focus();
    
      $.notify({icon:"icon-bell-check",message:'Fields Required !!!'},{type:"success",delay:1000 });

  }else{
    $('.spinnerclass').addClass("be-loading-active");
    button.attr('disabled',true);
    button.html('<i class="fa fa-spinner fa-spin"></i>');

    $.ajax({
     type: "POST",
     url: 'exception_raise/submitExceptionRaise',
     data: $('#Frm_ExceptionRaise').serialize() + "&" + $.param({"remarkstext": remarkstext})+ "&" + $.param({"selectreason": selectreason}) + "&" + $.param({"OrderUID": OrderUID}),
     dataType:'json',
     success: function(data)
     {
      $('.spinnerclass').removeClass("be-loading-active");

      if(data['Error'] == '0'){
        
      $.notify({icon:"icon-bell-check",message:data['message']},{type:"success",delay:1000 });

      }else{ 
        
      $.notify({icon:"icon-bell-check",message:data['message']},{type:"danger",delay:1000 });

      }
    }
  });

  }
});

$(document).off('submit','#frmrevokeworkflow').on('submit','#frmrevokeworkflow',function(event)
{
  event.preventDefault();
  var button = $('.Revoke_workflow_submit');
  var OrderUID = get_parameter_by_name('OrderUID').replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, ' ');

  var chkId = '';
  $("#revoke_workflowpopover-content input:checkbox:not(:checked)").each(function () {
    chkId += $(this).val() + ",";
  });
  WorkFlow = chkId;
  var VendorUID = $('.VendorUID').val();
  $('.spinnerclass').addClass("be-loading-active");
  button.attr('disabled',true);
  button.html('<i class="fa fa-spinner fa-spin"></i> Loading..');

  $.ajax({
   type: "POST",
   url: base_url+'/order_assignment/vendor_unassign_workflow',
     //url: 'http://localhost/direct2title.vendor/order_assignment/vendor_unassign_workflow',
     data:{'WorkFlow':WorkFlow,'OrderUID':OrderUID,'VendorUID':VendorUID},
     dataType:'json',
     cache: false,
     success: function(data)
     {
      $('.spinnerclass').removeClass("be-loading-active");

      if(data['Error'] == '0'){
        
      $.notify({icon:"icon-bell-check",message:data['message']},{type:"success",delay:1000 });

      }else{ 
        
      $.notify({icon:"icon-bell-check",message:data['message']},{type:"danger",delay:1000 });

      }
    }
  });
}); 



$(document).off('submit','#qcform').on('submit','#qcform',function(event)
{
	event.preventDefault();
	var button = $('.qcsubmit');
	var OrderUID = $('.OrderUID').val();
	var selected_checkbox = new Array();

	if($('.checkbox_container:visible').find("input[name='qccheckbox']:checked").length > 0){ 

		checked_values = $('.checkbox_container:visible').find("input[name='qccheckbox']:checked");

		$(checked_values).each(function(i,j){
			if($(j).data('modified') == 1){
				selected_checkbox.push($(this).val());
			}

		})

   $('.spinnerclass').addClass("be-loading-active");
   button.attr('disabled',true);
   button.html('<i class="fa fa-spinner fa-spin"></i> Loading..');

   $.ajax({
    type: "POST",
    url: base_url+'qc_orders/qc_complete',
    data:{'OrderUID':OrderUID,'qc_checkbox':selected_checkbox},
    dataType:'json',
    cache: false,
    success: function(data)
    {
     $('.spinnerclass').removeClass("be-loading-active");

     if(data['Error'] == '0'){
      
      $.notify({icon:"icon-bell-check",message:data['message']},{type:"success",delay:1000 });

    }else{ 
      button.removeAttr('disabled');
      
      $.notify({icon:"icon-bell-check",message:data['message']},{type:"danger",delay:1000 });

    }
  }
});
 }else{

			// alert('NO')
			/*No Checkbox*/

			
      $.notify({icon:"icon-bell-check",message:'No Workflow Selected'},{type:"success",delay:1000 });

		}
	});

$(document).off('click','.qc_checkbox').on("click",".qc_checkbox",function(){    
	// Get id of checkbox clicked.
	var itemIndex = $(this).attr("id").split("_")[2]; 

	// find original element
	var orig = $('.checkbox_container:visible').find('.qc_checkbox_'+itemIndex);

	// add or remove checked property to the original content accordingly.
	if($(this).is(":checked")) {
		orig.prop('checked', true);
		orig.attr('checked','checked');
		orig.attr('data-modified',1);
	}else{
		orig.attr('data-modified',1);
		orig.removeAttr('checked');  
		orig.prop('checked', false);
	} 
});

/***** Dismiss all popovers by clicking outside, don't dismiss if clicking inside the popover content  **************/

// $('body').on('click', function (e) {
//   //did not click a popover toggle, or icon in popover toggle, or popover
//   if ($(e.target).data('toggle') !== 'popover' && $(e.target).parents('[data-toggle="popover"]').length === 0 && $(e.target).parents('.popover.in').length === 0) { 
//     $('[data-toggle="popover"]').popover('hide');
//   }
//   if ($(e.target).data('toggle') !== 'onholdpopover' && $(e.target).parents('[data-toggle="onholdpopover"]').length === 0 && $(e.target).parents('.popover.in').length === 0) { 
//     $('[data-toggle="onholdpopover"]').popover('hide');
//   }

//   if ($(e.target).data('toggle') !== 'reviewonholdpopover' && $(e.target).parents('[data-toggle="reviewonholdpopover"]').length === 0 && $(e.target).parents('.popover.in').length === 0) { 
//     $('[data-toggle="reviewonholdpopover"]').popover('hide');
//   }

//   // if ($(e.target).data('toggle') !== 'reverse_workflowpopover' && $(e.target).parents('[data-toggle="reverse_workflowpopover"]').length === 0 && $(e.target).parents('.popover.in').length === 0) { 
//   //   $('[data-toggle="reverse_workflowpopover"]').popover('hide');
//   // }

//   if ($(e.target).data('toggle') !== 'revoke_workflowpopover' && $(e.target).parents('[data-toggle="revoke_workflowpopover"]').length === 0 && $(e.target).parents('.popover.in').length === 0) { 
//     $('[data-toggle="revoke_workflowpopover"]').popover('hide');
//   }

//   if ($(e.target).data('toggle') != "qcpopover" && $(e.target).parents('[data-toggle="qcpopover"]').length === 0 && $(e.target).parents('.popover.in').length == 0 && $(e.target).hasClass('qc_checkbox') == false) { 
//     $('[data-toggle="qcpopover"]').popover('hide');
//   }

//   if ($(e.target).data('toggle') != "exceptionclear_popover" && $(e.target).parents('[data-toggle="exceptionclear_popover"]').length === 0 && $(e.target).parents('.popover.in').length == 0 && $(e.target).parents().hasClass('multiple_emails-close') == false) { 
//     $('[data-toggle="exceptionclear_popover"]').popover('hide');
//   }
// });

// $('body').on('hidden.bs.popover', function (e) {
// 	$(e.target).data("bs.popover").inState = { click: false, hover: false, focus: false }
// });

$('body').on('shown.bs.popover', function (e) {
	var popover = '.popover.in';
	var originalHeight = $(popover).height();
	$('.CustomerEmails:visible').multiple_emails({position: "bottom"});
	var newHeight = $(popover).height();
	var top = parseFloat($(popover).css('top'));
	var changeInHeight = newHeight - originalHeight;

	$(popover).css({ top: top - (changeInHeight ) });
});

/***** Dismiss all popovers by clicking outside, don't dismiss if clicking inside the popover content --- END  **************/


/*Search Highlight Invalid*/
$(document).on("focusout",".highlight-invalid",function(event){
	if($(event.target).val() != '') {
		$(event.target).removeClass('highlight-invalid');
	}
});

