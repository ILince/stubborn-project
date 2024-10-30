<?php

namespace App\Controller;

use App\Entity\SweatShirt;
use App\Entity\Stock;
use App\Form\SweatShirtForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/sweatshirt')]
#[IsGranted('ROLE_ADMIN')] // Restreint l'accès aux utilisateurs ayant le rôle d'administrateur
class SweatShirtController extends AbstractController
{
    // Liste de tous les sweatshirts
    #[Route('/list', name: 'sweatshirt_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $sweatshirts = $entityManager->getRepository(SweatShirt::class)->findAll();

        return $this->render('sweat_shirt/list.html.twig', [
            'sweatshirts' => $sweatshirts,
        ]);
    }

    // Formulaire pour ajouter un nouveau sweatshirt
    #[Route('/add', name: 'app_sweatshirt_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sweatshirt = new SweatShirt();

        // Initialisation des stocks avec des tailles par défaut
        $sizes = ['XS', 'S', 'M', 'L', 'XL'];
        foreach ($sizes as $size) {
            $stock = new Stock();
            $stock->setSize($size);
            $stock->setQuantity(2);
            $sweatshirt->addStock($stock);
        }

        $form = $this->createForm(SweatShirtForm::class, $sweatshirt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer le téléchargement de l'image
            $file = $form->get('thumbnailFile')->getData();
            if ($file) {
                $filename = uniqid() . '.' . $file->guessExtension();
                $file->move($this->getParameter('kernel.project_dir') . '/public/sweat_shirt/images', $filename);
                $sweatshirt->setThumbnail($filename);
            } else {
                $sweatshirt->setThumbnail(null);
            }

            $entityManager->persist($sweatshirt);
            $entityManager->flush();
            $this->addFlash('success', 'Le Sweatshirt a été ajouté avec succès !');

            return $this->redirectToRoute('app_sweatshirt_add');
        }

        // Afficher la liste des sweatshirts déjà ajoutés
        $sweatshirts = $entityManager->getRepository(SweatShirt::class)->findAll();

        return $this->render('sweat_shirt/add.html.twig', [
            'form' => $form->createView(),
            'sweatshirts' => $sweatshirts,
        ]);
    }

    // Suppression d'un sweatshirt par son ID
    #[Route('/delete/{id}', name: 'sweatshirt_delete', methods: ['DELETE'])]
    public function delete(Request $request, SweatShirt $sweatshirt, EntityManagerInterface $entityManager): Response
    {
        // Validation du jeton CSRF pour la sécurité
        if ($this->isCsrfTokenValid('delete' . $sweatshirt->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sweatshirt);
            $entityManager->flush();
            $this->addFlash('success', 'Le Sweatshirt a été supprimé avec succès !');
        } else {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        return $this->redirectToRoute('app_sweatshirt_add');
    }

    // Modification d'un sweatshirt existant
    #[Route('/edit/{id}', name: 'sweatshirt_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SweatShirt $sweatshirt, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SweatShirtForm::class, $sweatshirt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'image si un fichier est fourni
            $file = $form->get('thumbnailFile')->getData();
            if ($file) {
                $filename = $sweatshirt->getId() . '.' . $file->guessExtension();
                $file->move($this->getParameter('kernel.project_dir') . '/public/sweat_shirt/images', $filename);
                $sweatshirt->setThumbnail($filename);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Le Sweatshirt a été modifié avec succès !');

            return $this->redirectToRoute('app_sweatshirt_add');
        }

        return $this->render('sweat_shirt/edit.html.twig', [
            'form' => $form->createView(),
            'sweatshirt' => $sweatshirt,
        ]);
    }
}
