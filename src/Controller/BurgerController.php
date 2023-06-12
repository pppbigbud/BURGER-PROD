<?php

namespace App\Controller;

use App\Entity\Burger;
use App\Entity\Frie;
use App\Form\BurgerType;
use App\Form\FrieType;
use App\Repository\BurgerRepository;
use App\Repository\FrieRepository;
use App\Services\CartService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/burgers')]
class BurgerController extends AbstractController
{
    #[Route('/', name: 'app_burger_index', methods: ['GET'])]
    public function index(BurgerRepository $burgerRepository): Response
    {

        return $this->render('burger/index.html.twig', [
            'burgers' => $burgerRepository->findAll(),
        ]);
    }
}
