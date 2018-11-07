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
            <div class="tab-pane active" id="link1">
              <?php $this->load->view('orderassign');?>
            </div>
            <div class="tab-pane" id="link2">
              <?php $this->load->view('orderreassign');?>
            </div>
          </div>
        </div>
      </div>
    </div>




<script type="text/javascript">


</script>

