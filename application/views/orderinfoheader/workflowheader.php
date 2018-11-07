<?php 
$OrderUID = $this->uri->segment(3);
$OrderDetails = $this->Common_Model->getOrderDetails($OrderUID); 
?>
<div class="col-md-12 navmenu">
   <div class="row">
      <ul class="nav nav-pills nav-pills-link txt-white" role="tablist">
         <li class=" nav-item">
            <a class="nav-link ajaxload <?php if ($this->uri->segment(1) == "Ordersummary") {echo "active";}?>" role="tablist" href="<?php echo base_url() . 'Ordersummary/index/' . $OrderDetails->OrderUID . '/' ?>">Order Info</a>
         </li>
         <li class=" nav-item">
             <a class="nav-link ajaxload <?php if ($this->uri->segment(1) == "Stacking") {echo "active";}?>" role="tablist" href="<?php echo base_url() . 'Ordersummary/index/' . $OrderDetails->OrderUID . '/' ?>">Stacking</a>
         </li>
      </ul>
   </div>
</div>


	