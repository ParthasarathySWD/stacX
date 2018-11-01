<style>
  .progress {
    height: 6px;
    margin-bottom: 5px !important;
  }
  .viewproduct   #accordion p{
    font-size: 13px !important;
    margin-bottom: 0rem  !important;
  }
  .viewproduct  .card-collapse .card-header {
    border-bottom: none !important;
    padding: 7px 10px 5px 0 !important;
  }
  .viewproduct  .addproduct{
    border:1px solid #ddd;
    padding:20px;
  }
  .viewproduct  .icon-pencil{
    cursor: pointer;
  }
  .viewproduct  .icon-close2{
    cursor: pointer;
  }
  .viewproduct .buttons-excel{
    background: #3b5998 !important;
    color: #fff !important;
  }
  .viewproduct  .buttons-pdf{
    background: #6f6f6f !important;
    color: #fff !important;
  }
  .viewproduct  .pagination>.page-item>.page-link, .pagination>.page-item>span {
    border-radius: 5px!important;
  }

  .viewproduct  .navdiv{
    padding: 5px 20px;
    border: 1px solid #e6e6e6;
    background: #fff;
  }
  .viewproduct  .breadcrumb {
    display: flex;
    flex-wrap: wrap;
    padding: 5px 0px;   
    background-color: #fff;
    margin-bottom: 0px;
  }
  .viewproduct  .breadcrumb>li>a {
    color: #333;
    font-size: 14px;
    font-weight: 400;
  }
  .viewproduct  .breadcrumb-item.active {
    color: #6c757d;
    font-size: 13px;
    font-weight: 400;
    padding-top: 2px;
  }
  .viewproduct .card{
    border-radius: 0px !important;
  }
  .showfilename{
    font-weight: 400;
    font-size:13px;
    margin-bottom: 0px !important;
    text-align: right;
  }
  .headerdiv{
    border-bottom: 1px solid #ddd;
    background: #f3f3f3;
    padding: 10px;
  }
  .instructiondivider{
    border-bottom: 1px solid #ddd;
    padding: 15px;
    margin-bottom: 20px;
  }
  .fileinput-button-upload{
    background: #fff !important;
    border: 2px dashed #ddd;
    color: black !important;
    width: 100%;
    height: 40px;    
  } 
  .fileinput-button i{
    display: block;
    font-size: 45px;
    color: #3d5b99;
  }
  .fileinput-button-upload input{
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    opacity: 0;
    -ms-filter: 'alpha(opacity=0)';
    font-size: 20px;
    direction: ltr;
    height: 20px;
  }
  .fileinput-button input{
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    opacity: 0;
    -ms-filter: 'alpha(opacity=0)';
    font-size: 200px;
    direction: ltr;
    height: 200px;
  }
  .fileinput-button{
    background: #fff !important;
    border: 2px dashed #ddd;
    color: black !important;
    width: 100%;
    height: 230px; 
    line-height: 80px;
  }
  .abstractordiv h5 , .excludeabstractordiv h5{
    border-bottom: 1px solid #ddd;
    padding: 10px;
    font-size: 14px;
    margin: 0px;
    font-weight: 400; 
    color: #3b5998;
    font-weight: 500; 
  }
  .headericon{
    padding: 4px;
    border: 2px solid #8e8c8c;
    border-radius: 50%;
    margin-right: 10px;
    color: #8e8c8c;
    font-size: 10px;
  }
  .selectproductdiv{  
    border-right: 1px solid #ddd;
  }
  .selectedproductheader{
    border-bottom: 1px solid #ddd;
    padding: 10px;
    margin-bottom: 20px;
  } 
  .addmastersetups{
    cursor: pointer;
    color: #11b8cc;
  }
  .viewmastersetups{
    cursor: pointer;
    color: #e66b24;
  }
  .tab{
    display: none; 
    width: 100%;
    height: 50%;
    margin: 0px auto;
  }
  .current{
    display: block;
  }
  .step {
    height: 30px;
    width: 30px; 
    cursor: pointer;
    margin: 0 2px;
    color: #fff;
    background-color: #bbbbbb;
    border: none; 
    border-radius: 50%; 
    display: block; 
    opacity: 0.8;
    padding: 5px; 
    margin-top: 15px;
  }
  .step.active {
    opacity: 1;
    background-color: #69c769;
  }
  .step.finish {
    background-color: #4CAF50; 
  }
  .error {
    color: #f00;
  }
  #myForm{
    width:100%;
  }
  .card #pricingtable tr td{
    width: 160px;
  }
  .iconverify{
    padding: 10px;
    color: #00717f;
    border-radius: 50%;
    font-size: 26px;
  }
  .pswverify{
    font-size: 20px;
    font-weight: 500;
  }
  .is-invalid {
    background-size: 100% 100%, 100% 100%;
    transition-duration: .3s;
    box-shadow: none;
    background-image: linear-gradient(to top, #f44336 2px, rgba(244, 67, 54, 0) 2px), linear-gradient(to top, #d2d2d2 1px, rgba(210, 210, 210, 0) 1px);
  }
  .hide{
    display: none;
  }
</style>



<div class="col-md-12 pd-0"> 


  <div class="wizard-container col-md-12">
    <div class="card card-wizard" data-color="rose" id="wizardProfile">
      <div class="card-header text-center">    

      </div>
      <div class="wizard-navigation">
        <ul class="nav nav-pills">
          <li class="nav-item">
            <a class="nav-link active" id="AssignmentSummayID" href="#AssignmentSummaryDiv" data-toggle="tab" role="tab">
               Assignment Summary
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link"  id="OrderAssignID" href="#OrderAssignDiv" data-toggle="tab" role="tab">
               Assign
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="OrderReassignID"  href="#OrderReassignDiv" data-toggle="tab" role="tab">
               Reassign
            </a>
          </li> 
        </ul>
      </div>


      <div class="card-body">         


        <div class="tab-content">
          <div class="tab-pane" id="AssignmentSummaryDiv" style="">

          </div>
          <div class="tab-pane" id="OrderAssignDiv" style="display: none">

          </div>
          <div class="tab-pane" id="OrderReassignDiv"  style="display: none">

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

        $("#AssignmentSummaryDiv").load('<?php echo base_url("Orderassignment/loadassignmentsummary")?>');
        $("#AssignmentSummaryDiv").slideDown(); 

        $("#AssignmentSummayID").click(function(){
          $(".tab-pane").hide();
          $("#AssignmentSummaryDiv").load('<?php echo base_url("Orderassignment/loadassignmentsummary")?>');     
          $("#AssignmentSummaryDiv").slideDown(); 
        });

        $("#OrderAssignID").click(function(){
          $(".tab-pane").hide();
          $("#OrderAssignDiv").load('<?php echo base_url("Orderassignment/loadorderassign")?>');     
          $("#OrderAssignDiv").slideDown(); 
        });

        $("#OrderReassignID").click(function(){
          $(".tab-pane").hide();
          $("#OrderReassignDiv").load('<?php echo base_url("Orderassignment/loadorderreassign")?>');     
          $("#OrderReassignDiv").slideDown(); 
        });



      });
</script>

