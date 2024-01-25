<?php
/**
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of tracks.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_banners
 * @since		1.6
 */
class IntranetViewFinPagamentos extends JViewLegacy
{
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		
	
		$_app	 = JFactory::getApplication(); 
		$_siteOffset = $_app->getCfg('offset');
		
		$basename		= 'relatorio_' . JFactory::getDate('now', $_siteOffset)->toFormat('%d-%m-%Y', true);
		$filetype		= 'xlsx';
		$mimetype		= 'application/vnd.ms-excel; charset=utf-8';
		

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		
		require_once(JPATH_LIBRARIES .DS. 'phpexcel' . DS. 'library' . DS. 'PHPExcel' . DS. 'IOFactory.php');
		$objPHPExcel = new PHPExcel();	

		$layout	= $this->getLayout();
		switch($layout):
			case 'email':	
				$contents = $this->get( 'ReportMail' );
				if(count($contents) >0 ):
		
					ini_set ( 'max_execution_time' ,  0 );
					ini_set('memory_limit','512M');
		
			
					$objPHPExcel->getActiveSheet()->setCellValue('A1','Nome')
						->setCellValue('B1','Sobrenome')
						->setCellValue('C1','Email')
						->setCellValue('D1','Celular')
						->setCellValue('E1','Documento')
						->setCellValue('F1','Vencimento')
						->setCellValue('G1','Valor')
						->setCellValue('H1','Código')
						->setCellValue('I1','Digitável');
						
					$objPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('B1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('C1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('D1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('E1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('F1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('G1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('H1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('I1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
		
		
					$objPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('B1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('C1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('D1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('E1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('F1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('G1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('H1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('I1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
		
					$objPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('B1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('C1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('D1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('E1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('F1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('G1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('H1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('I1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		
					$objPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('B1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('C1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('D1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('E1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('F1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('G1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('H1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('I1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
		
					$objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle('C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle('D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle('E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle('F1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle('G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle('H1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle('I1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					
		
					$objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('FFFFFF99');
					$objPHPExcel->getActiveSheet()->getStyle('B1')->getFill()->getStartColor()->setARGB('FFFFFF99');
					$objPHPExcel->getActiveSheet()->getStyle('C1')->getFill()->getStartColor()->setARGB('FFFFFF99');
					$objPHPExcel->getActiveSheet()->getStyle('D1')->getFill()->getStartColor()->setARGB('FFFFFF99');
					$objPHPExcel->getActiveSheet()->getStyle('E1')->getFill()->getStartColor()->setARGB('FFFFFF99');
					$objPHPExcel->getActiveSheet()->getStyle('F1')->getFill()->getStartColor()->setARGB('FFFFFF99');
					$objPHPExcel->getActiveSheet()->getStyle('G1')->getFill()->getStartColor()->setARGB('FFFFFF99');
					$objPHPExcel->getActiveSheet()->getStyle('H1')->getFill()->getStartColor()->setARGB('FFFFFF99');
					$objPHPExcel->getActiveSheet()->getStyle('I1')->getFill()->getStartColor()->setARGB('FFFFFF99');
					
							
					$objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);		
					$objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
					$objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
					$objPHPExcel->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
					$objPHPExcel->getActiveSheet()->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
					$objPHPExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
					$objPHPExcel->getActiveSheet()->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
						
					foreach($contents as $i => $content):
		 				$linha = ($i + 2);
		 /*
						$objPHPExcel->getActiveSheet()->getStyle('A' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
						$objPHPExcel->getActiveSheet()->getStyle('B' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
						$objPHPExcel->getActiveSheet()->getStyle('C' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
						$objPHPExcel->getActiveSheet()->getStyle('D' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
						$objPHPExcel->getActiveSheet()->getStyle('E' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
						$objPHPExcel->getActiveSheet()->getStyle('F' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
						$objPHPExcel->getActiveSheet()->getStyle('G' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
						$objPHPExcel->getActiveSheet()->getStyle('H' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
						$objPHPExcel->getActiveSheet()->getStyle('I' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
						*/
						/*
						$objPHPExcel->getActiveSheet()->setCellValue('A' . $linha, $content->Nome)
							->setCellValue('B' . $linha, $content->Sobrenome)
							->setCellValue('C' . $linha, $content->Email)  
							->setCellValue('D' . $linha, $content->Celular) 
							->setCellValue('E' . $linha, ''.$content->Documento.'') 
							->setCellValue('F' . $linha, $content->Vencimento) 
							->setCellValue('G' . $linha, number_format($content->Valor,2,',','.')) 
							->setCellValue('H' . $linha, ''.$content->Codigo.'') 
							->setCellValue('I' . $linha, $content->Digitavel);*/
						$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $linha)->setValueExplicit($content->Nome, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $linha)->setValueExplicit($content->Sobrenome, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $linha)->setValueExplicit($content->Email, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $linha)->setValueExplicit($content->Celular, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $linha)->setValueExplicit($content->Documento, PHPExcel_Cell_DataType::TYPE_STRING);	
						$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $linha)->setValueExplicit(JFactory::getDate($content->Vencimento, $_siteOffset)->toFormat('%d/%m/%Y', true), PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6, $linha)->setValueExplicit(number_format($content->Valor,2,',','.'), PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7, $linha)->setValueExplicit($content->Codigo, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(8, $linha)->setValueExplicit($content->Digitavel, PHPExcel_Cell_DataType::TYPE_STRING);
		/*
						$objPHPExcel->getActiveSheet()->getStyle('A' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
						$objPHPExcel->getActiveSheet()->getStyle('B' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
						$objPHPExcel->getActiveSheet()->getStyle('C' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
						$objPHPExcel->getActiveSheet()->getStyle('D' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
						$objPHPExcel->getActiveSheet()->getStyle('E' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
						$objPHPExcel->getActiveSheet()->getStyle('F' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
						$objPHPExcel->getActiveSheet()->getStyle('G' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
						$objPHPExcel->getActiveSheet()->getStyle('H' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
						$objPHPExcel->getActiveSheet()->getStyle('I' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);	
					
						$objPHPExcel->getActiveSheet()->getStyle('A' . $linha)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('B' . $linha)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('C' . $linha)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('D' . $linha)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('E' . $linha)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('F' . $linha)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('G' . $linha)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('H' . $linha)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('I' . $linha)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
						$objPHPExcel->getActiveSheet()->getStyle('A' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('B' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('C' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('D' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('E' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('F' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('G' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('H' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('I' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
						$objPHPExcel->getActiveSheet()->getStyle('A' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
						$objPHPExcel->getActiveSheet()->getStyle('B' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('C' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('D' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('E' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('F' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('G' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('H' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('I' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
						$objPHPExcel->getActiveSheet()->getStyle('A' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('B' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('C' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('D' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('E' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('F' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('G' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('H' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('I' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
						
						$objPHPExcel->getActiveSheet()->getStyle('C' . $linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
						$objPHPExcel->getActiveSheet()->getStyle('D' . $linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);		
						$objPHPExcel->getActiveSheet()->getStyle('E' . $linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
						$objPHPExcel->getActiveSheet()->getStyle('F' . $linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);		
						$objPHPExcel->getActiveSheet()->getStyle('G' . $linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);		
						$objPHPExcel->getActiveSheet()->getStyle('H' . $linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);		
						$objPHPExcel->getActiveSheet()->getStyle('I' . $linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);		
						*/
					endforeach;
					
					
					$objPHPExcel->getActiveSheet()->getStyle('A2:I' . $linha)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A2:I' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('A2:A' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					
					$objPHPExcel->getActiveSheet()->getStyle('B2:B' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('C2:C' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('D2:D' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('E2:E' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('F2:F' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('G2:G' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('H2:H' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('I2:I' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					
					$objPHPExcel->getActiveSheet()->getStyle('A2:A' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('B2:B' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('C2:C' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('D2:D' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('E2:E' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('F2:F' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('G2:G' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('H2:H' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('I2:I' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					
					$objPHPExcel->getActiveSheet()->getStyle('C2:I' . $linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
					$objPHPExcel->getActiveSheet()->getStyle('A2:I' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
					
					$objPHPExcel->getActiveSheet()->getStyle('A' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('B' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('C' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('D' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('E' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
			
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
					$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);	
					$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
					$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
					$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
					$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
					$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);			
					$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);				
					$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
					
					
					$objPHPExcel->getActiveSheet()->calculateColumnWidths();
					
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(false);	
					$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(false);			
					$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(false);					
					$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(false);				
					
					$document = JFactory::getDocument();
					$document->setMimeEncoding($mimetype);
					JResponse::setHeader('Content-disposition', 'attachment; filename="'.$basename.'.'.$filetype.'"; creation-date="'.JFactory::getDate()->toRFC822().'"', true);
					
					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'excel2007');
					$objWriter->save('php://output');
		
				else:
				
		
						$link = JRoute::_('index.php?view=finpagamentos', false );
						$_app 	= JFactory::getApplication(); 
						$_app->redirect($link,'Não existem Pagamentos para exportar.','alert-warning');
		
				
				endif;

				
				
				
				
				
				
				
				
							
			break;

			default:
				$contents		= $this->get('Report');
				if(count($contents) >0 ):	
				
					$objPHPExcel->getActiveSheet()->setCellValue('A1','Nome do Associado')
						->setCellValue('B1','CPF / CNPJ')
						->setCellValue('C1','Matrícula')
						->setCellValue('D1','CR')
						->setCellValue('E1','Clube');
						//->setCellValue('F1','Total Curso');	
						
					$objPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('B1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('C1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('D1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('E1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					//$objPHPExcel->getActiveSheet()->getStyle('F1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
		
		
					$objPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('B1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('C1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('D1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('E1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
				//	$objPHPExcel->getActiveSheet()->getStyle('F1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
		
					$objPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('B1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('C1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('D1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('E1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				//	$objPHPExcel->getActiveSheet()->getStyle('F1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		
					$objPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('B1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('C1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('D1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle('E1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					//$objPHPExcel->getActiveSheet()->getStyle('F1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
		
					$objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle('C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle('D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle('E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				//	$objPHPExcel->getActiveSheet()->getStyle('F1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					
		
					$objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('FFFFFF99');
					$objPHPExcel->getActiveSheet()->getStyle('B1')->getFill()->getStartColor()->setARGB('FFFFFF99');
					$objPHPExcel->getActiveSheet()->getStyle('C1')->getFill()->getStartColor()->setARGB('FFFFFF99');
					$objPHPExcel->getActiveSheet()->getStyle('D1')->getFill()->getStartColor()->setARGB('FFFFFF99');
					$objPHPExcel->getActiveSheet()->getStyle('E1')->getFill()->getStartColor()->setARGB('FFFFFF99');
				//	$objPHPExcel->getActiveSheet()->getStyle('F1')->getFill()->getStartColor()->setARGB('FFFFFF99');
					
							
					$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);		
					$objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
					$objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
					$objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
				//	$objPHPExcel->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
						
					foreach($contents as $i => $content):
		 
						$linha = ($i + 2);
						$objPHPExcel->getActiveSheet()->setCellValue('A' . $linha, $content->name_associado)
							->setCellValue('B' . $linha, $content->documanto_associado)
							->setCellValue('C' . $linha, $content->matricula_associado)  
							->setCellValue('D' . $linha, $content->numcr_associado) 
							->setCellValue('E' . $linha, $content->subfiliacao_associado);
		
						
					
						$objPHPExcel->getActiveSheet()->getStyle('A' . $linha)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('B' . $linha)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('C' . $linha)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('D' . $linha)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('E' . $linha)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						//$objPHPExcel->getActiveSheet()->getStyle('F' . $linha)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
						$objPHPExcel->getActiveSheet()->getStyle('A' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('B' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('C' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('D' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('E' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						//$objPHPExcel->getActiveSheet()->getStyle('F' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
						$objPHPExcel->getActiveSheet()->getStyle('A' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
						$objPHPExcel->getActiveSheet()->getStyle('B' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('C' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('D' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('E' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						//$objPHPExcel->getActiveSheet()->getStyle('F' . $linha)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
						$objPHPExcel->getActiveSheet()->getStyle('A' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('B' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('C' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('D' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
						$objPHPExcel->getActiveSheet()->getStyle('E' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
						//$objPHPExcel->getActiveSheet()->getStyle('F' . $linha)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
						
						$objPHPExcel->getActiveSheet()->getStyle('B' . $linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);		
						$objPHPExcel->getActiveSheet()->getStyle('C' . $linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
						$objPHPExcel->getActiveSheet()->getStyle('D' . $linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);		
						$objPHPExcel->getActiveSheet()->getStyle('E' . $linha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
					
							//$objPHPExcel->getActiveSheet()->getStyle('B' . $linha)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
							//$objPHPExcel->getActiveSheet()->getStyle('D' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
						
								
							//	$objPHPExcel->getActiveSheet()->getStyle('A' . $linhaC)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							//	$objPHPExcel->getActiveSheet()->getStyle('E' . $linhaM)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							//	$objPHPExcel->getActiveSheet()->getStyle('F' . $linhaC)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
								
							//	$objPHPExcel->getActiveSheet()->setCellValue('E' . $linhaM, round($totalM, 2));
							//	$objPHPExcel->getActiveSheet()->setCellValue('F' . $linhaC, round($totalC, 2));
								
							//	$objPHPExcel->getActiveSheet()->getStyle('E' . $linhaM)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
							//	$objPHPExcel->getActiveSheet()->getStyle('F' . $linhaC)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
								
		
								//$objPHPExcel->getActiveSheet()->getStyle('F' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
								
							/*	*/
			
					
					endforeach;
					
					$objPHPExcel->getActiveSheet()->getStyle('A' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('B' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('C' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('D' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
					$objPHPExcel->getActiveSheet()->getStyle('E' . $linha)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
			
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
					$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);	
					$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
					$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
					$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
				//	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
					
					$objPHPExcel->getActiveSheet()->calculateColumnWidths();
					
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(false);	
					$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(false);
					//$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(false);
					
					$document = JFactory::getDocument();
					$document->setMimeEncoding($mimetype);
					JResponse::setHeader('Content-disposition', 'attachment; filename="'.$basename.'.'.$filetype.'"; creation-date="'.JFactory::getDate()->toRFC822().'"', true);
					
					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'excel2007');
					$objWriter->save('php://output');
		
				else:
				
		
						$link = JRoute::_('index.php?view=finpagamentos', false );
						$_app 	= JFactory::getApplication(); 
						$_app->redirect($link,'Não existem Pagamentos para exportar.','alert-warning');
		
				
				endif;
			break;
		endswitch;
		
		
		
		//fopen('php://output', 'w');
		/*
		array_unshift($contents, array('Nome','E-mail'));
		
		*/
	
		$fp = fopen('php://output', 'w');
		
		

/*

		foreach($contents as $row) {
			fputcsv($fp, $row,';');
		}
		
*/
	}
}
