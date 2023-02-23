<?php

namespace App\Controller;

use App\Entity\Tacos;
use App\Form\TacosType;
use App\Repository\TacosRepository;
use App\Services\CartService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class TacosController extends AbstractController
{

    #[Route('/tacos/', name: 'app_tacos_index')]
    public function index(Request          $request,
                          SessionInterface $session,
                          TacosRepository  $tacosRepository,
                          CartService      $cartServices): Response
    {
        $tacos = new Tacos();
        $form = $this->createForm(TacosType::class, $tacos);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $tacosRepository->save($tacos, true);
            $cartServices->addTacos($session, $tacos->getId());
            return $this->redirectToRoute('app_tacos_index');
        }

        return $this->render('tacos/index.html.twig', [
            'form' => $form->createView(),
            'tacos' => $tacos,
        ]);
    }
}
