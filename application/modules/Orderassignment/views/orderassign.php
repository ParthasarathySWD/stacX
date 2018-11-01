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
      <form>
			 <table class="table">
			    <thead>
			      <tr>
			          <th>Order No</th>
			          <th>Customer</th>
			          <th>Project</th>
			          <th>State/County</th>
			          <th>Entry Date</th>
			          <th>Current Queue</th>
			          <th>Action</th>
			      </tr>
			    </thead>
			    <tbody>
			      <tr>
			        <td>P1800001</td>
			        <td>Z.B.NA</td>
			        <td>john@example.com</td>
					<td>State/County</td>
					<td>Entry Date</td>
					<td>Current Queue</td>
					<td>
			            <div class="form-check">
			              <label class="form-check-label" for="checkbox2">
			                <input class="form-check-input " type="checkbox" value="" name="checkbox2" id="checkbox2"> 
			                <span class="form-check-sign">
			                  <span class="check"></span>
			                </span>
			              </label>
			            </div>
					</td>
			      </tr>
			    </tbody>
			  </table>
      </form>
            <div id="assignusers" class="row col-md-12">
               <?php 
               $Workflows = array('1'=>'Search','2'=>'Typing','3'=>'Taxcert','4'=>'Review');
               foreach ($Workflows as $key => $value) { ?>
               <div class="col-md-3">
                  <div class="form-group bmd-form-group">
                     <label class="control-label"><b><?php echo $value; ?></b></label>
                     <select class="select2picker" id="<?php echo $Workflow->WorkflowModuleUID; ?>" data-id="<?php echo $Workflow->key; ?>">
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
});
</script>
