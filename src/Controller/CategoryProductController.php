<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryProductType;
use App\Repository\CategoryRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CategoryProductController extends AbstractController
{

    private $em;
    private $categoryRepository;
    private $translator;

    public function __construct(EntityManagerInterface $em, CategoryRepository $categoryRepository, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->categoryRepository = $categoryRepository;
        $this->translator = $translator;
    }
    
    /**
     * @Route("/category", name="category_product")
     */
    public function view(): Response
    {

        $categories = $this->categoryRepository->findAll();

        return $this->render('category_product/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    
    /**
     * @Route("/category/add", name="category_product_add")
     */
    public function add(Request $request): Response
    {
        $category = new Category;

        $form = $this->createForm(CategoryProductType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setCreatedAt(new DateTime());

            $this->em->persist($category);
            $this->em->flush();

            return $this->redirectToRoute('category_product');
        }

        return $this->render('category_product/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/category/update/{id}", name="category_product_update")
     */
    public function update(Request $request, Category $category): Response
    {

        $form = $this->createForm(CategoryProductType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $category->setUpdatedAt(new DateTime());

            $this->em->flush();

            return $this->redirectToRoute('category_product');
        }

        return $this->render('category_product/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/category/delete/{id}", name="category_product_delete")
     */
    public function delete(Request $request, Category $category): Response
    {

        $messageDelete = $this->translator->trans('The category has been deleted');

        $this->em->remove($category);
        $this->addFlash("danger", $messageDelete);

        $this->em->flush();

        return $this->redirectToRoute('category_product');

    }

}
