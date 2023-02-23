<?php

namespace App\Controller;

use App\Repository\DrinkRepository;
use App\Repository\TacosRepository;
use App\Services\CartService;
use App\Repository\BurgerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    public static string $CART = CartService::CART_KEY;
    public static string $ITEM_QTY = 'qty_panier';

    #[Route('/addDrinkToCart/{data}', name: 'app_drink_to_cart')]
    public function addDrinkToCart(
        Request          $request,
        SessionInterface $session
    ): Response

    {
        $DataDecoded = json_decode($request->get("data"), true);
        $drinkID = $DataDecoded["drinkID"];

        dump($DataDecoded);

        return new JsonResponse([]);

    }

    #[Route('/addBurgerToCart/{data}', name: 'app_fries_to_cart')]
    public function addBurgerToCart(
        Request          $request,
        SessionInterface $session
    ): Response

    {
        $DataDecoded = json_decode($request->get("data"), true);
        $burgerId = $DataDecoded["burgerId"];
        $fries = $DataDecoded["fries"];


//        $session->remove(self::$CART);
        $panier = $session->get(self::$CART, []);

        $nbFries = $fries ? 1 : 0;

        if (!isset($panier['burger'][$burgerId])) {
            $panier['burger'][$burgerId] = [
                'qty' => 1,
                'nbFries' => $nbFries,
            ];
        } else {
            $panier['burger'][$burgerId] = [
                'qty' => $panier['burger'][$burgerId]['qty'] + 1,
                'nbFries' => $panier['burger'][$burgerId]['nbFries'] + $nbFries,
            ];
        }
        $session->set(self::$CART, $panier);

        $qty = 0;
        foreach ($panier['burger'] as $item) {
            $qty += $item['qty'];
        }
        $session->set(self::$ITEM_QTY, $qty);


        return new JsonResponse(['newQty' => $qty]);
    }


    #[Route('/panier', name: 'app_cart')]
    public function index(SessionInterface $session,
                          BurgerRepository $burgerRepository,
                          TacosRepository  $tacosRepository,
                          DrinkRepository  $drinkRepository,
    ): Response

    {
        $panierWithData = [];
        $panierWithData['tacos'] = [];
        $panierWithData['burger'] = [];
        $panierWithData['drink'] = [];

        $totalBurger = 0;
        $totalTacos = 0;
        $totalDrink = 0;
        $totalArticles = 0;
        $priceTotalFries = 0;

// ---------------------------SESSION CART BURGER et TACOS---------------------------------------------------

        $panier = $session->get('panier', []);

        foreach ($panier as $typeProduits => $produits) {

            if ($typeProduits === 'tacos') {
                foreach ($produits as $tacosId => $quantity) {
                    $tacos = $tacosRepository->find($tacosId);
                    $panierWithData['tacos'][] = [
                        'tacos' => $tacos,
                        'quantity' => $quantity,
                    ];
                    $tacosPrice = ($tacos->getSize()->getPrice()) + ($tacos->getMeat()->getPrice());

                    $totalTacos += $tacosPrice * $quantity;
                }
            } else if ($typeProduits === 'drink') {
                foreach ($produits as $drinkId => $quantity) {
                    $drink = $drinkRepository->find($drinkId);
                    $panierWithData['drink'][] = [
                        'drink' => $drink,
                        'quantity' => $quantity,
                    ];
                    $drinkPrice = $drink->getPrice() * $quantity;

                    $totalDrink += $drinkPrice * $quantity;
                }
            }
        }

        return $this->render('cart/index.html.twig', [
//            'itemsTotal' => $totalBurgerWithFries + $totalTacos + $totalDrink,
            'itemsBurger' => $panierWithData['burger'],
            'itemsTacos' => $panierWithData['tacos'],
            'itemsDrink' => $panierWithData['drink'],
            'totalBurger' => $totalBurger,
            'totalTacos' => $totalTacos,
            'totalDrink' => $totalDrink,
            'items' => $panier,
            'session' => $session,
            'totalArticles' => $totalArticles,
        ]);
    }

//    ---------------------- SUP BURGER--------------------------

    #[Route('/panier/remove/burger/{id}', name: 'cart_remove_burger')]
    public function removeBurger($id, SessionInterface $session, CartService $cartService): Response
    {
        $panier = $cartService->getPanier($session);

        if (!empty($panier['burger'][$id])) {
            unset($panier['burger'][$id]);
        }

        $session->set(CartService::CART_KEY, $panier);
        $session->set('qty_panier', $cartService->getBurgerQuantity($session));

        return $this->redirectToRoute('app_cart', []);
    }

//    ----------------------AJOUT et SUP TACOS--------------------------

    #[Route('/panier/remove/tacos/{id}', name: 'cart_remove_tacos')]
    public function removeTacos($id, SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);

        if (!empty($panier['tacos'][$id])) {
            unset($panier['tacos'][$id]);
        }
        $session->set('panier', $panier);

        return $this->redirectToRoute('app_cart', []);
    }

//    ---------------------- SUP DRINK --------------------------

    #[Route('/panier/remove/drink/{id}', name: 'cart_remove_drink')]
    public function removeDrink($id, SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);

        if (!empty($panier['drink'][$id])) {
            unset($panier['drink'][$id]);
        }
        $session->set('panier', $panier);

        return $this->redirectToRoute('app_cart', []);
    }

}



//-------------------------------------FLASHER------------------------------------------

//        $product = $burgerRepository->find($id);
//        $productName = $product->getName();
//
//        $panier = $session->get('panier', []);
//
//        if (!empty($panier[$id])) {
//            $panier[$id]++;
//
//            $this->addFlash('success', 'Le 🍔' . $productName . '🍔 est ajouté à votre panier  ');
//
//        } else {
//            $panier[$id] = 1;
//        }
//
//        $session->set('panier', $panier);
//        Flasher $flasher, SessionInterface $session, BurgerRepository $burgerRepository
