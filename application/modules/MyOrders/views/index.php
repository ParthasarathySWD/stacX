<style>
	.DTFC_RightBodyWrapper{
		z-index: 9;
	}

	.labelinfo{
		color: #3e3e3e;
		font-weight: 600;
	}

	.notification-right{
		position: absolute;
		top: 10px;
		border: 1px solid #FFF;
		right: 46px;
		font-size: 9px;
		background: #f44336;
		color: #FFFFFF;
		min-width: 20px;
		padding: 0px 5px;
		height: 20px;
		border-radius: 10px;
		text-align: center;
		line-height: 19px;
		vertical-align: middle;
		display: block;
	}
</style>

<div class="card">
	<div class="card-header card-header-danger card-header-icon">
		<div class="card-icon">
			<i class="icon-folder-check"></i>
		</div>
		<div class="row">
			<div class="col-md-6">
				<h4 class="card-title">My Orders</h4>
			</div>
		</div>
	</div>
	<div class="card-body">
		<ul class="nav nav-pills nav-pills-rose" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="#orderslist" role="tablist">
					My Orders
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#lastviewedlist" role="tablist">
					Last Viewed Orders
				</a>
			</li>
		</ul>
		<?php
		if($IsGetNextOrder == 1)
		{ ?>
			<div class="row" style="margin-top: 10pt;margin-left: 15pt;">
				<div class="col-md-2">
					<div class="form-group bmd-form-group">
						<label for="ProjectUID" class="bmd-label-floating">Project <span class="mandatory"></span></label>
						<select class="select2picker form-control" id="ProjectUID"  name="ProjectUID">                   
						    <option value=""></option>
							<?php 
							foreach ($Projects as $key => $value) { ?>

								<option value="<?php echo $value->ProjectUID; ?>" ><?php echo $value->ProjectName; ?></option>
							<?php } ?>
						</select>
						</div>
				</div>

				<div class="col-md-2">
					<div class="form-group bmd-form-group">
						<label for="Workflow" class="bmd-label-floating">Workflow<span class="mandatory"></span></label>
						<select class="select2picker form-control" id="Workflow"  name="Workflow">  
						<option value=""></option>	
						<option value="1">Production</option>                 
						<option value="2">QC Orders</option>               
						</select>
					</div>
				</div>
				<div class="col-md-2">
	           		<button type="button" class="btn btn-fill  btn-danger btn-wd getnextorder" ><i class="icon-rotate-ccw2 pr-10"></i>Get Next Order</button>
				</div>
			</div>
		    <div class="ml-auto text-right">
	        </div>
		<?php } ?>

		<div class="tab-content tab-space">
			<div class="tab-pane active" id="orderslist">
						<div class="col-md-12 col-xs-12">
							<div class="material-datatables" id="myordertable_parent">
								<table class="table table-striped display nowrap" id="myordertable"  cellspacing="0" width="100%"  style="width:100%">
									<thead>
										<tr>
											<th>Prop No</th>
											<th>Customer </th>
											<th>Current Status</th>
											<th>Property Address</th>
											<th>Property City</th>
											<th>Property County</th>
											<th>Property State</th>
											<th>Zip Code</th>
											<th>Project</th>
											<th>Actions</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="tab-pane" id="lastviewedlist">
						<div class="col-md-12 col-xs-12">
							<div class="material-datatables tablescroll">
								<table class="table table-striped display nowrap" id="lastviewed_table"  cellspacing="0" width="100%">
									<thead>
										<tr >
											<th class="no-sort">Order No</th>
											<th class="no-sort">Product/SubProduct</th>
											<th class="no-sort">State</th>
											<th class="no-sort">Assigned User</th>
											<th class="no-sort">Status</th>
											<th class="no-sort">Ordered Date</th>
											<th class="no-sort">Due Date</th>
											<th class="no-sort">Actions</th>
										</tr>
									</thead>
									<tbody>
									<?php for ($i=0; $i < 5; $i++) {   ?>
										<tr>
											<td class="no-sort">Order No</td>
											<td class="no-sort">Product/SubProduct</td>
											<td class="no-sort">State</td>
											<td class="no-sort">Assigned User</td>
											<td class="no-sort">Status</td>
											<td class="no-sort">Ordered Date</td>
											<td class="no-sort">Due Date</td>
											<td class="no-sort">Actions</td>


										</tr>


											<?php   }?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<input type="hidden" id="AppendCancelOrderUID">
					</div>

				</div>
			</div>

			<div id="md-cancelorder" tabindex="-1" role="dialog"  class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header text-center" style="background-color: #1d4870;">
							<h5 style="color: #fff;">Order Cancellation Request</h5>
						</div>
						<div class="modal-body">

							<div class="form-group">
								<div id="append_history"></div>
								<form id="OrderCancellation">
									<div class="col-md-12">
										<div class="form-group">
											<label for="Remarks" class="bmd-label-floating">Remarks <span class="mandatory"></span> </label>
											<input type="text" class="form-control"  id="Remarks" name="Remarks" value=""/>
										</div>
									</div>
								</form>
							</div>
							<div class="text-right">
								<button class="btn btn-space btn-social btn-color btn-success Proceed" disabled="true" id="Proceed" value="" >Proceed</button>
								<button class="btn btn-space btn-social btn-color btn-danger Close" data-dismiss="modal" style="" id="Close" value="" >Close</button>
							</div>
						</div>

					</div>
				</div>
			</div>



			<script type="text/javascript">
				var myordertable = false;
				$(function() {
					$(".select2picker").select2({
						tags: false,
						theme: "bootstrap",
					});
					$('#myordertable').DataTable().destroy();
				});
				$(document).ready(function(){

					myordertable = $('#myordertable').DataTable( {
						scrollX:        true,
						scrollCollapse: true,
						paging:  true,
						"autoWidth": true,
					"processing": true, //Feature control the processing indicator.
					"serverSide": true, //Feature control DataTables' server-side processing mode.
					"order": [], //Initial no order.
					"pageLength": 50, // Set Page Length
					"lengthMenu":[[10, 25, 50, 100], [10, 25, 50, 100]],
					language: {
						sLengthMenu: "Show _MENU_ Orders",
						emptyTable:     "No Orders Found",
						info:           "Showing _START_ to _END_ of _TOTAL_ Orders",
						infoEmpty:      "Showing 0 to 0 of 0 Orders",
						infoFiltered:   "(filtered from _MAX_ total Orders)",
						zeroRecords:    "No matching Orders found",
						processing: '<svg class="d2tspinner-circular" viewBox="25 25 50 50"><circle class="d2tspinner-path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg>'
					},
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "<?php echo base_url('MyOrders/myorders_ajax_list')?>",
						"type": "POST"
					}

				});

					var lastviewed_table = $('#lastviewed_table').DataTable({

						lengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]] ,
						scrollCollapse: true,
						responsive:false,
						paging:true,
						language: {
							sLengthMenu: "Show _MENU_ Orders",
							emptyTable:     "No Orders Found",
							info:           "Showing _START_ to _END_ of _TOTAL_ Orders",
							infoEmpty:      "Showing 0 to 0 of 0 Orders",
							infoFiltered:   "(filtered from _MAX_ total Orders)",
							zeroRecords:    "No matching Orders found",
							processing: '<span class="progrss"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i> Processing...</span>'
						},

						columnDefs: [
						{
							orderable: false, targets:  "no-sort"}
							],
						});

					$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
						$($.fn.dataTable.tables(true)).DataTable().columns.adjust().fixedColumns().relayout().responsive.recalc();
					});

					$(window).resize(function() {
						$($.fn.dataTable.tables( true ) ).css('width', '100%');
						$($.fn.dataTable.tables(true)).DataTable().columns.adjust().fixedColumns().relayout();
					});


					new $.fn.dataTable.FixedColumns(myordertable, {
						leftColumns: 1,
						rightColumns: 1,
						heightMatch: 'auto'
					} );


					$('.Proceed').prop('disabled', true);
					$('.vendorRejectSubmit').prop('disabled', true);
					$('.CancelOrder').hide();
					$(document).on('click','.cancel_orders',function()
					{
						var OrderUID = $(this).attr('data-OrderUID');

						var data = {};
						data['OrderUID'] = OrderUID;
						$.post('<?php echo base_url();?>my_orders/get_order_history',data,function(responseText) {
							$('#append_history').html(responseText);
						});

						$('#md-cancelorder').modal('show');

						$('#AppendCancelOrderUID').val(OrderUID);
					});

					$('.remarks').keyup(function()
					{
						if($(".remarks").val().length > 0)
						{
							$('.Proceed').prop('disabled', false);
						}
						else
						{
							$('.Proceed').prop('disabled', true);
						}
					});

					/*GET NEXT ORDER*/

					/*--- GETNEXTORDER - TRIGGERED WHEN GROUP SELECTED ---*/
					$("#group").change(function(){


						$('#group_products').empty();
						$('#group_subproducts').empty();
						$('#group_workflows').empty();
						$('#group_products').append('<option value=""></option>').trigger('change');
						var GroupUID = $('#group option:selected').val();

						if(GroupUID != '')
						{
							$.ajax({
								type: "POST",
								url: '<?php echo base_url();?>MyOrders/get_product_bygroup',
								data:{'GroupUID':GroupUID},
								dataType:'json',
								beforeSend: function(){

									$('.spinnerclass').addClass("be-loading-active");

								},
								success: function(data)
								{



									$('.spinnerclass').removeClass("be-loading-active");

									if(data['Error'] == "0"){

										$.each(data['data'], function(k, v) {
											$('#group_products').append('<option value="' + v['ProductUID'] + '">' + v['ProductName'] + '</option>');
										});



										$('.spinnerclass').removeClass("be-loading-active");


									}
								},
							});
						}else{
							$('#group_products').empty();
							$('#group_products').append('<option value=""></option>').trigger('change');
							$('#group_subproducts').empty();
							$('#group_subproducts').append('<option value=""></option>').trigger('change');
							$('#group_workflows').empty();
							$('#group_workflows').append('<option value=""></option>').trigger('change');
						}

						select_mdl();
					});

					/*--- GETNEXTORDER - TRIGGERED WHEN PRODUCT SELECTED ---*/
					$("#group_products").change(function(){
						var GroupUID = $('#group option:selected').val();
						var ProductUID = $('#group_products option:selected').val();

						if(GroupUID !='' && ProductUID){
							$.ajax({
								type: "POST",
								url: '<?php echo base_url();?>MyOrders/subproduct_bygroup_product',
								data:{'GroupUID':GroupUID,'ProductUID':ProductUID},
								dataType:'json',
								beforeSend: function(){

									$('.spinnerclass').addClass("be-loading-active");

								},
								success: function(data)
								{



									$('.spinnerclass').removeClass("be-loading-active");

									if(data['Error'] == "0"){

										$('#group_subproducts').empty();
										$('#group_workflows').empty();
										$('#group_subproducts').append('<option value=""></option>');
										$('#group_workflows').append('<option value=""></option>');

										$.each(data['data'], function(k, v) {
											$('#group_subproducts').append('<option value="' + v['SubProductUID'] + '">' + v['SubProductName'] + '</option>');
										});


										$.each(data['Workflows'], function(k, v) {
											$('#group_workflows').append('<option value="' + v['WorkflowModuleUID'] + '">' + v['WorkflowModuleName'] + '</option>');
										});



										$('.spinnerclass').removeClass("be-loading-active");


									}else{

										$('#group_subproducts').empty();
										$('#group_workflows').empty();
										$('#group_subproducts').append('<option value=""></option>').trigger('change');
										$('#group_workflows').append('<option value=""></option>').trigger('change');

									}
								},
							});
						}else{
							$('#group_subproducts').empty();
							$('#group_workflows').empty();
							$('#group_subproducts').append('<option value=""></option>').trigger('change');
							$('#group_workflows').append('<option value=""></option>').trigger('change');

						}
						select_mdl();
					});

					/*--- GETNEXTORDER - TRIGGERED WHEN SUBPRODUCT SELECTED ---*/

					$("#group_subproducts").change(function(){

						$('#group_workflows').empty();

						$('#group_workflows').append('<option value=""></option>').trigger('change');

						var GroupUID = $('#group option:selected').val();
						var ProductUID = $('#group_products option:selected').val();
						var SubProductUID = $('#group_subproducts option:selected').val();


						if(GroupUID !='' && ProductUID!= '' && SubProductUID != ''){
							$.ajax({
								type: "POST",
								url: '<?php echo base_url();?>MyOrders/get_workflowbygroups',
								data:{'GroupUID':GroupUID,'ProductUID':ProductUID,'SubProductUID':SubProductUID},
								dataType:'json',
								beforeSend: function(){

									$('.spinnerclass').addClass("be-loading-active");

								},
								success: function(data)
								{



									$('.spinnerclass').removeClass("be-loading-active");

									if(data['Error'] == "0"){

										$('#group_workflows').empty();
										$('#group_workflows').append('<option value=""></option>');

										$.each(data['data'], function(k, v) {
											$('#group_workflows').append('<option value="' + v['WorkflowModuleUID'] + '">' + v['WorkflowModuleName'] + '</option>');
										});





										$('.spinnerclass').removeClass("be-loading-active");


									}
								},
							});
						}else if( GroupUID !='' && ProductUID!= ''  && SubProductUID == ''){
							$.ajax({
								type: "POST",
								url: '<?php echo base_url();?>MyOrders/subproduct_bygroup_product',
								data:{'GroupUID':GroupUID,'ProductUID':ProductUID},
								dataType:'json',
								beforeSend: function(){

									$('.spinnerclass').addClass("be-loading-active");

								},
								success: function(data)
								{



									$('.spinnerclass').removeClass("be-loading-active");

									if(data['Error'] == "0"){

										$('#group_workflows').empty();
										$('#group_workflows').append('<option value=""></option>');



										$.each(data['Workflows'], function(k, v) {
											$('#group_workflows').append('<option value="' + v['WorkflowModuleUID'] + '">' + v['WorkflowModuleName'] + '</option>');
										});



										$('.spinnerclass').removeClass("be-loading-active");


									}
								},
							});
						}
						select_mdl();
					});


					/*--- GETNEXTORDER - TRIGGERED WHEN GETNEXTORDER BUTTON CLICKED ---*/

					$('#getnextorder').click(function(){
						var button = $('#getnextorder');
						var button_text = $('#getnextorder').html();
						var filter_workflow = $('#group_workflows option:selected').val();
						var GroupUID = $('#group option:selected').val();
						var ProductUID = $('#group_products option:selected').val();
						var SubProductUID = $('#group_subproducts option:selected').val();
						if(filter_workflow == ''){
							$('#group_workflows').addClass("is-invalid").closest('.form-group').removeClass('has-success').addClass('has-danger');
							$('#group_workflows.select2picker').next().find('span.select2-selection').addClass('errordisplay');
							$.notify({icon:"icon-bell-check",message:"Select Workflow"},{type:"danger",delay:1000 });
							return false;
						}
						$.ajax({
							type: "POST",
							url: '<?php echo base_url();?>MyOrders/getnextorder',
							data:{'filter_workflow':filter_workflow,'GroupUID':GroupUID,'ProductUID':ProductUID,'SubProductUID':SubProductUID},
							dataType:'json',
							beforeSend: function(){
								button.attr("disabled", true);
								button.html('Loading ...');
								$('.spinnerclass').addClass("be-loading-active");

							},
							success: function(data)
							{

								$('.spinnerclass').removeClass("be-loading-active");

								if(data['Error'] == "0"){

									$.notify({icon:"icon-bell-check",message:data['message']},{type:data['status'],delay:1000 });

								}else{
									$.notify({icon:"icon-bell-check",message:data['message']},{type:data['status'],delay:1000 });

								}

								button.html(button_text);
								button.removeAttr("disabled");
							},
						});
					});

					/*GET NEXT ORDER*/

					$('body').on('click','.acceptorder', function(event) {

						var OrderUID = $(this).attr('data-orderuid');
						var value = $(this).val();

						OrderUID = $(this).attr('data-OrderUID');
						$.ajax({
							type: "POST",
							url: '<?php echo base_url();?>MyOrders/acceptorder',
							data:{"OrderUID":OrderUID},
							dataType:'json',
							cache: false,
							success: function(data)
							{
								if(data){
									triggerpage('<?php echo base_url(); ?>Order_Summary/index/'+OrderUID);
								}
							},

						});
					});

					$(document).on('click','.Proceed',function(){

						var OrderUID = $('#AppendCancelOrderUID').val();
						var Remarks = $('.remarks').val();
						$.ajax({
							type: "POST",
							url: '<?php echo base_url();?>MyOrders/cancel_order',
							data:{"OrderUID":OrderUID,"Remarks":Remarks},
							dataType:'json',
							cache: false,
							beforeSend: function()
							{
								$('.Proceed').prop('disabled', true);
							},
							success: function(data)
							{


								if(data.validation_error == 1)
								{
									$.gritter.add({
										title: data['message'],
										class_name: 'color success',
										fade: true,
										time: 3000,
										speed:'slow',
									});
									setTimeout ("window.location='my_orders'", 3500);
									setTimeout(function() { $('#md-cancelorder').modal('hide'); }, 3000);
								}
								else if(data.validation_error == 2)
								{
									$.gritter.add({
										title: data['message'],
										class_name: 'color danger',
										fade: true,
										time: 3000,
										speed:'slow',
									});
									setTimeout ("window.location='my_orders'", 3500);
									setTimeout(function() { $('#md-cancelorder').modal('hide'); }, 3000);
						// $('.remarks').val('');
						$('#OrderCancellation')[0].reset();
						$('.Proceed').prop('disabled', false);
					}
					else if(data.validation_error == 3)
					{
						$.gritter.add({
							title: data['message'],
							class_name: 'color danger',
							fade: true,
							time: 3000,
							speed:'slow',
						});
						setTimeout ("window.location='my_orders'", 3500);
						setTimeout(function() { $('#md-cancelorder').modal('hide'); }, 3000);
						$('.remarks').val('');
					}
					$('.Proceed').prop('disabled', false);
				},
				error: function (jqXHR, textStatus, errorThrown) {

					console.log(jqXHR.responseText);
				}
			});

					});


			$(document).on('click','.getnextorder',function(){

				var ProjectUID = $('#ProjectUID option:selected').val();
				var Workflow = $('#Workflow option:selected').val();

			    $.ajax({

					type: "POST",
					url: '<?php echo base_url(); ?>MyOrders/GetNextOrder',
					data:{'ProjectUID':ProjectUID,'Workflow':Workflow},
					dataType:'json',
					beforeSend: function(){

					},
					success: function(data)
					{
				 		  if(data.validation_error == 1)
				            {
								  $.notify(
					              {
					                icon:"icon-bell-check",
					                message:data.message
					              },
					              {
					                type:data.color,
					                delay:1000 
					              });
					        $.each(data, function(k, v) {
								$('#'+k).addClass("is-invalid").closest('.form-group').removeClass('has-success').addClass('has-danger');
								$('#'+ k +'.select2picker').next().find('span.select2-selection').addClass('errordisplay');

							});
				           }else{

				           		  $.notify(
					              {
					                icon:"icon-bell-check",
					                message:data.message
					              },
					              {
					                type:data.color,
					                delay:1000 
					              });
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

				});


			</script>
