<?php 
$OrderUID = $this->uri->segment(3);
$OrderDetails = $this->Common_Model->getOrderDetails($OrderUID); 
?>
<link href="<?php echo base_url(); ?>assets/css/workflow.css" rel="stylesheet" />

<input type="hidden" name="OrderUID" id="OrderUID" value="<?php echo $OrderDetails->OrderUID; ?>">
<div class="col-md-12 text-center">
	<div class="row">         
		<div class="col-md-8 mt-10">
			<div style="display: inline-block;line-height: 30px"> 
				<h3 class="card-title" style="color: #ffffff; margin-top: 0px !important;float:left;padding-right: 3px;">Order#&nbsp;<?php echo $OrderDetails->OrderNumber; ?></h3>
			</div>  

			<div class="preventoverflow">
					<p data-toggle="" data-placement="top" title="<?php echo $OrderDetails->PropertyAddress1; ?>, <?php echo $OrderDetails->PropertyCityName; ?>, <?php echo $OrderDetails->PropertyStateCode; ?>, <?php echo $OrderDetails->PropertyZipCode; ?>, <?php echo $OrderDetails->PropertyCountyName; ?>" data-container="body"><?php echo $OrderDetails->PropertyAddress1; ?>, <?php echo $OrderDetails->PropertyCityName; ?>, <?php echo $OrderDetails->PropertyStateCode; ?>, <?php echo $OrderDetails->PropertyZipCode; ?> <i class="icon-pin" style="margin-left: 5px;"></i> <?php echo $OrderDetails->PropertyCountyName; ?>             
				</p>
			</div>
			<div class="row">
				<div class="col-md-4 text-center preventoverflow">        
					<p>Customer Name :
						<span data-type="select2" id="customerdts" class="popoveroption"><?php echo $OrderDetails->CustomerName; ?></span>   
					</p>  
				</div>
				<div class="col-md-4 text-center preventoverflow">  
					<p class=""><i class="icon-file-stats" style="margin-right: 5px;"></i>Loan Number : <?php echo $OrderDetails->LoanNumber; ?></p>   
				</div>
			</div>
		</div>
		<div class="col-md-4 preventoverflow">          
				<div class="row" style="margin-top: 70pt;">
					<div class="col-md-12 text-center">
						<p class="text-center">
							<span class="badge badge-pill badge-warning"><span data-placement="top" data-type="select2" data-name="Priority" data-pk="<?php echo $OrderDetails->OrderUID; ?>" id="prioritydts" class="popoveroption"><?php echo $OrderDetails->Priority ?> </span></span>
							<span class="badge badge-pill badge-warning" style="background-color: #ffb300
							!important;"><?php echo $OrderDetails->StatusName;?></span>
						</p>
					</div>
				</div>  
			</div>
		</div>
	</div>


<script type="text/javascript">



</script>