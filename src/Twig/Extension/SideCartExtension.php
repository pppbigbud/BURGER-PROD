<?php

namespace App\Twig\Extension;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class SideCartExtension extends AbstractExtension
{

    public function __construct(
        private Environment  $environnement,
        private RequestStack $requestStack
    )
    {
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('SideCartCount', [$this, 'SideCartCount']),
        ];
    }

    public function SideCartCount(): string
    {
        $session = $this->requestStack->getSession();

        $totalTacosSideCart = 0;
        $totalDrinkSideCart = 0;

        $sessionQtityBurger = $session->get('qty_panier');
        $sessionPanier = $session->get('panier');

        if ($sessionPanier != []) {

            if (isset($sessionPanier['tacos']) && !empty($sessionPanier['tacos'])) {
                $totalTacosSideCart = array_sum($sessionPanier['tacos']);
            }

            if (isset($sessionPanier['drink']) && !empty($sessionPanier['drink'])) {
                $totalDrinkSideCart = array_sum($sessionPanier['drink']);
            }
        }

        return $this->environnement->render('partials/_cartSide.html.twig', [
            'totalBurgerSideCart' => $sessionQtityBurger,
            'totalTacosSideCart' => $totalTacosSideCart,
            'totalDrinkSideCart' => $totalDrinkSideCart,
        ]);

    }
}
