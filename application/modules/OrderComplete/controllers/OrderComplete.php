<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class OrderComplete extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('OrderComplete_Model');
		$this->lang->load('keywords');
		ini_set('display_errors', 1);

	}	

	public function StackingComplete()
	{
		$OrderUID = $this->input->post('OrderUID');

		$data['StatusUID'] = $this->config->item('keywords')['Stacking Completed'];

		$update = $this->Common_Model->save('tOrders', $data, 'OrderUID', $OrderUID);
		$response = [];
		$response['validation_error'] = 1;
		if ($update) {
			$response['message'] = $this->lang->line('Stacking_Complete');
			$response['validation_error'] = 0;
		}
		else{
			$response['message'] = $this->lang->line('Complete_Failed');
			$response['validation_error'] = 1;
		}

		

		$this->output->set_content_type('applicaton/json')
		->set_output(json_encode($response));
		
	}

	public function ReviewComplete()
	{
		$OrderUID = $this->input->post('OrderUID');

		$data['StatusUID'] = $this->config->item('keywords')['Review Completed'];

		$update = $this->Common_Model->save('tOrders', $data, 'OrderUID', $OrderUID);
		$response = [];
		$response['validation_error'] = 1;
		if ($update) {
			$response['message'] = $this->lang->line('Review_Complete');
			$response['validation_error'] = 0;
		}
		else{
			$response['message'] = $this->lang->line('Complete_Failed');
			$response['validation_error'] = 1;
		}

		

		$this->output->set_content_type('applicaton/json')
		->set_output(json_encode($response));
		
	}

	public function RaiseException()
	{
		$OrderUID = $this->input->post('OrderUID');
		$exceptiontype = $this->input->post('exceptiontype');
		$remarks = $this->input->post('remarks');

		$this->load->library('form_validation');


		$this->form_validation->set_error_delimiters('', '');


		$this->form_validation->set_rules('OrderUID', '', 'required');
		$this->form_validation->set_rules('exceptiontype', '', 'required');

		$this->form_validation->set_message('required', 'This Field is required');

		if ($this->form_validation->run() == true) {

			$data['OrderUID'] = $OrderUID;
			$data['ExceptionRemarks'] = $remarks;
			$data['ExceptionTypeUID'] = $exceptiontype;
			$data['ExceptionRaisedByUserUID'] = $this->loggedid;
			$data['ExceptionRaisedDateTime'] = date('Y-m-d H:i:s');

			$this->db->trans_begin();
			$insert = $this->Common_Model->save('tOrderException', $data);

			if ($exceptiontype == 1) {
				$StatusUID = $this->config->item('keywords')['Fatal Exception'];
				$this->Common_Model->save('torders', ['StatusUID'=>$StatusUID], 'OrderUID', $OrderUID);
			}
			elseif ($exceptiontype == 2) {
				$StatusUID = $this->config->item('keywords')['Non Fatal Exception'];
				$this->Common_Model->save('torders', ['StatusUID'=>$StatusUID], 'OrderUID', $OrderUID);
			}

			if ($this->db->trans_status()===false) {
				$this->db->trans_rollback();
				$Msg = $this->lang->line('Exception_Raise_Failed');
				$this->output->set_content_type('application/json')
					->set_output(json_encode(array('validation_error' => 0, 'message' => $Msg)))->_display();
				exit;
			}
			else{
				$this->db->trans_commit();
				$Msg = $this->lang->line('Exception_Raised');
				$this->output->set_content_type('application/json')
					->set_output(json_encode(array('validation_error'=>0, 'message'=>$Msg)))->_display();exit;
			}

		} else {

			$Msg = $this->lang->line('Empty_Validation');

			$formvalid = [];

			$validation_data = array(
				'validation_error' => 1,
				'message' => $Msg,
				'OrderUID' => form_error('OrderUID'),
				'exceptiontype' => form_error('exceptiontype'),
			);
			foreach ($validation_data as $key => $value) {
				if (is_null($value) || $value == '')
					unset($validation_data[$key]);
			}
			$this->output->set_content_type('application/json')
			->set_output(json_encode($validation_data))->_display(); exit;

		}

	}
	
}?>
