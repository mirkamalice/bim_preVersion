<?php
require_once __DIR__ . '/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

function pdf_create($html, $filename = '', $stream = TRUE, $set_paper = '', $attach = null, $folder_name = null, $watermark = null)
{
    // Class 'Dompdf\Options' not found
    $options = new Options();
    $options->set('defaultFont', 'dejavusanscondensed');
    $options->setDefaultMediaType('print');
    $options->setIsRemoteEnabled(true);
    $options->setIsPhpEnabled(true);
    $options->setIsHtml5ParserEnabled(true);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    
    if ($set_paper != '') {
        $dompdf->setPaper(array(0, 0, 900, 841), 'portrait');
    } else {
        $print_view = config_item('invoice_print_view');
        if (empty($print_view)) {
            $print_view = 'landscape';
        }
        $dompdf->setPaper("a4", $print_view);
//        $dompdf->setPaper("a4", "portrait");
        
        // ALTER USER  'root'@'localhost' IDENTIFIED BY 'Viti!Q@W#E$R56';
        // ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'Viti!Q@W#E$R56';
//        ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password';
        // CREATE USER 'roots'@'%' IDENTIFIED BY 'Viti!Q@W#E$R56';
        // GRANT ALL PRIVILEGES ON *.* TO 'roots'@'%' WITH GRANT OPTION;
        // FLUSH PRIVILEGES;
        
    }
//    $watermark = 'Cancelled';
    $dompdf->render();
    $watermark_enabled = config_item('show_watermark');
    if (!empty($watermark_enabled) && $watermark_enabled != 'FALSE' && !empty($watermark)) {
        // remove html tags and spaces from watermark
        $watermark = trim(strip_tags($watermark));
        // Instantiate canvas instance
        $canvas = $dompdf->getCanvas();
        $canvas->page_script('$pdf->set_opacity(.1, "Multiply");');
        $h = $canvas->get_height();
        $w = $canvas->get_width();
        $fontMetrics = $dompdf->getFontMetrics();
        $fontFamily = $fontMetrics->getFont("helvetica");
        
        $txtHeight = $fontMetrics->getFontHeight($fontFamily, 120);
        $textWidth = $fontMetrics->getTextWidth($watermark, $fontFamily, 120);
        
        $x = (($w - $textWidth) / 2);
        $y = (($h - $txtHeight) / 2);
//
//        // check $text size and change font size if needed to fit in the page
        if ($textWidth > $w) {
            $textWidth = $fontMetrics->getTextWidth($watermark, $fontFamily, 60);
            $txtHeight = $fontMetrics->getFontHeight($fontFamily, 60);
            $x = (($w - $textWidth) / 2);
            $y = (($h - $txtHeight) / 2);
            $canvas->page_text($x, $y, $watermark, $fontFamily, 70, array(
                0,
                0,
                0
            ), 0, 0, -35);
        } else {
            if ($textWidth > $w / 2) {
                $txtHeight = $fontMetrics->getFontHeight($fontFamily, 60);
                $textWidth = $fontMetrics->getTextWidth($watermark, $fontFamily, 60);
                $x = (($w - $textWidth) / 2);
                $y = (($h - $txtHeight) / 2);
                $canvas->page_text($x, $y, $watermark, $fontFamily, 90, array(
                    0,
                    0,
                    0
                ), 0, 0, -35);
            } else {
                $txtHeight = $fontMetrics->getFontHeight($fontFamily, 90);
                $textWidth = $fontMetrics->getTextWidth($watermark, $fontFamily, 90);
                $x = (($w - $textWidth) / 2);
                $y = (($h - $txtHeight) / 2);
                $canvas->page_text($x, $y, $watermark, $fontFamily, 120, array(
                    0,
                    0,
                    0
                ), 0, 0, -45);
            }
        }
    }
    
    
    if ($stream) {
        $pdf_string = $dompdf->output();
        if (!empty($attach)) {
            if (!empty($folder_name)) {
                $folder = "uploads/" . $folder_name . '/' . $filename . ".pdf";;
            } else {
                $folder = "uploads/" . $filename . ".pdf";;
            }
            file_put_contents($folder, $pdf_string);
        } else {
            $dompdf->stream($filename . ".pdf");
        }
    } else {
        return $dompdf->output();
    }
}

//require_once("dompdf/autoload.inc.php");
//use Dompdf\Dompdf;
//use Dompdf\Options;
//
//function pdf_create($html, $filename = '', $stream = TRUE, $set_paper = '', $attach = null, $folder_name = null)
//{
//
//    $options = new Options();
//    $options->set('defaultFont', 'dejavusanscondensed');
//    $dompdf = new Dompdf($options);
//    $dompdf->loadHtml($html,'UTF-8');
//
//    if ($set_paper != '') {
//        $dompdf->setPaper(array(0, 0, 900, 841), 'portrait');
//    } else {
//        $dompdf->setPaper("a4", "landscape");
//    }
//    $dompdf->render();
//    if ($stream) {
//        $pdf_string = $dompdf->output();
//        if (!empty($attach)) {
//            if (!empty($folder_name)) {
//                $folder = "uploads/" . $folder_name . '/' . $filename . ".pdf";;
//            } else {
//                $folder = "uploads/" . $filename . ".pdf";;
//            }
//            file_put_contents($folder, $pdf_string);
//        } else {
//            $dompdf->stream($filename . ".pdf");
//        }
//    } else {
//        return $dompdf->output();
//    }
//}
