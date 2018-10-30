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

<?php  $TATOrdersUIDs = GetTotalTATOrders(); ?>
<div class="card">
	<div class="card-header card-header-rose card-header-icon">
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
		<div class="tab-content tab-space">
			<div class="tab-pane active" id="orderslist">
				<?php if($is_selfassign == 1) : ?>
					<div class="row">
						<div class="col-md-2">
							<div class="form-group bmd-form-group">
								<label for="group" class="bmd-label-floating">Group <span class="mandatory"></span></label>
								<select class="select2picker form-control" id="group"  name="group">                   
									<?php if(count($groupsbyloggedid) == 1){ 
										foreach ($groupsbyloggedid as $key => $group) { ?>
										<option value="<?php echo $group->GroupUID; ?>" selected><?php echo $group->GroupName; ?></option>
										<?php } 
									}else{ ?>
									<option value=""></option>
									<?php
									foreach ($groupsbyloggedid as $key => $group) { ?>
									<option value="<?php echo $group->GroupUID; ?>"><?php echo $group->GroupName; ?></option>
									<?php } }?>    
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group bmd-form-group">
								<label for="group_products" class="bmd-label-floating">Product <span class="mandatory"></span></label>
								<select class="select2picker form-control" id="group_products"  name="group_products">                   
									<?php if(count($groupsbyloggedid) == 1){ 

										$Product = $controller->get_product_by_group($groupsbyloggedid[0]->GroupUID);

										if(count($Product) == 1){
											foreach ($Product as $key => $value) { ?>
											<option value="<?php echo $value->ProductUID; ?>" selected><?php echo $value->ProductName; ?></option>
											<?php }
										}else{ ?>
										<option value=""></option>
										<?php foreach ($Product as $key => $value) { ?>
										<option value="<?php echo $value->ProductUID; ?>"><?php echo $value->ProductName; ?></option>
										<?php }
									}


								}else{ ?>
								<option value=""></option>

								<?php } ?>  
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group bmd-form-group">
							<label for="group_subproducts" class="bmd-label-floating">SubProduct <span class="mandatory"></span></label>
							<select class="select2picker form-control" id="group_subproducts"  name="group_subproducts">                   
								<?php if(count($groupsbyloggedid) == 1){ 

									if(count($Product) == 1){
										$data = $controller->subproduct_by_group_product($groupsbyloggedid[0]->GroupUID,$Product[0]->ProductUID);

										if(count($data[0]) > 0){ ?>

										<option value=""></option>
										<?php  foreach ($data[0] as $key => $value) { ?>
										<option value="<?php echo $value->SubProductUID; ?>"><?php echo $value->SubProductName; ?></option>

										<?php }
									}
								}
								?>

								<?php }else{ ?>
								<option value=""></option>

								<?php } ?> 
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group bmd-form-group">
							<label for="group_workflows" class="bmd-label-floating">Workflow <span class="mandatory"></span></label>
							<select class="select2picker form-control" id="group_workflows"  name="group_workflows">                   
								<?php if(count($groupsbyloggedid) == 1){ 

									if(count($Product) == 1){
										$workflowroles = $this->common_model->get_role_workflows();
										$data = $controller->subproduct_by_group_product($groupsbyloggedid[0]->GroupUID,$Product[0]->ProductUID);

										if(count($data[1]) > 0){
											foreach ($data[1] as $key => $value) {
												if (in_array($value->WorkflowModuleUID,$workflowroles)){

													?>
													<option value="<?php echo $value->WorkflowModuleUID; ?>"><?php echo $value->WorkflowModuleName; ?></option>

													<?php } }
												}
											}
											?>

											<?php }else{ ?>
											<option value=""></option>

											<?php } ?> 
										</select>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group bmd-form-group">
										<button class="btn btn-primary" id="getnextorder"><i class="fa fa-forward" aria-hidden="true"></i> Get Next Order
											<div class="ripple-container"></div>
										</button>
									</div>
								</div>
							</div>
						<?php endif; ?>

						<div class="col-md-12 col-xs-12">
							<div class="material-datatables" id="myordertable_parent">
								<table class="table table-striped display nowrap" id="myordertable"  cellspacing="0" width="100%"  style="width:100%">
									<thead>
										<tr>
											<?php if(!$is_vendorlogin){ ?>
												<th>Comp Name</th>
												<th>Loan Number</th>
											<?php } ?>
											<th>Prop No</th>
											<th>Borrower Name</th>
											<th>Order Priority</th>
											<th>Current Status</th>
											<th>Current Queue</th>
											<th>Property Address</th>		
											<th>Property City</th>	
											<th>Property County</th>	
											<th>Property State</th>	
											<th>Zip Code</th>			
											<th>Product</th>
											<th>SubProduct</th>
											<th>SubProduct Code</th>		
											<th>Assigned Users</th>
											<th>Workflow Module Completed</th>
											<th>Due Date</th>
											<th>Due Past</th>
											<th>Ordered Date Time</th>
											<?php if(!$is_vendorlogin){ ?>
												<th>In-house/Abstractor</th>	
												<th>Assignment Type</th>		
												<th>Abstractor No</th>	
												<th>Abstractor Company Name</th>
											<?php } ?>
											<?php if($permission->AbstractorFee != 0) { ?> 
											<th>Abstractor Actual Cost</th>
											<?php } ?>
											<?php if($permission->CustomerPricing != 0) { ?>
											<th>Customer Actual Cost</th>	
											<?php } ?>				
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
											<?php if(!$is_vendorlogin){ ?>
											<th class="no-sort">Customer</th>
											<?php } ?>
											<th class="no-sort">Product/SubProduct</th>
											<th class="no-sort">State</th>
											<th class="no-sort">Assigned User</th>
											<th class="no-sort">Status</th>
											<?php if(!$is_vendorlogin){ ?>
											<th class="no-sort">Workflow Module Completed</th>
											<?php } ?>
											<th class="no-sort">Ordered Date</th>
											<?php if($is_vendorlogin){ ?>
											<th class="no-sort">Assigned Date</th>
											<?php } ?>

											<th class="no-sort">Due Date</th>
											<th class="no-sort">Actions</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($lastviewed_orders as $key => $value) {

											$completed_workflowstatus = $this->Common_Model->completed_status_order($value['OrderUID']);
											$res = $this->MyOrders_Model->getCancelOrderStatus($value['OrderUID']);
											$Workflowassigned = $this->MyOrders_Model->get_Workflowassigned($value['OrderUID']);
											$onholdworkflow = $this->Common_Model->get_onholdWorkflow($value['OrderUID']);

											?>
											<tr>

												<?php 

												if($value['PriorityName'] == 'ASAP'){?>

												<td class="nowrap"><?php if(in_array($value['OrderUID'], $TATOrdersUIDs)) : echo '<a href="'.base_url('Order_Summary/index/'.$value['OrderUID']).'/1" class="text-primary ajaxload"><span class="text-danger">'.$value['OrderNumber'].'</span></a>'; else: echo '<a href="'.base_url('Order_Summary/index/'.$value['OrderUID']).'/1" class="text-primary ajaxload"><span>'.$value['OrderNumber'].'</span></a>'; endif;  ?> <img src="<?php echo base_url(); ?>assets/img/asap.png" title="<?php echo $value['PriorityName']; ?>" height="20px" width="20px"></td>

												<?php } else if($value['PriorityName'] == 'Rush'){ ?>

												<td class="nowrap"><?php if(in_array($value['OrderUID'], $TATOrdersUIDs)) : echo '<a href="'.base_url('Order_Summary/index/'.$value['OrderUID']).'/1" class="text-primary ajaxload"><span class="text-danger">'.$value['OrderNumber'].'</span></a>'; else: echo '<a href="'.base_url('Order_Summary/index/'.$value['OrderUID']).'/1" class="text-primary ajaxload"><span>'.$value['OrderNumber'].'</span></a>'; endif;  ?> <img src="<?php echo base_url(); ?>assets/img/rush.png" height="20px" title="<?php echo $value['PriorityName']; ?>" width="20px"></td>

												<?php } else{ ?>

												<td  class="nowrap"><?php if(in_array($value['OrderUID'], $TATOrdersUIDs)) : echo '<a href="'.base_url('Order_Summary/index/'.$value['OrderUID']).'/1" class="text-primary ajaxload"><span class="text-danger">'.$value['OrderNumber'].'</span></a>'; else: echo '<a href="'.base_url('Order_Summary/index/'.$value['OrderUID']).'/1" class="text-primary ajaxload"><span>'.$value['OrderNumber'].'</span></a>'; endif;  ?></td>

												<?php }?>
												<?php if(!$is_vendorlogin){ ?>
												<td><?php echo $value['CustomerNumber'].' / '.$value['CustomerName'];  ?></td>
												<?php } ?>
												<td><?php echo substr($value['ProductName'], 0, 1).'-'.$value['SubProductName'];  ?> </td>
												<td><?php echo $value['PropertyStateCode']; ?></td>
												<td><?php echo $this->MyOrders_Model->get_assigned_workflow_users($value['OrderUID'],$is_vendorlogin,$logged_details);  ?></td>

												<td>
													<?php if($onholdworkflow->WorkflowModuleName != '') {?>

													<span class="btn btn-rounded btn-sm" style="font-size: 10px; color:#fff; background: #ff8600;"><?php echo $onholdworkflow->WorkflowModuleName; ?>-OnHold</span>

													<?php }else{ ?>

													<span class="btn btn-rounded btn-sm" style="font-size: 10px; color: #fff; background: <?php echo $value['StatusColor'];?>"><?php echo $value['StatusName']; ?></span>

													<?php } ?>
												</td>
												<?php if(!$is_vendorlogin){ ?>
												<td><?php echo $completed_workflowstatus->WorkflowModuleName; ?></td>
												<td><?php echo $value['OrderEntryDatetime']; ?></td>
												<td><?php echo $value['OrderDueDateTime']; ?></td>
												<?php }else{ ?>
												<td><span class="more"><?php echo $this->MyOrders_Model->get_vendor_ordered_datetime($value['OrderUID'],$is_vendorlogin,$logged_details); ?></span></td>
												<td><span class="more"><?php echo $this->MyOrders_Model->get_vendor_assigned_datetime($value['OrderUID'],$is_vendorlogin,$logged_details); ?></span></td>
												<td><span class="more"><?php echo $this->MyOrders_Model->get_vendor_due_datetime($value['OrderUID'],$is_vendorlogin,$logged_details); ?></span></td>
												<?php } ?>
												<td style="position:relative;">

													<?php
													if($Workflowassigned->WorkflowStatus == '0' && $this->Common_Model->check_order_is_assignedtouser($value['OrderUID']) > 0) { ?>
													<button   data-OrderUID = '<?php echo $value['OrderUID']; ?>' class="btn btn-link btn-success btn-just-icon btn-xs acceptorder"><i class="icon-checkmark4"></i><div class="ripple-container"></div></button>



													<?php }else{ ?>
													<div class="badgebar">
														<?php 
														$CustomerDelay = $this->Common_Model->GetCustomerDelayByOrder($value['OrderUID']);  
														if($CustomerDelay==1)
														{
															echo '<span class="badge badge-danger cus-badge">C</span>';
														}
														?>
														<a href="<?php echo base_url(); ?>Order_Summary/index/<?php echo $value['OrderUID']; ?>/1" class="btn btn-link btn-info btn-just-icon btn-xs ajaxload"><i class="icon-pencil"></i><div class="ripple-container"></div></a>


														<?php if($this->Common_Model->CheckOrderCancelRole($this->loggedid) == 1){?>
														<button data-OrderUID = "<?php echo $value['OrderUID']; ?>" class="btn btn-link btn-danger btn-just-icon btn-xs cancel_order"><i class="icon-cross2"></i><div class="ripple-container"></div></button>

														<?php  }?>

													</div>
													<?php } ?>
												</td>
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
						"url": "<?php echo base_url('MyOrders/ajax_list')?>",
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

				});

			</script>







