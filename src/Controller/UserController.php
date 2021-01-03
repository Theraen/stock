<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/changeLocale/{locale}", name="user-change-locale")
     */
    public function changeLocale($locale, Request $request): Response
    {
        $request->getSession()->set('_locale', $locale);

        return $this->redirect($request->headers->get('referer'));

    }
}
