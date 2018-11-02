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

					if ((count($arrayCode[$i]) + 1) % 11 != 0) {

						$error = $error + 1;

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
								$data['LoanNumber'] = $a[3];
								$data['AltOrderNumber'] = $a[4];
								$data['PropertyAddress1'] = $a[5];
								$data['PropertyCityName'] = $a[6];
								$data['PropertyCountyName'] = $a[7];
								$data['PropertyStateCode'] = $a[8];
								$data['PropertyZipcode'] = $a[9];



								$result = $this->Orderentrymodel->savebulkentry_order($data);
								$a['result']=$result;
								if (!empty($result) && isset($result['OrderUID'])) {
									$InsertedOrderUID[] = $result['OrderUID'];
								}

								if (is_array($result) && !empty($result['OrderUID'])){

									$SuccessData[]=$a;
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
					$arrayCode[$i][2] = $CustomerProject->Priority;
					$ProjectCheck[$i] = true;					
				}
				elseif ($CustomerUID!='' && $v[2]!='') {
					$CustomerProject = $this->Common_Model->GetCustomerandProject_rowByName($CustomerUID, $v[2]);
					$arrayCode[$i][0] = $CustomerProject->CustomerName;
					$arrayCode[$i][1] = $CustomerProject->ProjectName;
					$arrayCode[$i][2] = $CustomerProject->Priority;
					$ProjectCheck[$i] = true;
				}
				else{
					$ProjectCheck[$i] = false;
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
			
			$tableheadcount = $headingcount - 3;
			$data['arrayCode'] = $arrayCode;
			$data['tableheadcount'] = $tableheadcount;
			$data['ProjectCheck'] = $ProjectCheck;
			$preview = $this->load->view('bulk_preview', $data, true);

			echo json_encode(array('error'=>0, 'html'=>$preview));
			?>

			<?php
			
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
				$i = 4;
				$arrayCode[$r][0] = '';
				$arrayCode[$r][1] = '';
				$arrayCode[$r][2] = '';
				$arrayCode[$r][3] = '';

				foreach ($headingsArray as $columnKey => $columnHeading) {

					$arrayCode[$r][$i] = $dataRow[$row][$columnKey];
					$i++;
				}

			}

			array_unshift($headingsArray, "Customer");

			$returnvalue = false;

				//$arrayCode = array_map('array_filter', $arrayCode);

			?>
				<h6 class="panel-heading"><strong>Import Data Preview</strong></h6>
				<div class="col-sm-12">
					<p class="xs-mt-10 xs-mb-10 ">
						<!-- <span class="borderradius label pull-right" style="background-color: #090809;">Template</span> -->
						<span class="borderradius label pull-right" style="background-color: #AA00FF;">Borrower</span>
						<!-- <span class="borderradius label pull-right" style="background-color: #0277BD;">Priority</span> -->
						<!-- <span class="borderradius label pull-right" style="background-color: #EF55A1;">Product</span>
							<span class="borderradius label pull-right" style="background-color: #3CC7CC;">Customer</span> -->
							<span class="borderradius label pull-right" style="background-color: #BDA601;">Zipcode</span>
							<span class="borderradius label pull-right" style="background-color: #3502BD;">County</span>
							<span class="borderradius label pull-right" style="background-color: #074D1E;">City</span>
							<span class="borderradius label pull-right" style="background-color: #02BD5A;">State</span>
							<span class="borderradius label pull-right" style="background-color: #BF6105;">Sub Product</span>
							<span class="borderradius label pull-right" style="background-color: #757575;">Empty Field</span>
							<?php if ($IsFlood == '1') { ?>
							<span class="borderradius label pull-right" style="background-color: #ff5c33;">Loan Number</span>
							<?php 
					} ?>
						</p>
					</div>

					<div class="col-sm-12">
						<div class="table-responsive panel panel-default panel-table table-responsive defaultfontsize">
							<table class="table table-striped table-hover"  id="table-bulkorder">
								<thead>
									<tr>

										<?php 

									foreach ($headingsArray as $key => $value) {
										?><th><?php echo $value; ?></th><?php

																																									}

																																									?>
									</tr>
								</thead>
								<tbody>

									<?php


								$SubProduct_check = [];

								foreach ($arrayCode as $i => $v) {

										//$SubProductUID = $this->input->post('SubProductUID');

									$SubProduct_check[$i] = false;

									$msubproducts = array();

									if ($v[4] == '' && $SubProductUID == '') {
										$default_subproducts = $this->common_model->get_defaultsubproduct($CustomerUID);
										$msubproductt = explode(",", $default_subproducts->DefaultProductSubValue);
										if (count($msubproductt) == 1) {
											$msubproducts = $this->common_model->getsubproductbyUID($msubproductt[0]);
											if (count($msubproducts) == 0) {
												$SubProduct_check[$i] = false;
											} else {
												$SubProductUID = $msubproducts->SubProductUID;
											}
										} else {
											$SubProduct_check[$i] = false;
										}

									} elseif ($v[4] != '') {

										$msubproducts = $this->Orderentry_model->get_sub_product($v[4]);
										if (count($msubproducts) > 0) {
											$SubProductUID = $msubproducts->SubProductUID;
											$SubProduct_check[$i] = true;
										} else {
											$SubProduct_check[$i] = false;
										}

									} elseif ($SubProductUID != '') {
										$msubproducts = $this->common_model->getsubproductbyUID($SubProductUID);

										if (count($msubproducts) > 0) {
											$SubProductUID = $msubproducts->SubProductUID;
											$SubProduct_check[$i] = true;
										} else {
											$SubProduct_check[$i] = false;
										}
									}

									if (count($msubproducts) > 0) {

										$mcustomerproducts[$i] = $this->Orderentry_model->get_all_in_customerproduct($CustomerUID, $ProductUID, $SubProductUID);

										if (count($mcustomerproducts[$i]) > 0) {
											$SubProduct_check[$i] = true;
											$arrayCode[$i][0] = $mcustomerproducts[$i]->OrderTypeName;
											$arrayCode[$i][1] = "Normal";
											$arrayCode[$i][2] = $mcustomerproducts[$i]->CustomerName;
											$arrayCode[$i][3] = $mcustomerproducts[$i]->ProductName;
											$arrayCode[$i][4] = $mcustomerproducts[$i]->SubProductName;
										} else {
											$SubProduct_check[$i] = false;
											$mcustomerproducts[$i] = $this->Orderentry_model->get_customer_product($CustomerUID, $ProductUID);
											$arrayCode[$i][0] = '';
											$arrayCode[$i][1] = '';
											$arrayCode[$i][2] = $mcustomerproducts[$i]->CustomerName;
											$arrayCode[$i][3] = $mcustomerproducts[$i]->ProductName;
											$arrayCode[$i][4] = $msubproducts->SubProductName;
										}
									} else {
										$mcustomerproducts[$i] = $this->Orderentry_model->get_customer_product($CustomerUID, $ProductUID);
										$arrayCode[$i][0] = '';
										$arrayCode[$i][1] = '';
										$arrayCode[$i][2] = $mcustomerproducts[$i]->CustomerName;
										$arrayCode[$i][3] = $mcustomerproducts[$i]->ProductName;
										$arrayCode[$i][4] = $v[4];

									}
								}


								foreach ($arrayCode as $i => $a) {


									$count = count($a);
									$field_count = 17;

										//for missing fields
									if (count($arrayCode[$i]) >= $field_count) {

										if ((count($arrayCode[$i]) + 1) % 6 != 0) {
											?> <tr style="background-color: #757575; color: #fff;"> <?php 
																																																																			foreach ($arrayCode[$i] as $key => $value) {

																																																																				?>
													<td><?php echo $value; ?></td>										
													<?php 
											}

											?> </tr> <?php 

																			} else {

																				if ((count($a) >= 17)) {



																					$CityName = $a[8];

																					$StateCode = $a[10];
																					$Zipcode = $a[11];
																					$LoanNumber = $a[5];


																					$TemplateName = $a[14];
																					$CountyName = $a[9];



																					$template = $this->Orderentry_model->get_template($TemplateName);

																					if ($TemplateName == '') {
																						$default_template = $this->common_model->get_defaulttemplate_bycustomerUID($mcustomerproducts[$i]->CustomerUID);

																						if (count($default_template) > 0) {
																							$arrayCode[$i][14] = $default_template->TemplateName;
																							$template = $default_template;
																						}
																					}

													/*$mstates = $this->Orderentry_model->get_state($StateCode);

													$mcounties = [];
							                        if($StateCode > 0){
							                            $mcounties = $this->Orderentry_model->get_county($StateCode,$CountyName);
							                        }

													$mcities = $this->Orderentry_model->get_city($CityName,$a[11]);*/




																					if ($SubProduct_check[$i] == false) { ?>
													<tr style="background-color: #BF6105; color: #fff;"> <?php 
																																																																	foreach ($arrayCode[$i] as $key => $value) {

																																																																		?>
														<td ><?php echo $value; ?></td>										
														<?php 
												}
											} elseif ($arrayCode[$i][17] == '') { ?>
												<tr style="background-color: #AA00FF; color: #fff;"> <?php 
																																																																foreach ($arrayCode[$i] as $key => $value) {

																																																																	?>
													<td ><?php echo $value; ?></td>										
													<?php 
											}
										} elseif ($StateCode == '') {
											?> <tr style="background-color: #02BD5A; color: #fff;"> <?php 
																																																																			foreach ($arrayCode[$i] as $key => $value) {

																																																																				?>
													<td><?php echo $value; ?></td>										
													<?php 
											}

											?> </tr> <?php 


																			} elseif ($CountyName == '') {
																				?> <tr style="background-color: #3502BD; color: #fff;"> <?php 
																																																																			foreach ($arrayCode[$i] as $key => $value) {

																																																																				?>
													<td><?php echo $value; ?></td>										
													<?php 
											}

											?> </tr> <?php 

																			} elseif ($CityName == '') {
																				?> <tr style="background-color: #074D1E; color: #fff;"> <?php 
																																																																			foreach ($arrayCode[$i] as $key => $value) {

																																																																				?>
													<td><?php echo $value; ?></td>										
													<?php 
											}

											?> </tr> <?php 


																			} elseif ($Zipcode == '') {
																				?> <tr style="background-color: #BDA601; color: #fff;"> <?php 
																																																																			foreach ($arrayCode[$i] as $key => $value) {

																																																																				?>
													<td><?php echo $value; ?></td>										
													<?php 
											}

											?> </tr> <?php 


																			} else if ($IsFlood == '1') {
																				if ($LoanNumber == '') {
																					?> <tr style="background-color: #ff5c33; color: #fff;"> <?php 
																																																																				foreach ($arrayCode[$i] as $key => $value) {

																																																																					?>
														<td><?php echo $value; ?></td>										
														<?php 

												} ?>
													</tr> <?php 
																	}

																} else {
																	?>
												 <tr style="color: #090809;"> <?php 
																																									foreach ($arrayCode[$i] as $key => $value) {

																																										?>
													<td><?php echo $value; ?></td>										
													<?php 
											}

											?> </tr> <?php 
																			}




																		} else {
																			?> <tr style="background-color: #757575; color: #fff;"> <?php 
																																																																		foreach ($arrayCode[$i] as $key => $value) {

																																																																			?>
												<td><?php echo $value; ?></td>										
												<?php 
										}

										?> </tr> <?php 
																		}

																	}
																} else {


																	?> <tr style="background-color: #757575; color: #fff;"> <?php 
																																																																foreach ($arrayCode[$i] as $key => $value) {

																																																																	?>
										<td><?php echo $value; ?></td>										
										<?php 
								}

								?> 
								</tr> 

								<?php 

						}

					}

					?> 
					</tbody>

				</table>
				<?php

		} else {
			echo json_encode(array('error' => '1', 'message' => 'Please Upload Valid File'));
		}

	} else {
		echo json_encode(array('error' => '1', 'message' => 'Please upload File'));
	}

}
	/* ---- EXCEL BULK ENTRY ENDS --- */




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


	function isEmptyRow($row)
	{
		foreach ($row as $cell) {
			if (null !== $cell) return false;
		}
		return true;
	}


	/* ----- SUPPORTING FUNCTIONS ENDS ---- */
	
	
}?>
