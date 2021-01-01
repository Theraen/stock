<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\Mailer;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StockController extends AbstractController
{

    private $em;
    private $productRepository;

    public function __construct(EntityManagerInterface $em, ProductRepository $productRepository)
    {
        $this->em = $em;
        $this->productRepository = $productRepository;
    }
    /**
     * @Route("/stock", name="stock")
     */
    public function view(): Response
    {

        $products = $this->productRepository->findAll();

        return $this->render('stock/index.html.twig', [
            'products' => $products,
        ]);
    }


    /**
     * @Route("/stock/add", name="stock_add")
     */
    public function add(Request $request): Response
    {
        $product = new Product;

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $product->setCreatedAt(new DateTime());
            $product->setShortDlc(0);

            $this->em->persist($product);
            $this->em->flush();

            return $this->redirectToRoute('stock');
        }

        return $this->render('stock/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/stock/update/{id}", name="stock_update")
     */
    public function update(Request $request, Product $product): Response
    {

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $product->setUpdatedAt(new DateTime());

            $this->em->flush();

            return $this->redirectToRoute('stock');
        }

        return $this->render('stock/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/stock/update/{plusOrMinus}/{id}", name="stock_update_qte")
     */
    public function updateQte(Request $request, Product $product): Response
    {

        $qte = $product->getQuantity();
        $params = $request->attributes->get('_route_params');
        $plusOrMinus = $params['plusOrMinus'];


        if($plusOrMinus == 'minus' && $qte == 1) {
            $this->em->remove($product);
            $this->addFlash("danger", "Le produit a bien été supprimé");
        }
        elseif($plusOrMinus == 'minus') {
            $product->setQuantity($qte-1);
            $this->addFlash("success", "Les quantités ont été modifiées");
        }
        elseif($plusOrMinus == 'plus') {
            $product->setQuantity($qte+1);
            $this->addFlash("success", "Les quantités ont été modifiées");
        }
        else {
            throw $this->createNotFoundException("Tu as entré de mauvais paramètre");
        }

        $this->em->flush();

        return $this->redirectToRoute('stock');

    }

    /**
     * @Route("/stock/checkDlc", name="stock_check_dlc")
     */
    public function checkDlc(Mailer $mailer): Response
    {

        $productsShortDLC = $this->productRepository->findAllShortDlc();
        $productsLimitDLC = $this->productRepository->findAllLimitDlc();

        

        foreach($productsShortDLC as $productShort) {
            $productShort->setShortDlc(1);
            
            $mailer->send(
               'Un produit a une DLC courte', 
                $_ENV['MAILER_SENDER'],
                $_ENV['MAILER_RECEVER'],
                'dlc',
                [
                    'name' => $productShort->getName(),
                    'capacity' => $productShort->getCapacity(),
                    'unitMeasure' => $productShort->getUnitMeasureCapacity(),
                    'quantity' => $productShort->getQuantity(),
                    'dlc' => $productShort->getDlc(),
                ]

                );
                $this->em->flush();
      
        }

        foreach($productsLimitDLC as $productLimit) {
            $productLimit->setShortDlc(2);

            
            $mailer->send(
               'Un produit a une DLC dépassé', 
                $_ENV['MAILER_SENDER'],
                $_ENV['MAILER_RECEVER'],
                'dlc',
                [
                    'name' => $productLimit->getName(),
                    'capacity' => $productLimit->getCapacity(),
                    'unitMeasure' => $productLimit->getUnitMeasureCapacity(),
                    'quantity' => $productLimit->getQuantity(),
                    'dlc' => $productLimit->getDlc(),
                ]

                );
                $this->em->flush();
      
        }

        
        return $this->redirectToRoute('stock');

    }

    /**
     * @Route("/stock/started/{id}", name="stock_started")
     */
    public function started(Product $product): Response
    {

        $qte = $product->getQuantity();
        $started = $product->getStarted();

        if($qte >= 1 && $started == 0) {
            $product->setStarted(1);
        } 
        elseif($qte > 1 && $started == 1) {
            $product->setQuantity($qte-1);
            $product->setStarted(0);
        }
        elseif($qte == 1 && $started == 1) {
            $this->em->remove($product);
        }
        else {
            throw $this->createNotFoundException("Tu as entré de mauvais paramètre");
        }

        $this->em->flush();

        return $this->redirectToRoute('stock');

    }

}
