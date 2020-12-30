<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
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
            
            $product->setCreatedAt(new DateTime());

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

}
