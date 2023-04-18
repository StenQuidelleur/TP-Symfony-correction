<?php

namespace App\Controller;

use App\Entity\Screening;
use App\Form\ScreeningType;
use App\Repository\ScreeningRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/screening')]
class ScreeningController extends AbstractController
{
    #[Route('/', name: 'app_screening_index', methods: ['GET'])]
    public function index(ScreeningRepository $screeningRepository): Response
    {
        return $this->render('screening/index.html.twig', [
            'screenings' => $screeningRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_screening_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ScreeningRepository $screeningRepository): Response
    {  
        $screening = new Screening();
        $form = $this->createForm(ScreeningType::class, $screening);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $screening->setEndTime(clone $screening->getStartTime());
            $screening->setEndTime($screening->getEndTime()->modify("+{$screening->getMovie()->getDuration()} minutes"));
            $screeningRepository->save($screening, true);

            return $this->redirectToRoute('app_screening_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('screening/new.html.twig', [
            'screening' => $screening,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_screening_show', methods: ['GET'])]
    public function show(Screening $screening): Response
    {
        return $this->render('screening/show.html.twig', [
            'screening' => $screening,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_screening_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Screening $screening, ScreeningRepository $screeningRepository): Response
    {
        $form = $this->createForm(ScreeningType::class, $screening);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $screeningRepository->save($screening, true);

            return $this->redirectToRoute('app_screening_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('screening/edit.html.twig', [
            'screening' => $screening,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_screening_delete', methods: ['POST'])]
    public function delete(Request $request, Screening $screening, ScreeningRepository $screeningRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$screening->getId(), $request->request->get('_token'))) {
            $screeningRepository->remove($screening, true);
        }

        return $this->redirectToRoute('app_screening_index', [], Response::HTTP_SEE_OTHER);
    }
}
