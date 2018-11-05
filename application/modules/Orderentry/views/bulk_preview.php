			<!-- <div class="col-md-12 pd-0">
				<h4 class="sectionhead"><i class="icon-checkmark4 headericon"></i>Import Data Preview</h4>	
			</div> -->
			<div class="text-right">
				<!-- <span class="badge badge-pill" style="background-color: #AA00FF;">Borrower</span> -->
				<span class="badge badge-pill" style="background-color: #BDA601;">Zipcode</span>
				<span class="badge badge-pill" style="background-color: #3502BD;">County</span>
				<span class="badge badge-pill" style="background-color: #074D1E;">City</span>
				<span class="badge badge-pill" style="background-color: #02BD5A;">State</span>
				<span class="badge badge-pill" style="background-color: #757575;">Empty Field</span>
				<span class="badge badge-pill" style="background-color: #ff5c33;">Loan Number</span>
			</div>

			<div class="">
				<div class="tablescroll defaultfontsize">
					<table class="table table-striped table-hover table-format nowrap"  id="table-bulkorder">
						<thead>
							<tr>
							<?php 	
							
							foreach ($headingsArray as $key => $value) {
								?><th><?php echo $value; ?></th><?php
							}
							
							?>

							</tr>
						</thead>
						<tbody>
			
			<?php
			
			
			
			foreach ($arrayCode as $i => $a) {
				
				
				
				$count = count($a);
				$field_count = 10;
				
				//for missing fields
				if (count($arrayCode[$i]) >= $field_count) {
					
					if ((count($arrayCode[$i]) + 1) % ($field_count + 1) != 0) {
						?> <tr style="background-color: #757575; color: #fff;"> <?php 
						foreach ($arrayCode[$i] as $key => $value) {
							
							?>
							<td><?php echo $value; ?></td>										
							<?php 
						}
						
						?> </tr> <?php 
						
					} else {
						
						
						
						if ((count($a) >= $field_count)) {
							
							
							
							$CityName = $a[5];
							
							$StateCode = $a[7];
							$Zipcode = $a[6];
							
							$CountyName = $a[5];
							
							
							
							
							if ($ProjectCheck[$i] == false) { ?>
								<tr style="background-color: #BF6105; color: #fff;"> <?php 
								foreach ($arrayCode[$i] as $key => $value) {
									
									?>
									<td ><?php echo $value; ?></td>										
									<?php 
								}
							} else
							
							if ($StateCode == '') {
								?> <tr style="background-color: #02BD5A; color: #fff;"> <?php 
								foreach ($arrayCode[$i] as $key => $value) {
									
									?>
									<td><?php echo $value; ?></td>										
									<?php 
								}
								
								?> </tr> <?php 
								
								
							} elseif ($CountyName == '') {
								?> <tr style="background-color: #3502BD; color: #fff;"> <?php 
								foreach ($arrayCode[$i] as $key => $value) {
									
									?>
									<td><?php echo $value; ?></td>										
									<?php 
								}
								
								?> </tr> <?php 
								
							} elseif ($CityName == '') {
								?> <tr style="background-color: #074D1E; color: #fff;"> <?php 
								foreach ($arrayCode[$i] as $key => $value) {
									
									?>
									<td><?php echo $value; ?></td>										
									<?php 
								}
								
								?> </tr> <?php 
								
								
							} elseif ($Zipcode == '') {
								?> <tr style="background-color: #BDA601; color: #fff;"> <?php 
								foreach ($arrayCode[$i] as $key => $value) {
									
									?>
									<td><?php echo $value; ?></td>										
									<?php 
								}
								
								?> </tr> <?php 
								
								
							} else {
								?> <tr style="color: #090809;"> <?php 
								foreach ($arrayCode[$i] as $key => $value) {
									
									?>
									<td><?php echo $value; ?></td>										
									<?php 
								}
								
								?> </tr> <?php 
							}
							
							
							
							
						} else {
							?> <tr style="background-color: #757575; color: #fff;"> <?php 
							foreach ($arrayCode[$i] as $key => $value) {
								
								?>
								<td><?php echo $value; ?></td>										
								<?php 
							}
							
							?> </tr> <?php 
						}
						
					}
				} else {
					
					?> <tr style="background-color: #757575; color: #fff;"> <?php 
					foreach ($arrayCode[$i] as $key => $value) {
						
						?>
						<td><?php echo $value; ?></td>										
						<?php 
					}
					
					?> 
					</tr> 
					
					<?php 
					
				}
				
			}
			
			?> 
			</tbody>
			
			</table>
		</div>
	</div>