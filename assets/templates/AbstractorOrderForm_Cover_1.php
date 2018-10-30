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
		<p style="text-align: right;">1</p>
		</htmlpagefooter>

		<div class="container col-12">

			<div class="col-6">
				<img style="margin: 20px;" src="data:image/png;base64,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" alt="ISGN LOGO">
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
			<div class="col-4">
				<p> &nbsp;$%%fee%%</p>
				<p> &nbsp;%%AbstractorNumber%%</p>
				<p> &nbsp;%%AbstractorName%%</p>
				<p> &nbsp;%%OrderTypeName%%</p>

			</div>
			<div class="col-4">
				<p>&nbsp;</p>
			</div>
			<div class="col-4">
				<p>&nbsp;</p>
			</div>
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