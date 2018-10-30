<!DOCTYPE html>
<html lang="en">    

<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url(); ?>assets/img/apple-icon.png">
	<link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Direct2Title</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
	<meta name="keywords" content="Direct2Title">
	<meta name="description" content="">
	<!-- Schema.org markup for Google+ -->
	<meta itemprop="name" content="">
	<meta itemprop="description" content="">
	<link rel="stylesheet" type="text/css"  href="<?php echo base_url(); ?>assets/icon/css/ionicons.css" />
	<link href="<?php echo base_url(); ?>assets/css/icomoon.css" rel="stylesheet" type="text/css"/>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"  rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url(); ?>assets/css/material-dashboard.min.css?v=2.0.2" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url(); ?>assets/demo/demo.css" rel="stylesheet" type="text/css"/>
		
	<link href="<?php echo base_url(); ?>assets/css/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jquery.datetextentry.css" />
	<link href="<?php echo base_url(); ?>assets/css/jquerysctipttop.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/select2/select2.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/select2/select2-bootstrap.css" />
	<link rel="stylesheet" type="text/css"  href="<?php echo base_url();?>assets/css/select2/pmd-select2.css" />
	<link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url(); ?>assets/css/responsive.dataTables.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">

<!-- 	<link href="<?php echo base_url(); ?>assets/plugins/bootstrap4-editable/css/bootstrap-editable.css" rel="stylesheet" -->
	<link href="<?php echo base_url(); ?>assets/plugins/nprogress/nprogress.css" rel="stylesheet">
	<!-- js Files -->
	<script src="<?php echo base_url(); ?>assets/js/core/jquery.min.js" type="text/javascript" ></script>
	<script src="<?php echo base_url(); ?>assets/js/load.js" type="text/javascript" ></script>	
	<script src="<?php echo base_url(); ?>assets/plugins/nprogress/nprogress.js"  type="text/javascript"></script>



	<script type="text/javascript">
		const base_url = '<?php echo base_url(); ?>';
		const USERNAME = '<?php echo $this->session->userdata('UserName') ?>';
		const USERUID = '<?php echo $this->session->userdata('UserUID') ?>';
		
		var MENU_URL='';


	</script>
	<style>
		.select2{
			display:block !important;
			width:100%  !important;
		}
		@media (min-width: 576px){
			#abstractormodal .modal-dialog{
				max-width: 1000px;
			}
		}
		#abstractormodal .modal-dialog{
			    margin-top: 20px !important;
		}
		#abstractormodal p{
			font-size: 12px;
		}		

	</style>

</head>
