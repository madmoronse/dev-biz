<?php
class ControllerAccountEmailSubscribers extends Controller {


    public function index() {

        $json = array();

                $this->generate();

    }

    private function generate() {
        
        set_include_path(DIR_SYSTEM.'PHPExcel/Classes');

        require_once "PHPExcel.php";

		$workbook = new PHPExcel();
		$workbook->setActiveSheetIndex(0);
		$this->sheet = $workbook->getActiveSheet();
        $this->sheet->setTitle('email_subscribers');
		
		$query = $this->db->query("SELECT email FROM " . DB_PREFIX . "email_subscribers");
		$row = 0;
        foreach ($query->rows as $email) {
			$this->sheet->setCellValueByColumnAndRow(0,$row,$email['email']);
			$row++;			
		}



				// Вывод файла в браузер
                include_once("PHPExcel/Writer/Excel2007.php");
                $objWriter = new PHPExcel_Writer_Excel2007($workbook);
                // redirect output to client browser
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Email_Subscribers.xlsx"');
                header('Cache-Control: max-age=0');
                $objWriter->save('php://output');
                
         /*		//Сохранение файла на сервере в downloads
                include_once("PHPExcel/Writer/Excel2007.php");
                $objWriter = new PHPExcel_Writer_Excel2007($workbook);
                $objWriter->save(DIR_DOWNLOAD."Subscribers.xlsx");
           */
            $workbook->disconnectWorksheets();
            unset($objWriter);
            unset($workbook);
        
    }


}
?>