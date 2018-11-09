<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MyOrders extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('MyOrders_Model');
	}

	public function index()
	{
		$data['content']='index';
		$data['is_selfassign'] = 1;
		$data['IsGetNextOrder'] = $this->MyOrders_Model->CheckAutoAssignEnabled($this->loggedid);
		$data['Projects'] = $this->MyOrders_Model->GetProjectCustomers($this->loggedid);
		$this->load->view($this->input->is_ajax_request() ? $data['content'] : 'page', $data);
	}

	function myorders_ajax_list()
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
        $list = $this->MyOrders_Model->MyOrders($post);

        $no = $post['start'];
        $myorderslist = [];
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
		        $Action = '<a href="'.base_url('Ordersummary/index/'.$myorders->OrderUID).'" class="btn btn-link btn-info btn-just-icon btn-xs ajaxload">
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
			"recordsTotal" => $this->MyOrders_Model->count_all(),
			"recordsFiltered" =>  $this->MyOrders_Model->count_filtered($post),
			"data" => $data['myorderslist'],
		);

		unset($post);
		unset($data);

		echo json_encode($output);
	}


	function GetNextOrder()
	{
		$ProjectUID = $this->input->post('ProjectUID');
		$Workflow = $this->input->post('Workflow');
		$this->load->library('form_validation');
		if ($this->input->server('REQUEST_METHOD') === 'POST') 
		{

			$this->form_validation->set_error_delimiters('', '');
			$this->form_validation->set_rules('ProjectUID', '', 'required');
			$this->form_validation->set_rules('Workflow', '', 'required');
			$this->form_validation->set_message('required', 'This Field is required');
			if ($this->form_validation->run() == true)
			{

					$existingarray = [];
					$ExistingOrders = $this->MyOrders_Model->CheckExistingOrders($this->loggedid,$ProjectUID,$Workflow);

					foreach ($ExistingOrders as $key => $value) {
						 if($value->StatusUID != 100)
						 {
						 	$existingarray[] = 'Not Completed';
						 }
					}
					if(sizeof($existingarray) > 0)
					{
						$result = array('validation_error' => 2,'message'=>'Existing Orders Not Completed','color'=>'danger');
						echo json_encode($result);

					}
					else
					{
						
						$data = $this->MyOrders_Model->AssignOrders($this->loggedid,$ProjectUID,$Workflow);

						if($data == 0)
						{	
							$result = array('validation_error' => 2,'message'=>'No Orders found','color'=>'danger');
							echo json_encode($result);

						}else{
							$result = array('validation_error' => 2,'message'=>'Order Assigned','color'=>'success');
							echo json_encode($result);
						}

					}
			}else{

				$Msg = $this->lang->line('Empty_Validation');
				$formvalid = [];

				$data = array(
					'validation_error' => 1,
					'message' => $Msg,
					'ProjectUID' => form_error('ProjectUID'),
					'Workflow' => form_error('Workflow'),
					'color' => 'danger',
					
				);
				foreach ($data as $key => $value) {
					if (is_null($value) || $value == '')
						unset($data[$key]);
				}
				echo json_encode($data);
			}
	    }

		
	}


}?>
