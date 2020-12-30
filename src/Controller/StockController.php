<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StockController extends AbstractController
{
    /**
     * @Route("/stock", name="stock")
     */
    public function view(): Response
    {
        return $this->render('stock/index.html.twig', [
            
        ]);
    }


    /**
     * @Route("/stock/add", name="stock_add")
     */
    public function add(): Response
    {
        $product = new Product;

        $form = $this->createForm(ProductType::class, $product);
        return $this->render('stock/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
