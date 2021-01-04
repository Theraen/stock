<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/legal-notice", name="legal-notice")
     */
    public function legalNotice(): Response
    {
        return $this->render('main/legalNotice.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/terms", name="terms")
     */
    public function terms(): Response
    {
        return $this->render('main/terms.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

}
