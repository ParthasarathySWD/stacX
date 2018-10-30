
    var filetoupload = [];

    $(document).ready(function(){
      $("input#ufile").change(function() {  

      });



      $(document).off('change', '#AbstractorStatus').on('change', '#AbstractorStatus', function()
      {
        var listtype=$('#AbstractorStatus :selected').text();
        $.ajax({
          type: "POST",
          url: base_url+"Abstractor/GetTotalAbstractors",
          dataType: "JSON",
          data: {'listtype':listtype}, 
          cache: false,
          beforeSend: function () {
            $('.spinnerclass').addClass('be-loading-active');
          },
          success: function(data)
          {

            if(data['validation_error']==1)
            {
              $('#abstractorviewlist').DataTable().destroy();
              $('#abstractorviewlist').find('tbody').html(data.html);
              $('#abstractorviewlist').dataTable({
                responsive :true,
                dom: 'Bfrtip',
                buttons: [
                'excel', 'pdf'
                ]
              });

            } else {

            }
          }
        });

      });


      $(document).on('change', '#AbstractorZipCode', function(event) {
        AbstractorZipCode = $(this).val();
        $.ajax({
          type: "POST",
          url: base_url + 'Customer/getzip',
          data: {'CustomerZipCode':AbstractorZipCode}, 
          dataType:'json',
          cache: false,
          success: function(data)
          {
            $('#AbstractorCityUID').empty();
            $('#AbstractorStateUID').empty();
            $('#AbstractorCountyUID').empty();

            if(data != ''){

              $('#AbstractorCityUID').append('<option value="' + data['CityUID'] + '" selected="">' + data['CityName'] + '</option>').trigger('change');

              $('#AbstractorStateUID').append('<option value="' + data['StateUID'] + '" selected="">' + data['StateName'] + '</option>').trigger('change');
              $('#AbstractorCountyUID').append('<option value="' + data['CountyUID'] + '" selected="">' + data['CountyName'] + '</option>').trigger('change');
            }

          },
          error: function (jqXHR, textStatus, errorThrown) {

            console.log(errorThrown);

          },
          failure: function (jqXHR, textStatus, errorThrown) {

            console.log(errorThrown);

          },
        });
      });


      $(document).off('submit', '.frmabstractorinfo').on('submit', '.frmabstractorinfo', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var button = $('#UpdateAbstractor');
        var button_text = $('#UpdateAbstractor').html();
        var formdata=new FormData($(this)[0]);

        if ($('#AbstractorUID').val()!='' && typeof $('#AbstractorUID').val()!='undefined') {
          formdata.append('AbstractorUID', $('#AbstractorUID').val());
        }


        $.ajax({
          type: "POST",
          url: base_url + 'Abstractor/SaveAbstractor',
          data: formdata, 
          processData: false,
          contentType: false,
          dataType:'json',
          beforeSend: function(){
            button.prop("disabled", true);
            button.html('Loading ...'); 
            $('.errorindicator').remove();
          },
          success: function(data)
          {

            if(data.validation_error == 0){
              $.notify({icon:"icon-bell-check",message:data.message},{type:"success",delay:1000 });
              if (data.AbstractorUID>0) {
                triggerpage(base_url+'Abstractor/EditAbstractor/'+data.AbstractorUID);
              }
            }else{

              $.notify({icon:"icon-bell-check",message:data.message},{type:"danger",delay:1000 });
              $.each(data, function(k, v) 
              {
                $('#'+k).addClass("is-invalid").closest('.form-group').removeClass('has-success').addClass('has-danger');
                $('#'+ k +'.select2picker').next().find('span.select2-selection').addClass('errordisplay'); 
                $('#'+k).parent().append('<span class="errorindicator" style="color:red">'+v+'</span>');
              });
            }

            button.html(button_text);
            button.prop("disabled", false);

          }
        });

      });

      $(document).off('submit', '.frmcontactperson').on('submit', '.frmcontactperson', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var button = $('#UpdateContact');
        var button_text = $('#UpdateContact').html();
        var formdata=new FormData($(this)[0]);
        formdata.append('AbstractorUID', $('#AbstractorUID').val());

        $.ajax({
          type: "POST",
          url: base_url + 'Abstractor/SaveAbstractorContact',
          data: formdata, 
          processData: false,
          contentType: false,
          dataType:'json',
          beforeSend: function(){
            button.attr("disabled", true);
            button.html('Loading ...'); 
          },
          success: function(data)
          {

            if(data.validation_error == 0){
              $.notify({icon:"icon-bell-check",message:data.message},{type:"success",delay:1000 });
            }else{
              $.notify({icon:"icon-bell-check",message:data.message},{type:"danger",delay:1000 });

/*          $.each(data, function(k, v) 
          {
            $('#'+k).closest('.form-group').removeClass('has-success').addClass('has-danger');
            $('#'+k).addClass("is-invalid");;
          });
        */        }

        button.html(button_text);
        button.removeAttr("disabled");

      }
    });

      })

      $(document).off('submit', '.frmabstractorminorities').on('submit', '.frmabstractorminorities', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var button = $('#UpdateAbstractorMinority');
        var button_text = $('#UpdateAbstractorMinority').html();
        var formdata=new FormData($(this)[0]);
        formdata.append('AbstractorUID', $('#AbstractorUID').val());

        $.ajax({
          type: "POST",
          url: base_url + 'Abstractor/SaveAbstractorMinority',
          data: formdata, 
          processData: false,
          contentType: false,
          dataType:'json',
          beforeSend: function(){
            button.attr("disabled", true);
            button.html('Loading ...'); 
          },
          success: function(data)
          {

            if(data.validation_error == 0){
              $.notify({icon:"icon-bell-check",message:data.message},{type:"success",delay:1000 });
            }else{
              $.notify({icon:"icon-bell-check",message:data.message},{type:"danger",delay:1000 });

/*          $.each(data, function(k, v) 
          {
            $('#'+k).closest('.form-group').removeClass('has-success').addClass('has-danger');
            $('#'+k).addClass("is-invalid");;
          });
        */        }

        button.html(button_text);
        button.removeAttr("disabled");

      }
    });

      })

      $(document).off('submit', '.frmabstractorbankdetails').on('submit', '.frmabstractorbankdetails', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var button = $('#UpdateBankDetails');
        var button_text = $('#UpdateBankDetails').html();
        var formdata=new FormData($(this)[0]);
        formdata.append('AbstractorUID', $('#AbstractorUID').val());

        $.ajax({
          type: "POST",
          url: base_url + 'Abstractor/SaveAbstractorBankDetails',
          data: formdata, 
          processData: false,
          contentType: false,
          dataType:'json',
          beforeSend: function(){
            button.attr("disabled", true);
            button.html('Loading ...'); 
          },
          success: function(data)
          {

            if(data.validation_error == 0){
              $.notify({icon:"icon-bell-check",message:data.message},{type:"success",delay:1000 });
            }else{
              $.notify({icon:"icon-bell-check",message:data.message},{type:"danger",delay:1000 });

/*          $.each(data, function(k, v) 
          {
            $('#'+k).closest('.form-group').removeClass('has-success').addClass('has-danger');
            $('#'+k).addClass("is-invalid");;
          });
        */        }

        button.html(button_text);
        button.removeAttr("disabled");

      }
    });

      });



      /* ABSTRACTOR DOCUMENT SCRIPT SECTION STARTS */
      $(document).on('change', '#upload_file', function(event){

        var DocumentTypeSelect = $('#DocumentTypeSelect').val();     

        if(DocumentTypeSelect != ''){

          var output = [];
          var documenttypeuid = DocumentTypeSelect;


          for(var i = 0; i < event.target.files.length; i++)
          {
            var fileid=filetoupload.length;
            var file = event.target.files[i];
            filetoupload.push({file: file, filename: file.name });
            console.log(filetoupload);
            var DocumentTypeName = $('#DocumentTypeSelect').find(":Selected").text();

            var datetime=calcTime('Caribbean', '-5');
            var uploaded={};
            uploaded.username=USERNAME;
            uploaded.userid=USERUID;
            uploaded.datetime=datetime;

            var documentrow='<tr class="AbstractorFileRow">';
            documentrow+='<td>'+DocumentTypeName+'</td>';
            documentrow+='<td>'+file.name+'</td>';
            documentrow+='<td>'+datetime+'</td>';
            documentrow+='<td>'+UserName+'</td>';
            documentrow+='<td><div class="togglebutton"><label><input type="checkbox" name="AbstractorDocument" class="status" value="1" checked="true" disabled><span class="toggle"></span> </label></div></td>';
            documentrow+='<td style="text-align: left;"><button type="button" data-fileuploadid="'+fileid+'" class="RemoveAbstractorFile btn btn-link btn-danger btn-just-icon btn-xs"><i class="icon-x"></i></button></td>';
            documentrow+='</tr>';

            output.push(documentrow);

          }

          $('#upload-preview-table').find('tbody').append(output.join(""));

          /*Loader START To BE Added*/

        }
        else{
          $.notify({icon:"icon-bell-check",message:'Please Select Document Type'},{type:"danger",delay:1000 });

          $('#DocumentTypeSelect'+'.select2').next().find('span.select2-selection').addClass('errordisplay');
          $('#DocumentTypeSelect').closest('div.is_error').addClass('is-invalid');
        }

      });
      
      $(document).off('submit', '.frmcustomerdocuments').on('submit', '.frmcustomerdocuments', function (e) {
        e.preventDefault();
        e.stopPropagation();

        if (filetoupload.length>0 && $('#DocumentTypeSelect').val() == '') {
                $('#DocumentTypeSelect').closest('.form-group').removeClass('has-success').addClass('has-danger');
                $('#DocumentTypeSelect').addClass("is-invalid");
                $.notify({icon:"icon-bell-check",message:'Document Type is Required'},{type:"danger",delay:1000 });
                return false;
        }
        var formdata=new FormData($(this)[0]);

        $.each(filetoupload, function (key, value) {
          formdata.append('AbstractorDocument[]', value.file);
        });

        formdata.append('AbstractorUID',$('#AbstractorUID').val());
        var progress=$('.progress-bar');
        $.ajax({
          type: "POST",
          url: base_url + 'Abstractor/SaveAbstractorDocuments',
          data: formdata, 
          processData:false,
          contentType: false,
          dataType:'json',
          beforeSend: function(){
            if (filetoupload.length) {
              $("#progressupload").show();
            }
          },
          xhr: function () {
            var xhr = new window.XMLHttpRequest();
            if (filetoupload.length) {
              xhr.upload.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                  var percentComplete = evt.loaded / evt.total;
                  percentComplete = parseInt(percentComplete * 100);
                  $(progress).width(percentComplete + '%');
                  $(progress).text('Uploading ' + percentComplete + '%');
                }
              }, false);
            }
            return xhr;
          },
          success: function(data)
          {
            if(data.validation_error == 0){

              $.notify({icon:"icon-bell-check",message:data.message},{type:"success",delay:1000 });
              filetoupload=[];
              $("#documents").load(base_url + "Abstractor/loaddocument", {"AbstractorUID" : AbstractorUID});
              $("#documents").slideDown();

            }else{

              $.each(data, function(k, v) 
              {
                $('#'+k).closest('.form-group').removeClass('has-success').addClass('has-danger');
                $('#'+k).addClass("is-invalid");;
              });
              $.notify({icon:"icon-bell-check",message:data.message},{type:"danger",delay:1000 });
            }

            ResetProgress(progress);
          },
          error: function(jqXHR){
            console.log(jqXHR);
            ResetProgress(progress);
          }
        });

      });

      $(document).on('click', '.RemoveAbstractorFileServer', function (e) {
        e.preventDefault();
        e.stopPropagation();


        var button = $(this);
        var button_text = $(this).html();

        var removedata={};
        removedata.AbstractorUID=$('#AbstractorUID').val();
        removedata.abstractor_doc_uid=$(this).val();

        /*SWEET ALERT CONFIRMATION*/
        swal({
          title: "<i class='icon-warning iconwarning'></i>",     
          html: '<p>Are you sure you want to delete this record ?</p>',   
          showCancelButton: true,
          confirmButtonClass: 'btn btn-success',
          cancelButtonClass: 'btn btn-danger',
          buttonsStyling: false,
          closeOnClickOutside: false,
          allowOutsideClick: false,
          showLoaderOnConfirm: true,
          position: 'top-end'
        }).then(function(confirm) {

          $.ajax({
            type: "POST",
            url: base_url + 'Abstractor/RemoveAbstractorDocumentFile',
            data: removedata, 
            dataType:'json',
            beforeSend: function(){
              button.attr("disabled", true);
            // button.html('Loading ...'); 
          },
          success: function(data)
          {

            if(data.validation_error == 0){

              $(button).closest('tr').remove();
              /*Sweet Alert MSG*/
              swal({
                title: "<i class='icon-checkmark2 iconsuccess'></i>", 
                html: "<p>Record Deleted Successfully</p>",
                confirmButtonClass: "btn btn-success",
                allowOutsideClick: false,
                width: '300px',
                buttonsStyling: false
              }).catch(swal.noop)

            }else{
              $.notify({icon:"icon-bell-check",message:data.message},{type:"danger",delay:1000 });
              // button.html(button_text);
              button.attr("disabled", false);

            }
          }
        });


        },
        function(dismiss) {              

        });

      });

      /* ABSTRACTOR DOCUMENT SCRIPT SECTION ENDS */


      /*--- COVERAGE SECTION STARTS---*/

      $(document).off('submit', '#frmabstractorcoverage').on('submit', '#frmabstractorcoverage', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var button = $('#AddCoverage');
        var button_text = $('#AddCoverage').html();
        var formdata=new FormData($(this)[0]);
        formdata.append('AbstractorUID', $('#AbstractorUID').val());

        $.ajax({
          type: "POST",
          url: base_url + 'Abstractor/SaveCoverage',  
          data: formdata, 
          processData: false,
          contentType: false,
          dataType:'json',
          beforeSend: function(){
            button.attr("disabled", true);
            button.html('Loading ...'); 
          },
          success: function(data)
          {

            if(data.validation_error == 0){
              $.notify({icon:"icon-bell-check",message:data.message},{type:"success",delay:1000 });
              table.draw();
              $('select').find(':selected').prop('selected', false);
            }else{

              $.each(data, function(k, v) 
              {
                $('#'+k).closest('.form-group').removeClass('has-success').addClass('has-danger');
                $('#'+k).addClass("is-invalid");;
              });
              $.notify({icon:"icon-bell-check",message:data.message},{type:"danger",delay:1000 });

            }

            button.html(button_text);
            button.removeAttr("disabled");

          }
        });

      });



        $(document).off('click', '.deletecoverage').on('click','.deletecoverage',function(event) {

          var currentRow=$(this).closest("tr"); 
          var obj={};
          obj.AbstractorUID = $('#AbstractorUID').val();
          obj.CountyUID = $(this).attr('data-countyuid');
          obj.StateUID = $(this).attr('data-stateuid');
          obj.ZipCode = $(this).attr('data-zipcode');

          $.ajax({
            type: "POST",
            url: base_url+'Abstractor/DeleteCoverage',
            data: obj, 
            dataType:'json',
            cache: false,
            success: function(data)
            {
              if(data.validation_error == 1)
              {
                $.notify({icon:"icon-bell-check",message:data['message']},{type:"success",delay:3000 });
                table.draw();
              }
              else
              {
                $.notify({icon:"icon-bell-check",message:data['message']},{type:"danger",delay:3000 });
                table.draw();
              }
            },
            error: function (jqXHR, textStatus, errorThrown) {

              console.log(errorThrown);

            },
            failure: function (jqXHR, textStatus, errorThrown) {

              console.log(errorThrown);

            },
          });
        });


      /*--- COVERAGE SECTION ENDS---*/


    });
//End of Document REady Function


/*GENERAL FUNCTIONS*/
function callselect2(){
  $(".select2picker").select2({
    tags: false,
    theme: "bootstrap",
  });
}

function callselect2byclass(byclass){
  $('.'+byclass).select2({
    tags: false,
    theme: "bootstrap",
  });
}

function callselect2byid(byid){
  $('#'+byid).select2({
    tags: false,
    theme: "bootstrap",
  });
}

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

function calcTime(city, offset) {
    // create Date object for current location
    var d = new Date();

    // convert to msec
    // subtract local time zone offset
    // get UTC time in msec
    var utc = d.getTime() + (d.getTimezoneOffset() * 60000);

    // create new Date object for different city
    // using supplied offset
    var nd = new Date(utc + (3600000*offset));

    return nd.toISOString().slice(0,10);
    // return time as a string
    return "The local time for city "+ city +" is "+ nd.toLocaleString();
  }

// alert(calcTime('Caribbean', '-5'));
