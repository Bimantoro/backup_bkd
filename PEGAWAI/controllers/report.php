<?php
require_once 'includes/PHPExcel/PHPExcel.php';

class Report extends CI_Controller {
	function __construct(){
		parent::__construct();
	}
	
	function xls_rekap_remun_total(){
			// Create new PHPExcel object
			$objPHPExcel = new PHPExcel();

			// Set document properties
			$objPHPExcel->getProperties()->setCreator("Sistem Informasi Kepegawaian")
										 ->setLastModifiedBy("Sistem Informasi Kepegawaian")
										 ->setTitle("Daftar Jabatan Struktural - ".date('Y-m-d'))
										 ->setSubject("Daftar Jabatan Struktural - ".date('Y-m-d'))
										 ->setDescription("Daftar Jabatan Struktural - ".date('Y-m-d'))
										 ->setKeywords("Daftar Jabatan Struktural - ".date('Y-m-d'))
										 ->setCategory("Jabatan Struktural");


			// Add data
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Daftar Jabatan Struktural')
									->setCellValue('A3', 'Kode Jabatan')
									->setCellValue('B3', 'Jabatan')
									->setCellValue('C3', 'Unit')
									->setCellValue('D3', 'No. Kegiatan')
									->setCellValue('E3', 'Kegiatan')
									->setCellValue('F3', 'Induk Kegiatan')
									->setCellValue('G3', 'Standar Kuantitas')
									->setCellValue('H3', 'Standar Waktu (Menit)')
									->setCellValue('I3', 'Standar Biaya')
									->setCellValue('J3', 'Satuan Output')
									->setCellValue('K3', 'Satuan Hasil')
									->setCellValue('L3', 'Standar Mutu')
									->setCellValue('M3', 'Standar Angka Kredit SKP')
									->setCellValue('N3', 'Standar Waktu SKP (Bulan)')
									->setCellValue('O3', 'Standar Biaya SKP')
									->setCellValue('P3', 'Standar Mutu SKP')
									->setCellValue('Q3', 'Tanggal Mulai Kegiatan (d/m/Y)')
									->setCellValue('R3', 'Tanggal Selesai Kegiatan (d/m/Y)')
									->setCellValue('S3', 'ID Status Kegiatan');

			#Keterangan Status Kegiatan
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V1', 'Status Kegiatan')
									->setCellValue('V3', 'ID Status Kegiatan')
									->setCellValue('W3', 'Status Kegiatan');
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells('V1:W1');

			$title_style = array(
				'font' => array(
					'bold' => true,
					'size' => 18,
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				)
			);
			$header_style = array(
				'font' => array(
					'bold' => true,
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				),
				'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'startcolor' => array(
						'argb' => 'F7F7F7F7'
					)
				)
			);
			
			$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:S1")->applyFromArray($title_style);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle("A3:S3")->applyFromArray($header_style);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle("V1:W1")->applyFromArray($title_style);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle("V3:W3")->applyFromArray($header_style);
	
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);


			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="jabatan-struktural_'.date('Y-m-d').'.xls"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
	}
	
}
?>
