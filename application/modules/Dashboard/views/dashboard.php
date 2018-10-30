
<link href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/amchart/export.css" type="text/css" media="all" />
<link  rel="stylesheet" href="<?php echo base_url();?>assets/plugins/multiselect/css/bootstrap-multiselect.css"   type="text/css" />
<link  rel="stylesheet" href="<?php echo base_url();?>assets/plugins/multiselect/css/awesome-bootstrap-checkbox.css"   type="text/css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/tooltipster/3.0.5/css/tooltipster.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/tooltipster/3.0.5/js/jquery.tooltipster.min.js"></script>
<style>
.ct-legend{
	margin-bottom: 0rem !important;
}
.reduceindex{
	z-index: -1 !important;
}
.card .card-header.card-header-icon i, .card .card-header.card-header-text i{
	width: 10px;
	height: 10px;
	text-align: center;
	line-height: 10px;
}
.pendingicon{
	background: #e9a31e;
	text-align: center;
	color: #fff;
}
.pendingicon i{
	font-size: 45px;
	padding-top: 25px;
}
#ordertable td{
	padding: 8px !important;
}
#ordertable td a{
	color : #000 !important;
}
#mapdiv {
	width: 100%;
	height: 500px;
}
#chartdiv {
	width: 100%;
	height: 500px;
}
.ct-series-c .ct-area, .ct-series-c .ct-slice-donut-solid, .ct-series-c .ct-slice-pie {
	fill: #ff9800;
}
.ct-legend{
	padding:0px !important;
}
.pct-chart .ct-legend {
	position: relative;
	z-index: 10;
	list-style: none;
	text-align: center;
}
.pct-chart .ct-legend li {
	position: relative;
	padding-left: 17px;
	margin-right: 7px;
	margin-bottom: 3px;
	cursor: pointer;
	display: inline-block;
	width:28%;
	font-size: 11px;
}
.pct-chart .ct-legend li:before {
	width: 12px;
	height: 12px;
	position: absolute;
	left: 0;
	content: '';
	border: 3px solid transparent;
	border-radius: 2px;
	top: 6px;
}
.pct-chart .ct-legend li .inactive:before {
	background: transparent;
}
.pct-chart .ct-legend li:nth-child(1)::before {
	background-color: #d70206;
}
.pct-chart .ct-legend li:nth-child(2)::before {
	background-color: #ff9800;
}
.pct-chart .ct-legend li:nth-child(3)::before {
	background-color: #158445;
}
.pct-chart .ct-legend li:nth-child(1n+4)::before {
	background-color: #F06292;
}
.pct-chart .ct-legend .ct-legend-inside {
	position: absolute;
	top: 0;
	right: 0;
}
.pct-chart g:not(.ct-grids):not(.ct-labels) g:nth-child(1) .ct-point,
.pct-chart g:not(.ct-grids):not(.ct-labels) g:nth-child(1) .ct-line {
	stroke: #d70206;
}
.pct-chart g:not(.ct-grids):not(.ct-labels) g:nth-child(2) .ct-point,
.pct-chart g:not(.ct-grids):not(.ct-labels) g:nth-child(2) .ct-line {
	stroke: #f05b4f;
}
.pct-chart g:not(.ct-grids):not(.ct-labels) g:nth-child(3) .ct-point,
.pct-chart g:not(.ct-grids):not(.ct-labels) g:nth-child(3) .ct-line {
	stroke: #f4c63d;
}
.pct-chart g:not(.ct-grids):not(.ct-labels) g:nth-child(1n+4) .ct-point,
.pct-chart g:not(.ct-grids):not(.ct-labels) g:nth-child(1n+4) .ct-line {
	stroke: #F06292;
}


.card-stats .card-header .card-category:not([class*=text-]) {   
	font-size: 13px;
}
.renewal .ct-series-a .ct-slice-donut-solid,.renewal .ct-series-a .ct-slice-pie {
	fill: #ea2727 !important;
}
.ct-chart .ct-series-a .ct-bar {
	stroke: #36bea6 !important;
}
.ct-chart .ct-series-a .ct-line,.ct-chart .ct-series-a .ct-point {
	stroke: #2196f3 !important;
}
.renewal.ct-series-b .ct-area,.renewal .ct-series-b .ct-slice-donut-solid, .renewal.ct-series-b .ct-slice-pie {
	fill: #43a047 !important;
}
.inbox-wid .inbox-item .inbox-item-date {
	font-size: 11px;
	position: absolute;
	right: 7px;
	top: 8px;
	color : #fff !important;   
}
.inbox-wid .inbox-item {
	position: relative;
	border-bottom: 1px solid rgba(243,243,243,.9);
	overflow: hidden;
	padding: 10px 0;
}
.align-items-center {
	-webkit-box-align: center !important;
	-ms-flex-align: center !important;
	align-items: center !important;
}
.round {
	color: #fff;
	width: 25px;
	height: 25px;
	display: inline-block;
	font-weight: 400;
	text-align: center;
	border-radius: 50%;
	background: #d70206;
	line-height: 25px;
	margin-right: 9px;
}
.bg-success {
	background-color: #36bea6 !important;
}
.ct-series-a .ct-area{
	fill: #e91e63 !important;
}
#chartdiv {
	width: 100%;
	height: 500px;
}	
.card .card-header-primary .card-icon, .card .card-header-primary .card-text, .card .card-header-primary:not(.card-header-icon):not(.card-header-text), .card.bg-primary, .card.card-rotate.bg-primary .back, .card.card-rotate.bg-primary .front {
	background: linear-gradient(60deg, #03A9F4, #00BCD4);
}
.position-relative {
	position: relative!important;
}
.mini-stat .mini-stat-desc .verti-label {
	-webkit-transform: rotate(-90deg);
	transform: rotate(-90deg);
	position: absolute;
	top: 44px;
	right: -9px;
	letter-spacing: 2px;
	font-weight: 700;
}
.text-white-50 {
	color: rgba(255,255,255,.5)!important;
}
.text-white-50 {
	color: rgba(255,255,255,.5)!important;
}
.mini-stat .mini-stat-icon i {
	position: absolute;
	right: 46px;
	top: -35px;
	color: rgba(255,255,255,.3);
}
.display-2 {
	font-size: 5.5rem;
	font-weight: 300;
	line-height: 1.2;
}
.thumb-md {
	height: 48px;
	width: 48px;
	display: block;
	text-align: center;
}
.abstractorordercompleted {
	height: 500px;
	overflow-y:scroll;
}
.recent-activity-tab .nav-item {
	position: relative;
	padding-top: 30px;
	border-top: 2px solid #e9ecef;
}
.recent-activity-tab.nav-justified .nav-item {
	-ms-flex-preferred-size: 0;
	flex-basis: 0;
	-ms-flex-positive: 1;
	flex-grow: 1;
	text-align: center;
}
.recent-activity-tab .nav-item::before {
	content: "";
	position: absolute;
	top: -7px;
	width: 12px;
	height: 12px;
	background: #1b82ec;
	border-radius: 50%;
	border: 2px solid rgba(255,255,255,.4);
}
.recent-activity-tab .nav-item .nav-link.active {
	color: #fff;
}
.recent-activity-tab .nav-item .nav-link {
	color: #fff;
	border-radius: 30px;
	position: relative;
}
.nav-pills .nav-link.active, .nav-pills .show>.nav-link {
	background: #1b82ec;
	-webkit-box-shadow: 0 2px 3px rgba(0,0,0,.1), 0 2px 5px rgba(0,0,0,.15);
	box-shadow: 0 2px 3px rgba(0,0,0,.1), 0 2px 5px rgba(0,0,0,.15);
}
.recent-activity-tab .nav-item .nav-link.active:before {
	content: "";
	position: absolute;
	width: 20px;
	height: 16px;
	background: #1b82ec;
	-webkit-transform: rotate(-60deg) skew(60deg);
	transform: rotate(-60deg) skew(60deg);
	top: -7px;
}
.nav-pills .nav-item .nav-link{
	min-width: 50px !important;
	background-color: #1b82ec !important;
	box-shadow: 0 4px 20px 0 rgba(0, 0, 0, .14), 0 7px 10px -5px rgb(201, 225, 251) !important;
} 
.infodiv p{
	font-size: 12px;
}
.infodiv{
	padding:5px;
}
.infoicon i{
	font-size: 15px;
	padding-top: 20px;
}
.card .form-check {
	margin-top: -15px;
}
.orderscount i{
	font-size: 17px;
	line-height: 10px;
	top:-3px;
}
.card-footer {
	margin-top:15px !important;
}
.orderspan  i{
	font-size: 8px;
	line-height: 20px;
	color: #fff;
}
.orderspan {
	width: 21px;
	height: 21px;
	/* margin-top: 5px; */
	box-shadow: 2px 1px 3px 1px #dad9d9;
	border-radius: 50%;
	text-align: center;
	line-height: 23px;
}
.orderyellow{
	background:#FFC107;
}
.orderpink{
	background:#F50057;
}
.ordergreen{
	background:#558B2F;
}
.orderblue{
	background:#3F51B5;
}
.orderred{
	background:#D50000;
}
.orderteal{
	background:#673AB7;
}
.orderorange{
	background:#E65100;
}
.orderskyblue{
	background:#039BE5;
}
.orderlime{
	background:#827717;
}
.progress {
	height: 6px !important;
}
</style>



<div class="col-md-12 dashboardview">
	<div class="row">
		<div class="col-lg-3 col-md-6 col-sm-6">
			<div class="card card-stats hover-widget">
				<div class="card-header card-header-warning card-header-icon">
					<div class="card-icon">
						<i class="icon-hour-glass2"></i>
					</div>
					<p class="card-category">TOTAL ORDERS </p>
					<h3 class="card-title">34</h3>
				</div>
				<div class="card-footer">
					<div class="stats">			
						<!-- <i class="icon-lab text-danger"></i> -->
						Total Orders
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-6">
			<div class="card card-stats hover-widget">
				<div class="card-header card-header-rose card-header-icon">
					<div class="card-icon">
						<i class="icon-alarm"></i>
					</div>
					<p class="card-category">PENDING </p>
					<h3 class="card-title">5</h3>
				</div>
				<div class="card-footer">
					<div class="stats">
						<!-- <i class="icon-lab"></i> -->
						Total Pending Orders
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-6">
			<div class="card card-stats hover-widget">
				<div class="card-header card-header-danger card-header-icon">
					<div class="card-icon">
						<i class="icon-wall"></i>
					</div>
					<p class="card-category">EXCEPTION </p>
					<h3 class="card-title">2</h3>
				</div>
				<div class="card-footer">
					<div class="stats">
						<!-- <i class="icon-lab"></i>  -->
						Exception Orders
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-6">
			<div class="card card-stats hover-widget">
				<div class="card-header card-header-info card-header-icon">
					<div class="card-icon">
						<i class="icon-forward2"></i>
					</div>
					<p class="card-category">COMPLETED </p>
					<h3 class="card-title">10</h3>
				</div>
				<div class="card-footer">
					<div class="stats">
						<!-- <i class="icon-lab"></i> -->
						Completed
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">   
		<div class="col-md-12">   
			<div class="card mt-10">
				<div class="card-header card-header-icon card-header-info">
					<div class="card-icon">
						Order Statistics
						<i class="icon-stats-growth"></i>
					</div> 
					<div class="row mt-10"> 
						<div class="col-md-12 text-right"> 
							<button class="btn btn-link btn-warning btn-xs filterbtn" id="orderstatisticsfilter"><i class="icon-filter3"></i></button> 
							<button class="btn btn-default btn-xs btn-link refreshdiv" ><i class="icon-sync"></i></button>
						</div>
					</div>
				</div>
				<div class="card-body pd-0 dd">
					<div class="col-md-12 filterdiv mb-20" style="display: none;">
						<div class="row">
							<div class="col-md-4">
								<select id="selectcustomer" class="form-control" multiple="multiple">
									<option value="vikings">Minnesota Vikings</option>
									<option value="packers">Green Bay Packers</option>
									<option value="lions">Detroit Lions</option>
									<option value="bears">Chicago Bears</option>
									<option value="patriots">New England Patriots</option>
									<option value="jets">New York Jets</option>
									<option value="bills">Buffalo Bills</option>
									<option value="dolphins">Miami Dolphins</option>
								</select>
							</div>
							<div class="col-md-4">

							</div>
						</div>
					</div>


					<div class="col-md-12">
						<div class="row">
							<div class="col-md-4">
								<div class="col-md-12 mt-20 pd-0">
									<div class="row borderseparator">
										<div class="col-2">
											<p class="orderspan orderyellow"><i class="icon-grid52"></i></p>
										</div>
										<div class="col-6">
											<p>Total Orders</p>
										</div>
										<div class="col-4">
											<a href="#"  class="getorders">34</a>
										</div>
									</div>
									<div class="row borderseparator">
										<div class="col-2">
											<p class="orderspan orderlime"><i class="icon-history"></i></p>
										</div>
										<div class="col-6">
											<p>Pending</p>
										</div>
										<div class="col-4">
											<a href="#" class="getorders">5</a>
										</div>
									</div>
									<div class="row borderseparator">
										<div class="col-2">
											<p class="orderspan orderred"><i class="icon-wall"></i></p>
										</div>
										<div class="col-6">
											<p>Exception</p>
										</div>
										<div class="col-4">
											<a href="#" class="getorders">2</a>
										</div>
									</div>
									<div class="row borderseparator">
										<div class="col-2">
											<p class="orderspan ordergreen"><i class="icon-checkmark4"></i></p>
										</div>
										<div class="col-6">
											<p>Completed</p>
										</div>
										<div class="col-4">
											<a href="#" class="getorders">10</a>
										</div>
									</div>



								</div>

							</div>
							<div class="col-md-8 pd-0 mt-20">
								<div id="colouredBarsChart1" class="ct-chart" style="padding: 20px 0px;"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">   
			<div class="card mt-10">
				<div class="card-header card-header-icon card-header-info">
					<div class="card-icon">
						Orders Due
						<i class="icon-pie-chart4"></i>
					</div> 

					<div class="row mt-10"> 
						<div class="col-md-12 text-right"> 				
							<button class="btn btn-default btn-xs btn-link refreshdiv"><i class="icon-sync"></i></button>
						</div>
					</div>

				</div>
				<div class="card-body pd-0"> 				
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-6 mt-30">
								<div id="chartPreferences" class="ct-chart mb-20"></div>
				<!-- 				<div class="col-md-12 mt-20 text-center">
									<button class="btn btn-tumblr"><i class="icon-unlink pr-1"></i> Aging Report</button>
								</div> -->
							</div>
							<div class="col-md-6 mb-20">
								<h4>ORDERS DUE</h4>
								<div class="row mt-20">
									<div class="col-md-3">
										<span>Due Today</span>
									</div>
									<div class="col-md-6">
										<div class="mt-10">
											<div class="progress progress-line-default">
												<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="40" style="width: 40%;">
													<span class="sr-only">100% Complete</span>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<span>23 (2 / 23)</span>
									</div>
								</div>

								<div class="row mt-10">
									<div class="col-md-3">
										<span>Past Due</span>
									</div>
									<div class="col-md-6">
										<div class="mt-10">
											<div class="progress progress-line-default">
												<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="40" style="width: 60%;">
													<span class="sr-only">100% Complete</span>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<span>932 (0 / 932)</span>
									</div>
								</div>

								<div class="row mt-10">
									<div class="col-md-3">
										<span>Future Due</span>
									</div>
									<div class="col-md-6">
										<div class="mt-10">
											<div class="progress progress-line-default">
												<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="40" style="width: 20%;">
													<span class="sr-only">100% Complete</span>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<span>67 (2 / 69)</span>
									</div>
								</div>	
								<div class="row">
									<div class="col-md-12">
										<h3 class="mb-0"> Total Orders in Due  : <span id="totaldueorders" class="fweight700">1024</span> </h3>
									</div>
								</div>
								<div class="row mt-10">
									<div class="col-md-12">
										<a class="btn btn-link btn-info pd-0">For details see Aging Report</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>




	<div class="row">
		<div class="col-md-12">
			<div class="card mt-30">
				<div class="card-header card-header-icon card-header-info">
					<div class="card-icon">
						Order Aging 
						<i class="icon-database-time2"></i>
					</div>
					<div class="row mt-10"> 
						<div class="col-md-12 text-right"> 				
							<button class="btn btn-default btn-xs btn-link refreshdiv"><i class="icon-sync"></i></button>
						</div>
					</div>
				</div>

				<div class="card-body">
					<div class="col-md-12 col-xs-12">
						<div class="table-responsive" style="overflow-y:auto;">
							<table class="table table-stripd table-bordered grp-mdl" id="pending-data">
								<thead>
									<tr>
										<th width="90" class="text-center">Orders Due</th>
										<th width="110" class="text-center">Due By</th>
										<th class="text-center" id="orders">
											<div class="icon-container">
												<div class="icon1">
													<span class="icon-file-text"></span>
												</div>
											</div><br>
											Total Pending Orders
										</th> 
										<th class="text-center" id="search">
											<div class="icon-container">
												<div class="icon9"><span class="icon-home8"></span></div>
											</div><br>
											Waiting For Images
										</th>
										<th class="text-center" id="search">
											<div class="icon-container">
												<div class="icon10"><span class="icon-paperplane"></span></div>
											</div><br>
											Stacking
										</th>
										<th class="text-center" id="exceptions">
											<div class="icon-container">
												<div class="icon6"><span class="icon-file-eye"></span></div>
											</div><br>
											Exceptions
										</th>
										<th class="text-center" id="taxcert">
											<div class="icon-container">
												<div class="icon5"><span class="icon-stamp"></span></div>
											</div><br>
											Export
										</th>
									</tr>
								</thead>
								<tbody id="OrderAge_Count">
									<tr><td class="text-center" rowspan="5"><b>Past Due</b></td>
										<td class="text-center"><b>1 day past due</b></td>
										<td class="text-center"><a href="javascript:void(0);" data-group="1 day past due" data-title="Pending Orders" class="text-primary grp-track" data-orderid="4547,15441">2</a></td>
										<td class="text-center"><a href="javascript:void(0);" data-group="1 day past due" data-title="In-house Search" class="text-primary grp-track" data-orderid="4547,15441">2</a></td>
										<td class="text-center">0</td>
										<td class="text-center">0</td>
										<td class="text-center">0</td>
									</tr><tr>
										<td class="text-center"><b>2 day past due</b></td>
										<td class="text-center"><a href="javascript:void(0);" data-group="2 day past due" data-title="Pending Orders" class="text-primary grp-track" data-orderid="15437,15438,15439">3</a></td>
										<td class="text-center"><a href="javascript:void(0);" data-group="2 day past due" data-title="In-house Search" class="text-primary grp-track" data-orderid="15438,15439">2</a></td>
										<td class="text-center"><a href="javascript:void(0);" data-group="2 day past due" data-title="External Search" class="text-primary grp-track" data-orderid="15437">1</a></td>
										<td class="text-center">0</td>
										<td class="text-center"><a href="javascript:void(0);" data-group="2 day past due" data-title="Tax Cert" class="text-primary grp-track" data-orderid="15437">1</a></td>
									</tr><tr>
										<td class="text-center"><b>3 day past due</b></td>
										<td class="text-center"><a href="javascript:void(0);" data-group="3 day past due" data-title="Pending Orders" class="text-primary grp-track" data-orderid="15436">1</a></td>
										<td class="text-center"><a href="javascript:void(0);" data-group="3 day past due" data-title="In-house Search" class="text-primary grp-track" data-orderid="15436">1</a></td>
										<td class="text-center">0</td>
										<td class="text-center">0</td>
										<td class="text-center">0</td>
									</tr><tr>
										<td class="text-center"><b>4 day past due</b></td>
										<td class="text-center"><a href="javascript:void(0);" data-group="4 day past due" data-title="Pending Orders" class="text-primary grp-track" data-orderid="1955,15433,15434">3</a></td>
										<td class="text-center"><a href="javascript:void(0);" data-group="4 day past due" data-title="In-house Search" class="text-primary grp-track" data-orderid="1955,15433,15434">3</a></td>
										<td class="text-center">0</td>
										<td class="text-center">0</td>
										<td class="text-center">0</td>
									</tr><tr>
										<td class="text-center"><b>&gt;7 day past due</b></td>
										<td class="text-center"><a href="javascript:void(0);" data-group=">7 day past due" data-title="Pending Orders" class="text-primary grp-track">443</a></td>
										<td class="text-center"><a href="javascript:void(0);" data-group=">7 day past due" data-title="In-house Search" class="text-primary grp-track" >151</a></td>
										<td class="text-center"><a href="javascript:void(0);" data-group=">7 day past due" data-title="External Search" class="text-primary grp-track" >96</a></td>
										<td class="text-center"><a href="javascript:void(0);" data-group=">7 day past due" data-title="Typing" class="text-primary grp-track" >43</a></td>
										<td class="text-center"><a href="javascript:void(0);" data-group=">7 day past due" data-title="Tax Cert" class="text-primary grp-track" >117</a></td>
									</tr><tr style="background:#f3f2f2">
										<td colspan="2" class="text-center"><b>Total Past Due</b></td>
										<td class="text-center"><a href="javascript:void(0);" data-group="Total Past Due" data-title="Pending Orders" class="text-primary grp-track" >452</a></td>
										<td class="text-center"><a href="javascript:void(0);" data-group="Total Past Due" data-title="In-house" class="text-primary grp-track" >159</a></td>
										<td class="text-center"><a href="javascript:void(0);" data-group="Total Past Due" data-title="External" class="text-primary grp-track" >97</a></td>
										<td class="text-center"><a href="javascript:void(0);" data-group="Total Past Due" data-title="Typing" class="text-primary grp-track" >43</a></td>
										<td class="text-center"><a href="javascript:void(0);" data-group="Total Past Due" data-title="Tax" class="text-primary grp-track" >118</a></td>
										</tr><tr><td class="text-center" rowspan="1"><b>Future Due</b></td>
											<td class="text-center"><b>within 3 days</b></td>
											<td class="text-center"><a href="javascript:void(0);" data-group="within 3 days" data-title="Pending Orders" class="text-primary grp-track" data-orderid="15442,15443,15444,15445,15446,15447,15448,15449">8</a></td>
											<td class="text-center"><a href="javascript:void(0);" data-group="within 3 days" data-title="In-house Search" class="text-primary grp-track" data-orderid="15444,15446,15447,15449">4</a></td>
											<td class="text-center"><a href="javascript:void(0);" data-group="within 3 days" data-title="External Search" class="text-primary grp-track" data-orderid="15442,15443,15445,15448">4</a></td>
											<td class="text-center">0</td>
											<td class="text-center"><a href="javascript:void(0);" data-group="within 3 days" data-title="Tax Cert" class="text-primary grp-track" data-orderid="15442,15443,15444,15445,15449">5</a></td>
										</tr><tr style="background:#f3f2f2">
											<td colspan="2" class="text-center"><b>Total Future Due</b></td>
											<td class="text-center"><a href="javascript:void(0);" data-group="Total Future Due" data-title="Pending Orders" class="text-primary grp-track" data-orderid="15442,15443,15444,15445,15446,15447,15448,15449">8</a></td>
											<td class="text-center"><a href="javascript:void(0);" data-group="Total Future Due" data-title="In-house" class="text-primary grp-track" data-orderid="15444,15446,15447,15449">4</a></td>
											<td class="text-center"><a href="javascript:void(0);" data-group="Total Future Due" data-title="External" class="text-primary grp-track" data-orderid="15442,15443,15445,15448">4</a></td>
											<td class="text-center"><a href="javascript:void(0);" data-group="Total Future Due" data-title="Typing" class="text-primary grp-track" data-orderid="0">0</a></td>
											<td class="text-center"><a href="javascript:void(0);" data-group="Total Future Due" data-title="Tax" class="text-primary grp-track" data-orderid="15442,15443,15444,15445,15449">5</a></td>
											</tr><tr>
												<td colspan="2" class="text-center"><b>Total Orders</b></td>
												<td class="text-center"><a href="javascript:void(0);" data-group="Total Orders" data-title="Pending Orders" class="text-primary grp-track" >460</a></td>
												<td class="text-center"><a href="javascript:void(0);" >163</a></td>
												<td class="text-center"><a href="javascript:void(0);" >101</a></td>
												<td class="text-center"><a href="javascript:void(0);" >43</a></td>
												<td class="text-center"><a href="javascript:void(0);" >123</a></td>
											</tr></tbody>
										</table>
									</div>
								</div>
							</div>
						</div>	
					</div>

				</div>
			</div>



			<div class="col-md-12 pd-0 orderstable" style="display:none">
			</div>

			<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/chartist.min.js"></script>
			<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/amchart/amcharts.js"></script>
			<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/amchart/serial.js"></script>
			<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/amchart/export.min.js"></script>
			<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/amchart/light.js"></script>
			<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/multiselect/js/bootstrap-multiselect.js"></script>
			<script  type="text/javascript"  src="<?php echo base_url(); ?>assets/js/Flex-Gauge.js" ></script>
			<script  type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/chartist-plugin-legend.min.js"  type="text/javascript" ></script>
			<script src="<?php echo base_url(); ?>assets/plugins/amchart/ammap.js" type="text/javascript" ></script>
			<script  src="<?php echo base_url(); ?>assets/plugins/amchart/usaLow.js"  type="text/javascript" ></script>

			<script type="text/javascript">
				$(document).ready(function(){

				// var spinnerbody = '<svg class="d2tspinner-circular" viewBox="25 25 50 50" style="width:70px;height:70px;z-index: 99999;"><circle class="d2tspinner-path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg>'; 
				// var bodyoverlaydiv = "<div class='bodyoverlaydiv'></div>"; 
				// $("body").append(spinnerbody);
				// $("body").append(bodyoverlaydiv);


				$('#boot-multiselect-demo').multiselect({
					includeSelectAllOption: true,
					maxHeight: 400							
				});
					// demo.initCharts();


					// values = [
					// [12, 9, 7, 8, 5, 4],
					// [2, 1, 3.5, 7, 3, 9],
					// [1, 3, 4, 5, 6, 7]
					// ];

// Initiate Chart 1
Chartist.Line("#colouredBarsChart1", {
	labels: [1, 2, 3],
	series: [
	[
	{meta: 'description', value: 1 },
	{meta: 'description', value: 5},
	{meta: 'description', value: 3},
	{meta: 'description', value: 3},
	{meta: 'description', value: 3},
	{meta: 'description', value: 3},
	{meta: 'description', value: 3}
	],
	[
	{meta: 'other description', value: 2},
	{meta: 'other description', value: 4},
	{meta: 'other description', value: 2},
	{meta: 'other description', value: 2},
	{meta: 'other description', value: 4},
	{meta: 'other description', value: 2}
	]
	]
}, {
	fullWidth: true,
	chartPadding: {
		right: 40
	},
	height: '300px',
	axisY: {
		onlyInteger: true
	},
	plugins: [
	// Chartist.plugins.tooltip()
	]
})

new Chartist.Pie('.pct-chart', {
	series: [
	{ "name": "Pending", "data": 70 },
	{ "name": "Approved", "data": 20 },
	{ "name": "Waiting", "data": 10 },
	],
	labels: ['Pending','Waiting','Approved']
},
{
	donut: true,
	donutWidth: 10,
	donutSolid: true,
	startAngle: 270,
	showLabel: false,
	plugins: [
	Chartist.plugins.legend({
	})
	]
}).on('draw', function(context) {
	if(context.type === 'slice') {
		var $slice = $(context.element._node);
		$slice.tooltipster({
			content: $slice.parent().attr('ct:series-name') + ' ' + $slice.attr('ct:value')
		});
	}
});



Chartist.Pie('#chartPreferences', {
	series: [
	{ "name": "Future Due", "data": 62 },
	{ "name": "Past Due", "data": 32 },
	{ "name": "Due Today", "data": 8 },
	],
	labels: ['62%', '32%', '6%']
                // series: [62, 32, 6]
            }, {
            	height: '230px'
            }).on('draw', function(context) {
            	if(context.type === 'slice') {
            		var $slice = $(context.element._node);
            		$slice.tooltipster({
            			content: $slice.parent().attr('ct:series-name') + ' ' + $slice.attr('ct:value')
            		});
            	}
            });




            var dataSimpleBarChart = {
            	labels: ['p', 'd', 't', 'b', 'z', 'f'],
            	series: [
            	[542, 443, 320, 780, 553, 453]
            	]
            };

            var optionsSimpleBarChart = {
            	seriesBarDistance: 10,
            	axisX: {
            		showGrid: false
            	}
            };

            var responsiveOptionsSimpleBarChart = [
            ['screen and (max-width: 640px)', {
            	seriesBarDistance: 5,
            	axisX: {
            		labelInterpolationFnc: function(value) {
            			return value[0];
            		}
            	}
            }]
            ];

            var simpleBarChart = Chartist.Bar('#simpleBarChart', dataSimpleBarChart, optionsSimpleBarChart, responsiveOptionsSimpleBarChart).on('draw', function(context) {
            	if(context.type === 'slice') {
            		var $slice = $(context.element._node);
            		$slice.tooltipster({
            			content: $slice.parent().attr('ct:series-name') + ' ' + $slice.attr('ct:value')
            		});
            	}
            });




            // md.startAnimationForLineChart(colouredBarsChart);




            $("#orderstatisticsfilter").click(function(){
            	loadJS()
            	$(".filterdiv").show();
            	$(".filterdiv").html("");
            	$(".filterdiv").load("<?php echo base_url()?>dashboard/Loadfilter");
            });
            $("#abstractorfilter").click(function(){
            	loadJS()
            	$(".absfilterdiv").show();			
            });
            $(".abstractoroption").change(function(){
            	var absoption  = $(".abstractoroption option:selected").val();
            	if(absoption == "1"){
            		$(".abstractorordercompleted").slideDown();
            		$(".abstractortat").slideUp();
            		$(".abstractorquality").slideUp();
            	}
            	else if(absoption == "2"){
            		$(".abstractorordercompleted").slideUp();
            		$(".abstractortat").slideDown();
            		$(".abstractorquality").slideUp();
            	}
            	else if(absoption == "3"){
            		$(".abstractorordercompleted").slideUp();
            		$(".abstractortat").slideUp();
            		$(".abstractorquality").slideDown();
            	}
            });	

            $(".orderfilterbtn").click(function(){
            	$(".orderfilter").toggle();
            	$("i" , this).toggleClass("icon-x icon-filter3");
            	$(this).toggleClass("btn-danger btn-warning");
            	$(".filterbtn").css("z-index" , "-1 !important");
            });


            $(".productfilterbtn").click(function(){
            	$(".productfilter").toggle();
            	$("i" , this).toggleClass("icon-x icon-filter3");
            	$(this).toggleClass("btn-danger btn-warning");
            	$(".filterbtn").css("z-index" , "-1 !important");
            });



            $(".slafilterbtn").click(function(){
            	$(".slafilter").toggle();
            	$("i" , this).toggleClass("icon-x icon-filter3");
            	$(this).toggleClass("btn-danger btn-warning");
            	$(".filterbtn").css("z-index" , "-1 !important");
            });



            $(".abstractorfilter").click(function(){
            	$(".absfilterdiv").toggle();
            	$("i" , this).toggleClass("icon-x icon-filter3");
            	$(this).toggleClass("btn-danger btn-warning");
            	$(".filterbtn").css("z-index" , "-1 !important");
            });

            $(".orderrevenuebtn").click(function(){
            	$(".orderrevenuediv").toggle();
            	$("i" , this).toggleClass("icon-x icon-filter3");
            	$(this).toggleClass("btn-danger btn-warning");
            	$(".filterbtn").css("z-index" , "-1 !important");
            });







            




            var gauge = new FlexGauge({
            	appendTo: '#example6',                                                  
            	arcFillInt:70,
            	arcFillTotal: 100,
            	colorArcFg: "#43A047" 
            });

        });


function loadJS()
{	

}

</script>

<script>







	var chart = AmCharts.makeChart( "chartdiv", {
		"type": "serial",
		"theme": "light",
		"depth3D":0,
		"angle": 30,
		"columnWidth": 0.4,
		"colors": [
		"#00cae3",
		"#66BB6A",
		"#F44336",
		"#EF6C00"
		],
		"legend": {
			"horizontalGap": 10,
			"useGraphSettings": true,
			"markerSize": 10
		},
		"dataProvider": [ {
			"colname": "03-07-2018",
			"withSLA": 2.5,
			"namerica": 2.5,
			"asia": 2.1,
			"lamerica": 1.2
		}, {
			"colname": "04-07-2018",
			"withSLA": 2.6,
			"namerica": 2.7,
			"asia": 2.2,
			"lamerica": 1.3
		}, {
			"colname":  "05-07-2018",
			"withSLA": 2.8,
			"namerica": 2.9,
			"asia": 2.4,
			"lamerica": 1.4
		}, 
		{
			"colname":  "06-07-2018",
			"withSLA": 2.8,
			"namerica": 2.9,
			"asia": 2.4,
			"lamerica": 1.4
		}, 
		{
			"colname":  "07-07-2018",
			"withSLA": 2.8,
			"namerica": 2.9,
			"asia": 2.4,
			"lamerica": 1.4
		}, 
		{
			"colname":  "08-07-2018",
			"withSLA": 2.8,
			"namerica": 2.9,
			"asia": 2.4,
			"lamerica": 1.4
		}, 
		{
			"colname":  "09-07-2018",
			"withSLA": 2.8,
			"namerica": 2.9,
			"asia": 2.4,
			"lamerica": 1.4
		}, 
		{
			"colname":  "10-07-2018",
			"withSLA": 2.8,
			"namerica": 2.9,
			"asia": 2.4,
			"lamerica": 1.4
		}
		],
		"valueAxes": [ {
			"stackType": "regular",
			"axisAlpha": 0,
			"gridAlpha": 0
		} ],
		"graphs": [ {
			"balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
			"fillAlphas":1,   
			"lineAlpha": 0.3,
			"title": "Pending Within SLA",
			"type": "column",
			"color": "#fff",
			"valueField": "withSLA"
		}, {
			"balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
			"fillAlphas":1,     
			"lineAlpha": 0.3,
			"title": "Completed Within SLA",
			"type": "column",
			"color": "#fff",
			"valueField": "namerica"
		}, {
			"balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
			"fillAlphas":1,    
			"lineAlpha": 0.3,
			"title": "Pending Crossed SLA",
			"type": "column",
			"newStack": true,
			"color": "#fff",
			"valueField": "asia"
		}, {
			"balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
			"fillAlphas": 1,   
			"lineAlpha": 0.3,
			"title": "Completed Crossed SLA",
			"type": "column",
			"color": "#fff",
			"valueField": "lamerica"
		}],
		"categoryField": "colname",
		"categoryAxis": {
			"gridPosition": "start",
			"axisAlpha": 0,
			"gridAlpha": 0,
			"position": "left"
		}
	});






            //start animation for the Emails Subscription Chart
          //  md.startAnimationForBarChart(simpleBarChart);



            // md.startAnimationForLineChart(RoundedLineChart);




       // var dataPreferences = {
       //          labels: ['62%', '32%', '6%'],
       //          series: [62, 32, 6]
       //      };
       //      var optionsPreferences = {
       //          height: '230px'
       //      };

            // var data = {
            // 	series: [5, 3, 4]
            // };
            // var sum = function(a, b) { return a + b };
            // new Chartist.Pie('#chartPreferences', data, {
            // 	labelInterpolationFnc: function(value) {
            // 		return Math.round(value / data.series.reduce(sum) * 100) + '%';
            // 	}
            // })


            dataStraightLinesChart = {
            	labels: ['\'07', '\'08', '\'09', '\'10', '\'11', '\'12', '\'13', '\'14', '\'15'],
            	series: [
            	[10, 16, 8, 13, 20, 15, 20, 34, 30]
            	]
            };

            optionsStraightLinesChart = {
            	lineSmooth: Chartist.Interpolation.cardinal({
            		tension: 0
            	}),
            	low: 0,
                high: 50, // creative tim: we recommend you to set the high sa the biggest value + something for a better look
                chartPadding: {
                	top: 0,
                	right: 0,
                	bottom: 0,
                	left: 0
                },
                classNames: {
                	point: 'ct-point ct-white',
                	line: 'ct-line ct-white'
                }
            }

            var straightLinesChart = new Chartist.Line('#straightLinesChart', dataStraightLinesChart, optionsStraightLinesChart).on('draw', function(context) {
            	if(context.type === 'slice') {
            		var $slice = $(context.element._node);
            		$slice.tooltipster({
            			content: $slice.parent().attr('ct:series-name') + ' ' + $slice.attr('ct:value')
            		});
            	}
            })




            // md.startAnimationForLineChart(straightLinesChart);


            var chart = new Chartist.Line('#feestatistics', {
            	labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June' , 'July' , 'Aug' , 'Sep' , 'Oct'],
            	series: [
            	[1, 5, 2, 5, 4, 3 ,5,7,4,3]   
            	]
            }, {
            	low: 0,
            	showArea: true,
            	showPoint: true,
            	fullWidth: true
            }).on('draw', function(context) {
            	if(context.type === 'slice') {
            		var $slice = $(context.element._node);
            		$slice.tooltipster({
            			content: $slice.parent().attr('ct:series-name') + ' ' + $slice.attr('ct:value')
            		});
            	}
            }).on("created", function() {
	// Initiate Tooltip
	$("#feestatistics").tooltip({
		selector: '[data-chart-tooltip="feestatistics"]',
		container: "#feestatistics",
		html: true
	});
});

            // chart.on('draw', function(data) {
            // 	if(data.type === 'line' || data.type === 'area') {
            // 		data.element.animate({
            // 			d: {
            // 				begin: 2000 * data.index,
            // 				dur: 2000,
            // 				from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
            // 				to: data.path.clone().stringify(),
            // 				easing: Chartist.Svg.Easing.easeOutQuint
            // 			}
            // 		});
            // 	}
            // });


            $(document).ready(function() {

            	var spinner = '<svg class="d2tspinner-circular" viewBox="25 25 50 50" style="width:50px;z-index: 10000;"><circle class="d2tspinner-path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg>';
            	var overlaydiv  = "<div class='overlay'></div>";


            	// var spinnerbody = '<svg class="d2tspinner-circular" viewBox="25 25 50 50" style="width:50px;z-index: 999999;"><circle class="d2tspinner-path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg>';            
            	// var bodyoverlaydiv  = "<div class='bodyoverlaydiv'></div>";            	
            	// $("body").append(spinnerbody);
            	// $("body").append(bodyoverlaydiv);



            	$(".refreshdiv").click(function(){	
            		$(this).closest(".card").append(spinner);	
            		$(this).closest(".card").append(overlaydiv);
            		$(this).closest(".card .btn").addClass("reduceindex");
            	});


            	$(".getorders").click(function(){
            		$(".dashboardview").slideUp();
            		$(".orderstable").slideDown();
            		$(".orderstable").load("<?php echo base_url('Dashboardui/viewordersdetails'); ?>");
            	});		

            	$(".multiselectpicker").multiselect({
            		includeSelectAllOption: true,
            		nonSelectedText: 'Select Customer',
            		templates: { 
            			li: '<li><div class="checkbox"><label></label></div></li>'
            		}
            	});
            	$('#selectcustomer').multiselect({
            		includeSelectAllOption: true,
            		nonSelectedText: 'Select Customer',
            		templates: { 
            			li: '<li><div class="checkbox"><label></label></div></li>'
            		}
            	});
            	$('.multiselect-container div.checkbox').each(function (index) {
            		var id = 'multiselect-' + index,
            		$input = $(this).find('input');       
            		$(this).find('label').attr('for', id);  
            		$input.attr('id', id);
            		$input.detach();
            		$input.prependTo($(this));
            		$(this).click(function (e) {      
            			e.stopPropagation();
            		});
            	});

            	var map = AmCharts.makeChart( "mapdiv", {
            		"type": "map",
            		"theme": "light",
            		"colorSteps": 10,
            		"zoomControl": {
            			"homeButtonEnabled": false,
            			"zoomControlEnabled": false,
            			"panControlEnabled": false,
            		},
            		"dataProvider": {
            			"map": "usaLow",
            			"areas": [ {
            				"id": "US-AL",
            				"value": 24,
            				"customData" : 45
            			}, {
            				"id": "US-AK",
            				"value": 45,
            				"customData" : 55
            			}, {
            				"id": "US-AZ",
            				"value": 23,
            				"customData" : 55
            			}, {
            				"id": "US-AR",
            				"value": 45,
            				"customData" : 55
            			}, {
            				"id": "US-CA",
            				"value": 12,
            				"customData" : 65
            			}, {
            				"id": "US-CO",
            				"value": 56,
            				"customData" : 65
            			}, {
            				"id": "US-CT",
            				"value": 31,
            				"customData" :75
            			}, {
            				"id": "US-DE",
            				"value": 17,
            				"customData" :25
            			}, {
            				"id": "US-FL",
            				"value": 21,
            				"customData" :45
            			}, {
            				"id": "US-GA",
            				"value": 41,
            				"customData" :65
            			}, {
            				"id": "US-HI",
            				"value": 0,
            				"customData" :0
            			}, {
            				"id": "US-ID",
            				"value": 18,
            				"customData" :35
            			}, {
            				"id": "US-IL",
            				"value": 49,
            				"customData" :55
            			}, {
            				"id": "US-IN",
            				"value": 27,
            				"customData" :55
            			}, {
            				"id": "US-IA",
            				"value": 45,
            				"customData" :65
            			}, {
            				"id": "US-KS",
            				"value": 21,
            				"customData" :75
            			}, {
            				"id": "US-KY",
            				"value": 12,
            				"customData" :15
            			}, {
            				"id": "US-LA",
            				"value": 56,
            				"customData" :25
            			}, {
            				"id": "US-ME",
            				"value": 62,
            				"customData" :85
            			}, {
            				"id": "US-MD",
            				"value": 59,
            				"customData" :85
            			}, {
            				"id": "US-MA",
            				"value": 82,
            				"customData" :85
            			}, {
            				"id": "US-MI",
            				"value": 71,
            				"customData" :85
            			}, {
            				"id": "US-MN",
            				"value": 25,
            				"customData" :55
            			}, {
            				"id": "US-MS",
            				"value": 34,
            				"customData" :65
            			}, {
            				"id": "US-MO",
            				"value": 59,
            				"customData" :65
            			}, {
            				"id": "US-MT",
            				"value": 61,
            				"customData" :75
            			}, {
            				"id": "US-NE",
            				"value": 69,
            				"customData" :75
            			}, {
            				"id": "US-NV",
            				"value": 31,
            				"customData" :75
            			}, {
            				"id": "US-NH",
            				"value": 56,
            				"customData" :65
            			}, {
            				"id": "US-NJ",
            				"value": 51,
            				"customData" :65
            			}, {
            				"id": "US-NM",
            				"value": 40,
            				"customData" :65
            			}, {
            				"id": "US-NY",
            				"value": 33,
            				"customData" :65
            			}, {
            				"id": "US-NC",
            				"value": 22,
            				"customData" :65
            			}, {
            				"id": "US-ND",
            				"value": 12,
            				"customData" :65
            			}, {
            				"id": "US-OH",
            				"value": 34,
            				"customData" :65
            			}, {
            				"id": "US-OK",
            				"value": 12,
            				"customData" :65
            			}, {
            				"id": "US-OR",
            				"value": 45,
            				"customData" :65
            			}, {
            				"id": "US-PA",
            				"value": 34,
            				"customData" :65
            			}, {
            				"id": "US-RI",
            				"value": 45,
            				"customData" :75
            			}, {
            				"id": "US-SC",
            				"value": 18,
            				"customData" :75
            			}, {
            				"id": "US-SD",
            				"value": 19,
            				"customData" :75
            			}, {
            				"id": "US-TN",
            				"value": 36,
            				"customData" :75
            			}, {
            				"id": "US-TX",
            				"value": 25,
            				"customData" :75
            			}, {
            				"id": "US-UT",
            				"value": 41,
            				"customData" :75
            			}, {
            				"id": "US-VT",
            				"value": 14,
            				"customData" :75
            			}, {
            				"id": "US-VA",
            				"value":18,
            				"customData" :75
            			}, {
            				"id": "US-WA",
            				"value": 56,
            				"customData" :75
            			}, {
            				"id": "US-WV",
            				"value": 20,
            				"customData" :25
            			}, {
            				"id": "US-WI",
            				"value": 10,
            				"customData" :25
            			}, {
            				"id": "US-WY",
            				"value": 21,
            				"customData" :25

            			} ]
            		},
            		"areasSettings": {							
            			"balloonText": "[[title]] <br> [[value]] - Pending Orders , <br> [[customData]] - Orders placed"
            		},
            		"valueLegend": {
            			"right": 10,
            			"minValue": "min",
            			"maxValue": "Max"
            		}
            	});


map.addListener("init", function() {
	setTimeout(function() {
    // iterate through areas and put a label over center of each
    map.dataProvider.images = [];
    for (x in map.dataProvider.areas) {
    	var area = map.dataProvider.areas[x];
    	var image = new AmCharts.MapImage();
    	image.latitude = map.getAreaCenterLatitude(area);
    	image.longitude = map.getAreaCenterLongitude(area);
    	image.label = area.id.substr(3);
    	image.title = area.title;
    	image.linkToObject = area;
    	map.dataProvider.images.push(image);
    }
    map.validateData();
    // console.log( map.dataProvider );
}, 100)
});
});

</script>






<!-- https://codepen.io/chimmer/pen/aJvBgv -->
<!-- https://codepen.io/niketmalik/pen/BZjgpQ