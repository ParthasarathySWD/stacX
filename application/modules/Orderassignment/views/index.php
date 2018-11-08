<style>

</style>

<div class="col-md-12 pd-0"> 
    <div class="col-md-12">
      <div class="card ">
        <div class="card-header card-header-danger card-header-icon">
          <div class="card-icon">ORDER ASSIGNMENT
          </div>
        </div>
        <div class="card-body ">
          <ul class="nav nav-pills nav-pills-danger" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#link1" role="tablist">
                Order Assign
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#link2" role="tablist">
                Order Re-Assign
              </a>
            </li>
          </ul>
          <div class="tab-content tab-space">
            <div class="tab-pane active" id="OrderAssignDiv">

            </div>
            <div class="tab-pane" id="OrderReassignDiv">

            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="<?php echo base_url(); ?>assets/js/multi-form.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/jquery.bootstrap-wizard.js"  type="text/javascript"></script>

    <script type="text/javascript">

      $(document).ready(function(){
        demo.initMaterialWizard();
  setTimeout(function() {
    $('.card.card-wizard').addClass('active');
  }, 600);

          $("#OrderAssignDiv").load('<?php echo base_url("OrderAssignment/loadorderassign")?>');     
          $("#OrderAssignDiv").slideDown(); 


        $("#OrderAssignID").click(function(){
          $(".tab-pane").hide();
          $("#OrderAssignDiv").load('<?php echo base_url("OrderAssignment/loadorderassign")?>');     
          $("#OrderAssignDiv").slideDown(); 
        });

        $("#OrderReassignID").click(function(){
          $(".tab-pane").hide();
          $("#OrderReassignDiv").load('<?php echo base_url("OrderAssignment/loadorderreassign")?>');     
          $("#OrderReassignDiv").slideDown(); 
        });



      });
</script>



<script type="text/javascript">


</script>

