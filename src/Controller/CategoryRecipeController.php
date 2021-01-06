<?php

namespace App\Controller;

use App\Entity\CategoryRecipe;
use App\Form\CategoryRecipeType;
use App\Repository\CategoryRecipeRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CategoryRecipeController extends AbstractController
{
    private $em;
    private $categoryRecipeRepository;
    private $translator;

    public function __construct(EntityManagerInterface $em, CategoryRecipeRepository $categoryRecipeRepository, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->categoryRecipeRepository = $categoryRecipeRepository;
        $this->translator = $translator;
    }
    
    /**
     * @Route("/categoryRecipe", name="category_recipe")
     */
    public function view(): Response
    {

        $categories = $this->categoryRecipeRepository->findAll();

        return $this->render('category_recipe/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    
    /**
     * @Route("/categoryRecipe/add", name="category_recipe_add")
     */
    public function add(Request $request, UserInterface $user): Response
    {
        $category = new CategoryRecipe;

        $form = $this->createForm(CategoryRecipeType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setCreatedAt(new DateTime());

            $category->setUser($user);

            $this->em->persist($category);
            $this->em->flush();

            return $this->redirectToRoute('category_recipe');
        }

        return $this->render('category_recipe/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/categoryRecipe/update/{id}", name="category_recipe_update")
     */
    public function update(Request $request, CategoryRecipe $category): Response
    {

        $form = $this->createForm(CategoryRecipeType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $category->setUpdatedAt(new DateTime());

            $this->em->flush();

            return $this->redirectToRoute('category_recipe');
        }

        return $this->render('category_recipe/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/categoryRecipe/delete/{id}", name="category_recipe_delete")
     */
    public function delete(CategoryRecipe $category): Response
    {

        $messageDelete = $this->translator->trans('The category has been deleted');

        $this->em->remove($category);
        $this->addFlash("danger", $messageDelete);

        $this->em->flush();

        return $this->redirectToRoute('category_recipe');

    }
}
