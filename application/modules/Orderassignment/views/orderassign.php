<style>
#orderassigntable tbody td{
	font-size: 10px !important;
}
#orderassigntable thead th{
	font-size: 10px !important;
}
</style>
<svg style="height: 50px;width: 50px;z-index: 99;display: none;" class="d2tspinner-circular" viewBox="25 25 50 50"><circle class="d2tspinner-path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg>
<div class="card">
	<div class="card-body">
		<div class="row col-md-12">
<!-- 			<div class="col-md-4">
				<div class="form-group bmd-form-group">
					<label for="Customer" class="bmd-label-floating">Customer<span class="mandatory"></span></label>
					<select class="select2picker form-control"  id="Customer" name="Customer" required>
						<option value=""></option>
						<?php foreach ($Customers as $key => $value) { ?>
							<option value="<?php echo $value->CustomerUID; ?>"><?php echo $value->CustomerName; ?></option>
						<?php } ?>								
					</select>
				</div>
			</div> -->
			<div class="col-md-4">

				<div class="form-group  bmd-form-group">
					<label for="Project" class="bmd-label-floating">Project</label>
					<select class="select2picker form-control"  id="Project"  name="Project" >
						<option value="all" selected>All</option>
						<?php foreach ($Projects as $key => $project) {?> 
							<option value="<?php echo $project->Project; ?>"><?php echo $project->ProjectName;?>                               
						</option>
						<?php } ?>       
					</select>
				</div>
			</div>
			<div class="col-md-6 text-right" style="margin-top: 10pt;">
				<h4>Total Orders : <span  class="number" id="total_orders">0</span></h4>
			</div>
		</div>
		<div class="material-datatables table-responsive tablescroll" id="myordertable_parent">

			<table class="table table-striped display nowrap" id="orderassigntable"  cellspacing="0" width="100%"  style="width:100%">
				<thead>
					<tr>
						<th>Order No</th>
						<th>Customer</th>
						<th>Project</th>
						<th>Status</th>
						<th>Property Address1</th>
						<th>Property City</th>
						<th>Property County</th>
						<th>Property StateCode</th>
						<th>Property ZipCode</th>
						<th>Entry Date</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		<div id="assignusers" class="row col-md-12">
			<?php 
			$Workflows = array('1'=>'Production','2'=>'Qc');
			foreach ($Workflows as $key => $value) { ?>
				<div class="col-md-3">
					<div class="form-group bmd-form-group">
						<label class="control-label"><b><?php echo $value; ?></b></label>
						<select class="select2picker workflowusers" id="<?php echo $Workflow->WorkflowModuleUID; ?>" data-id="<?php echo $Workflow->key; ?>">
							<option value=""></option>

						</select>
					</div>
				</div>
			<?php } ?>
		</div>
		<div class="ml-auto text-right">
			<button type="button" class="btn btn-fill  btn-info btn-wd assignorder" ><i class="icon-rotate-cw2 pr-10"></i>Assign Order</button>
			<button type="button" class="btn btn-fill  btn-danger btn-wd unassign_order" ><i class="icon-rotate-ccw2 pr-10"></i>Unassign Order</button>
		</div>
	</div>
</div>



<!--END CONTENT-->
<script type="text/javascript"> 

	$(document).ready(function(){

		$(".select2picker").select2({
			tags: false,
			theme: "bootstrap",
		});
		var assignment_table = '';
		var SelectedProject = $('#Project').val();

		fn_orderassigntable_init(SelectedProject);

		$('#Project').on('change', function (e) {
			var SelectedProject = $('#Project').val();
			fn_orderassigntable_destroy();
			fn_orderassigntable_init(SelectedProject);
		});


		$(document).on('click','input[name="input_assigncheckbox"]', function(e) {
			var checkbox_count = $("input[name='input_assigncheckbox']:checked").length;
			if(checkbox_count == 1 && $(this).prop('checked') == true){

				var SelectedProject = $('#Project').val();
				var OrderUID = $(this).attr('data-OrderUID');
				var obj = {"OrderUID":OrderUID};

				$.ajax({
					type: "POST",
					url: "Orderassignment/GetProjectUsers",
					data: "{'OrderUID':OrderUID}",
					dataType: "json",
					success: function (response) {
						
						$('.workflowusers').append(response.html);
						fn_orderassigntable_destroy();
						fn_orderassigntable_init.call(obj, SelectedProject);
					}
				});
			}
			else if(checkbox_count == 0 && $(this).prop('checked') == false){

				$('.workflowusers').html('<option value=""></option>');
				var SelectedProject = $('#Project').val();
				var OrderUID = '';
				var obj = {"OrderUID":OrderUID};
				fn_orderassigntable_destroy();
				fn_orderassigntable_init.call(obj, SelectedProject);
			}
		});

	});//Document Load Complete

		var fn_orderassigntable_destroy = function () {
			$('#orderassigntable').DataTable().destroy();
		}
		var fn_orderassigntable_init = function (SelectedProject) {
			if (typeof this.OrderUID === 'undefined') {
				var OrderUID = '';
			}
			else{
				var OrderUID = this.OrderUID;
			}
			assignment_table = $('#orderassigntable').DataTable( {
				processing: true, //Feature control the processing indicator.
				serverSide: true, //Feature control DataTables' server-side processing mode.
				scrollCollapse: true,
				paging:true,
				autoWidth: false,
				ordering: true,
				columnDefs: [
				{ 
					orderable: false, targets:  "no-sort"}
					],
					responsive:false,
					lengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]] , 

					iDisplayLength: 25,

					language: {
						sLengthMenu: "Show _MENU_ Orders",
						emptyTable:     "No Orders Found",
						info:           "Showing _START_ to _END_ of _TOTAL_ Orders",
						infoEmpty:      "Showing 0 to 0 of 0 Orders",
						infoFiltered:   "(filtered from _MAX_ total Orders)",
						zeroRecords:    "No matching Orders found",
						processing: '<svg class="d2tspinner-circular" viewBox="25 25 50 50"><circle class="d2tspinner-path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg>'
					},

					ajax: {
						url: "<?php echo base_url('OrderAssignment/assignment_ajax_list')?>",
						type: "POST",
						data: {
							"ProjectUID": SelectedProject,
							"OrderUID": OrderUID,
						},
						dataSrc: function ( json ) {
							$(".orderassigntable-error").html("");
							$('#total_orders').html(json.recordsTotal);
							return json.data;
							callselect2();
						},
						error: function(){ 
							// error handling
							$(".orderassigntable-error").html("");
							$("#orderassigntable").append('<tbody class="orderassigntable-error"><tr><td colspan="10" class="text-center">No Orders found</td></tr></tbody>');
							$("#orderassigntable_processing").css("display","none");

						} 
					},
			});
		}

</script>
