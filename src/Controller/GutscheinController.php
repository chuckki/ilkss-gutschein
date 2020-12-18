<?php

namespace App\Controller;

use App\Entity\Gutschein;
use App\Form\GutscheinType;
use App\Repository\GutscheinRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/gutschein")
 * @IsGranted("ROLE_USER")
 */
class GutscheinController extends AbstractController
{
    /**
     * @Route("/", name="gutschein_index", methods={"GET"})
     */
    public function index(GutscheinRepository $gutscheinRepository): Response
    {
        $allGutscheine = $gutscheinRepository->findAll();
        $sum = 0;
        foreach ($allGutscheine as $gutschein) {
            $sum += $gutschein->getGsBetrag();
        }
        return $this->render('gutschein/index.html.twig', [
            'gutscheins' => $allGutscheine,
            'sum' => $sum,
        ]);
    }

    /**
     * @Route("/new", name="gutschein_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $gutschein = new Gutschein();
        $gutschein->setUser($this->getUser());
        $form = $this->createForm(GutscheinType::class, $gutschein);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($gutschein);
            $entityManager->flush();

            return $this->redirectToRoute('gutschein_index');
        }

        return $this->render('gutschein/new.html.twig', [
            'gutschein' => $gutschein,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="gutschein_show", methods={"GET"})
     */
    public function show(Gutschein $gutschein): Response
    {
        return $this->render('gutschein/show.html.twig', [
            'gutschein' => $gutschein,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="gutschein_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Gutschein $gutschein): Response
    {
        $form = $this->createForm(GutscheinType::class, $gutschein);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gutschein_index');
        }

        return $this->render('gutschein/edit.html.twig', [
            'gutschein' => $gutschein,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="gutschein_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Gutschein $gutschein): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gutschein->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($gutschein);
            $entityManager->flush();
        }

        return $this->redirectToRoute('gutschein_index');
    }
}
