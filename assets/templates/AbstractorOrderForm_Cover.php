<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style type="text/css">

	@page {
		header: html_MyCustomHeader;/* display <htmlpageheader name="MyCustomHeader"> on all pages */
		footer: html_MyCustomFooter;/* display <htmlpagefooter name="MyCustomFooter"> on all pages */
	}

	@page { sheet-size: Letter; }
	@page {
		margin-top:2cm;
		margin-left: 0.5cm;
		margin-right: 0.5cm;
		margin-bottom: 1.7cm;
	}

	body p{
		font-family: 'calibri', serif;
		font-size: 11pt;
		margin: 0px; font-weight: 100;
	}

	.col-6, .col-12
	{
		position: relative;
		min-height: 1px;
		padding-left: 15px;
		padding-right: 15px;
		float: left;
	}

	.col-6{width: 43%;}
	.col-12{width: 100%;}
	.col-2{width: 16.66%;
		position: relative;
		min-height: 1px;
		float: left;
	}
	.col-3{width: 25%;
		position: relative;
		min-height: 1px;
		float: left;
	}
	.col-4{width: 25%;
		position: relative;
		min-height: 1px;
		float: left;
	}
	.col-8{
		width: 75%;
		position: relative;
		min-height: 1px;
		float: left;
	}
	.col-4 p{
		margin-top: 5px;
	}
	.col-8 p{
		margin-top: 5px;
	}
	.box{border: 1pt solid black;}
	.box p{text-align: center;}
	.b-5{border: 5px solid black;}
	.b-10{border: 10px solid black;}
	.text-center{text-align: center;}
	.m-tb-5{margin-top: 5px; margin-bottom: 5px;} 
	.m-rl-30{margin-right: 10px; margin-left: 10px;} 

</style>
</head>

<body>

	<htmlpagefooter name="MyCustomFooter">
		<!-- <p style="text-align: right;">1</p> -->
		</htmlpagefooter>

		<div class="container col-12">

			<div class="col-6">
				<img style="margin: 20px;" src="%%Imageurl%%" alt="ISGN LOGO">
				<div class="col-12">
					<h2 style="padding: 0px; margin: 0px;">ABSTRACTOR ORDER</h2>
				</div>
			</div>
			<div class="col-6 box">
				<div class="" style="font-family: 'times', serif; font-size: 11px;">
					<div class="m-tb-5 m-rl-30">
						<p>&nbsp;</p>
						<p><strong>ISGN FULFILLMENT SERVICES, INC.</strong></p>
						<p><strong>2330 COMMERCE DRIVE, SUITE 2</strong></p>
						<p><strong>PALM BAY, FL 32905</strong></p>
						<p><strong>Phone: (855) 884-8001&nbsp;&nbsp; Fax: (866) 513-9477</strong></p>
						<p>&nbsp;</p>
					</div>
				</div>
			</div>
		</div>

		<div class="container">
			<table style="padding: 0; margin: 0; font-family: 'calibri', serif; white-space: nowrap;">
				<tbody>
					<tr>
						<td style="text-align: right; width: 25%;"><p><strong>Order Number:</strong></p></td>
						<td style="width: 10%;"><p><strong>%%OrderNumber%%</strong></p></td>
						<td style="text-align: right; width: 15%;"><strong>Ordered By: </strong></td>
						<td style="width: 10%;">%%UserName%%</td>
						<td style="text-align: right;" style="width: 30%;"><p style="color:white;"><strong>Deal No: </strong></td>
					</tr>
					<tr>
						<td style="text-align: right; width: 25%;"><p><strong>Order Date:</strong></p></td>
						<td style="width: 10%;"><p><strong>%%OrderDatetime%% EST</strong></p></td>
						<td style="text-align: right; width: 15%;"><strong> <span style="color: white;">Print Date:</span></strong></td>
						<td style="" colspan="2"><strong> <span style="color: white;">CureentDateTime EST </span></strong></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="container col-12">



			<p style="margin-top: 5px; border: none; border-bottom: 5px solid black;"></p>
			<p>&nbsp;</p>
			<div class="col-4">
				<p style="text-align: right;"><strong>Vendor Charge: </strong></p>
				<p style="text-align: right;"><strong>Vendor No: </strong></p>
				<p style="text-align: right;"><strong>Vendor Name: </strong></p>
				<p style="text-align: right;"style="text-align: right;"><strong>Assignment Type: </strong></p>
			</div>
			<div class="col-8">
				<p> &nbsp;$%%fee%%</p>
				<p> &nbsp;%%AbstractorNumber%%</p>
				<p> &nbsp;%%AbstractorName%%</p>
				<p> &nbsp;%%OrderTypeNames%%</p>

			</div>
<!-- 			<div class="col-4">
				<p>&nbsp;</p>
			</div>
			<div class="col-4">
				<p>&nbsp;</p>
			</div> -->
			<div class="col-6" style="float: right;">
				<p style="text-align: right;"><strong>Subscriber Due Date:</strong>Report Due Back within %%AbstractorProductTAT%% hours of Receipt</p>	
			</div>
			<div style="clear:both">
			</div>


			<div class="col-4">
				<p style="text-align: right;"><strong>Product Code: </strong></p>
			</div>
			<div class="col-4">
				<p>&nbsp;%%SubProductName%%</p>
			</div>
			<div class="col-4">
				<p>&nbsp;</p>
			</div>
			<div class="col-4">
				<p>&nbsp;</p>
			</div>

			<p style="margin-top: 5px; border: none; border-bottom: 2px solid black;"></p>
			<p>&nbsp;</p>

			<div class="col-4">
				<p style="text-align: right;"><strong>Borrower:</strong></p>
			</div>
			<div class="col-4">
				<p> &nbsp;%%BorrowerName%%</p>
			</div>
			<div class="col-4">
				<p>&nbsp;</p>
			</div>
			<div class="col-4">
				<p>&nbsp;</p>
			</div>

			<p style="margin-top: 10px; border: none; border-bottom: 3px solid black;"></p>

			<p>&nbsp;</p>
			<div class="col-4">
				<p style="text-align: right;"><strong>Property Address: </strong></p>
				<p style="text-align: right;"><strong>&nbsp; </strong></p>
				<p style="text-align: right;"><strong>County: </strong></p>
				<p style="text-align: right;"><strong>Loan Number: </strong></p>

			</div>
			<div class="col-4">
				<p> &nbsp;%%PropertyAddress1%%</p>
				<p>&nbsp; %%CityName%%, %%StateCode%% - %%ZipCode%%</p>
				<p>&nbsp; %%CountyName%%</p>
				<p>&nbsp; %%LoanNumber%%</p>
			</div>
			<div class="col-4">
				<p>&nbsp;</p>
			</div>
			<div class="col-4">
				<p>&nbsp;</p>
			</div>
			<div class="col-4">
				<p style="text-align: right;"><strong>Comments: </strong></p>
			</div>
			<div class="col-8">
				<p style="font-size: 11pt; color: #000080;"><strong>&nbsp; %%Notes%%</strong></p>
			</div>

		</div>


	</body>
	</html>