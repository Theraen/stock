<?php

namespace App\Controller;

use App\Data\SearchProduct;
use App\Entity\Product;
use App\Form\ProductType;
use App\Form\SearchProductType;
use App\Repository\ProductRepository;
use App\Service\Mailer;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class StockController extends AbstractController
{

    private $em;
    private $productRepository;
    private $translator;

    public function __construct(EntityManagerInterface $em, ProductRepository $productRepository, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->productRepository = $productRepository;
        $this->translator = $translator;
    }
    /**
     * @Route("/supply", name="stock")
     */
    public function view(Request $request, UserInterface $user): Response
    {
        $dataProduct = new SearchProduct();
        $dataProduct->page = $request->get('page', 1);
        
        $form = $this->createForm(SearchProductType::class, $dataProduct);
        
        $form->handleRequest($request);
        $products = $this->productRepository->findSearch($dataProduct, $user);

 

        return $this->render('stock/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/stock/add", name="stock_add")
     */
    public function add(Request $request, UserInterface $user): Response
    {
        $product = new Product;

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $product->setCreatedAt(new DateTime());
            $product->setShortDlc(0);
            $product->setStarted(0);
            $product->setUser($user);

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

        $messageUpdate = $this->translator->trans('Quantities have been changed');
        $messageBadParam = $this->translator->trans('You entered the wrong parameters');

        if($plusOrMinus == 'minus') {
            $product->setQuantity($qte-1);
            $this->addFlash("success", $messageUpdate);
        }
        elseif($plusOrMinus == 'plus') {
            $product->setQuantity($qte+1);
            $this->addFlash("success", $messageUpdate);
        }
        else {
            throw $this->createNotFoundException($messageBadParam);
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

        $messageShortDLC = $this->translator->trans('A product has a short shelf life');
        $messageExceededDLC = $this->translator->trans('A product has an outdated shelf life');

        

        foreach($productsShortDLC as $productShort) {
            $productShort->setShortDlc(1);
            
            $mailer->send(
               $messageShortDLC, 
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
               $messageExceededDLC, 
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
        $messageDelete = $this->translator->trans('The product has been deleted');
        $messageBadParam = $this->translator->trans('You entered the wrong parameters');

        if($qte >= 1 && $started == 0) {
            $product->setStarted(1);
        } 
        elseif($qte > 1 && $started == 1) {
            $product->setQuantity($qte-1);
            $product->setStarted(0);
            $this->addFlash("danger", $messageDelete);
        }
        elseif($qte == 1 && $started == 1) {
            $this->em->remove($product);
            $this->addFlash("danger", $messageDelete);
        }
        else {
            throw $this->createNotFoundException($messageBadParam);
        }

        $this->em->flush();

        return $this->redirectToRoute('stock');

    }

}
