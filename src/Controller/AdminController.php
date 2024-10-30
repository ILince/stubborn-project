<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    // Route pour accéder à la page d'administration
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        // Rendu de la vue 'admin.html.twig' avec un paramètre de nom du contrôleur
        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
