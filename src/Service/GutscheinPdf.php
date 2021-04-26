<?php
/*
 * This file is part of backend-hvb.
 *
 * (c) Dennis Esken - callme@projektorientiert.de
 *
 * @license NO LICENSE - So dont use it without permission (it could be expensive..)
 */

namespace App\Service;

use App\Entity\Gutschein;
use Exception;
use setasign\Fpdi\Tcpdf\Fpdi;
use Symfony\Component\HttpFoundation\Response;

class GutscheinPdf
{
    /**
     * @var string
     */
    private $rootDir;

    /**
     * CustomPdf constructor.
     *
     * @param string $rootDir
     */
    public function __construct(
        string $rootDir
    ) {
        $this->rootDir = $rootDir;
    }

    public function printGutscheinAction(Gutschein $gutschein): Response
    {

        $name      = $gutschein->getGsName();
        $betrag    = number_format($gutschein->getGsBetrag(), 2, ',', '.');
        $bemerkung = $gutschein->getGsNummer();
        $pdf = $this->initialPDF($gutschein);
        $pdf = $this->setTitle($pdf, $gutschein);
        /*
                $pdf->SetFont('promptlight', '', 16);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->SetTopMargin(0);
                $pdf->SetY(34);
                $pdf->SetX(142);
        //        $pdf->Cell(61, 5, $name, $border, 0, 'C');
                $pdf->SetY(44);
                $pdf->SetX(144);
        //        $pdf->Cell(58, 5, $betrag, $border, 0, 'C');
                $pdf->SetY(54);
                $pdf->SetX(144);
        //        $pdf->Cell(58, 5, $bemerkung, $border, 0, 'C');
                $pdf->SetFont('promptlight', '', 13);
                $datum = "Ausgestellt am ".$gutschein->getGsDate()->format('d.m.Y');
                $pdf->SetY(64);
                $pdf->SetX(144);
        //        $pdf->Cell(58, 5, $datum, $border, 0, 'C');
                $pdf->SetFont('promptlight', '', 16);
        */
        $pdf->setPageUnit('px');
        $pdf->SetFont('promptlight', '', 10);
        $gutscheinCode = "Gutscheincode: ".$gutschein->getGsNummer();
        $pdf->SetY(235, true);
        $pdf->SetX(492);
        $pdf->Cell(61, 5, $gutscheinCode, 0, 0, 'R');
        $pdf->SetY(0, true, true);
        $pdf->SetX(532);
//        $pdf->Cell(61, 5, $gutscheinCode, 0, 0, 'R');
        $pdf = $this->printFooter($pdf, $gutschein);

        return new Response(
            $pdf->Output('Gutschein'.'_'.str_replace('.', '_', $name).'.pdf'),
            200,
            ['Content-Type' => 'application/pdf']
        );
    }


    private function initialPDF(Gutschein $gutschein): Fpdi
    {

        $pdf = new Fpdi();
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->setFooterMargin(0);
        $pdf->setPageOrientation('L', "off", 0);
        $pdf->SetMargins(0, 0, 0);
        $pageId = 0;

        $wingImg = 'gutschein-wing.pdf';
        $imgSrc = $kiteImg = 'gutschein-kite.pdf';

        switch ($gutschein->getKurstyp()){
            case 2:
            case 3:
            case 4:
            case 7:
                $imgSrc = $kiteImg;
                break;
            case 5:
            case 6:
                $imgSrc = $wingImg;
                break;
        }

        try {
            $pdf->setSourceFile($this->rootDir.'/../ressources/'.$imgSrc);
            $pageId = $pdf->importPage(1);
        } catch (Exception $e) {
            // todo: something
        }
        $pdf->AddPage('L');
        $size = $pdf->getTemplateSize($pageId);
        $pdf->useImportedPage($pageId, 0, 0, $size['width'], $size['height'], true);
        $pdf = $this->setBoxes($pdf);
        $pdf->SetTextColor(255, 255, 255);
        // write Text
        $pdf->SetFont('prompt', '', 31);
        $pdf->SetY(12);
        $pdf->SetX(142);
        $pdf->Cell(61, 5, "GUTSCHEIN", 0, 0, 'C');
        $pdf = $this->printImage($pdf, $gutschein);

        return $pdf;
    }


    private function setTitle(Fpdi $pdf, Gutschein $gutschein): Fpdi
    {
        $pdf->SetY(24, true, true);
        $pdf->SetX(142);
        switch ($gutschein->getKurstyp()) {
            case 2:
                $pdf->SetFont('prompt', '', 18);
                $pdf->Cell(61, 5, "Kitesurf-Grundkurs", 0, 0, 'C', false, '', 2);
                break;
            case 3:
                $pdf->SetFont('prompt', '', 14);
                $pdf->Cell(61, 5, "Kitesurf-Schnupperkurs", 0, 0, 'C', false, '', 2);
                break;
            case 4:
                $pdf->SetFont('prompt', '', 14);
                $pdf->Cell(61, 5, "Kitesurf-Aufsteigerkurs", 0, 0, 'C', false, '', 2);
                break;
            case 6:
                $pdf->SetFont('prompt', '', 14);
                $pdf->Cell(61, 5, "Wingsurf-Aufsteigerkurs", 0, 0, 'C', false, '', 2);
                break;
            case 5:
                $pdf->SetFont('prompt', '', 18);
                $pdf->Cell(61, 5, "Wingsurf-Grundkurs", 0, 0, 'C', false, '', 2);
                break;
            case 7:
                $pdf->SetFont('prompt', '', 18);
                $pdf->Cell(61, 5, "3h Kitesurftraining", 0, 0, 'C', false, '', 2);
                break;
        }

        return $pdf;
    }

    private function setBoxes(Fpdi $pdf): Fpdi
    {
        // build transparent boxes
        $pdf->setAlpha(0.2);
/*
        $pdf->SetY(24, true, true);
        $pdf->SetX(143);
        $pdf->setColor('fill', 255, 255, 255);
        $pdf->Cell(59, 7, '', 0, 0, 'C', true);
*/
        $pdf->SetY(34, true, true);
        $pdf->SetX(143);
        $pdf->setColor('fill', 255, 255, 255);
        $pdf->Cell(59, 7, '', 0, 0, 'C', true);
        $pdf->SetY(44);
        $pdf->SetX(143);
        $pdf->setColor('fill', 255, 255, 255);
        $pdf->Cell(59, 35, '', 0, 0, 'L', true);
        $pdf->setAlpha(1);

        return $pdf;
    }

    private function printImage(Fpdi $pdf, Gutschein $gutschein): Fpdi
    {

        switch ($gutschein->getKurstyp()){
            case 2:
            case 3:
            case 4:
            case 7:
                return $pdf;
        }

        $pdf->setPageUnit('px');
        $pdf->Image($this->rootDir.'/../ressources/SH_Wing_Surf-02.png', 55, 10, 130, '', 'PNG');
        //$pdf->Image($this->rootDir.'/../ressources/logo-weiss.png', 60, 10, 120, '', 'PNG');
        $pdf->setPageUnit('mm');

        return $pdf;
    }

    private function printFooter(Fpdi $pdf, Gutschein $gutschein): Fpdi
    {
        $pdf->setPageUnit('px');
        // add bottom
        $pdf->SetY(260.68, true, true);
        $pdf->SetX(0);
        $pdf->SetFont('promptlight', '', 9);
        $pdf->setColor('fill', 0, 0, 0);
        $pdf->Cell(595.2, 17, '', 0, 0, 'C', true);
        $width  = 595.2;
        $height = 15;
        $y      = 260.68;
        $final = strtotime("+3 year", $gutschein->getGsDate()->getTimestamp());
        $final = date("d.m.Y", $final);
        //$text = "Dieser Gutschein ist 3 Jahre gültig (bis " . $final . ")";
        $text = "  Gültig bis ".$final;
        $pdf->SetY($y, true, true);
        $pdf->SetX(0);
        $pdf->Cell($width, $height, $text, 0, 0, 'L');
        $pdf->SetY($y, true, true);
        $pdf->SetX(0);
        $pdf->Cell($width, $height, $gutschein->getUrlString() , 0, 0, 'C');
        $pdf->SetY($y, true, true);
        $pdf->SetX(0);
        $pdf->Cell($width, $height, ' 0173 396 666 9  ', 0, 0, 'R');

        return $pdf;
    }

    private function showGrid(Fpdi $pdf): Fpdi
    {

        /*
        height =  277.68
        width =  595.2
         */
//        $pdf->setPageUnit('px');
        $pheight = $pdf->getPageHeight();
        $pwidth  = $pdf->getPageWidth();
        //dump($pheight);
        //dump($pwidth);
        //die;
        //277.68 - CROP_BOX
        //     die;
        /*
                $tm = $pdf->getCellMargins();
                $tm = $pdf->getMargins();
                dump($tm);
                $pdf->SetMargins(0, 0, 0);
                $tm = $pdf->getMargins();

                dump($tm);
                die;
        */
        $pdf->setFooterMargin(0);
        $height = 0;
        while ($height < $pheight) {
            $pdf->SetY($height, true);
            $pdf->SetX(10);
            $pdf->SetFont('promptlight', '', 5);
            $pdf->Cell(50, 10, $height.'  x 50', 1, 0, 'L');
            $height += 10;
        }

        return $pdf;
    }

    private function setDatumOldFormat(Fpdi $pdf, \DateTime $dateValue): Fpdi
    {
        $datumTag   = $dateValue->format('d');
        $datumMonat = $dateValue->format('m');
        $datumJahr  = $dateValue->format('Y');
        $pdf->setPageUnit('px');
        $pdf->SetY(205, true);
        $pdf->SetX(442);
        $pdf->Cell(30, 20, $datumTag, 0, 0, 'L');
        $pdf->SetY(205, true);
        $pdf->SetX(480);
        $pdf->Cell(30, 20, $datumMonat, 0, 0, 'L');
        $pdf->SetY(205, true);
        $pdf->SetX(520);
        $pdf->Cell(30, 20, $datumJahr, 0, 0, 'L');

        return $pdf;
    }

    private function setBoxes2(Fpdi $pdf): Fpdi
    {
        // build transparent boxes
        $pdf->setAlpha(0.2);
        $pdf->SetY(34, true);
        $pdf->SetX(144);
        $pdf->setColor('fill', 255, 255, 255);
        $pdf->Cell(58, 7, '', 0, 0, 'C', true);
        $pdf->SetY(44);
        $pdf->SetX(144);
        $pdf->setColor('fill', 255, 255, 255);
        $pdf->Cell(58, 7, '', 0, 0, 'L', true);
        $pdf->SetY(54);
        $pdf->SetX(144);
        $pdf->setColor('fill', 255, 255, 255);
        $pdf->Cell(58, 7, '', 0, 0, 'L', true);
        $pdf->setAlpha(1);

        return $pdf;
    }

}
