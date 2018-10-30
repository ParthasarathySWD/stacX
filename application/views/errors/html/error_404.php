<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $heading; ?></title>
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons">
	<link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>assets/css/material-dashboard.min.css" rel="stylesheet">
	<style>
	/* ================= GENERAL=================*/
	.errorpage {
		max-width: 650px;
		margin: 5% auto auto auto;
	}
	svg{
		fill: #444243;
	}
	.errorpage .no-border {
		border: none!important;
	}
	.errorpage .no-padding {
		padding: 10px !important;
	}
	.errorpage .bottom-margin {
		margin-bottom: 2em;
	}
	.errorpage .no-margin {
		margin-bottom: 0!important;
	}
	.errorpage img.pull-right {
		margin: 0px 0px 1.5em 1.5em;
		border-radius: 0!important;
	}
	.errorpage img.pull-left {
		margin: 0px 1.5em 1.5em 0px;
		border-radius: 0!important;
	}
	.errorpage i {
		margin-right: 0.3em;
	}

	.errorpage blockquote {
		font-size: 1.1em;
	}
	.errorpage ul.list-default {
		list-style-type: none;
	}
	.errorpage ul.list-default li {
		padding: 0.8em 0 0.8em 2.5em;
		position: relative;
	}

	.errorpage input, .errorpage  button,.errorpage  select, .errorpage textarea {
		border-radius: 0!important;
	}
	.errorpage .text-muted {
		color: #b2b2b2;
		margin: 0
	}
	/* ================== LAYOUT SPECIFIC =================*/
	.errorpage #wrapper {
		background: #fff;
		-webkit-border-radius: 0.4em;
		border-radius: 0.4em;
		padding: 0;
		-webkit-box-shadow: 0 0 25px 0 #d5d9e0;
		box-shadow: 0 0 25px 0 #d5d9e0;
		border: 1px solid #d0d7df;
		overflow: hidden;
	}
	.errorpage #wrapper article h1, #wrapper article h4 {
		text-align: center;
	}
	.errorpage header {
		display: block;
		background: #fff;
		padding: 1.5em 1.5em 1.5em 1.5em;
		margin: 0em 0em 2em;
		border-bottom: 1px solid rgba(0,0,0,0.1);
		-webkit-border-radius: 0.4em 0.4em 0 0;
		border-radius: 0.4em 0.4em 0 0;
		-webkit-box-shadow: inset 0 0 0 1px rgba(255,255,255,1);
		box-shadow: inset 0 0 0 1px rgba(255,255,255,1);
	}
	.errorpage header h3 {
		color: #9ea7b3;
		font-size: 1.5em;
		margin: 0;
	}
	.errorpage header h3 a {
		line-height: 1.3em;
	}
	.errorpage .errorpage .errorpage header ul.list-inline {
		margin-bottom: 0;
	}
	.errorpage .errorpage header ul.list-inline li a i {
		margin-right: 0.2em;
	}
	.errorpage header ul.list-inline li a {
		font-size: 1.2em;
	}
	.errorpage header ul.list-inline li.last a i {
		margin-right: 0;
	}
	.errorpage .section-icon {
		display: block;
		color: #fff;
		text-align: center;
		width: 4em;
		height: 4em;
		-webkit-border-radius: 30em;
		border-radius: 30em;
		position: absolute;
		top: -4.1em;
		left: 50%;
		margin-left: -2em;
		line-height: 3.8em;
	}
	.errorpage .section-icon i {
		margin: 0;
		font-size: 26px;
	}
	.errorpage .tab-content-wrapper {
		position: relative;
	}
	.errorpage .tb-content {
		margin: 0;
		padding: 0;
		overflow: hidden;
	}
	.errorpage .tb-content .box {
		padding: 1em 1.3em 0 1.3em;
	}
	.errorpage article {
		text-align: center;
		padding: 0;
	}
	.errorpage .errorpage .form-dark {
		display: block;
		padding: 1.5em;
		margin: 0 0em 0em 0em;
		border: 1px solid rgba(0,0,0,0.05);
		border-style: solid none solid none;
	}
	.errorpage .form-dark .input-group {
		display: block;
		width: 100%;
		float: none;
		position: relative;
	}
	.errorpage .form-dark input[type=text],.errorpage  .form-dark input[type=email],.errorpage  .form-dark textarea {
		display: block;
		border: rgba(0,0,0,0.45) 1px solid!important;
		padding: 0.6em 1em;
		width: 100%;
		-webkit-border-radius: 20em!important;
		border-radius: 20em!important;
		-webkit-box-shadow: inset 0 1px 2px 0 #eaedf1, 0 1px 0 0 rgba(255,255,255,0.3);
		box-shadow: inset 0 1px 2px 0 #eaedf1, 0 1px 0 0 rgba(255,255,255,0.3);
		margin-bottom: 0;
	}
	.errorpage .form-dark input[type=submit] {
		font-family: FontAwesome;
		background: none;
		border: none;
		color: #9ea7b3;
		position: absolute;
		right: 1em;
		top: 30%;
		height: 1.3em;
		line-height: 0!important;
	}
	.errorpage .form-dark .alert-danger {
		display: block;
		margin: 1em 0 0 0;
		background-color: #f27935;
		border-color: #da5f1a;
		color: #fff;
		font-size: 1.1em;
		padding: 0.6em 2em;
	}
	.errorpage .form-dark .alert-success {
		display: block;
		margin: 1em 0 0 0;
		background-color: #42d8c4;
		border-color: #0ebaa3;
		color: #fff;
		font-size: 1.1em;
		padding: 0.6em 2em;
	} 
	.errorpage .form-row {
		position: relative;
	}
	.errorpage .form-row span.error {
		position: absolute;
		right: 1.5em;
		top: 0.25em;
		font-size: 1.3em;
		color: #f27935;
	}
	.errorpage footer {
		overflow: hidden;
	}
	.errorpage .tabs {
		list-style: none;
		display: block;
		background: #f9fafc;
		padding: 0;
		border-bottom: none;
		margin: 0 -2px 0 0;
		-webkit-border-radius: 0 0 0.4em 0.4em;
		border-radius: 0 0 0.4em 0.4em;
		-webkit-box-shadow: inset 0 0 0 1px rgba(255,255,255,1);
		box-shadow: inset 0 0 0 1px rgba(255,255,255,1);
	}
	.errorpage .tabs li {
		display: block;
		float: left;
		padding: 0;
		margin: 0;
		position: relative;
		width: 34%;
	}
	.errorpage .tabs li:first-child,.errorpage  .tabs li:last-child {
		width: 33%;
	}
	.errorpage .tabs li:first-child, .tabs li:first-child a {
		-webkit-border-radius: 0 0 0 0.4em;
		border-radius: 0 0 0 0.4em;
	}
	.errorpage .tabs li:last-child,.errorpage  .tabs li:last-child a {
		-webkit-border-radius: 0 0 0.4em 0;
		border-radius: 0 0 0.4em 0;
	}
	.errorpage .tabs li a {
		display: block;
		font-size: 1.3em;
		padding: 1em 0em!important;
		margin: 0;
		-webkit-box-shadow: inset 1px 0 0 0 #e2e5eb;
		box-shadow: inset 1px 0 0 0 #e2e5eb;
		text-align: center;
		color: #9ea7b3;
	}
	.errorpage .tabs li:first-child a {
		-webkit-box-shadow: none;
		box-shadow: none;
	}
	.errorpage .tabs li a:hover {
		color: #fff;
	}
	.errorpage .error {
		color: #FFF;
	}
	/* Transitions */
	.errorpage a, .errorpage ul.list-inline li a {
		-webkit-transition: all 0.3s ease-in-out;
		-moz-transition: all 0.3s ease-in-out;
		-ms-transition: all 0.3s ease-in-out;
		-o-transition: all 0.3s ease-in-out;
		transition: all 0.3s ease-in-out;
	}
	@media screen and (max-width : 320px) {
		.errorpage .tabs li a {
			font-size: 1em;
		}
		.errorpage .tabs li a i {
			font-size: 1em;
			display: block;
		}
		.errorpage svg {
			width: 70%!important;
		}
	}
	@media only screen and (max-width : 768px) {
		.errorpage header h3.brand {
			text-align: center;
			margin-bottom: 1em;
		}
		.errorpage header ul.list-inline {
			text-align: center;
		}
		vsvg {
			width: 55%;
		}
	}
	@media screen and (max-width : 400px) {
		.errorpage  .tabs li a i {
			display: block;
		}
	}
</style>
</head>
<div class="container errorpage mt-30">
	<div id="wrapper">
		<header class="clearfix">
			<div class="row">
				<div class="col-md-12 col-sm-12 text-center">
					<h3><b><?php echo $heading; ?></b></h3>
				</div>    
			</div>     
		</header>
		<article> 

			<!-- Tab panes -->
			<div class="tab-content-wrapper">
				<div class="tb-content active" id="home">
					<div class="box"> <span class="section-icon"><i class="icon-unlink"></i></span>

						<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 211.665 104.049" enable-background="new 0 0 211.665 104.049" xml:space="preserve">
							<g>
								<polygon points="67.893,72.853 61.042,72.853 61.042,62.046 0,62.046 62.046,0 66.89,4.844 16.539,55.196 67.893,55.196  "/>
							</g>
							<g>
								<polygon points="150.623,72.853 143.772,72.853 143.772,55.196 195.126,55.196 144.776,4.844 149.619,0 211.666,62.046 150.623,62.046  "/>
							</g>
							<g>
								<path d="M105.24,78.726c-14.677,0-26.175-17.289-26.175-39.363C79.065,17.29,90.563,0,105.24,0c14.678,0,26.176,17.291,26.176,39.363C131.416,61.437,119.917,78.726,105.24,78.726z M105.24,6.85c-10.475,0-19.324,14.889-19.324,32.513c0,17.625,8.85,32.514,19.324,32.514s19.325-14.89,19.325-32.514C124.564,21.74,115.716,6.85,105.24,6.85z"/>
							</g>
							<g opacity="0.3">
								<path d="M69.568,104.049l-3.525-3.485c21.478-21.725,56.625-21.926,78.349-0.449l-3.485,3.525C121.125,84.084,89.123,84.269,69.568,104.049z"/>
							</g>
							<g opacity="0.3">
								<path d="M58.594,46.856c-4.168,0-7.559-3.39-7.559-7.559s3.391-7.56,7.559-7.56c4.168,0,7.559,3.391,7.559,7.56S62.762,46.856,58.594,46.856z M58.594,35.848c-1.902,0-3.449,1.547-3.449,3.449c0,1.901,1.547,3.449,3.449,3.449c1.901,0,3.448-1.547,3.448-3.449C62.042,37.395,60.496,35.848,58.594,35.848z"/>
							</g>
							<g opacity="0.3">
								<path d="M150.759,46.856c-4.168,0-7.559-3.39-7.559-7.559s3.391-7.56,7.559-7.56c4.169,0,7.56,3.391,7.56,7.56S154.928,46.856,150.759,46.856z M150.759,35.848c-1.902,0-3.449,1.547-3.449,3.449c0,1.901,1.547,3.449,3.449,3.449c1.9,0,3.449-1.547,3.449-3.449C154.208,37.395,152.66,35.848,150.759,35.848z"/>
							</g>
						</svg>
						
						<h4><b><?php echo $message; ?></b></h4>
						<a href="<?php echo base_url(); ?>" class="btn btn-rose" style="margin: -3px auto 20px;">Go Home</a>
					</div>

				</div>

			</div>
		</article>

	</div>
</div>


