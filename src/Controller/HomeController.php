<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use App\Entity\Screening;
use App\Repository\TicketRepository;
use App\Repository\ScreeningRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/{_locale}')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ScreeningRepository $screeningRepository): Response
    {
        $screenings = $screeningRepository->findScreeningDay();
        $movies = [];
        foreach ($screenings as $screening) {
            $movie = $screening->getMovie();
            $movies[$movie->getId()] = $movie;
        }
        
        return $this->render('home/index.html.twig', [
            'screenings' => $screenings,
            'movies' => $movies,
        ]);
    }

    #[Route('/screening/show/{id}', name: 'app_screening', methods: ['GET', 'POST'])]
    public function screening(Request $request, Screening $screening, TicketRepository $ticketRepository): Response
    {
        $form = $this->createForm(TicketType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();
            $availableTickets = $screening->getRoom()->getCapacity() - count($screening->getTickets());
            $resteTickets = $availableTickets - $datas['nbr_place'];
            if ($resteTickets < 0) {
                $this->addFlash('danger', 'There is not enough room left, for the number of places selected');
                return $this->redirectToRoute('app_screening', ['id' => $screening->getId()], Response::HTTP_SEE_OTHER);
            }

            for ($i = 1; $i <= $datas['nbr_place']; $i++) {
                $ticket = new Ticket();
                $ticket->setScreening($screening);
                $ticketRepository->save($ticket);
            }
            $ticketRepository->save($ticket, true);

            $message = sprintf('You paid for %d ticket(s) for %s at %s', $datas['nbr_place'], $screening->getMovie()->getTitle(), $screening->getStartTime()->format('H:i'));
            $this->addFlash('success', $message);
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('home/show_screening.html.twig', [
            'screening' => $screening,
            'form' => $form,
        ]);
    }
}
