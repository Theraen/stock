<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserChecker implements UserCheckerInterface
{

    private $translator;

    public function __construct(TranslatorInterface $translator) {

        $this->translator = $translator;

    }
    public function checkPreAuth(UserInterface $user)
    {
        if(!$user instanceof User) {
            return;
        }

    }

    public function checkPostAuth(UserInterface $user)
    {
        if(!$user instanceof User) {
            return;
        }

        if(!$user->isVerified()) { 
            $messageVerify = $this->translator->trans('Your email address is not validated. Please click on the link in the confirmation email.');
            throw new CustomUserMessageAccountStatusException($messageVerify);
        }
    }
}