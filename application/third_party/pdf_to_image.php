<?php  
class pdf2image{
	
	public function convert_pdf_to_image($pdf_file, $page_number, $destination_file){
		$directory = FCPATH;
		$source_path = FCPATH.escapeshellarg($pdf_file);
		$destination_path = $directory.'assets/OCR/OCRImages/'.escapeshellarg($destination_file);

		// check current OS
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

			$command = $directory.'assets/gs/win/bin/gs.exe -q -sDEVICE=pngalpha -dBATCH -dNOPAUSE -dFirstPage='.(int)$page_number.' -dLastPage='.(int)($page_number+1).' -r150x150 -sOutputFile='.$destination_path.' '.$source_path;
		}else{

			$command = 'gs -q -sDEVICE=pngalpha -dBATCH -dNOPAUSE -dFirstPage='.(int)$page_number.' -dLastPage='.(int)($page_number+1).' -r150x150 -sOutputFile='.$destination_path.' '.$source_path;
		}
		$command;

		exec($command, $retArr, $retVal);

		if(empty($retVal)){
			return 'Success';
		}
		else{
			return 'Error occured while converting the file using below command. <br>'.$command;
		}
	}
}
?>