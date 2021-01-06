<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RecipeController extends AbstractController
{

    private $em;
    private $recipeRepository;
    private $translator;

    public function __construct(EntityManagerInterface $em, RecipeRepository $recipeRepository, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->recipeRepository = $recipeRepository;
        $this->translator = $translator;
    }
    /**
     * @Route("/recipes", name="recipe_list")
     */
    public function list(): Response
    {

        $recipes = $this->recipeRepository->findAll();
        return $this->render('recipe/list.html.twig', [
            'recipes' => $recipes
        ]);
    }

    /**
     * @Route("/recipes/add", name="recipe_add")
     */
    public function add(Request $request, SluggerInterface $slugger): Response
    {
        $recipe = new Recipe;

        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            
            dump($form);

            //return $this->redirectToRoute('recipe_list');
        }

        return $this->render('recipe/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
