<?php

namespace App\Controller\Admin;

use App\Entity\Drink;
use App\Form\DrinkType;
use App\Repository\DrinkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[isGranted("ROLE_ADMIN")]
class DrinkAdminController extends AbstractController
{
    public function __construct(
        private ParameterBagInterface  $parameterBag,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route('admin/drink/new', name: 'app_drink_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $drinkDirectoryPath = $this->parameterBag->get('drink_directory');
        $drink = new Drink();
        $form = $this->createForm(DrinkType::class, $drink);
        $form->handleRequest($request);

        $drinkImgFile = $form->get('imageFile')->getData();

        if ($drinkImgFile) {
            //exemple de nom de fichier : chat.jpg
            //require le nom du fichier sans l'extension => chat
            $originalFilename = pathinfo($drinkImgFile->getClientOriginalName(), PATHINFO_FILENAME);

            //Slug l'originalName, exemple: chat noir -> chat-noir
            $safeFilename = $slugger->slug($originalFilename);

            // vrai nom de fichier unique, exemple chat-noir -> chat-noir-fkljfdljfdlkj.jpg
            $newFileName = $safeFilename . '-' . uniqid() . '.' . $drinkImgFile->guessExtension();

            try {
                $drinkImgFile->move(
                    $drinkDirectoryPath,
                    $newFileName
                );

                $drink->setImagePath($newFileName);
                $this->entityManager->persist($drink);
                $this->entityManager->flush();

                return $this->redirectToRoute('app_drink_index');

            } catch (\Exception $e) {

            }
        }

        return $this->renderForm('admin/drink/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('admin/drink/{id}/edit', name: 'app_drink_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Drink $drink, DrinkRepository $drinkRepository): Response
    {
        $form = $this->createForm(DrinkType::class, $drink);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $drinkRepository->save($drink, true);

            return $this->redirectToRoute('app_drink_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/drink/edit.html.twig', [
            'drink' => $drink,
            'form' => $form,
        ]);
    }

    #[Route('admin/drink/{id}', name: 'app_drink_delete', methods: ['POST'])]
    public function delete(Request $request, Drink $drink, DrinkRepository $drinkRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $drink->getId(), $request->request->get('_token'))) {
            $drinkRepository->remove($drink, true);
        }

        return $this->redirectToRoute('app_drink_index', [], Response::HTTP_SEE_OTHER);
    }

}
