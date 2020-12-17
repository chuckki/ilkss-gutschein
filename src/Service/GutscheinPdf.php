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
use Doctrine\ORM\EntityManagerInterface;
use setasign\Fpdi\PdfReader\PageBoundaries;
use setasign\Fpdi\Tcpdf\Fpdi;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GutscheinPdf
{
    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * CustomPdf constructor.
     *
     * @param string                 $rootDir
     * @param EntityManagerInterface $entityManager
     * @param FormFactoryInterface   $formFactory
     */
    public function __construct(string $rootDir, EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->rootDir = $rootDir;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    public function printGutscheinAction(Gutschein $gutschein)
    {

        $name = $gutschein->getGsName();
        $betrag = number_format($gutschein->getGsBetrag()/100,2,',','.');
        $bemerkung = $gutschein->getGsBemerkung();
        $datumTag = $gutschein->getGsDate()->format('d');
        $datumMonat = $gutschein->getGsDate()->format('m');
        $datumJahr = $gutschein->getGsDate()->format('Y');

        $pdf = new Fpdi();
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pageId = 0;
        try {
            $pdf->setSourceFile($this->rootDir.'/../ressources/DD_1704_IlovekitesurfSylt_Gutschein_02.pdf');
            $pageId = $pdf->importPage(1, PageBoundaries::MEDIA_BOX);
            $pageId = $pdf->importPage(1);
        } catch (\Exception $e) {
            // todo: something
        }

        $pdf->addPage('L');
//        $pdf->useImportedPage($pageId,0,0,210.1,98.0,true);
        $size = $pdf->getTemplateSize($pageId);

        $pdf->useImportedPage($pageId,0,0,$size['width'],$size['height'],true);

        //$pdf->useImportedPage($pageId,0,0,$size['width'],$size['height']);

        $border = 1;

        $pdf->SetFont('Courier', '', 16);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetTopMargin(0);
        $pdf->SetY(34);
        $pdf->SetX(142);
        $pdf->Cell(61, 5, $name, $border, 0, 'C');

        $pdf->SetY(44);
        $pdf->SetX(142);
        $pdf->Cell(61, 5, $betrag, $border, 0, 'C');

        $pdf->SetY(54);
        $pdf->SetX(142);
        $pdf->Cell(61, 5, $bemerkung, $border, 0, 'C');



        $pdf->setPageUnit('px');

        //$pdf->SetY(71);
        $pdf->SetY(200,true);
        $pdf->SetX(438);
        $pdf->Cell(30, 40, $datumTag, 1,0,"J",true,'',0,false,"C");
        //$pdf->Cell(30, 20, $datumTag, $border, 0, 'C');

        $pdf->SetY(71);
        $pdf->SetX(167);
        $pdf->Cell(12, 3, $datumMonat, $border, 0, 'C');


        $pdf->SetY(71);
        $pdf->SetX(180);
        $pdf->Cell(23, 3, $datumJahr, $border, 0, 'C');



        return new Response($pdf->Output('Gutschein_'.'_'.str_replace('.', '_', $name).'.pdf'), 200, ['Content-Type' => 'application/pdf']);
    }

}
