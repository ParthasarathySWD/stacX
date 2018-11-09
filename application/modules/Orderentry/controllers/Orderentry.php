<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class OrderEntry extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Orderentrymodel');

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

				
				$result = $this->Orderentrymodel->insert_order($OrderDetails);


				
				$Path='uploads/OrderDocumentPath/' .$result['OrderNumber'] . '/';

				// Executes entire block when file is uploaded.
				if (isset($_FILES['DocumentFiles']) && count($_FILES['DocumentFiles'])) {
					$this->Orderentrymodel->CreateDirectoryToPath($Path);
					$UploaedFiles = $this->UploadFileToPath($_FILES['DocumentFiles'], $Path, 'DocumentFiles[]');
					
					$OrderUID=$result['OrderUID'];
	
					foreach ($UploaedFiles as $key => $File) {
						/*Save tDocuments*/
						$tDocuments['DocumentName'] = $File['file_name'];
						$tDocuments['DocumentURL'] = $Path . $File['file_name'];
						$tDocuments['OrderUID'] = $OrderUID;
						$tDocuments['IsStacking'] = isset($OrderDetails['Stacking'][$key]) ? 1 : 0;
						$tDocuments['UploadedDateTime'] = date('Y-m-d H:i:s');
						$tDocuments['UploadedByUserUID'] = $this->loggedid;
						$this->Orderentrymodel->save('tDocuments', $tDocuments);
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



	function text_save_bulkentry()
	{
		if ($this->input->post('bulk_order_details') != '') {

			$inputdata = $this->input->post('bulk_order_details');
			$returnvalue = false;
			$orders_yts = 0;
			$duplicate_order_id = 0;
			$duplicate_row = 0;
			$column_empty = 0;
			$element_empty = 0;
			$error = 0;

			$CustomerUID = $this->input->post('CustomerUID');
			$ProjectUID = $this->input->post('ProjectUID');
			$CustomerName = '';			
			$ProjectName = '';			



			if ($CustomerUID == '') {
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

				if ($CustomerUID != '' && $ProjectUID != '') {
					$CustomerProject = $this->Common_Model->GetCustomerandProject_row($CustomerUID, $ProjectUID);
					$arrayCode[$i][0] = $CustomerProject->CustomerName;
					$arrayCode[$i][1] = $CustomerProject->ProjectName;
					$arrayCode[$i][2] = $CustomerProject->Priority;
					$ProjectCheck[$i] = true;
				} elseif ($CustomerUID != '' && $v[2] != '') {
					$CustomerProject = $this->Common_Model->GetCustomerandProject_rowByName($CustomerUID, $v[2]);
					$arrayCode[$i][0] = $CustomerProject->CustomerName;
					$arrayCode[$i][1] = $CustomerProject->ProjectName;
					$arrayCode[$i][2] = $CustomerProject->Priority;
					$ProjectUID = $CustomerProject->ProjectUID;
					$ProjectCheck[$i] = true;
				} else {
					$ProjectCheck[$i] = false;
				}


			}

			$headingcount = 0;
			$counts = 0;
			$additionalparameters = 0;
			foreach ($arrayCode as $key => $value) {


				if (count($value) > $counts && $headingcount > 0) {
					$headingcount = count(array_chunk($value, $headingcount));
				}
			}

			$tableheadcount = $headingcount - 3;
			$data['arrayCode'] = $arrayCode;
			$data['tableheadcount'] = $tableheadcount;
			$data['ProjectCheck'] = $ProjectCheck;
			
			$html = '';
			$FailedData = [];
			$SuccessData = [];
			$InsertedOrderUID = [];
			$InsertedOrderUIDs = '';




			foreach ($arrayCode as $i => $a) {

				$count = count($a);
				$field_count = 10;

					//for missing fields
				if (count($arrayCode[$i]) >= $field_count) {

					if ((count($arrayCode[$i])) % $field_count != 0) {

						$error = $error + 1;
						array_push($FailedData, $arrayCode[$i]);

					} else {

						if ((count($a) >= $field_count)) {


							$CityName = $a[6];

							$StateCode = $a[8];
							$Zipcode = $a[9];

							$CountyName = $a[7];
							



							if ($ProjectCheck[$i] == false) {

								$error = $error + 1;
								array_push($FailedData, $arrayCode[$i]);

							} elseif ($StateCode == '') {

								$error = $error + 1;
								array_push($FailedData, $arrayCode[$i]);


							} elseif ($CountyName == '') {

								$error = $error + 1;
								array_push($FailedData, $arrayCode[$i]);

							} elseif ($CityName == '') {

								$error = $error + 1;
								array_push($FailedData, $arrayCode[$i]);


							} elseif ($Zipcode == '') {

								$error = $error + 1;
								array_push($FailedData, $arrayCode[$i]);


							} else {

								$data['Customer'] = $CustomerUID;
								$data['ProjectUID'] = $ProjectUID;
								$data['OrderNumber'] = '';
								$data['LoanNumber'] = $a[2];
								$data['AltOrderNumber'] = $a[3];
								$data['PropertyAddress1'] = $a[4];
								$data['PropertyCityName'] = $a[5];
								$data['PropertyCountyName'] = $a[6];
								$data['PropertyStateCode'] = $a[7];
								$data['PropertyZipcode'] = $a[8];



								$result = $this->Orderentrymodel->savebulkentry_order($data);
								$a['result']=$result;
								if (!empty($result) && isset($result['OrderUID'])) {
									$InsertedOrderUID[] = $result['OrderUID'];

									$SuccessData[] = $a;

									$Path = 'uploads/OrderDocumentPath/' . $result['OrderNumber'] . '/';
									$this->Orderentrymodel->CreateDirectoryToPath($Path);

									// Executes entire block when file is uploaded.
									foreach ($_FILES['MIME_FILES']['name'] as $key => $value) {
										$dotposition = strripos($value, '.');
										$documentname = substr($value, 0, $dotposition);

										if ($documentname == $a[2]) {
											$this->NormalFileUpload($_FILES['MIME_FILES']['tmp_name'][$key], $Path, $result['OrderUID']);

											$tDocuments['DocumentName'] = $value;
											$tDocuments['DocumentURL'] = $Path . $value;
											$tDocuments['OrderUID'] = $result['OrderUID'];
											$tDocuments['IsStacking'] = 1;
											$tDocuments['UploadedDateTime'] = date('Y-m-d H:i:s');
											$tDocuments['UploadedByUserUID'] = $this->loggedid;
											$this->Orderentrymodel->save('tDocuments', $tDocuments);

										}
									}

								}else {
									$error = $error + 1;
									array_push($FailedData, $arrayCode[$i]);

								}



							}
						} else {

							$error = $error + 1;

						}
					}
				}
			}

			$previewdata['SuccessData'] = $SuccessData;
			$previewdata['FailedData'] = $FailedData;
			$previewdata['InsertedOrderUID'] = implode(',', $InsertedOrderUID);
			$html = $this->load->view('bulk_imported', $previewdata, true);


			echo json_encode(array('error'=>0,'html'=>$html)); exit;



		} else {
			echo json_encode(array('error' => '1', 'message' => 'Please Fill the Required Field'));
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
			$filenames = $this->input->post('FILE_NAMES');

			
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
					
					$field = trim($field, "$ ");
					
					$arrayCode[$idx][] = $field;
				}
			}
			
			
			
			$ProjectCheck = [];
			$FileUploadPreview = [];
			
			
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

				$is_available = false;
					if (!empty($filenames)) {
						
						foreach ($filenames as $key => $filename) {
							
							$LoanNumber = $v[2];
							$arraycount = count($v);
							
							$dotposition = strripos($filename, '.');
							$documentname = substr($filename, 0, $dotposition);
							
							if ($LoanNumber == $documentname) {
							$is_available = true;
							$obj = new stdClass();
							$obj->LoanNumber = $LoanNumber;
							$obj->DocumentName = $filename;
							$FileUploadPreview[] = $obj;
						}
					}
					
				}
				if ($is_available) {
					$arrayCode[$i][] = 'AVAILABLE';
				} else {
					$arrayCode[$i][] = 'NOT AVAILABLE';
				}


			}
			
			$headingcount = 0;
			$counts = 0;
			$additionalparameters = 0;
			foreach ($arrayCode as $key => $value) {
				
				
				if (count($value) > $counts && $headingcount>0) {
					$headingcount = count(array_chunk($value, $headingcount));
				}
			}
			
			$headingsArray = ['Customer/Client','Project','Loan Number','Alt Order No','Property Address','Property City','Property County','Property State','Property Zip Code','FileAvailable'];

			$tableheadcount = $headingcount - 3;
			for ($i = 1; $i <= $tableheadcount; $i++) {
				$headingsArray[]= 'Email';
				$headingsArray[]='Home No';
				$headingsArray[]='Work No';
				$headingsArray[]='Cell No';
			}
			$data['arrayCode'] = $arrayCode;
			$data['tableheadcount'] = $tableheadcount;
			$data['ProjectCheck'] = $ProjectCheck;
			$data['headingsArray']=$headingsArray;

			$preview = $this->load->view('bulk_preview', $data, true);
			$filepreview = $this->load->view('bulk_uploade_filepreview', ['FileUploadPreview' => $FileUploadPreview], true);

			echo json_encode(array('error' => 0, 'html' => $preview, 'filehtml' => $filepreview));
			exit;
			
		} else {
			echo json_encode(array('error' => '1', 'message' => 'Please Fill the Required Field'));
		}
		
	}

	/* ---- EXCEL BULK ENTRY STARTS --- */

function preview_bulkentry()
{
		// echo '<pre>';print_r('test');exit;
	if (isset($_FILES['file'])) {
		$lib = $this->load->library('Excel');

		$inputFile = $_FILES['file']['tmp_name'];
		$filenames= $this->input->post('FILE_NAMES');

		$extension = strtoupper(pathinfo($inputFile, PATHINFO_EXTENSION));
		$temp = explode(".", $_FILES["file"]["name"]);

		$allowedExts = array("xlsx", "xls");

		$extension = end($temp);

		if (in_array($extension, $allowedExts)) {

			try {

				$inputFileType = PHPExcel_IOFactory::identify($inputFile);
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$worksheets = $objReader->listWorkSheetNames($inputFile);
				$objReader->setLoadSheetsOnly($worksheets[0]);
				$objReader->setReadDataOnly(true);
				$objPHPExcel = $objReader->load($inputFile);

			} catch (Exception $e) {

				$msg = 'Error Uploading file';
				echo json_encode(array('error' => '1', 'message' => $msg));
				exit;
			}

			$CustomerUID = $this->input->post('CustomerUID');
			$ProjectUID = $this->input->post('ProjectUID');
			
			$FileUploadPreview = [];


			if ($CustomerUID == '' || $ProjectUID == '') {
				echo json_encode(array('error' => '1', 'message' => 'Select the Required Fields'));
				exit;
			}




			$objWorksheet = $objPHPExcel->getActiveSheet();
        		//excel with first row header, use header as key
			$highestRow = $objWorksheet->getHighestDataRow();
			$highestColumn = $objWorksheet->getHighestDataColumn();


			$headingsArray = $objWorksheet->rangeToArray('A1:' . $highestColumn . '1', null, true, true, true);
			$headingsArray = $headingsArray[1];





			$arrayCode = array();
			$r = -1;
			$headingArray = array();
			for ($row = 2; $row <= $highestRow; ++$row) {
				$dataRow = $objWorksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, true, true);
				if ($this->isEmptyRow(reset($dataRow))) {
					continue;
				} // skip empty row
				++$r;

					// $arrayCode[$r][4] = $mcustomerproducts[$i]->SubProductName;
				$i = 1;
				$arrayCode[$r][0] = '';

				foreach ($headingsArray as $columnKey => $columnHeading) {

					$arrayCode[$r][$i] = $dataRow[$row][$columnKey];
					$i++;
				}

			}

			array_unshift($headingsArray, "Customer");

			$returnvalue = false;

				//$arrayCode = array_map('array_filter', $arrayCode);

			$ProjectCheck = [];

			foreach ($arrayCode as $i => $v) {

				$ProjectCheck[$i] = false;

				$msubproducts = array();


				if ($CustomerUID != '' && $ProjectUID != '') {
					$CustomerProject = $this->Common_Model->GetCustomerandProject_row($CustomerUID, $ProjectUID);
					$arrayCode[$i][0] = $CustomerProject->CustomerName;
					$arrayCode[$i][1] = $CustomerProject->ProjectName;
					$ProjectCheck[$i] = true;
				} elseif ($CustomerUID != '' && $v[2] != '') {
					$CustomerProject = $this->Common_Model->GetCustomerandProject_rowByName($CustomerUID, $v[2]);
					$arrayCode[$i][0] = $CustomerProject->CustomerName;
					$arrayCode[$i][1] = $CustomerProject->ProjectName;
					$ProjectCheck[$i] = true;
				} else {
					$ProjectCheck[$i] = false;
				}

				$is_available = false;
				foreach ($filenames as $key => $filename) {
					
					$LoanNumber = $v[2];
					$arraycount = count($v);
					
					$dotposition = strripos($filename, '.');
					$documentname = substr($filename, 0, $dotposition);
					
					if ($LoanNumber == $documentname) {
						$is_available = true;
						$obj = new stdClass();
						$obj->LoanNumber = $LoanNumber;
						$obj->DocumentName = $filename;
						$FileUploadPreview[] = $obj;
					}
				} 

				if ($is_available) {
					$arrayCode[$i][] = 'AVAILABLE';
				}
				else{
					$arrayCode[$i][] = 'NOT AVAILABLE';
				}
			}

			$headingcount = 0;
			$counts = 0;
			$additionalparameters = 0;
			foreach ($arrayCode as $key => $value) {


				if (count($value) > $counts && $headingcount > 0) {
					$headingcount = count(array_chunk($value, $headingcount));
				}
			}

			$tableheadcount = $headingcount - 3;
			$data['arrayCode'] = $arrayCode;
			$data['tableheadcount'] = $tableheadcount;
			$data['ProjectCheck'] = $ProjectCheck;
			$data['headingsArray'] = $headingsArray;

			$preview = $this->load->view('bulk_preview', $data, true);
			$filepreview = $this->load->view('bulk_uploade_filepreview', ['FileUploadPreview' => $FileUploadPreview], true);

			echo json_encode(array('error' => 0, 'html' => $preview, 'filehtml' => $filepreview)); exit;

		} else {
			echo json_encode(array('error' => '1', 'message' => 'Please Upload Valid File'));
		}

	} else {
		echo json_encode(array('error' => '1', 'message' => 'Please upload File'));
	}

}

function save_bulkentry()
{
		// echo '<pre>';print_r('test');exit;
	if (isset($_FILES['file'])) {
		$lib = $this->load->library('Excel');

		$inputFile = $_FILES['file']['tmp_name'];

		$extension = strtoupper(pathinfo($inputFile, PATHINFO_EXTENSION));
		$temp = explode(".", $_FILES["file"]["name"]);

		$allowedExts = array("xlsx", "xls");

		$extension = end($temp);

		if (in_array($extension, $allowedExts)) {

			try {

				$inputFileType = PHPExcel_IOFactory::identify($inputFile);
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$worksheets = $objReader->listWorkSheetNames($inputFile);
				$objReader->setLoadSheetsOnly($worksheets[0]);
				$objReader->setReadDataOnly(true);
				$objPHPExcel = $objReader->load($inputFile);

			} catch (Exception $e) {

				$msg = 'Error Uploading file';
				echo json_encode(array('error' => '1', 'message' => $msg));
				exit;
			}

			$CustomerUID = $this->input->post('CustomerUID');
			$ProjectUID = $this->input->post('ProjectUID');
			



			if ($CustomerUID == '' || $ProjectUID == '') {
				echo json_encode(array('error' => '1', 'message' => 'Select the Required Fields'));
				exit;
			}




			$objWorksheet = $objPHPExcel->getActiveSheet();
        		//excel with first row header, use header as key
			$highestRow = $objWorksheet->getHighestDataRow();
			$highestColumn = $objWorksheet->getHighestDataColumn();


			$headingsArray = $objWorksheet->rangeToArray('A1:' . $highestColumn . '1', null, true, true, true);
			$headingsArray = $headingsArray[1];





			$arrayCode = array();
			$r = -1;
			$headingArray = array();
			for ($row = 2; $row <= $highestRow; ++$row) {
				$dataRow = $objWorksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, true, true);
				if ($this->isEmptyRow(reset($dataRow))) {
					continue;
				} // skip empty row
				++$r;

					// $arrayCode[$r][4] = $mcustomerproducts[$i]->SubProductName;
				$i = 1;
				$arrayCode[$r][0] = '';

				foreach ($headingsArray as $columnKey => $columnHeading) {

					$arrayCode[$r][$i] = $dataRow[$row][$columnKey];
					$i++;
				}

			}

			array_unshift($headingsArray, "Customer");

			$returnvalue = false;

				//$arrayCode = array_map('array_filter', $arrayCode);

			$ProjectCheck = [];

			foreach ($arrayCode as $i => $v) {

				$ProjectCheck[$i] = false;

				$msubproducts = array();


				if ($CustomerUID != '' && $ProjectUID != '') {
					$CustomerProject = $this->Common_Model->GetCustomerandProject_row($CustomerUID, $ProjectUID);
					$arrayCode[$i][0] = $CustomerProject->CustomerName;
					$arrayCode[$i][1] = $CustomerProject->ProjectName;
					$ProjectCheck[$i] = true;
				} elseif ($CustomerUID != '' && $v[2] != '') {
					$CustomerProject = $this->Common_Model->GetCustomerandProject_rowByName($CustomerUID, $v[2]);
					$arrayCode[$i][0] = $CustomerProject->CustomerName;
					$arrayCode[$i][1] = $CustomerProject->ProjectName;
					$ProjectCheck[$i] = true;
				} else {
					$ProjectCheck[$i] = false;
				}


			}


			$headingcount = 0;
			$counts = 0;
			$additionalparameters = 0;
			foreach ($arrayCode as $key => $value) {


				if (count($value) > $counts && $headingcount > 0) {
					$headingcount = count(array_chunk($value, $headingcount));
				}
			}

			$tableheadcount = $headingcount - 3;

			$html = '';
			$FailedData = [];
			$SuccessData = [];
			$InsertedOrderUID = [];
			$InsertedOrderUIDs = '';




			foreach ($arrayCode as $i => $a) {

				$count = count($a);
				$field_count = 9;

					//for missing fields
				if (count($arrayCode[$i]) >= $field_count) {
					$mere= count($arrayCode[$i]) + 1;
					if ((count($arrayCode[$i]) + 1) % 10 != 0) {

						$error = $error + 1;

					} else {

						if ((count($a) >= $field_count)) {


							$CityName = $a[5];

							$StateCode = $a[7];
							$Zipcode = $a[8];

							$CountyName = $a[6];




							if ($ProjectCheck[$i] == false) {

								$error = $error + 1;
								array_push($FailedData, $arrayCode[$i]);

							} elseif ($StateCode == '') {

								$error = $error + 1;
								array_push($FailedData, $arrayCode[$i]);


							} elseif ($CountyName == '') {

								$error = $error + 1;
								array_push($FailedData, $arrayCode[$i]);

							} elseif ($CityName == '') {

								$error = $error + 1;
								array_push($FailedData, $arrayCode[$i]);


							} elseif ($Zipcode == '') {

								$error = $error + 1;
								array_push($FailedData, $arrayCode[$i]);


							} else {

								$data['Customer'] = $CustomerUID;
								$data['ProjectUID'] = $ProjectUID;
								$data['OrderNumber'] = '';
								$data['LoanNumber'] = $a[2];
								$data['AltOrderNumber'] = $a[3];
								$data['PropertyAddress1'] = $a[4];
								$data['PropertyCityName'] = $a[5];
								$data['PropertyCountyName'] = $a[6];
								$data['PropertyStateCode'] = $a[7];
								$data['PropertyZipcode'] = $a[8];



								$result = $this->Orderentrymodel->savebulkentry_order($data);
								$a['result'] = $result;
								
								if (is_array($result) && !empty($result['OrderUID'])) {
									
									$InsertedOrderUID[] = $result['OrderUID'];
									$SuccessData[] = $a;

									$Path = 'uploads/OrderDocumentPath/' . $result['OrderNumber'] . '/';
									$this->Orderentrymodel->CreateDirectoryToPath($Path);

									// Executes entire block when file is uploaded.
									foreach ($_FILES['MIME_FILES']['name'] as $key => $value) {
										$dotposition = strripos($value, '.');
										$documentname = substr($value, 0, $dotposition);

										if ($documentname == $a[2]) {
											$this->NormalFileUpload($_FILES['MIME_FILES']['tmp_name'][$key], $Path, $result['OrderUID']);

											$tDocuments['DocumentName'] = $value;
											$tDocuments['DocumentURL'] = $Path . $value;
											$tDocuments['OrderUID'] = $result['OrderUID'];
											$tDocuments['IsStacking'] = 1;
											$tDocuments['UploadedDateTime'] = date('Y-m-d H:i:s');
											$tDocuments['UploadedByUserUID'] = $this->loggedid;
											$this->Orderentrymodel->save('tDocuments', $tDocuments);

										}
									}
								} else {
									$error = $error + 1;
									array_push($FailedData, $arrayCode[$i]);

								}



							}
						} else {

							$error = $error + 1;

						}
					}
				}
			}

			$previewdata['SuccessData'] = $SuccessData;
			$previewdata['FailedData'] = $FailedData;
			$previewdata['InsertedOrderUID'] = implode(',', $InsertedOrderUID);
			$html = $this->load->view('bulk_imported', $previewdata, true);



			echo json_encode(array('error' => 0, 'html' => $html)); exit;

		} else {
			echo json_encode(array('error' => '1', 'message' => 'Please Upload Valid File'));
		}

	} else {
		echo json_encode(array('error' => '1', 'message' => 'Please upload File'));
	}

}




public function bulkentrypreviewfile()
{
	$filepath = FCPATH.'assets/previewfile/bulkformat.xlsx';
	if (ob_get_contents()) ob_end_clean();
	header("Content-Description: File Transfer");
	header("Content-Type: application/octet-stream");
	header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
	header("Content-Transfer-Encoding: binary");
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header("Content-Type: application/force-download");
	header("Content-Type: application/download");
	header("Content-Length: ".filesize($filepath));
	readfile($filepath);
	ob_clean();
	exit;
}

	/* ---- EXCEL BULK ENTRY ENDS --- */




	/* ----- SUPPORTING FUNCTIONS STARTS ---- */
	function UploadFileToPath($files, $Path, $name)
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
			$_FILES[$name]['name'] = $files['name'][$key];
			$_FILES[$name]['type'] = $files['type'][$key];
			$_FILES[$name]['tmp_name'] = $files['tmp_name'][$key];
			$_FILES[$name]['error'] = $files['error'][$key];
			$_FILES[$name]['size'] = $files['size'][$key];
			
			$fileName = $files['name'][$key];
			
            // $DocumentFiles[] = $fileName;
			
			$config['file_name'] = $fileName;
			
			$this->upload->initialize($config);
			
			if ($this->upload->do_upload($name)) {
				$DocumentFiles[] = $this->upload->data();
			} else {
				$this->upload->display_errors();
			}
		}
		return $DocumentFiles;
	}

	public function NormalFileUpload($File, $PATH, $OrderUID)
	{
		if (is_uploaded_file($File)) {
			if (move_uploaded_file($File, $PATH)) {
				return true;
			}
			
		}
		return false;
	}

	function isEmptyRow($row)
	{
		foreach ($row as $cell) {
			if (null !== $cell) return false;
		}
		return true;
	}


	/* ----- SUPPORTING FUNCTIONS ENDS ---- */
	
	
}?>
