<link href="<?php echo base_url(); ?>assets/plugins/dropify/css/dropify.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>assets/plugins/dropify/css/dropify.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>assets/plugins/dropify/css/dropify.css" rel="stylesheet" />

<style type="text/css">
.pd-btm-0{
	padding-bottom: 0px;
}

.margin-minus8{
	margin: -8px;
}

.mt--15{
	margin-top: -15px;
}

.bulk-notes
{
	list-style-type: none
}
.bulk-notes li:before
{
	content: "*  ";
	color: red;
	font-size: 15px;
}

.nowrap{
	white-space: nowrap
}

.table-format > thead > tr > th{
	font-size: 12px;
}
</style>
<div class="card" id="Orderentrycard">
	<div class="card-header card-header-danger card-header-icon">
		<div class="card-icon">ORDER ENTRY
		</div>
	</div>
	<div class="card-body">
		<ul class="nav nav-pills nav-pills-danger" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="#singleentry" role="tablist">
					Single Entry
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#bulkentry" role="tablist">
					Bulk Entry 
				</a>
			</li>
		</ul>

		<div class="tab-content tab-space">

			<div class="tab-pane active" id="singleentry">


				<form action="#"  name="orderform" id="order_frm">

					<div class="col-md-12 pd-0">
						
					</div>

					<div class="row">
						<div class="col-md-3">
							<div class="form-group bmd-form-group">
								<label for="Customer" class="bmd-label-floating">Customer<span class="mandatory"></span></label>
								<select class="select2picker form-control"  id="Customer" name="Customer" required>
									<option value=""></option>
									<?php foreach ($Customers as $key => $value) { ?>
										<option value="<?php echo $value->CustomerUID; ?>"><?php echo $value->CustomerName; ?></option>
									<?php } ?>								
								</select>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group bmd-form-group">
								<label for="CustomerRefNum" class="bmd-label-floating">Customer Ref Number</label>
								<input type="text" class="form-control" id="CustomerRefNum" name="CustomerRefNum" />
							</div>
						</div>


						<div class="col-md-3 productrow">
							<div class="form-group bmd-form-group">
								<label for="ProjectUID" class="bmd-label-floating">Project<span class="mandatory"></span></label>
								<select class="select2picker form-control ProjectUID"  id="ProjectUID" name="ProjectUID" required>
									<option value=""></option>
									
								</select>
							</div> 
						</div>



						<div class="col-md-3 priorityrow">
							<div class="form-group bmd-form-group">
								<label for="PriorityUID" class="bmd-label-floating">Priority<span class="mandatory"></span></label>
								<select class="select2picker form-control PriorityUID"  id="PriorityUID" name="PriorityUID" required>
									<option value=""></option>
									<option value="">Rush</option>
									<option value="">ASAP</option>
									<option value="">Normal</option>
  
								</select>
							</div> 
						</div>

					</div>

					<div class="col-md-12 pd-0 mt-30">
						<!-- <h4 class="sectionhead"><i class="icon-checkmark4 headericon"></i>Project</h4>	 -->
					</div>

					<div class="row productfield_add mt-10">


						<div class="col-md-3">
							<div class="form-group bmd-form-group">
								<label for="AltORderNumber" class="bmd-label-floating">Alternate Order Number</label>
								<input type="text" class="form-control" id="AltORderNumber" name="AltORderNumber">
							</div> 
						</div>

						<div class="col-md-3" id="divLoanNumber">
							<div class="form-group bmd-form-group">
								<label for="LoanNumber" class="bmd-label-floating">Loan Number</label>
								<input type="text" class="form-control" id="LoanNumber" name="LoanNumber">
							</div> 
						</div>


						<div class="col-md-3">
							<div class="form-group bmd-form-group">
								<label for="PropertyAddress1" class="bmd-label-floating">Address Line 1<span class="mandatory"></span></label>
								<input type="text" class="form-control" id="PropertyAddress1" name="PropertyAddress1" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group bmd-form-group">
								<label for="PropertyAddress2" class="bmd-label-floating">Address Line 2</label>
								<input type="text" class="form-control" id="PropertyAddress2" name="PropertyAddress2">
							</div>
						</div>

						<!-- <div class="col-md-3">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fa fa-usd" aria-hidden="true"></i>
									</span>
								</div>
								<div class="form-group bmd-form-group"> 
									<label for="LoanAmount" class="bmd-label-floating">Loan Amount</label>
									<input type="text" class="form-control currency" id="LoanAmount" name="LoanAmount" value="" />
								</div>
							</div>
						</div> -->

					</div>

					<div class="col-md-12 pd-0  mt-30">
						<!-- <h4 class="sectionhead"><i class="icon-checkmark4 headericon"></i>Order / Loan Details</h4>	 -->
					</div>

					<div class="row mt-10">

	
	
					</div>

					<div class="col-md-12 pd-0  mt-30">
						<!-- <h4 class="sectionhead"><i class="icon-checkmark4 headericon"></i>Contact Details</h4>	 -->
					</div>

					<div class="row mt-10">

						<div class="col-md-3">
							<div class="form-group bmd-form-group">
								<label for="PropertyZipcode" class="bmd-label-floating">Zipcode<span class="mandatory"></span></label>
								<input type="text" class="form-control" id="PropertyZipcode" name="PropertyZipcode" required>
								<span data-modal="zipcode-form" class="label label-success label-zip md-trigger" id="zipcodeadd" style="display: none;">Add Zipcode</span>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group bmd-form-group">
								<label for="PropertyCityName" class="bmd-label-floating">City<span class="mandatory"></span></label>
								<input type="text" class="form-control" id="PropertyCityName" name="PropertyCityName" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" required>
								<ul class="dropdown-menu dropdown-style PropertyCityName"></ul>
							</div>											
						</div>

						<div class="col-md-3">
							<div class="form-group bmd-form-group">
								<label for="PropertyCountyName" class="bmd-label-floating">County<span class="mandatory"></span></label>
								<input type="text" class="form-control" id="PropertyCountyName" name="PropertyCountyName" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" required>
								<ul class="dropdown-menu dropdown-style PropertyCountyName"></ul>
							</div>											
						</div>
						<div class="col-md-3">
							<div class="form-group bmd-form-group">
								<label for="PropertyStateCode" class="bmd-label-floating">State<span class="mandatory"></span></label>
								<input type="text" class="form-control" id="PropertyStateCode" name="PropertyStateCode" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" required>
								<ul class="dropdown-menu dropdown-style PropertyStateCode"></ul>
							</div>											
						</div>

					</div>
					<div class="row mt-10">

					</div>


					<div class="row">
						<div class="col-md-12 text-left">									
							<button type="button" class="btn btn-dribbble btn-sm btn-github" id="btnpricingimport"><i class="icon-upload4 pr-10"></i>Upload File(s)</button>
						</div>
						<div class="col-md-12 mt-20" style="display: none;" id="pricingimport">
								<input type="file" id="DocumentUpload" class="dropify" accept=".pdf">
								<div class="col-md-12 text-right">
									<!-- <button type="submit" class="btn btn-success btn-sm" id="btnUploadExcelFile"><i class="icon-upload4"></i> Upload<div class="ripple-container"></div></button> -->
									<button type="button" class="btn  btn-gray-outline  btn-sm" id="btncloseupload"><i class="icon-cross3 pr-10"></i>Cancel</button> 
								</div>
								<div class="progress progress-line-info" id="orderentry-progressupload" style="display:none; height: 22px;">
									<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width:0%; height: 21px;">
										<span class="sr-only">0% Complete</span>
									</div>
								</div>

								<div class="table-responsive">
									<table class="table table-bordered" id="DocumentPreviewTable">
										<thead class="text-primary">
											<th>
												Document Name	
											</th>
											<th>
												Uploaded DateTime
											</th>
											<th>
												Uploaded User	
											</th>
											<th>
												IsStacking
											</th>
											<th>
												Action
											</th>
										</thead>
										<tbody>

										</tbody>
									</table>
								</div>
						</div>


					</div>

					<div class="col-md-12 pd-0 mt-30">
						<!-- <h4 class="sectionhead"><i class="icon-checkmark4 headericon"></i>Property Roles</h4>	 -->
					</div>
					<div class="field_add">
						<div class="row mt-10">
							<div class="col-md-12">
								<div class="row">


								</div>

							</div>											
						</div>
					</div>
					<div class="col-sm-12 form-group pull-right">
						<p class="text-right">
							<button type="submit" class="btn btn-space btn-social btn-color btn-twitter single_submit" value="1">Save &amp; New Order</button>
							<button type="submit" class="btn btn-space  btn-social btn-color btn-tumblr single_submit" value="2">Save &amp; Assign
							<button type="submit" class="btn btn-space btn-social btn-color btn-dribbble single_submit" value="3">Save &amp; Exit</button>
						</p>
					</div>

				</form>
			</div>

			<div class="tab-pane" id="bulkentry">

				<div class="row">

					<div class="col-md-4">
						<div class="form-group bmd-form-group">
							<label for="bulk_Customers" class="bmd-label-floating">Customer<span class="mandatory"></span></label>
							<select class="select2picker form-control"  id="bulk_Customers" name="bulk_Customers">
								<option value=""></option>
									<?php foreach ($Customers as $key => $value) { ?>
										<option value="<?php echo $value->CustomerUID; ?>"><?php echo $value->CustomerName; ?></option>
									<?php } ?>								
							</select>		
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group bmd-form-group">
							<label for="bulk_ProjectUID" class="bmd-label-floating">Project<span class="mandatory"></span></label>
							<select class="select2picker form-control"  id="bulk_ProjectUID" name="bulk_ProjectUID">
								<option value=""></option>
							</select>		
						</div>
					</div>




					<div class="col-sm-4">
						<p class="text-right">
							<button class="btn btn-primary changeentry"><i class="icon-file-excel"></i> Upload Excel</button>	
						</p>
					</div>
				</div>


				<div class="textentry">

					<form id="bulkentry-form" class="form group-border-dashed">
						<div class="form-group bmd-form-group">
							<textarea required class="form-control" rows="10" placeholder="Project... Priority... Alternate OrderNumber... Loan Number... Property Address... Property ZipCode... Property City... Property County... Property State..." 
							name="bulk_order_details" id="bulk_order_details" style="resize:none;" ></textarea>
						</div>

						<div class="text-right">
							<button type="submit" class="btn btn-success" id="text_bulk_save">Save</button>
							<a href="" class="btn btn-github" id="text_preview">Preview</a>
						</div>

					</form>
				</div>

				<div class="row fileentry mt-10" style="display: none;" >
					<div class="col-sm-6">
						<form  name="formfile"  class="formfile">
							<input type="file" name="excelfile" id="filebulk_entry" class="dropify">
						</form>
						<div class="text-right form-group">
							<button type="submit" class="btn btn-success" id="bulk_save">Save</button>
							<a href="" class="btn btn-space btn-social btn-color btn-github" id="preview">Preview</a>
						</div>
					</div>

					<div class="col-sm-6">
						<ul class="bulk-notes">
							
							<li >Please follow the below steps to upload Order.</li><br/>
							<li>Download the available Excel Format XLSX sheet.<br/>
								<a href="<?php echo base_url('Orderentry/bulkentrypreviewfile'); ?>" class="btn btn-primary">EXCEL FORMAT</a>
							</li><br/>
							<li>Fill in your Order details into the downloaded XLSX.</li><br />
							<li>Upload file size max 5MB </li><br />
							<li>Upload back the XLSX.<br/><br /></li>  
						</ul>
					</div>


				</div>
				<div class="form-group" id="preview-table">


				</div>

				<div class="form-group" id="imported-table">


				</div>

			</div>
		</div>

	</div>
</div>




<script src="<?php echo base_url(); ?>assets/plugins/dropify/js/dropify.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/CommonAjax.js" type="text/javascript"></script>

<script type="text/javascript">
	$(document).ready(function(){

			filetoupload=[];
			$('#filebulk_entry').dropify();	

			$(".select2picker").select2({
			theme: "bootstrap",
			});


			$('.changeentry').click(function()
			{
			$('.textentry').toggle();
			$('.fileentry').toggle();
			$('#preview-table').html('');
			$('#imported-table').html('');

			});


			$('#btnpricingimport').off('click').on('click', function (e) {
				$('#pricingimport').slideToggle('slow');
			});

			
			$('#btncloseupload').off('click').on('click', function (e) {
				$('#pricingimport').slideUp('slow');
			});

			/* --- Dropify initialization starts */

		$('.dropify').dropify();

                // Used events
                var drEvent = $('.dropify').dropify();

                drEvent.on('dropify.beforeClear', function(event, element){
                	// return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
                });

                drEvent.on('dropify.afterClear', function(event, element){
                	// alert('File deleted');
                });

                drEvent.on('dropify.errors', function(event, element){
                	console.log('Has Errors');
                });

                var drDestroy = $('#input-file-to-destroy').dropify();
                drDestroy = drDestroy.data('dropify')
                $('#toggleDropify').on('click', function(e){
                	e.preventDefault();
                	if (drDestroy.isDropified()) {
                		drDestroy.destroy();
                	} else {
                		drDestroy.init();
                	}
                });


			/* --- Dropify initialization ends */

			$(document).off('click',".single_submit").on('click',".single_submit", function(e) {
				$(".single_submit", $(this).parents("form")).removeAttr("clicked");
				$(this).attr("clicked", "true");
				console.log($(this));
			});

			/*For single entry*/
			$(document).off('submit', '#order_frm').on('submit', '#order_frm', function(event) {
				/* Act on the event */
				event.preventDefault();
				event.stopPropagation();
				button = $(".single_submit[clicked=true]");
				button_val = $(".single_submit[clicked=true]").val();
				button_text = $(".single_submit[clicked=true]").html();
				
				console.log(button);
				// var LoanAmount = $('#LoanAmount').val();
				// LoanAmount = LoanAmount.replace(/[,$]/g , ''); 
				// var LoanAmount = Number(LoanAmount);
				// var formData = $('#order_frm').serialize()+'&'+$.param({ 'LoanAmount': LoanAmount });

				var progress=$('.progress-bar');


				$('#DocumentUpload').val('');
				var formData = new FormData($(this)[0]);
				

				$.each(filetoupload, function (key, value) {
					formData.append('DocumentFiles[]', value.file);
				});

				$.ajax({
					type: "POST",
					url: '<?php echo base_url(); ?>OrderEntry/insert',
					data: formData, 
					dataType:'json',
					cache: false,
					processData:false,
					contentType:false,
					beforeSend: function(){
						addcardspinner('#Orderentrycard');
						button.attr("disabled", true);
						button.html('Loading ...'); 
						if (filetoupload.length) {
						$("#orderentry-progressupload").show();
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
						if(data['validation_error'] == 0){

							$.notify({icon:"icon-bell-check",message:data['message']},{type:"success",delay:3000 });

							if(button_val == 1)
							{
								triggerpage(base_url+'OrderEntry');
							}

							if(button_val == 2)
							{
								triggerpage(base_url+'OrderAssignment');
								$('#leftsidebarmenu li').removeClass('active')
								$('a[href*="'+base_url+'OrderAssignment"]').parent().addClass('active');

							}

							if(button_val == 3)
							{
								triggerpage(base_url+'Dashboard');
								$('#leftsidebarmenu li').removeClass('active')
								$('a[href*="'+base_url+'Dashboard"]').parent().addClass('active');
							}


						}else if(data['validation_error'] == 1){

							removecardspinner('#Orderentrycard');

							$.notify({icon:"icon-bell-check",message:data['message']},{type:"danger",delay:1000 });

							button.html(button_text);
							button.removeAttr("disabled");


							$.each(data, function(k, v) {
								$('#'+k).addClass("is-invalid").closest('.form-group').removeClass('has-success').addClass('has-danger');
								$('#'+ k +'.select2picker').next().find('span.select2-selection').addClass('errordisplay');

							});
						}else if(data['validation_error'] == 2){
							removecardspinner('#Orderentrycard');
							$('#duplicate-modal').modal('show');
							$('#Skip_duplicate').val(1);
							$('#button_value').val(button_val);
							$('#insert_html').html(data['html']);	
							$('#insert_order').removeAttr('disabled');									
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



      /* ABSTRACTOR DOCUMENT SCRIPT SECTION STARTS */
      $(document).on('change', '#DocumentUpload', function(event){


          var output = [];


          for(var i = 0; i < event.target.files.length; i++)
          {
            var fileid=filetoupload.length;
            var file = event.target.files[i];
            filetoupload.push({file: file, filename: file.name , is_stacking: 1});
            console.log(filetoupload);

            var datetime=calcTime('Caribbean', '-5');
            var uploaded={};
            uploaded.username=USERNAME;
            uploaded.userid=USERUID;
            uploaded.datetime=datetime;

            var documentrow='<tr class="AbstractorFileRow">';
            documentrow+='<td>'+file.name+'</td>';
            documentrow+='<td>'+datetime+'</td>';
            documentrow+='<td>'+USERNAME+'</td>';
            documentrow+='<td><div class="togglebutton"><label><input type="checkbox" name="Stacking['+fileid+']" class="chkbox_stacking" value="1" checked="true"><span class="toggle"></span> </label></div></td>';
            documentrow+='<td style="text-align: left;"><button type="button" data-fileuploadid="'+fileid+'" class="DeleteUploadDocument btn btn-link btn-danger btn-just-icon btn-xs"><i class="icon-x"></i></button></td>';
            documentrow+='</tr>';

            output.push(documentrow);

          }

          $('#DocumentPreviewTable').find('tbody').append(output.join(""));

          /*Loader START To BE Added*/

      });
   


		$("body").on("click" , ".DeleteUploadDocument" , function(e){
			e.preventDefault();

			var currentrow = $(this);
			var fuid = $(currentrow).attr('data-fileuploadid');

			filetoupload.splice(fuid,1);
			$(currentrow).closest('tr').remove();
			console.log(filetoupload);

			$('tr.AbstractorFileRow').find('.DeleteUploadDocument').each(function(key, element){
				$(element).attr('data-fileuploadid', key);
			});
		});


		/* ---- BULK ORDER ENTRY STARTS ----*/

		//preview bulk entry
		$('#preview').click(function(event) {
			event.preventDefault();
			button = $(this);
			button_val = $(this).val();
			button_text = $(this).html();
			
			var file_data = $('#filebulk_entry').prop('files')[0];
			var form_data = new FormData();
			form_data.append('file', file_data);
			form_data.append('CustomerUID', $('#bulk_Customers').val());
			form_data.append('ProductUID', $('#bulk_products').val());
			form_data.append('SubProductUID', $('#bulk_subproducts').val());
			
			
			
			$.ajax({
				type: "POST",
				url: '<?php echo base_url(); ?>orderentry/preview_bulkentry',
				data: form_data,
				processData: false,
				contentType: false,
				cache:false,
				
				beforeSend: function(){
					$('.spinnerclass').addClass("be-loading-active");  
					button.attr("disabled", true);
					button.html('Loading ...'); 
				},
				success: function(data)
				{ 
					$('.spinnerclass').removeClass("be-loading-active");
					$('#preview-table').html('');
					try {
						obj = JSON.parse(data);
						$.gritter.add({
							title: obj['message'],
							class_name: 'color danger',
							fade: true,
							time: 1000,
							speed:'fast',
							
						});
					} catch (e) {
						$('#imported-table').html('');
						$('#preview-table').html(data);
					}
					
					button.html('Preview');
					button.removeAttr("disabled");
					
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log(errorThrown);
					
				},
				failure: function (jqXHR, textStatus, errorThrown) {
					
					console.log(errorThrown);
					
				},
			});
		});



		//save bulk entry
		$('#bulk_save').click(function(event) {
			event.preventDefault();
			button = $(this);
			button_val = $(this).val();
			button_text = $(this).html();
			var file_data = $('#filebulk_entry').prop('files')[0];
			var form_data = new FormData();
			form_data.append('file', file_data);
			form_data.append('CustomerUID', $('#bulk_Customers').val());
			form_data.append('ProductUID', $('#bulk_products').val());
			form_data.append('SubProductUID', $('#bulk_subproducts').val());
			form_data.append('ProjectUID', $('#bulk_projects').val());
			
			$.ajax({
				type: "POST",
				url: '<?php echo base_url(); ?>orderentry/save_bulkentry',
				data: form_data,
				processData: false,
				contentType: false,
				cache:false,
				beforeSend: function(){
					$('.spinnerclass').addClass("be-loading-active");
					button.attr("disabled", true);
					button.html('Loading ...'); 
				},
				success: function(data)
				{
					button.html('save'); 
					button.removeAttr('disabled');
					$('.spinnerclass').removeClass("be-loading-active");
					try {
						obj = JSON.parse(data);
						$.gritter.add({
							title: obj['message'],
							// text: data['message'],
							class_name: 'color danger',
							fade: true,
							time: 1000,
							speed:'fast',
							
						});
					} catch (e) {
						$('#preview-table').html('');
						$('#imported-table').html(data);
						// 	$('#modal-data').html(data); 
						// 	$('#md-modal').modal('show');
						// 	$("#md-modal").on("hidden.bs.modal", function () {
							// 	window.location.replace("<?php echo base_url(); ?>orderentry");
							// });
						}
						$('.dropify-clear').click();
						
						
						
					},
					error: function (jqXHR, textStatus, errorThrown) {
						console.log(errorThrown);
						
					},
					failure: function (jqXHR, textStatus, errorThrown) {
						
						console.log(errorThrown);
						
					},
				});
			});
			
			
			//preview bulk entry
			$('#text_preview').click(function(event) {
				event.preventDefault();
				button = $(this);
				button_val = $(this).val();
				button_text = $(this).html();
				
				var CustomerUID = $('#bulk_Customers').val();
				var ProductUID = $('#bulk_products').val();
				var SubProductUID = $('#bulk_subproducts').val();
				
				
				var formData = $('#bulkentry-form').serialize()+ '&CustomerUID=' + CustomerUID+ '&ProductUID=' + ProductUID+ '&SubProductUID=' + SubProductUID;
				
				$.ajax({
					type: "POST",
					url: '<?php echo base_url(); ?>orderentry/text_preview_bulkentry',
					data: formData,
					// dataType:'json',
					beforeSend: function(){
						$('.spinnerclass').addClass("be-loading-active");
						button.attr("disabled", true);
						button.html('Loading ...'); 
					},
					success: function(data)
					{
						$('.spinnerclass').removeClass("be-loading-active");
						button.html('Preview');
						
						button.removeAttr("disabled");
						
						try {
							obj = JSON.parse(data);
							$.gritter.add({
								title: obj['message'],
								class_name: 'color danger',
								fade: true,
								time: 1000,
								speed:'fast',
								
							});
						} catch (e) {
							$('#preview-table').html(data);
							
							
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
			
			
			//save bulk entry
			$('#text_bulk_save').click(function(event) {
				event.preventDefault();
				button = $(this);
				button_val = $(this).val();
				button_text = $(this).html();
				var CustomerUID = $('#bulk_Customers').val();
				var ProductUID = $('#bulk_products').val();
				var SubProductUID = $('#bulk_subproducts').val();
				var ProjectUID = $('#bulk_projects').val();
				
				var formData = $('#bulkentry-form').serialize()+ '&CustomerUID=' + CustomerUID+ '&ProductUID=' + ProductUID+ '&SubProductUID=' + SubProductUID+ '&ProjectUID=' + ProjectUID;
				$.ajax({
					type: "POST",
					url: '<?php echo base_url(); ?>orderentry/text_save_bulkentry',
					data: formData,
					beforeSend: function(){
						$('.spinnerclass').addClass("be-loading-active");
						button.attr("disabled", true);
						button.html('Loading ...'); 
					},
					success: function(data)
					{
						button.html('save'); 
						button.removeAttr('disabled');
						$('.spinnerclass').removeClass("be-loading-active");
						
						try {
							obj = JSON.parse(data);
							$.gritter.add({
								title: obj['message'],
								// text: data['message'],
								class_name: 'color danger',
								fade: true,
								time: 1000,
								speed:'fast',
								
							});
						} catch (e) {
							$('#preview-table').html('');
							$('#imported-table').html(data);
							// $('#modal-data').html(data); 
							// $('#md-modal').modal('show');
							// $("#md-modal").on("hidden.bs.modal", function () {
								// 	window.location.replace("<?php echo base_url(); ?>orderentry");
								// });
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
				


		/* ---- BULK ORDER ENTRY ENDS ----*/

	});
	</script>







