<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Form\CalendarType;
use App\Repository\BurgerRepository;
use App\Repository\CalendarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/calendar')]
class CalendarController extends AbstractController
{
    #[Route('/', name: 'app_calendar_index', methods: ['GET'])]
    public function index(BurgerRepository $burgerRepository, Session $session, CalendarRepository $calendarRepository): Response
    {
        // ---------------------------TEST AFFICHAGE CALENDRIER---------------------------------------------------
        $events = $calendarRepository->findAll();

        $rdvs = [];
        foreach ($events as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundcolor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
            ];

        }

        $data = json_encode($rdvs);
        // ---------------------------AJOUT BURGER CALENDAR---------------------------------------------------





        // ---------------------------SESSION SIDECART---------------------------------------------------

        $totalBurgerSideCart = 0;
        $totalTacosSideCart = 0;
        $totalDrinkSideCart = 0;

        $sessionPanier = ($session->get('panier'));

        if ($sessionPanier === null) {
            $totalArticles = 0;
        } else {
            if (isset($sessionPanier['tacos'])) {
                $totalTacosSideCart = array_sum($sessionPanier['tacos']);
            }
            if (isset($sessionPanier['burger'])) {
                $totalBurgerSideCart = array_sum($sessionPanier['burger']);
            }
            if (isset($sessionPanier['drink'])) {
                $totalDrinkSideCart = array_sum($sessionPanier['drink']);
            }
        }

        return $this->render('calendar/index.html.twig', [
            'data' => $data,
            'totalBurgerSideCart' => $totalBurgerSideCart,
            'totalTacosSideCart' => $totalTacosSideCart,
            'totalDrinkSideCart' => $totalDrinkSideCart,

        ]);
    }


    #[Route('/new', name: 'app_calendar_new', methods: ['GET', 'POST'])]
    public function new(Session $session, Request $request, CalendarRepository $calendarRepository): Response
    {
        $calendar = new Calendar();
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $calendarRepository->save($calendar, true);

            return $this->redirectToRoute('app_calendar_index', [], Response::HTTP_SEE_OTHER);
        }

        // ---------------------------SESSION SIDECART---------------------------------------------------

        $sessionPanier = ($session->get('panier'));

        if ($sessionPanier === null) {
            $totalArticles = 0;
        } else {
            if (isset($sessionPanier['tacos'])) {
                $totalTacosSideCart = array_sum($sessionPanier['tacos']);
            }
            if (isset($sessionPanier['burger'])) {
                $totalBurgerSideCart = array_sum($sessionPanier['burger']);
            }
            if (isset($sessionPanier['drink'])) {
                $totalDrinkSideCart = array_sum($sessionPanier['drink']);
            }
        }

        return $this->renderForm('calendar/new.html.twig', [
            'calendar' => $calendar,
            'form' => $form,
//            'totalBurgerSideCart' => $totalBurgerSideCart,
//            'totalTacosSideCart' => $totalTacosSideCart,
//            'totalDrinkSideCart' => $totalDrinkSideCart,
        ]);
    }


    #[Route('/{id}', name: 'app_calendar_show', methods: ['GET'])]
    public function show(Calendar $calendar): Response
    {
        return $this->render('calendar/show.html.twig', [
            'calendar' => $calendar,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_calendar_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Calendar $calendar, CalendarRepository $calendarRepository): Response
    {
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $calendarRepository->save($calendar, true);

            return $this->redirectToRoute('app_calendar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('calendar/edit.html.twig', [
            'calendar' => $calendar,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_calendar_delete', methods: ['POST'])]
    public function delete(Request $request, Calendar $calendar, CalendarRepository $calendarRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $calendar->getId(), $request->request->get('_token'))) {
            $calendarRepository->remove($calendar, true);
        }

        return $this->redirectToRoute('app_calendar_index', [], Response::HTTP_SEE_OTHER);
    }
}
