<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ordersummary extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Ordersummarymodel');
		$this->lang->load('keywords');
		ini_set('display_errors', 1);

	}	

	public function index()
	{
		
		$data['content'] = 'index';

		$data['Customers'] = $this->Common_Model->get('mCustomer', [], ['CustomerUID'=>'ASC'], []);

		$this->load->view($this->input->is_ajax_request() ? $data['content'] : 'page', $data);
	}



	function insert()
	{
		$this->load->library('form_validation');

		$data['content'] = 'index';
		if ($this->input->server('REQUEST_METHOD') === 'POST') {

			$this->form_validation->set_error_delimiters('', '');


			$this->form_validation->set_rules('Customer', '', 'required');
			$this->form_validation->set_rules('PropertyAddress1', '', 'required');
			$this->form_validation->set_rules('PropertyCityName', '', 'required');
			$this->form_validation->set_rules('PropertyStateCode', '', 'required');
			$this->form_validation->set_rules('PropertyCountyName', '', 'required');
			$this->form_validation->set_rules('PropertyZipcode', '', 'required');
			$this->form_validation->set_rules('ProjectUID', '', 'required');
			$this->form_validation->set_rules('PriorityUID', '', 'required');

			/*LOOP VALIDATION*/


			$this->form_validation->set_message('required', 'This Field is required');

			if ($this->form_validation->run() == true) {

				$OrderDetails = $this->input->post();

				
				$result = $this->Ordersummarymodel->insert_order($OrderDetails);


				
				$Path='uploads/OrderDocumentPath/' .$result['OrderNumber'] . '/';

				// Executes entire block when file is uploaded.
				if (isset($_FILES['DocumentFiles']) && count($_FILES['DocumentFiles'])) {
					$this->Ordersummarymodel->CreateDirectoryToPath($Path);
					$UploaedFiles = $this->UploadFileToPath($_FILES['DocumentFiles'], $Path);					
					
					$OrderUID=$result['OrderUID'];
	
					foreach ($UploaedFiles as $key => $File) {
						/*Save tDocuments*/
						$tDocuments['DocumentName'] = $File['file_name'];
						$tDocuments['DocumentURL'] = $Path . $File['file_name'];
						$tDocuments['OrderUID'] = $OrderUID;
						$tDocuments['IsStacking'] = isset($OrderDetails['Stacking'][$key]) ? 1 : 0;
						$tDocuments['UploadedDateTime'] = date('Y-m-d H:i:s');
						$tDocuments['UploadedByUserUID'] = $this->loggedid;
						$this->Ordersummarymodel->save('tDocuments', $tDocuments);
					}
				}





				$result = array("validation_error" => 0, "id" => '', 'message' => $result['message']);
				echo json_encode($result);

			} else {

				$Msg = $this->lang->line('Empty_Validation');

				$formvalid = [];

				$data = array(
					'validation_error' => 1,
					'message' => $Msg,
					'Customer' => form_error('Customer'),
					'PropertyAddress1' => form_error('PropertyAddress1'),
					'PropertyCityName' => form_error('PropertyCityName'),
					'PropertyStateCode' => form_error('PropertyStateCode'),
					'PropertyCountyName' => form_error('PropertyCountyName'),
					'PropertyZipcode' => form_error('PropertyZipcode'),
					'LoanNumber' => form_error('LoanNumber'),
					'ProjectUID' => form_error('ProjectUID'),
					'PriorityUID' => form_error('PriorityUID'),
				);
				// $datas = array_merge($datas1, $datas2, $formvalid);
				// $Merged = array_merge($datas, $data);
				foreach ($data as $key => $value) {
					if (is_null($value) || $value == '')
						unset($data[$key]);
				}
				echo json_encode($data);
			}
		}
	}


	function text_preview_bulkentry()
	{
		// echo '<pre>';print_r('test');exit;
		if ($this->input->post('bulk_order_details') != '') {
			
			$inputdata = $this->input->post('bulk_order_details');
			$returnvalue = false;
			$orders_yts = 0;
			$duplicate_order_id = 0;
			$duplicate_row = 0;
			$column_empty = 0;
			$element_empty = 0;
			
			
			$CustomerUID = $this->input->post('CustomerUID');
			$ProjectUID = $this->input->post('ProjectUID');
			
			
			
			
			if ($CustomerUID == '' ) {
				echo json_encode(array('error' => '1', 'message' => 'Select the Required Fields'));
				exit;
			}
			
			
			
			
			
			
			$arrayCode = array();
			$rows = explode("\n", $inputdata);
			$rows = array_filter($rows);
			
			
			foreach ($rows as $idx => $row) {
				$row = explode("\t", $row);
				
				//to get rid of first item (the number)
				//comment it if you don't need.
				//array_shift ( $row );
				
				foreach ($row as $field) {
					//to clean up $ sign
					$arrayCode[$idx][0] = '';
					$arrayCode[$idx][1] = '';
					
					$field = trim($field, "$ ");
					
					$arrayCode[$idx][] = $field;
				}
			}
			
			
			
			$ProjectCheck = [];
			
			foreach ($arrayCode as $i => $v) {

				$ProjectCheck[$i] = false;

				$msubproducts = array();
				
				
				if ($CustomerUID!='' && $ProjectUID!='') {
					$CustomerProject=$this->Common_Model->GetCustomerandProject_row($CustomerUID, $ProjectUID);
					$arrayCode[$i][0] = $CustomerProject->CustomerName;
					$arrayCode[$i][1] = $CustomerProject->ProjectName;
					$ProjectCheck[$i] = true;					
				}
				elseif ($CustomerUID!='' && $v[2]!='') {
					$CustomerProject = $this->Common_Model->GetCustomerandProject_rowByName($CustomerUID, $v[2]);
					$arrayCode[$i][0] = $CustomerProject->CustomerName;
					$arrayCode[$i][1] = $CustomerProject->ProjectName;
					$ProjectCheck[$i] = true;
				}
				else{
					$ProjectCheck[$i] = false;
				}


			}
			
			$headingcount = 0;
			$counts = 0;
			foreach ($arrayCode as $key => $value) {
				
				
				if (count($value) > $counts) {
					$counts = count($value);
					$headingcount = count(array_chunk($value, 6));
				}
			}
			
			$tableheadcount = $headingcount - 3;
			?>

			<?php
			
		} else {
			echo json_encode(array('error' => '1', 'message' => 'Please Fill the Required Field'));
		}
		
	}	






	/* ----- SUPPORTING FUNCTIONS STARTS ---- */
	function UploadFileToPath($files, $Path)
	{
		if (!file_exists($Path)) {
			if (!mkdir($Path, 0777, true)) die('Unable to create directory');
		}
		
		$config['upload_path'] = $Path;
		$config['allowed_types'] = 'pdf';
		$config['max_size'] = 0;
		$config['overwrite'] = true;
		
		$this->load->library('upload', $config);
		
		$DocumentFiles = [];
		$Errors = [];
		foreach ($files['name'] as $key => $image) {
			$_FILES['DocumentFiles[]']['name'] = $files['name'][$key];
			$_FILES['DocumentFiles[]']['type'] = $files['type'][$key];
			$_FILES['DocumentFiles[]']['tmp_name'] = $files['tmp_name'][$key];
			$_FILES['DocumentFiles[]']['error'] = $files['error'][$key];
			$_FILES['DocumentFiles[]']['size'] = $files['size'][$key];
			
			$fileName = $files['name'][$key];
			
            // $DocumentFiles[] = $fileName;
			
			$config['file_name'] = $fileName;
			
			$this->upload->initialize($config);
			
			if ($this->upload->do_upload('DocumentFiles[]')) {
				$DocumentFiles[] = $this->upload->data();
			} else {
				$this->upload->display_errors();
			}
		}
		return $DocumentFiles;
	}
	/* ----- SUPPORTING FUNCTIONS ENDS ---- */
	
	
}?>
