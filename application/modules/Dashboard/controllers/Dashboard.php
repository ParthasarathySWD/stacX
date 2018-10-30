<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller 
{
	function __construct(){
		parent::__construct();	
		
	}

	function index()
	{
		$data['content'] = 'dashboard';			
		$users=$this->Common_Model->get('musers', NULL, ['UserName'=>'DESC'], ['RoleUID']);
		$users=$this->Common_Model->get_row('musers', ['UserUID'=>1], ['UserName'=>'DESC'], ['RoleUID']);
		$this->load->view($this->input->is_ajax_request() ? $data['content'] : 'page', $data);
	}



}?>