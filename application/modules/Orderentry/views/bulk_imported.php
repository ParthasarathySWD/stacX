<ul class="nav nav-pills nav-pills-rose" role="tablist">
	<li class="nav-item">
		<a class="nav-link active" data-toggle="tab" href="#success-table" role="tablist"><small>
			Imported&nbsp;<i class="fa fa-check-circle"></i></small>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#error-data" role="tablist"><small>
			Not Imported&nbsp;<i class="fa fa-times-circle-o"></i></small>
		</a>
	</li>
</ul>
<div class="tab-content tab-space">
	<div id="success-table" class="tab-pane active cont">

		<div class="mb-20">
			<button type="button" class="btn btn-success" id="pdfimport">PDF</button>
			<button type="button" id="excelimport" class="btn btn-success">Excel</button>
		</div>
		<div class="">
			<div class="table-responsive defaultfontsize tablescroll">
				<table class="table table-striped table-hover table-format nowrap" id="importdata">
					<tr>
						<th>Order Number</th>
						<th>Alt Order Number</th>
						<th>Priority</th>
						<th>Customer/Client</th>
						<th>Project</th>
						<th>Loan Number</th>
						<th>Property Address</th>
						<th>Property City</th>
						<th>Property County</th>
						<th>Property State</th>
						<th>Property Zip Code</th>
					</tr>


					<?php foreach ($SuccessData as $key => $a) { ?>
						<tr>
							<td> <?php echo $a['result']['OrderNumber']; ?> </td>
							<td> <?php echo $a[4]; ?> </td>
							<td> <?php echo $a[2]; ?> </td>
							<td> <?php echo $a[0]; ?> </td>
							<td> <?php echo $a[1]; ?> </td>
							<td> <?php echo $a[3]; ?> </td>
							<td> <?php echo $a[5]; ?> </td>
							<td> <?php echo $a[6]; ?> </td>
							<td> <?php echo $a[7]; ?> </td>
							<td> <?php echo $a[8]; ?> </td>
							<td> <?php echo $a[9]; ?> </td>
						</tr>
					<?php } ?>


				</table>
			</div>
		</div>
	</div>

	<div id="error-data" class="tab-pane cont">
		<div class=mb-20">
			<button type="button" class="btn btn-success" id="pdferror">PDF</button>
			<button type="button" id="excelerror" class="btn btn-success">Excel</button>
		</div>
		<div class="col-sm-12">
			<div class="table-responsive defaultfontsize tablescroll">
				<table class="table table-striped table-hover table-format nowrap">
					<tr>
						<th>Alt Order Number</th>
						<th>Priority</th>
						<th>Customer/Client</th>
						<th>Project</th>
						<th>Loan Number</th>
						<th>Property Address</th>
						<th>Property City</th>
						<th>Property County</th>
						<th>Property State</th>
						<th>Property Zip Code</th>
					</tr>
					<?php 
    foreach ($FailedData as $key => $value) { ?>
						<tr>
							<td><?php echo $value[4]; ?></td>
							<td><?php echo $value[2]; ?></td>
							<td><?php echo $value[0]; ?></td>
							<td><?php echo $value[1]; ?></td>
							<td><?php echo $value[3]; ?></td>
							<td><?php echo $value[5]; ?></td>
							<td><?php echo $value[6]; ?></td>
							<td><?php echo $value[7]; ?></td>
							<td><?php echo $value[8]; ?></td>
							<td><?php echo $value[9]; ?></td>
						</tr>
						<?php } ?>
				</table>
			</div>
		</div>
	</div>
</div>
</div>
</div>
<input type="hidden" value="<?php echo $InsertedOrderUID; ?>" name="InsertedOrderUID[]" id="InsertedOrderUID">