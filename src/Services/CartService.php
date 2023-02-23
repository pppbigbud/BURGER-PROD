<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CartService
{
    const CART_KEY = "panier";

    public function getPanier(SessionInterface $session) : array
    {
        return $session->get(self::CART_KEY, []);
    }

    public function addBurgerToCart(SessionInterface $session, string $id): void
    {
        $panier = $this->getPanier($session);

        if (!empty($panier['burger'][$id])) {
            $panier['burger'][$id]++;
        } else {
            $panier['burger'][$id] = 1;
        }
        $session->set(self::CART_KEY, $panier);
        dump($panier['burger']);
    }

    public function addTacos(SessionInterface $session, string $id): void
    {
        $panier = $this->getPanier($session);

        if (!empty($panier['tacos'][$id])) {
            $panier['tacos'][$id]++;

        } else {

            $panier['tacos'][$id] = 1;
        }

        $session->set(self::CART_KEY, $panier);
    }

    public function addDrink(SessionInterface $session, string $id): void
    {
        $panier = $this->getPanier($session);

        if (!empty($panier['drink'][$id])) {
            $panier['drink'][$id]++;

        } else {

            $panier['drink'][$id] = 1;
        }

        $session->set(self::CART_KEY, $panier);
    }

    /**
     * Return how many burgers are currently in session Cart
     * @param SessionInterface $session
     * @return int
     */
    public function getBurgerQuantity(SessionInterface $session): int
    {
        $panier = $this->getPanier($session);
        $qty = 0;

        foreach($panier['burger'] as $burger) {
            $qty += $burger['qty'];
        }
        return $qty;
    }

//    public function addFrie(SessionInterface $session): void
//    {
//        $panier = $session->get('panier', []);
//
//        if (!empty($panier['frie'])) {
//            $panier['frie']++;
//
//        } else {
//
//            $panier['frie'] = 1;
//        }
//
//        $session->set('panier', $panier);
//    }


}