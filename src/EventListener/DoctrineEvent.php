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

class DoctrineEvent implements EventSubscriber {

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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
            
            $log->setName('Un nouveau produit a été ajouté'); 
            $this->em->persist($log);

        }

        elseif($entity instanceof PictureStock) {
            $log->setName('Une nouvelle image a été ajouté'); 
            $this->em->persist($log);
        }

        elseif($entity instanceof Category) {
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
            
            $log->setName('Le produit '. $entity->getName() . ' a été supprimé'); 
            $this->em->persist($log);

        }

        elseif($entity instanceof PictureStock) {
            $log->setName('L\'image ' . $entity->getName() . ' a été supprimé'); 
            $this->em->persist($log);
        }

        elseif($entity instanceof Category) {
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
            
            $log->setName('Le produit '. $entity->getName() . ' a été modifié'); 
            $this->em->persist($log);

        }

        elseif($entity instanceof Category) {
            
            $log->setName('La categorie '. $entity->getName() . ' a été modifié'); 
            $this->em->persist($log);

        }

        
        $this->em->flush();
    }

}