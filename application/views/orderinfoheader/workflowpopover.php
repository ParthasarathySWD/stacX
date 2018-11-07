
				<div id="exceptiononholdpopover-content" class="hide">
					<form class="form-inline" id="raiseexcetion">
						<select class="form-control" id="exceptiontype" name="exceptiontype" style="width: 244px;height: 31px;padding: 0px;" >
							<option value="">--Select--</option>
							<?php $mExceptions = $this->Common_Model->get('mExceptions');
							foreach ($mExceptions as $key => $value) { ?>
							<option value="<?php echo $value->ExceptionUID; ?>"><?php echo $value->ExceptionName; ?></option>
							<?php
							}
							?>

						</select>
						<br><br>
						<textarea  class="remarkstext"  placeholder="Enter Remarks Here..." name="remarks" style="width:244px;"></textarea>
						<div class="input-group">
							<br>
							<br>
							<br>
							<button class="btn btn-primary" id="btnraiseexcetion" type="submit" style="height: 30px;" >Submit</button>
						</div>
					</form> 
				</div>


<!-- STACKING COMPLETE POPUP CONTENT STARTS -->                
<div id="confirmModal" tabindex="-1" role="dialog" aria-hidden="true"  class="modal fade">
	<div class="modal-dialog" >
		<div class="modal-content" style="width: 450px;left: 30%;">
			<div class="modal-header" style="padding: 10px;">
				<button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"> <i class="icon-x"></i></button>
			</div>
			<div class="modal-body">
				<div class="text-center">
				<!-- <p id="msg" class="text-center" style="color:red;font-weight: bold"></p>
				-->
				<div class="text-primary" id="iconchg"><i style="font-size: 40px;" class="fa fa-info-circle fa-5x"></i></div>
				<span id="modal_msg" class="modal_spanheading">Do you want to complete the Stacking?</span>


					<div class="xs-mt-40">
						<button type="button" data-dismiss="modal" class="btn btn-default btn-space modal-close">Cancel</button>
						<button type="button" class="btn btn-primary btn-space workflow_complete">Proceed</button>
						<button type="button" class="btn btn-primary btn-space workflow_complete_final" style="display: none;">complete</button>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- STACKING COMPLETE POPUP CONTENT STARTS -->                
