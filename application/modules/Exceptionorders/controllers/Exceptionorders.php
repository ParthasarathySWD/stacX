<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Exceptionorders extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Exceptionordersmodel');

	}

	public function index()
	{
		$data['content'] = 'index';
		$this->load->view($this->input->is_ajax_request() ? $data['content'] : 'page', $data);
	}

		function exceptionorders_ajax_list()
	{
		//get_post_input_data
    	$post['length'] = $this->input->post('length');
        $post['start'] = $this->input->post('start');
        $search = $this->input->post('search');
        $post['search_value'] = $search['value'];
        $post['order'] = $this->input->post('order');
        $post['draw'] = $this->input->post('draw');
    	//get_post_input_data
    	//column order
        $post['column_order'] = array('torders.OrderNumber','');
        $post['column_search'] = array('torders.OrderNumber');
        //column order
        $list = $this->Exceptionordersmodel->ExceptionOrders($post);

        $no = $post['start'];

		foreach ($list as $myorders)
        {
		        $row = array();
		        $row[] = $myorders->OrderNumber;
		        $row[] = $myorders->CustomerName;

		        $row[] = '<a href="#" style=" background: '.$myorders->StatusColor.' !important;" class="btn  btn-round mt-10">'.$myorders->StatusName.'</a>';
		        $row[] = $myorders->PropertyAddress1.' '.$myorders->PropertyAddress2;
		        $row[] = $myorders->PropertyCityName;
		        $row[] = $myorders->PropertyCountyName;
		        $row[] = $myorders->PropertyStateCode;
		        $row[] = $myorders->PropertyZipCode;
		        $row[] = $myorders->ProjectName;
		        $Action = '<a href="'.base_url().'" class="btn btn-link btn-info btn-just-icon btn-xs ajaxload">
							<i class="icon-pencil"></i></a>';
		        $row[] = $Action;
		        $myorderslist[] = $row;
        }



        $data =  array(
        	'myorderslist' => $myorderslist,
        	'post' => $post
        );



		$post = $data['post'];
		$output = array(
			"draw" => $post['draw'],
			"recordsTotal" => $this->Exceptionordersmodel->count_all(),
			"recordsFiltered" =>  $this->Exceptionordersmodel->count_filtered($post),
			"data" => $data['myorderslist'],
		);

		unset($post);
		unset($data);

		echo json_encode($output);
	}


}?>
