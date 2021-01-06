<?php

namespace App\EventListener;

use App\Entity\Category;
use App\Entity\Log;
use App\Entity\PictureStock;
use App\Entity\Product;
use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DoctrineEvent implements EventSubscriber {

    private $em;
    private $user;


    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function getSubscribedEvents()
    {
        return array('postPersist', 'postUpdate', 'postRemove');
    }

    public function postPersist(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        $log = new Log;
        $log->setType('add');
        $log->setCreatedAt(new DateTime());



        if($entity instanceof Product) {
            $log->setUser($this->user);
            $log->setName('Un nouveau produit a été ajouté');
            
            $this->em->persist($log);

        }

        elseif($entity instanceof PictureStock) {
            $log->setUser($this->user);
            $log->setName('Une nouvelle image a été ajouté'); 
            $this->em->persist($log);
        }

        elseif($entity instanceof Category) {
            $log->setUser($this->user);
            $log->setName('Une nouvelle categorie a été ajouté'); 
            $this->em->persist($log);
        }
        
        $this->em->flush();
    }

    public function postRemove(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        $log = new Log;
        $log->setType('delete');
        $log->setCreatedAt(new DateTime());
        
        if($entity instanceof Product) {
            
            $log->setUser($this->user);
            $log->setName('Le produit '. $entity->getName() . ' a été supprimé'); 
            $this->em->persist($log);

        }

        elseif($entity instanceof PictureStock) {
            $log->setUser($this->user);
            $log->setName('L\'image ' . $entity->getName() . ' a été supprimé'); 
            $this->em->persist($log);
        }

        elseif($entity instanceof Category) {
            $log->setUser($this->user);
            $log->setName('La categorie ' . $entity->getName() . ' a été supprimé'); 
            $this->em->persist($log);
        }
        
        $this->em->flush();
    }

    public function postUpdate(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        $log = new Log;
        $log->setType('update');
        $log->setCreatedAt(new DateTime());
        

        

        if($entity instanceof Product) {
            $log->setUser($this->user);
            $log->setName('Le produit '. $entity->getName() . ' a été modifié'); 
            $this->em->persist($log);

        }

        elseif($entity instanceof Category) {
            $log->setUser($this->user);
            $log->setName('La categorie '. $entity->getName() . ' a été modifié'); 
            $this->em->persist($log);

        }

        
        $this->em->flush();
    }

}