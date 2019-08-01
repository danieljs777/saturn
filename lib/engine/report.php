<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */

class Report
{

    const DEFAULT_ENGINE = "mpdf";
    
    private $title;
    private $margin;
    private $orientation;
    private $page_format;
    private $scale;
    private $template;
    private $filename;
        
    private $data;
    
    public function set_filename($filename)
    {
        $this->filename = $filename;        
    }
    
    public function set_title($title)
    {
        $this->title = $title;
    } 
    
    public function set_margin($margin)
    {
        $this->margin = $margin;
    }

    public function set_orientation($orientation)
    {
        $this->orientation = $orientation;
    }

    public function set_page_format($page_format)
    {
        $this->page_format = $page_format;
    }

    public function set_scale($scale)
    {
        $this->scale = $scale;
    }

    public function set_template($template)
    {
        $this->template = $template;
    }    
    
    public function __construct(&$data)
    {
        require_once PATH_ROOT . "/lib/system/system.php";
        require_once PATH_ROOT . "/lib/engine/render.php";
        
        $this->page_format    = @$_POST['page_format'];
        $this->margin         = @$_POST['margin'];
        $this->orientation    = @$_POST['orientation'];
        $this->scale          = @$_POST['scale'];
        
        if($this->scale === "custom")
            $this->scale = $_REQUEST['scale_percent'];        

        $this->data           = $data;
        

        
    }
    
    
    public function output($filename, $mode = 'D')
    {
        $this->set_filename($filename);
        
        switch(self::DEFAULT_ENGINE)
        {
        	case "dompdf" : $this->download_by_dompdf(); break;
        	case "mpdf"   : $this->download_by_mpdf($mode);   break;
        	case "tcpdf"  : $this->download_by_tcpdf();  break;
        	
        }
    }
    
    public function download_by_mpdf($mode = 'D')
    {
        ini_set('memory_limit', '192M');
    
        require_once(LIB_ROOT . '/components/mpdf54/mpdf.php');
    
        $mpdf = new mPDF('c', 'A4');
    
        $mpdf->SetDisplayMode('default');
    
        $mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
    
        $html = $this->load_content();
    
        $mpdf->WriteHTML($html);
    
        $mpdf->SetJS('
		function TwoPages() {
			this.layout="TwoColumnRight";
			this.zoomType = zoomtype.fitW;
		}
		function OnePage() {
			this.layout="SinglePage";
			this.zoom = 100;
		}
		');
    
        $mpdf->Output($this->filename . '.pdf', $mode);
        //exit;
    
    }
    
    public function download_by_tcpdf()
    {
    
        require_once(LIB_ROOT . '/components/tcpdf/config/lang/eng.php');
        require_once(LIB_ROOT . '/components/tcpdf/tcpdf.php');
    
        $pdf = new TCPDF($this->orientation, "mm", $this->page_format, true, 'UTF-8', false);
    
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
    
        $pdf->SetCreator(SYSTEM_NAME);
        $pdf->SetAuthor(SYSTEM_NAME);
        $pdf->SetTitle($this->title);
        $pdf->SetSubject($this->title);
    
        $pdf->SetMargins($this->margin, $this->margin, $this->margin);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
        //$pdf->setImageScale($scale);
    
        $pdf->setLanguageArray($l);
        $pdf->SetFont('helvetica', '', 10);
    
        $pdf->AddPage();
    
        $html = $this->load_content();
    
        $pdf->writeHTML($html, true, false, true, false, '');
    
        $pdf->lastPage();
    
        $pdf->Output($this->filename . '.pdf', 'FD');
    
    }
    
    
    public function download_by_dompdf()
    {
    
        require_once(LIB_ROOT . '/components/dompdf/dompdf_config.inc.php');
    
        ini_set('memory_limit', '192M');
         
        $dompdf = new DOMPDF();
        $html = $this->load_content();
        
        $dompdf->load_html($html);
        $dompdf->set_paper($this->page_format, $this->orientation);
        $dompdf->render();
    
        $dompdf->stream($this->filename . '.pdf');
    
    
    }
    
    public function load_content()
    {
        return Render::get_file(LIB_ROOT . 'templates/report.' . $this->template . '.php', $this->data, true);
    }
    
    
    
}

?>