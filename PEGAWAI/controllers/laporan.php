<?php
require_once 'includes/PHPExcel/PHPExcel.php';
require_once 'includes/dompdf/autoload.inc.php';
use Dompdf\Dompdf as Dompdf;
use Dompdf\Options as Options;

define('FORMAT_UANG', '#,##0.00;-#,##0.00');

class Laporan extends CI_Controller {
	public $pdf;
	public $xls;
	
	function __construct(){
		parent::__construct();
		$this->api = $this->load->library('s00_lib_api');
        $this->url = $this->load->library('lib_url');
        $this->util = $this->load->library('lib_util');

		$options = new Options();
		//$options->set('defaultFont', 'Courier');
		$options->set('isRemoteEnabled', TRUE);
		//$options->set('debugKeepTemp', TRUE);
		//$options->set('isHtml5ParserEnabled', true);
		
		$this->pdf = new Dompdf($options);
		$this->pdf->setPaper('A4');
		//$this->pdf->setPaper('A4', 'landscape');
		
	}
	
	function setSubject($subject){
		$this->pdf->add_info('Title', $subject);
		$this->pdf->add_info('Author', 'UIN Sunan Kalijaga Yogyakarta');
		$this->pdf->add_info('Subject', $subject);
		$this->pdf->add_info('Creator', 'Aplikasi Remunerasi');
	}
	
	function exportPDF($view, $data, $output, $settings=null){
		$html 		= $this->load->view($view, $data, true);
		
		$this->pdf->loadHtml($html);
		if(!empty($settings)){
			$paperOrientation = 'portrait';
			$paperSize		  = 'A4';
			if (array_key_exists('paper_size', $settings)){
				$paperSize	= $settings['paper_size'];
			}
			if (array_key_exists('orientation', $settings)){
				$paperOrientation	= $settings['orientation'];
			}
			$this->pdf->setPaper($paperSize, $paperOrientation);
		}
		$this->pdf->render();
		$this->setSubject(ucwords(str_replace('_', ' ', $str=$output)));
		$this->pdf->stream($output,array('Attachment'=>0));
	}
	
	function xls_rekap_remun_total($periode, $bulan){
		//include 'includes/PHPExcel/PHPExcel/Writer/Excel2007.php';

		$par	= array('PERIODE'=>$periode, 'BULAN'=>$bulan);
		$tanggal= '01-'.$bulan.'-'.$periode;
		$rekap	= $this->api->post($this->url->remun . '/rekap_remun', $par);

		$bulan_periode = $this->util->bulan($tanggal);
		$bulan_periode = explode(' ', $bulan_periode);
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		// Set document properties
			$objPHPExcel->getProperties()->setCreator("Sistem Informasi Kepegawaian")
										 ->setLastModifiedBy("Sistem Informasi Kepegawaian")
										 ->setTitle("Pembayaran Remunerasi " . $bulan_periode[1] . ' ' . $bulan_periode[2])
										 ->setSubject("Pembayaran Remunerasi " . $bulan_periode[1] . ' ' . $bulan_periode[2])
										 ->setDescription("Pembayaran Remunerasi " . $bulan_periode[1] . ' ' . $bulan_periode[2])
										 ->setKeywords("Remunerasi")
										 ->setCategory("Payroll");

		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'NO')
					->setCellValue('B1', 'NAMA')
					->setCellValue('C1', 'NO. REK')
					->setCellValue('D1', 'P1')
					->setCellValue('E1', 'P2')
					->setCellValue('F1', 'JML. REMUN');
		
		$header_style = array(
			'font' => array(
				'bold' => true,
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' => array(
					'argb' => 'F7F7F7F7'
				)
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('rgb' => 'FF000000')
				)
			)
		);
		
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:F1")->applyFromArray($header_style);
		$objPHPExcel->setActiveSheetIndex(0)->getRowDimension('1')->setRowHeight(25);		
		
		if (!empty($rekap)){
		// Miscellaneous glyphs, UTF-8
			$total_p1	= 0;
			$total_p2	= 0;
			$total 		= 0;
			for($i=0;$i<count($rekap);$i++){
				$total += $rekap[$i]['TOTAL_TERIMA'];
				$p1 = $rekap[$i]['P1']-$rekap[$i]['NOMINAL_PAJAK_P1'];
				$p2 = $rekap[$i]['PEROLEHAN_P2'] - $rekap[$i]['NOMINAL_POT_A_P2'] - $rekap[$i]['NOMINAL_POT_B_P2'] - $rekap[$i]['NOMINAL_PAJAK_P2'] ;
				
				$total_p1 += $p1;
				$total_p2 += $p2;
				
				$baris = $i + 2;
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A' . $baris, $i+1)
							->setCellValue('B' . $baris, $rekap[$i]['DATA_PEGAWAI']['NM_PGW'])
							->setCellValue('C' . $baris, $rekap[$i]['DATA_PEGAWAI']['NO_REKENING'])
							->setCellValue('D' . $baris, $p1)
							->setCellValue('E' . $baris, $p2)
							->setCellValue('F' . $baris, $rekap[$i]['TOTAL_TERIMA']);
							
				$border_style = array(
					'alignment' => array(
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
					),
					'borders' => array(
						'allborders' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('rgb' => 'FF000000')
						)
					)
				);
				
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("A$baris:F$baris")->applyFromArray($border_style);				
				$objPHPExcel->setActiveSheetIndex(0)->getRowDimension($baris)->setRowHeight(25);
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("D$baris:F$baris")->getNumberFormat()->setFormatCode( FORMAT_UANG );
			}

			$baris++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $baris, $total_p1)
										->setCellValue('E' . $baris, $total_p2)
										->setCellValue('F' . $baris, $total);
			$objPHPExcel->setActiveSheetIndex(0)->getRowDimension($baris)->setRowHeight(25);		
			$objPHPExcel->setActiveSheetIndex(0)->getStyle("D$baris:F$baris")->getNumberFormat()->setFormatCode( FORMAT_UANG );
						
			$border_style = array(
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				),
				'borders' => array(
					'left' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('rgb' => 'FF000000')
					),
					'right' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('rgb' => 'FF000000')
					),
					'bottom' => array(
						'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
						'color' => array('rgb' => 'FF000000')
					)
				)
			);
			
			$objPHPExcel->setActiveSheetIndex(0)->getStyle("D$baris:F$baris")->applyFromArray($border_style);						
						
			//pejabat
			$baris = $baris + 5;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $baris, 'Yogyakarta, ' . date('d') . ' ' . $bulan_periode[1] . ' ' . $bulan_periode[2]);
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells("E$baris:F$baris");
			$baris++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $baris, 'Diverifikasi oleh,');
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells("E$baris:F$baris");
			$baris++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $baris, 'BPP. BLU PAU');
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells("E$baris:F$baris");
			$baris = $baris + 5;
			$baris++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $baris, 'Yuli Triwahyuningsih');
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells("E$baris:F$baris");
			$baris++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $baris, $this->util->spasiNip('197107051994032004'));
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells("E$baris:F$baris");
		}

		//Set column width to autosize
		foreach(range('A','F') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}
				
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle($bulan_periode[1] . '_' . $bulan_periode[2]);
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		// Redirect output to a client’s web browser (Excel2007)
		$nama = "Pembayaran Remunerasi " . $bulan_periode[1] . ' ' . $bulan_periode[2];
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $nama . '.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;	
		
	}
	
	
	function xls_rekap_remun_detail($periode, $bulan){
		//include 'includes/PHPExcel/PHPExcel/Writer/Excel2007.php';

		$par	= array('PERIODE'=>$periode, 'BULAN'=>$bulan);
		$tanggal= '01-'.$bulan.'-'.$periode;
		$rekap	= $this->api->post($this->url->remun . '/rekap_remun', $par);

		$bulan_periode = $this->util->bulan($tanggal);
		$bulan_periode = explode(' ', $bulan_periode);
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		// Set document properties
			$objPHPExcel->getProperties()->setCreator("Sistem Informasi Kepegawaian")
										 ->setLastModifiedBy("Sistem Informasi Kepegawaian")
										 ->setTitle("Daftar Penerimaan Remunerasi Pegawai " . $bulan_periode[1] . ' ' . $bulan_periode[2])
										 ->setSubject("Daftar Penerimaan Remunerasi Pegawai " . $bulan_periode[1] . ' ' . $bulan_periode[2])
										 ->setDescription("Daftar Penerimaan Remunerasi Pegawai " . $bulan_periode[1] . ' ' . $bulan_periode[2])
										 ->setKeywords("Remunerasi")
										 ->setCategory("Payroll");

		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Daftar Penerimaan Remunerasi Pegawai Kantor Pusat UIN Sunan Kalijaga Yogyakarta')
					->setCellValue('A2', 'Berdasarkan:')
					->setCellValue('C2', '-Peraturan Rektor No. 1 Tahun 2016 Tanggal 6 September 2016')
					->setCellValue('C3', '-Peraturan Rektor No. 2 Tahun 2016 Tanggal 7 September 2016')
					->setCellValue('K1', 'Tahun: ' . $bulan_periode[2])
					->setCellValue('K2', 'No. Buku: ')
					->setCellValue('K3', 'MAK: ')
					->setCellValue('A4', 'Bulan: ' . $bulan_periode[1] . ' ' . $bulan_periode[2]);
		
		$baris = 6;
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'. $baris, 'NO')
					->setCellValue('B'. $baris, 'Nama')
					->setCellValue('E'. $baris, 'NIP')
					->setCellValue('E'. $baris, 'Gol/Pangkat')
					->setCellValue('F'. $baris, 'Grade')
					->setCellValue('G'. $baris, 'Status Pegawai')
					->setCellValue('H'. $baris, 'P1')
					->setCellValue('I'. $baris, 'P2')
					->setCellValue('J'. $baris, 'Potongan')
					->setCellValue('K'. $baris, 'Pajak')
					->setCellValue('L'. $baris, 'Jumlah Netto');
		
		$judul_style = array(
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			)
		);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:A3")->applyFromArray($judul_style);
		
		$header_style = array(
			'font' => array(
				'bold' => true,
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' => array(
					'argb' => 'F7F7F7F7'
				)
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('rgb' => 'FF000000')
				)
			)
		);
		
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("A$baris:L$baris")->applyFromArray($header_style);

		$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A1:J1")
											->mergeCells("A2:B3")
											->mergeCells("A3:B3")
											->mergeCells("C2:I2")
											->mergeCells("C3:I3")
											->mergeCells("A4:D4")
											->mergeCells("B$baris:C$baris");

		$objPHPExcel->setActiveSheetIndex(0)->getRowDimension($baris)->setRowHeight(25);	

		if (!empty($rekap)){
		// Miscellaneous glyphs, UTF-8
			$total_p1	= 0;
			$total_p2	= 0;
			$total_pot	= 0;
			$total_pajak= 0;
			$total 		= 0;
			for($i=0;$i<count($rekap);$i++){
				$total += $rekap[$i]['TOTAL_TERIMA'];
				$p1 	= $rekap[$i]['P1']-$rekap[$i]['NOMINAL_PAJAK_P1'];
				$p2 	= $rekap[$i]['PEROLEHAN_P2'] - $rekap[$i]['NOMINAL_POT_A_P2'] - $rekap[$i]['NOMINAL_POT_B_P2'] - $rekap[$i]['NOMINAL_PAJAK_P2'] ;
				$jml_potongan 	= $rekap[$i]['NOMINAL_POT_A_P2'] + $rekap[$i]['NOMINAL_POT_B_P2'];
				$jml_pajak 		= $rekap[$i]['NOMINAL_PAJAK_P1'] + $rekap[$i]['NOMINAL_PAJAK_P2'];
				$status_pns 	= '';
				
				$total_p1 += $p1;
				$total_p2 += $p2;
				$total_pot	+= $jml_potongan;
				$total_pajak+= $jml_pajak;
				
				$baris++;
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A' . $baris, $i+1)
							->setCellValue('B' . $baris, $rekap[$i]['DATA_PEGAWAI']['NM_PGW'])
							//->setCellValue('C' . $baris, $rekap[$i]['DATA_PEGAWAI']['KD_PGW'])
							->setCellValue('E' . $baris, $rekap[$i]['GOL_PANGKAT'])
							->setCellValue('F' . $baris, $rekap[$i]['KD_LAYER'])
							->setCellValue('G' . $baris, $status_pns)
							->setCellValue('H' . $baris, $p1)
							->setCellValue('I' . $baris, $p2)
							->setCellValue('J' . $baris, $jml_potongan)
							->setCellValue('K' . $baris, $jml_pajak)
							->setCellValue('L' . $baris, $rekap[$i]['TOTAL_TERIMA']);
				
				//format numeric as string
				$objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit("D$baris", $rekap[$i]['DATA_PEGAWAI']['KD_PGW'],  PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->setActiveSheetIndex(0)->mergeCells("B$baris:C$baris");
							
				$border_style = array(
					'alignment' => array(
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
					),
					'borders' => array(
						'allborders' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('rgb' => 'FF000000')
						)
					)
				);
				
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("A$baris:L$baris")->applyFromArray($border_style);
				
				$border_style = array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
					)
				);
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("D$baris:G$baris")->applyFromArray($border_style);
				$objPHPExcel->setActiveSheetIndex(0)->getRowDimension($baris)->setRowHeight(25);
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("H$baris:L$baris")->getNumberFormat()->setFormatCode( FORMAT_UANG );
			}
		
					
			$baris++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $baris, $total_p1)
												->setCellValue('I' . $baris, $total_p2)
												->setCellValue('J' . $baris, $total_pot)
												->setCellValue('K' . $baris, $total_pajak)
												->setCellValue('L' . $baris, $total);
			$objPHPExcel->setActiveSheetIndex(0)->getRowDimension($baris)->setRowHeight(25);		
			$objPHPExcel->setActiveSheetIndex(0)->getStyle("H$baris:L$baris")->getNumberFormat()->setFormatCode( FORMAT_UANG );
			
			$border_style = array(
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				),
				'borders' => array(
					'left' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('rgb' => 'FF000000')
					),
					'right' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('rgb' => 'FF000000')
					),
					'bottom' => array(
						'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
						'color' => array('rgb' => 'FF000000')
					)
				)
			);
			
			$objPHPExcel->setActiveSheetIndex(0)->getStyle("H$baris:L$baris")->applyFromArray($border_style);
											
			//pejabat
			$baris = $baris + 5;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $baris, 'Yogyakarta, ' . date('d') . ' ' . $bulan_periode[1] . ' ' . $bulan_periode[2]);
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells("J$baris:L$baris");
			$baris++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $baris, 'Diverifikasi oleh,');
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells("J$baris:L$baris");
			$baris++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $baris, 'BPP. BLU PAU');
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells("J$baris:L$baris");
			$baris = $baris + 5;
			$baris++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $baris, 'Yuli Triwahyuningsih');
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells("J$baris:L$baris");
			$baris++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $baris, $this->util->spasiNip('197107051994032004'));
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells("J$baris:L$baris");
		}

		//Set column width to autosize
		foreach(range('A','L') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}
				
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle($bulan_periode[1] . '_' . $bulan_periode[2]);
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		// Redirect output to a client’s web browser (Excel2007)
		$nama = "Pembayaran Remunerasi " . $bulan_periode[1] . ' ' . $bulan_periode[2];
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $nama . '.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;	
		
	}
	
}

?>
