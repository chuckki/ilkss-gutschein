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
use setasign\Fpdi\Tfpdf\FpdfTpl;
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
        $betrag = number_format($gutschein->getGsBetrag(),2,',','.');
        $bemerkung = $gutschein->getGsBemerkung();
        $datumTag = $gutschein->getGsDate()->format('d');
        $datumMonat = $gutschein->getGsDate()->format('m');
        $datumJahr = $gutschein->getGsDate()->format('Y');

        $pdf = new Fpdi();
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pageId = 0;
        try {
            $pdf->setSourceFile($this->rootDir.'/../ressources/Gutschein-2.pdf');
            $pageId = $pdf->importPage(1, PageBoundaries::MEDIA_BOX);
            $pageId = $pdf->importPage(1);
        } catch (\Exception $e) {
            // todo: something
        }

        $pdf->addPage('L');
        $size = $pdf->getTemplateSize($pageId);

        $pdf->useImportedPage($pageId,0,0,$size['width'],$size['height'],true);

        $border = 0;

        //$pdf->SetFont('Courier', '', 16);
        $pdf->SetFont('promptlight', '', 16);
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
        $pdf->SetY(205,true);
        $pdf->SetX(442);
        $pdf->Cell(30, 20, $datumTag, 0, 0, 'L');

        $pdf->SetY(205,true);
        $pdf->SetX(480);
        $pdf->Cell(30, 20, $datumMonat, 0, 0, 'L');

        $pdf->SetY(205,true);
        $pdf->SetX(520);
        $pdf->Cell(30, 20, $datumJahr, 0, 0, 'L');

        $pdf->Image($this->rootDir.'/../ressources/logo.png',55,10,130,'','PNG');

        return new Response($pdf->Output('Gutschein'.'_'.str_replace('.', '_', $name).'.pdf'), 200, ['Content-Type' => 'application/pdf']);
    }

}
