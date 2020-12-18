<?php

namespace App\Controller;

use App\Entity\Gutschein;
use App\Repository\GutscheinRepository;
use App\Service\GutscheinPdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('gutschein_index');
    }

    /**
     * @Route("/printa/{id}", name="gutschein_pdf")
     */
    public function printPdf(Gutschein $gutschein, GutscheinPdf $gutscheinPdf): Response
    {
        return $gutscheinPdf->printGutscheinAction($gutschein);
    }

    /**
     * @Route("/print/{hash}", name="gutschein_pdf_hash")
     */
    public function printPdfByHash(string $hash, GutscheinRepository $gutscheinRepository, GutscheinPdf $gutscheinPdf): Response
    {
        $gutschein = $gutscheinRepository->findOneBy(['hash' => $hash]);
        return $gutscheinPdf->printGutscheinAction($gutschein);
    }

}
