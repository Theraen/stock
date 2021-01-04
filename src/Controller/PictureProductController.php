<?php

namespace App\Controller;

use App\Data\SearchPicture;
use App\Entity\PictureStock;
use App\Form\PictureProductType;
use App\Form\SearchPictureType;
use App\Repository\PictureStockRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PictureProductController extends AbstractController
{
    private $em;
    private $pictureStockRepository;
    private $appKernel;
    private $translator;

    public function __construct(EntityManagerInterface $em, PictureStockRepository $pictureStockRepository, KernelInterface $appKernel, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->pictureStockRepository = $pictureStockRepository;
        $this->appKernel = $appKernel;
        $this->translator = $translator;
    }
    /**
     * @Route("/picture", name="picture_product")
     */
    public function view(Request $request, UserInterface $user): Response
    {
        $dataPicture = new SearchPicture();
        $dataPicture->page = $request->get('page', 1);

        $form = $this->createForm(SearchPictureType::class, $dataPicture);
        
        $form->handleRequest($request);

        $pictures = $this->pictureStockRepository->findSearch($dataPicture, $user);


        return $this->render('picture_product/index.html.twig', [
            'pictures' => $pictures,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/picture/add", name="picture_product_add")
     */
    public function add(Request $request, SluggerInterface $slugger, UserInterface $user): Response
    {
        $picture = new PictureStock;

        $form = $this->createForm(PictureProductType::class, $picture);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $picture->setCreatedAt(new DateTime());

            $file = $form->get('picture')->getData();
            
            if($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('pictureProduct_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $picture->setPicture($newFilename);
            }

            $picture->setUser($user);

            $this->em->persist($picture);
            $this->em->flush();

            return $this->redirectToRoute('picture_product');
        }

        return $this->render('picture_product/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/picture/delete/{id}", name="picture_product_delete")
     */
    public function delete(Request $request, PictureStock $pictureStock): Response
    {

        $messageDelete = $this->translator->trans('The image has been deleted');
        $projectDir = $this->appKernel->getProjectDir();
        unlink($projectDir . '/public/uploads/picture/product/'.$pictureStock->getPicture());
        $this->em->remove($pictureStock);
        $this->addFlash("danger", $messageDelete);

        $this->em->flush();

        return $this->redirectToRoute('picture_product');

    }


}
