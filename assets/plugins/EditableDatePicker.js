 $(function() {
  $(".DtHidden").datetimepicker({
    format: "MM/DD/YYYY",
    useCurrent: false,    
    icons: {
      time: "fa fa-clock-o",
      date: "fa fa-calendar",
      up: "fa fa-chevron-up",
      down: "fa fa-chevron-down",
      previous: "fa fa-chevron-left",
      next: "fa fa-chevron-right",
      today: "fa fa-screenshot",
      clear: "fa fa-trash",
      close: "fa fa-remove"
    },
  }).on('dp.change', function(e){
    var CurrentID = $(this).closest("div.form-group").find("input[type='hidden']").attr('id');
    var Selecteddate = $('#'+CurrentID).val();
    var array = new Array();
    date = Selecteddate.split('/');
    var Month = date[0];
    var Day = date[1];
    var Year = date[2]; 
    $(this).closest('div').find('.jq-dte-day').val(Day).removeClass('hint');
    $(this).closest('div').find('.jq-dte-month').val(Month).removeClass('hint');
    $(this).closest('div').find('.jq-dte-year').val(Year).removeClass('hint');  
    $(this).closest('div').find('.jq-dte-day').removeClass('error');
    $(this).closest('div').find('.jq-dte-month').removeClass('error');
    $(this).closest('div').find('.jq-dte-year').removeClass('error');
    $(this).closest('div').find('.jq-dte-errorbox').css('display','none');
  }).on('dp.hide', function(){

  });  


$("body").on("click" , ".calendar-icon", function(e){  
e.preventDefault();     
    $('.DtHidden').datetimepicker('hide');    
   $(this).closest("div.form-group").find("input[type='hidden']").datetimepicker('show');
 });

  $("body").on("input" , ".jq-dte-year" , function(){       
    var dtval =  $(this).val();
    var hval  = $(this).closest("div.form-group").find("input[type='hidden']").val();         
    if(hval == ""){
      newval = "" + "/" + "" + "/" + dtval;
      $(this).closest("div.form-group").find("input[type='hidden']").val("");
      $(this).closest("div.form-group").find("input[type='hidden']").val(newval);
    }else{
     var arr  = [];
     arr  =  hval.split("/");
     newval = arr[0] + "/" + arr[1] + "/" + dtval;         
     $(this).closest("div.form-group").find("input[type='hidden']").val("");
     $(this).closest("div.form-group").find("input[type='hidden']").val(newval);                  
   }   
 });

  $("body").on("input" , ".jq-dte-month" , function(){
    var dtval =  $(this).val();
    var hval  = $(this).closest("div.form-group").find("input[type='hidden']").val();
    if(hval == ""){
     newval = dtval + "/" + "" + "/" + "";
     $(this).closest("div.form-group").find("input[type='hidden']").val("");
     $(this).closest("div.form-group").find("input[type='hidden']").val(newval); 
   }else{
     var arr  = [];
     arr  =  hval.split("/");
     newval = dtval+ "/" + arr[1] + "/" +  arr[2];          
     $(this).closest("div.form-group").find("input[type='hidden']").val("");
     $(this).closest("div.form-group").find("input[type='hidden']").val(newval);           
   }   
 });


  $("body").on("input" , ".jq-dte-day" , function(){
    var dtval =  $(this).val();
    var hval  = $(this).closest("div.form-group").find("input[type='hidden']").val();
    if(hval == ""){
     newval = "" + "/" + dtval + "/" + "";
     $(this).closest("div.form-group").find("input[type='hidden']").val(""); 
     $(this).closest("div.form-group").find("input[type='hidden']").val(newval); 
   }else{
     var arr  = [];
     arr  =  hval.split("/");          
     newval = arr[0] + "/" + dtval + "/" +  arr[2];          
     $(this).closest("div.form-group").find("input[type='hidden']").val("");
     $(this).closest("div.form-group").find("input[type='hidden']").val(newval);          
   }   
 });

});






