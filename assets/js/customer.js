
var clipboard=[];
var priorityclipboard=[];
var copied=0;
var copiedelem='';
$(document).ready(function(){
  $("input#ufile").change(function() {  

  });


  $("body").on("click" , ".fileremove" , function(){
    $(".filediv").remove();
    $(".uploadedfile").html("No files were uploaded");
  })


  $("input.abstractorreffile").change(function() {
    var ele = document.getElementById($(this).attr('id'));
    var result = ele.files;
    for(var x = 0;x< result.length;x++){
      var fle = result[x];        
      var fname = fle.name;
      $(this).closest("center").siblings(".showfilename").text(fname);
      $(this).closest("center").siblings(".progress").show();
    }
  });


  // $('.selectivity-input').selectivity({
  //   items: ['Amsterdam', 'Antwerp','45645654' ,'4565465' , '546546' ,'546456'],
  //   multiple: true,
  //   placeholder: 'Select Sub Product'
  // });

  // $('.selectworkflow').selectivity({
  //   items: ['Amsterdam', 'Antwerp','45645654' ,'4565465' , '546546' ,'546456'],
  //   multiple: true,
  //   placeholder: 'Select Sub Product'
  // });


  $("#myForm").multiStepForm(
  {       
    callback : function(){
      console.log("save");
    }
  }
  ).navigateTo(0);



  demo.initMaterialWizard();
  setTimeout(function() {
    $('.card.card-wizard').addClass('active');
  }, 600);

  $("#getparentlist").click(function(){
    if($(this).prop("checked") === false){
      $("#listparentcompany").css("visibility" , "visible");
    }else{
      $("#listparentcompany").css("visibility" , "hidden");
    }
  });




  $(document).on('click','.addabstractordoc',function(e){
   e.preventDefault();
   var dyndivcount=$('.abstractordoc').length;
   var appenddiv   = '';
   appenddiv='<form method="post" enctype="multipart/form-data" class="frmabstractordoc test">';
   appenddiv+='<div class="row abstractordoc"><div class="col-md-4 dyndiv"><div class="form-group bmd-form-group"><label for="filter_ordertypeuid" class="bmd-label-floating">Order Types</label>'
   appenddiv+='<select class="select2picker select2-hidden-accessible ordertypes_select" name="ordertypes[]" id="ordertypes_'+dyndivcount+'" multiple="" tabindex="-1" aria-hidden="true">'+ordertypes_options;
   appenddiv+='</select>';
   appenddiv+='</div></div><div class="col-md-4 dyndiv"><center style="padding:5px;"><span class="btn btn-default fileinput-button-upload"> <h6>Browse</h6> <input type="file" name="ordertypedocs" id="ordertypedocs_'+dyndivcount+'" class="abstractorordertypedoc" accept=".pdf"></span></center>';
   appenddiv+='<p class="showfilename"></p><div class="progress progress-line-info" id="documentupload'+dyndivcount+'" style="display: none; height: 22px;">';
   appenddiv+='<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width:0%; height: 21px;"><span class="sr-only" style="height: 21px;">60% Complete</span></div></div></div>';
   appenddiv+='<div class="col-md-4 dyndiv"><div class="row"><div class="col-6 viewdocumentcontainer"></div><div class="col-6">';
   appenddiv+='<button type="button" class="btn btn-github btn-sm removeabstractordoc"><i class="icon-minus3"></i></button>';
   appenddiv+='<button type="submit" class="btn btn-sm btn-success upload hide"><i class="icon-upload"></i> Upload</button>';
   appenddiv+='</div></div>'
   appenddiv+='</div></div>';
   appenddiv+='</form>'
   $(".dynamicdiv").append(appenddiv); 
   $(".selectpicker").selectpicker();
   $(".select2picker").select2({
    tags: false,
    theme: "bootstrap",
  });
  //  $('.selectivity-input'+i).selectivity({
  //   items: ['Amsterdam', 'Antwerp','45645654' ,'4565465' , '546546' ,'546456'],
  //   multiple: true,
  //   placeholder: 'Select Sub Product'
  // });
  return false;
});


  $("body").on("click" , ".removeabstractordoc" , function(e){
    e.preventDefault();
    $(this).closest(".abstractordoc").remove();
    return false;
  });
  
// 
$("body").on("click" , "#confirmdelete" , function(){
  $.notifyClose();
});







$("body").on("click" , ".closesetup" , function(){
  $(".setupdiv").slideUp();
});


    /*---Workflow Tab Copypaste Functionality Starts---*/

    //Copy Workflow Details
    $('body').on('click','.copyworkflow', function(event) { 

      event.preventDefault();

      var copiedworkflow = getParentByClass($(this)[0], 'WorkflowmoduleWrapper');
      clipboard['TemplateUID'] = $(copiedworkflow).find('.TemplateUID').val();
      clipboard['WorkflowModuleUID'] = $(copiedworkflow).find('.WorkflowModuleUID').val();
      clipboard['OptionalWorkflowModuleUID'] = $(copiedworkflow).find('.OptionalWorkflowModuleUID').val();
      clipboard['GroupUID'] = $(copiedworkflow).find('.GroupUID').val();
      console.log(clipboard);
      copiedelem=copiedworkflow;
    
      copied = 1;
    });


    // Paste Workflow & Templates Details

    $(document).delegate("form.frmcustomerworkflow .WorkflowmoduleWrapper", "click", function(e){
      if(copied == 1 && copiedelem!==this && $(e.target).hasClass('.copyworkflow')!=true){
        var pastetr = $(this);
        $(pastetr).find('.TemplateUID').val(clipboard['TemplateUID']).trigger("change");
        $(pastetr).find('.WorkflowModuleUID').val(clipboard['WorkflowModuleUID']).trigger("change");
        $(pastetr).find('.OptionalWorkflowModuleUID').val(clipboard['OptionalWorkflowModuleUID']).trigger("change");
        // componentHandler.upgradeDom();
        // select_mdl();
        $(".select2picker").select2({
          tags: false,
          theme: "bootstrap",
        });
        copied = 0;
      }
    });
    /*---Workflow Tab Copypaste Functionality Ends---*/

    /*---TAT Tab Copypaste Functionality Ends---*/

    //Copy TAT Details
    $('body').on('click','.copyproduct', function(event) { 

      event.preventDefault();

      var copiedpriority = getParentByClass($(this)[0], 'prioritymoduleWrapper');
      var priorityarray = [];
      var SkipOrderOpenDatearray=[];

      $(copiedpriority).find('.PriorityTime').each(function (Prioritykey,PriorityValue) {
        priorityarray[$(PriorityValue).attr('data-priorityuid')] = $(PriorityValue).val() ? $(PriorityValue).val() : 0; 
      });
      priorityclipboard['priorityarray'] = priorityarray;
      
      $(copiedpriority).find('.SkipOrderOpenDate').each(function(SkipOrderOpenDatekey,SkipOrderOpenDateValue){
        SkipOrderOpenDatearray[$(this).attr("data-priorityuid")] = $(this).val();
      });
      priorityclipboard['SkipOrderOpenDatearray'] = SkipOrderOpenDatearray;

      console.log(priorityclipboard);
      copiedelem=copiedpriority;
      copied = 1;
    });


    // Paste Priority Details

    $(document).delegate("form#frmcustomerpriority .prioritymoduleWrapper", "click", function(e){
      if(copied == 1 && copiedelem!==this && $(e.target).hasClass('.copyproduct')!=true){
        var pastetr = $(this);
        var tesd=$(pastetr).find('.PriorityTime');
        $(pastetr).find('.PriorityTime').each(function(ToPrioritykey,ToPriorityValue){
          $(ToPriorityValue).val(priorityclipboard['priorityarray'][$(ToPriorityValue).attr('data-priorityuid')]);
          $(ToPriorityValue).trigger('change')
        });

        $(pastetr).find('.SkipOrderOpenDate').each(function(SkipOrderOpenDatekey,SkipOrderOpenDateValue){
          $(this).val(priorityclipboard['SkipOrderOpenDatearray'][$(SkipOrderOpenDateValue).attr('data-priorityuid')]).trigger("change");
        });
        // componentHandler.upgradeDom();
        // select_mdl();
        $(".select2picker").select2({
          tags: false,
          theme: "bootstrap",
        });
        copied = 0;
      }
    });

    /*---TAT Tab Copypaste Functionality Ends---*/


/*---Optional Workflows Filter Starts---*/
$(document).on('change', '.WorkflowModuleUID', function(e){
  var chged_worflows=$(this).find(':selected').map(function(key, value){
    return value.value;
  }).get();
  var parent=getParentByClass($(this)[0],'WorkflowmoduleWrapper');
  var optionalworkflowcombo=$(parent).find('.OptionalWorkflowModuleUID');
  $(chged_worflows).each(function (k, v) {
    $(optionalworkflowcombo).find('option[value="'+v+'"]').prop('selected', false);
    $('.OptionalWorkflowModuleUID').select2({
      tags: false,
      theme: "bootstrap",
    });
  })
});


$(document).on('change', '.OptionalWorkflowModuleUID', function(e){
  var chged_worflows=$(this).find(':selected').map(function(key, value){
    return value.value;
  }).get();
  var parent=getParentByClass($(this)[0],'WorkflowmoduleWrapper');
  var workflowcombo=$(parent).find('.WorkflowModuleUID');
  $(chged_worflows).each(function (k, v) {
    $(workflowcombo).find('option[value="'+v+'"]').prop('selected', false);
    $('.WorkflowModuleUID').select2({
      tags: false,
      theme: "bootstrap",
    });

  })
});

/*---Optional Workflows Filter Ends---*/


 $('body').on('click', '.defaultvalue', function(event) {

  var element = this;
  $.ajax({

    type: "POST",
    url: base_url + 'Customer/getdefaultpriorityvalues',
    dataType:'json',
    beforeSend: function(){
      // $(element).html('<span class="icon defaultvalue"><i class="fa fa-refresh fa-spin"></i></span>');
    },
    success: function(data)
    {
      $.each(data, function(k, v) {
        var parent=getParentByClass(element, 'prioritymoduleWrapper');

        $(parent).find(".PriorityTime[data-priorityuid='" + v['PriorityUID'] + "']").val(v['TAT']);
        $(parent).find(".PriorityTime[data-priorityuid='" + v['PriorityUID'] + "']").trigger('change');
      });

      // $(element).html('<span class="icon defaultvalue"><i class="fa fa-refresh"></i></span>');

      // componentHandler.upgradeDom();


    }
  });
 });

});

function myfunction(){
 $.notify(
 {
  icon:"icon-bell-check",
  message:"Record deleted successfully"
},
{
  type:"success",
  delay : 1000 
});
}

function ChangeCustomerFileDetails(file, data) {

  fsize  = bytesToSize(file.size);   
  var fname = file.name;    
  var appeddiv  = "<div class='row filediv'><div class='col-md-2'><p class='mb-0'><strong>"+fname+"</strong></p><p><strong>"+fsize+"</strong></p></div><div class='col-md-10'><a href='"+data.URL+"' target='_blank' class='btn btn-sm btn-outline-info defaultfileview'><i class='icon-eye'></i></a><button class='btn btn-outline-danger btn-sm customerdocumentremove_server'><i class='icon-x'></i></button></div></div>";
  $(".uploadedfile").html(appeddiv);   
}

function ShowViewDocumentLink(URL, currentform) {
  var view='<a class="btn btn-link btn-dribbble" href="'+URL+'" target="_blank"><i class="icon-eye"></i> View Document</a>'
  var file= $(currentform).find('.viewdocumentcontainer');
  $(currentform).find('.removeabstractordoc').addClass('removeabstractordocserver');
  $(currentform).find('.removeabstractordoc').removeClass('removeabstractordoc');
  $(currentform).find('.viewdocumentcontainer').html(view);
}

function ResetProgress(progress) {
  $(progress).width(0 + '%');
  $(progress).text('');
  $(progress).parent('.progress').hide(); 
}

function bytesToSize(bytes) {
 var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
 if (bytes == 0) return '0 Byte';
 var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
 return (bytes / Math.pow(1024, i)).toFixed(2) + ' ' + sizes[i];
};
function findParentForm(elem){ 
  var parent = elem.parentNode; 
  if(parent && parent.tagName != 'FORM'){
    parent = findParentForm(parent);
  }
  return parent;
}

function getParentForm( elem )
{
  var parentForm = findParentForm(elem);
  if(parentForm){
    return parentForm;
  }else{
    alert("unable to locate parent Form");
  }

}

function findParentElement(elem, parentClass){ 
  var parent = elem.parentNode; 
  var classlist = parent.classList;
  var ispresent=$.inArray(parentClass, classlist);
  if(parent && ispresent ==-1){
    parent = findParentElement(parent, parentClass);
  }
  return parent;
}

function getParentByClass(elem, parentClass) {
  var parentElement=findParentElement(elem, parentClass);
  if (parentElement) {
    return parentElement;
  }
  
}


$(document).ready(function() {


  var Producttable = $('#product_datatable').DataTable({
    "pagingType": "full_numbers",        
    "lengthMenu": [
    [5,10, 25, 50, -1],
    [5,10, 25, 50, "All"]
    ],
    iDisplayLength: 5,  
    buttons: [          
    {
      extend: 'excelHtml5',
      exportOptions: { orthogonal: 'export' }
    },
    {
      extend: 'pdfHtml5',
      exportOptions: { orthogonal: 'export' }
    }
    ], 
    language: {    

      paginate: {
        next: '<i class="icon-arrow-right13"></i>',
        previous: '<i class="icon-arrow-left12"></i>' 
      }
    }
  });

  var SubProducttable = $('#subproduct_datatable').DataTable({  
    "pagingType": "full_numbers",        
    "lengthMenu": [
    [5,10, 25, 50, -1],
    [5,10, 25, 50, "All"]
    ],
    iDisplayLength: 5,  
    buttons: [          
    {
      extend: 'excelHtml5',
      exportOptions: { orthogonal: 'export' }
    },
    {
      extend: 'pdfHtml5',
      exportOptions: { orthogonal: 'export' }
    }
    ], 
    language: {    

      paginate: {
        next: '<i class="icon-arrow-right13"></i>',
        previous: '<i class="icon-arrow-left12"></i>' 
      }
    }
  });




  $(document).off('change', '.product_status').on('change', '.product_status', function (e) {
    var ProductUID = $(this).attr('data-type');
    var status = $(this).val();
    if(status==1)
    {
      $(this).val('0')
      var status = 0;
    } else {
      $(this).val('1')
      var status = 1;
    } 
    $.ajax({
      type: "POST",
      url: base_url+"Customer/product_status",
      dataType: "JSON",
      data: {'ProductUID':ProductUID,'status':status}, 
      cache: false,
      success: function(data)
      {
        if(data['validation_error']==0)
        {
          $.notify({icon:"icon-bell-check",message:data.message},{type:"success",delay:1000 });
          $("#customerproduct").click();

        } else {
          $.notify({icon:"icon-bell-check",message:data.message},{type:"danger",delay:1000 });
        }
      }
    });
  });

  $(document).off('click', '.editproduct').on('click', '.editproduct', function (e) {
    var ProductUID = $(this).attr('data-type');
    $(".viewproduct").slideUp();
    $('#Productform_ProductUID,#Productform_ProductCode,#Productform_ProductName').closest('.form-group').removeClass('has-danger');
    $('#Productform_ProductCode,#Productform_ProductName').removeClass('is-invalid');

    $.ajax({
      type: "POST",
      url: base_url+"Customer/get_product_details",
      dataType: "JSON",
      data: {'ProductUID':ProductUID}, 
      cache: false,
      success: function(data)
      {
        $('.addproduct').slideDown();
        $('#Productform_ProductCode').val(data.ProductCode);
        $('#Productform_ProductUID').val(data.ProductUID);
        $('#Productform_PreviousProductName').val(data.ProductName);
        $('#Productform_PreviousProductCode').val(data.ProductCode);
        $('#Productform_ProductName').val(data.ProductName);
        $('#Productform_ProductCode,#Productform_ProductName').closest('.form-group').addClass('is-filled');
        if(data.InsuranceType == "1"){
          $( "#Productform_InsuranceType" ).prop( "checked", true );
        }else{
          $( "#Productform_InsuranceType" ).prop( "checked", false );
        }

        if(data.AgentPricing == "1"){
          $( "#Productform_AgentPricing" ).prop( "checked", true );
        }else{
          $( "#Productform_AgentPricing" ).prop( "checked", false );
        }

        if(data.UnderWritingPricing == "1"){
          $( "#Productform_UnderWritingPricing" ).prop( "checked", true );
        }else{
          $( "#Productform_UnderWritingPricing" ).prop( "checked", false );
        }

        if(data.Active == "1"){
          $( "#Productform_Active" ).prop( "checked", true );
        }else{
          $( "#Productform_Active" ).prop( "checked", false );
        }
      }
    });
  });


  $(document).off('click', '.closeaddproduct').on('click', '.closeaddproduct', function (e) {
    /* Act on the event */
    $('.addproduct').slideUp();
    $(".viewproduct").slideDown();
  });


  $('#Productform').on('submit', function(e){
    e.preventDefault();
    var button = $('#SaveProduct');
    var button_text = $('#SaveProduct').html();
    var ProductUID = $('#Productform_ProductUID').val();
    var ProductCode = $('#Productform_ProductCode').val();
    var ProductName = $('#Productform_ProductName').val();
    var PreviousProductName = $('#Productform_PreviousProductName').val();
    var PreviousProductCode = $('#Productform_PreviousProductCode').val();
    var AgentPricing = $('#Productform_AgentPricing').prop('checked') ? '1' : '0';
    var UnderWritingPricing = $('#Productform_UnderWritingPricing').prop('checked') ? '1' : '0';
    var InsuranceType = $('#Productform_InsuranceType').prop('checked') ? '1' : '0';
    var Active = $('#Productform_Active').prop('checked') ? '1' : '0';
    $.ajax({
      type: "POST",
      url: base_url+"Customer/save_product_details",
      data: {'ProductUID':ProductUID,'ProductName':ProductName,'ProductCode':ProductCode,'AgentPricing':AgentPricing,'UnderWritingPricing':UnderWritingPricing,'InsuranceType':InsuranceType,'Active':Active,'PreviousProductName':PreviousProductName,'PreviousProductCode':PreviousProductCode}, 
      dataType: "json",
      beforeSend: function(){
        button.attr("disabled", true);
        button.html('Please Wait ...'); 
      },
      success: function(successdata)
      {

        if(successdata.validation_error == 0){

          $.notify({icon:"icon-bell-check",message:successdata.message},{type:"success",delay:1000 });
          Producttable.clear();
          if(successdata.tabledata.length > 0){
            for (var i = 0; i < successdata.tabledata.length; i++) {
              Producttable.row.add([
                i,
                successdata.tabledata[i].ProductCode,
                successdata.tabledata[i].ProductName,
                '<div class="togglebutton"><label><input data-type="'+successdata.tabledata[i].ProductUID+'"  name="SubProduct_status" class="SubProduct_status" type="checkbox" '+(successdata.tabledata[i].Active == 1 ? "checked value='1'" : "value='0'") +'><span class="toggle"></span></label></div>',
                '<a href="javascript:void(0);" data-type="'+successdata.tabledata[i].ProductUID+'" class="btn btn-link btn-info btn-just-icon btn-xs editproduct"><i class="icon-pencil"></i><div class="ripple-container"></div></a>',           
                ]);
            };
          };
          Producttable.draw();
          $("#customerproduct").click();
        }else{
          $.notify({icon:"icon-bell-check",message:successdata.message},{type:"danger",delay:1000 });

          $.each(successdata, function(k, v) 
          {
            $('#Productform_'+k).closest('.form-group').removeClass('has-success').addClass('has-danger');
            $('#Productform_'+k).addClass("is-invalid");;
          });
        }
        $(".selectpicker").selectpicker();
        $(".select2picker").select2({
          tags: false,
          theme: "bootstrap",
        });
        button.html(button_text);
        button.removeAttr("disabled");
        select_mdl();
      }
    });
  });


$(document).on('click', '#cancelsubproduct', function(event) {
  event.preventDefault();
  /* Act on the event */
  $('.addsubproduct').slideUp();
  $('.subproductlist').slideDown();
});


$(document).off('click', '.editsubproduct').on('click', '.editsubproduct', function (e) {
  var SubProductUID = $(this).attr('data-type');
  $('#SubProductform_ProductUID,#SubProductform_SubProductName,#SubProductform_PreviousSubProductName,#SubProductform_SubProductCode,#SubProductform_OrderTypeUID,#SubProductform_PriorityUID,#SubProductform_ReportHeading').closest('.form-group').removeClass('has-danger');
  $('#SubProductform_ProductUID,#SubProductform_SubProductName,#SubProductform_PreviousSubProductName,#SubProductform_SubProductCode,#SubProductform_OrderTypeUID,#SubProductform_PriorityUID,#SubProductform_ReportHeading').removeClass('is-invalid');

  $.ajax({
    type: "POST",
    url: base_url+"Customer/get_subproduct_details",
    dataType: "JSON",
    data: {'SubProductUID':SubProductUID}, 
    cache: false,
    success: function(data)
    {
      console.log(data)
      $('.subproductlist').slideUp();
      $('.addsubproduct').slideDown();

      $('#SubProductform_SubProductUID').val(data.SubProductUID);
      $('#SubProductform_ProductUID').val(data.ProductUID).trigger('change');
      $('#SubProductform_SubProductName').val(data.SubProductName);
      $('#SubProductform_PreviousSubProductName').val(data.SubProductName);
      $('#SubProductform_SubProductCode').val(data.SubProductCode);
      $('#SubProductform_OrderTypeUID').val(data.OrderTypeUID).trigger('change');
      $('#SubProductform_PriorityUID').val(data.PriorityUID).trigger('change');
      $('#SubProductform_ReportHeading').val(data.ReportHeading);

      $('#SubProductform_ProductUID,#SubProductform_SubProductName,#SubProductform_PreviousSubProductName,#SubProductform_SubProductCode,#SubProductform_OrderTypeUID,#SubProductform_PriorityUID,#SubProductform_ReportHeading').closest('.form-group').addClass('is-filled');

      if(data.Active == "1"){
        $( "#SubProductform_Active" ).prop( "checked", true );
      }else{
        $( "#SubProductform_Active" ).prop( "checked", false );
      }
    }
  });
});


$('#SubProductform').on('submit', function(e){
  e.preventDefault();
  var button = $('#savesubproduct');
  var button_text = $('#savesubproduct').html();
  var SubProductUID = $('#SubProductform_SubProductUID').val();
  var ProductUID = $('#SubProductform_ProductUID').val();
  var SubProductName = $('#SubProductform_SubProductName').val();
  var PreviousSubProductName = $('#SubProductform_PreviousSubProductName').val();
  var SubProductCode = $('#SubProductform_SubProductCode').val();
  var OrderTypeUID = $('#SubProductform_OrderTypeUID').val();
  var PriorityUID = $('#SubProductform_PriorityUID').val();
  var ReportHeading = $('#SubProductform_ReportHeading').val();
  var Active = $('#SubProductform_Active').prop('checked') ? '1' : '0';
  $.ajax({
    type: "POST",
    url: base_url+"Customer/save_subproduct_details",
    data: {'SubProductUID':SubProductUID,'ProductUID':ProductUID,'SubProductName':SubProductName,'SubProductCode':SubProductCode,'OrderTypeUID':OrderTypeUID,'PriorityUID':PriorityUID,'ReportHeading':ReportHeading,'Active':Active,'PreviousSubProductName':PreviousSubProductName}, 
    dataType: "json",
    cache: false,
    beforeSend: function(){
      button.attr("disabled", true);
      button.html('Please Wait ...'); 
    },
    success: function(successdata)
    {
      if(successdata.validation_error == 0){

        $.notify({icon:"icon-bell-check",message:successdata.message},{type:"success",delay:1000 });
        $("#customerproduct").click();
        SubProducttable.clear();
        if(successdata.tabledata.length > 0){
          for (var i = 0; i < successdata.tabledata.length; i++) {
            SubProducttable.row.add([
              i,
              successdata.tabledata[i].SubProductCode,
              successdata.tabledata[i].SubProductName,
              successdata.tabledata[i].ProductName,
              successdata.tabledata[i].ReportHeading,
              '<div class="togglebutton"><label><input data-type="'+successdata.tabledata[i].SubProductUID+'"  name="SubProduct_status" class="SubProduct_status" type="checkbox" '+(successdata.tabledata[i].Active == 1 ? "checked value='1'" : "value='0'") +'><span class="toggle"></span></label></div>',
              '<a href="javascript:void(0);" data-type="'+successdata.tabledata[i].SubProductUID+'" class="btn btn-link btn-info btn-just-icon btn-xs editsubproduct"><i class="icon-pencil"></i><div class="ripple-container"></div></a>',           
              ]);
          };
        };
        SubProducttable.draw();

      }else{
        $.notify({icon:"icon-bell-check",message:successdata.message},{type:"danger",delay:1000 });

        $.each(successdata, function(k, v) 
        {
          $('#SubProductform_'+k).closest('.form-group').removeClass('has-success').addClass('has-danger');
          $('#SubProductform_'+k).addClass("is-invalid");;
        });
      }
      $(".selectpicker").selectpicker();
      $(".select2picker").select2({
        tags: false,
        theme: "bootstrap",
      });
      button.html(button_text);
      button.removeAttr("disabled");
      select_mdl();
    }
  });
});


$(document).off('change', '.SubProduct_status').on('change', '.SubProduct_status', function (e) {
  var SubProductUID = $(this).attr('data-type');
  var status = $(this).val();
  if(status==1)
  {
    $(this).val('0')
    var status = 0;
  } else {
    $(this).val('1')
    var status = 1;
  } 
  $.ajax({
    type: "POST",
    url: base_url+"Customer/subproduct_status",
    dataType: "JSON",
    data: {'SubProductUID':SubProductUID,'status':status}, 
    cache: false,
    success: function(data)
    {
      if(data['validation_error']==0)
      {
        $.notify({icon:"icon-bell-check",message:data.message},{type:"success",delay:1000 });
        $("#customerproduct").click();

      } else {
        $.notify({icon:"icon-bell-check",message:data.message},{type:"danger",delay:1000 });
      }
    }
  });
});

});


