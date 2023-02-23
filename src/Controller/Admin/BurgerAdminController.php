<?php

namespace App\Controller\Admin;

use App\Entity\Burger;
use App\Form\BurgerType;
use App\Repository\BurgerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[isGranted("ROLE_ADMIN")]
class BurgerAdminController extends AbstractController
{
    public function __construct(
        private ParameterBagInterface  $parameterBag,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route('admin/burger/new', name: 'app_burger_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $burgerDirectoryPath = $this->parameterBag->get('burger_directory');
        $burger = new Burger();
        $form = $this->createForm(BurgerType::class, $burger);
        $form->handleRequest($request);

        $burgerImgFile = $form->get('imageFile')->getData();

        if ($burgerImgFile) {
            //exemple de nom de fichier : chat.jpg
            //require le nom du fichier sans l'extension => chat
            $originalFilename = pathinfo($burgerImgFile->getClientOriginalName(), PATHINFO_FILENAME);

            //Slug l'originalName, exemple: chat noir -> chat-noir
            $safeFilename = $slugger->slug($originalFilename);

            // vrai nom de fichier unique, exemple chat-noir -> chat-noir-fkljfdljfdlkj.jpg
            $newFileName = $safeFilename . '-' . uniqid() . '.' . $burgerImgFile->guessExtension();

            try {
                $burgerImgFile->move(
                    $burgerDirectoryPath,
                    $newFileName
                );

                $burger->setImagePath($newFileName);
                $this->entityManager->persist($burger);
                $this->entityManager->flush();

                return $this->redirectToRoute('app_burger_index');

            } catch (\Exception $e) {

            }
        }

        return $this->renderForm('admin/burger/new.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('admin/burger/{id}/edit', name: 'app_burger_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Burger $burger, BurgerRepository $burgerRepository, string $id): Response
    {
        $sandwich = $burgerRepository->find($id);

        $form = $this->createForm(BurgerType::class, $burger);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $burgerRepository->save($burger, true);

            return $this->redirectToRoute('app_burger_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/burger/edit.html.twig', [
            'sandwich' => $sandwich,
            'burger' => $burger,
            'form' => $form,
        ]);
    }

    #[Route('admin/burger/{id}', name: 'app_burger_delete', methods: ['POST'])]
    public function delete(Request $request, Burger $burger, BurgerRepository $burgerRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $burger->getId(), $request->request->get('_token'))) {
            $burgerRepository->remove($burger, true);
        }

        return $this->redirectToRoute('app_burger_index', [], Response::HTTP_SEE_OTHER);
    }
}
