<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangerPasswordSecurityType;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserController extends AbstractController
{

    private $translator;
    private $em;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator) {

        $this->translator = $translator;
        $this->em = $em;
    }

    /**
     * @Route("/user/changeLocale/{locale}", name="user-change-locale")
     */
    public function changeLocale($locale, Request $request): Response
    {
        $request->getSession()->set('_locale', $locale);

        return $this->redirect($request->headers->get('referer'));

    }

    /**
     * @Route("/user/profile", name="user-profile")
     */
    public function profile(Request $request, UserInterface $user): Response
    {

        
        $form = $this->createForm(ProfileType::class, $user);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $this->em->flush();

            return $this->redirectToRoute('user-profil');
        }

        return $this->render('user/profile.html.twig', [
            'form' => $form->createView()
        ]);

    }

        /**
     * @Route("/user/security", name="user-security")
     */
    public function security(Request $request, UserInterface $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {

        $form = $this->createForm(ChangerPasswordSecurityType::class);
        $user = $this->getUser();
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            if(!password_verify($form->get('password')->getData(), $user->getPassword())) {
                $this->addFlash('', $this->translator->trans('Old password does not match.'));
                $this->redirectToRoute('user-security');
            } else {
                $newPassword = $form->get('newPassword')->getData();
                $hash = $passwordEncoder->encodePassword($user, $newPassword);

                $user->setPassword($hash);

                $this->em->flush();

                $this->addFlash('success', $this->translator->trans('Your password has been updated.'));
            }
            
            
        }

        return $this->render('user/security.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
