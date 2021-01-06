<?php

namespace App\Controller;

use App\Data\SearchRecipe;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Form\SearchRecipeType;
use App\Repository\CategoryRecipeRepository;
use App\Repository\RecipeRepository;
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

class RecipeController extends AbstractController
{

    private $em;
    private $recipeRepository;
    private $translator;
    private $categoryRecipeRepository;
    private $appKernel;
    private $slugger;

    public function __construct(EntityManagerInterface $em,SluggerInterface $slugger, RecipeRepository $recipeRepository, KernelInterface $appKernel, CategoryRecipeRepository $categoryRecipeRepository, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->recipeRepository = $recipeRepository;
        $this->translator = $translator;
        $this->categoryRecipeRepository = $categoryRecipeRepository;
        $this->appKernel = $appKernel;
        $this->slugger = $slugger;
    }
    /**
     * @Route("/recipes", name="recipe_list")
     */
    public function list(Request $request, UserInterface $user): Response
    {

        $dataRecipe = new SearchRecipe();
        $dataRecipe->page = $request->get('page', 1);
        
        $form = $this->createForm(SearchRecipeType::class, $dataRecipe);
        
        $form->handleRequest($request);

        $recipes = $this->recipeRepository->findSearch($dataRecipe, $user);

        return $this->render('recipe/list.html.twig', [
            'recipes' => $recipes,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/recipes/add", name="recipe_add")
     */
    public function add(Request $request, UserInterface $user): Response
    {

        $categoriesRecipe = $this->categoryRecipeRepository->findByUser($user);
        if(empty($categoriesRecipe)) {

        $messageEmptyCategoriesRecipe = $this->translator->trans('To be able to insert a recipe you must first set the categories. Settings => Recipe categories');

            $this->addFlash("warning", $messageEmptyCategoriesRecipe);
        
            return $this->redirectToRoute('recipe_list');
        }
        
        $recipe = new Recipe;

        $form = $this->createForm(RecipeType::class, $recipe, [
            'user' => $user
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            
            $file = $form->get('picture')->getData();
            
            if($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('pictureRecipe_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $recipe->setPicture($newFilename);
            }

            $ingredients = $recipe->getIngredients();
            $preparations = $recipe->getPreparations();
            $categoriesRecipe = $recipe->getCategoryRecipes();

            foreach ($ingredients as $ingredient) {
                $ingredient->setRecipe($recipe);
            }

            foreach ($preparations as $preparation) {
                $preparation->setRecipe($recipe);
            }

            foreach ($categoriesRecipe as $category) {
                $category->addRecipe($recipe);
            }
            
            $recipe->setCreatedAt(new DateTime());
            $recipe->setUser($user);

            $this->em->persist($recipe);
            $this->em->flush();

            return $this->redirectToRoute('recipe_list');

        }

        return $this->render('recipe/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/recipes/update/{id}", name="recipe_update")
     */
    public function update(Request $request, Recipe $recipe, UserInterface $user): Response
    {
        $picture = $recipe->getPicture();

        $projectDir = $this->appKernel->getProjectDir();

        $form = $this->createForm(RecipeType::class, $recipe, [
            'user' => $user
        ]);

        $form->handleRequest($request);

        dump($form);

        if($form->isSubmitted() && $form->isValid()) {
            
            $recipe->setUpdatedAt(new DateTime());
            dump($form);


            $file = $form->get('picture')->getData();
            if(!$file) {
                $recipe->setPicture($picture);
            } else {
                unlink($projectDir . '/public/uploads/picture/recipe/'.$picture);

                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('pictureRecipe_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

            }
                $recipe->setPicture($newFilename);
            }
            $ingredients = $recipe->getIngredients();
            $preparations = $recipe->getPreparations();
            $categoriesRecipe = $recipe->getCategoryRecipes();


            foreach ($ingredients as $ingredient) {
                if(!$ingredient->getId()) {
                    $ingredient->setRecipe($recipe); 
                }
            }
            foreach ($preparations as $preparation) {
                if (!$ingredient->getId()) {
                    $preparation->setRecipe($recipe);
                }
            }
            foreach ($categoriesRecipe as $categoryRecipe) {

                if(!$categoryRecipe->getId()) {
                    $categoryRecipe->setRecipe($recipe); 
                }
            }

            $this->em->flush();

            return $this->redirectToRoute('recipe_list');
        }

        return $this->render('recipe/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

       /**
     * @Route("/recipes/delete/{id}", name="recipe_delete")
     */
    public function delete(Request $request, Recipe $recipe): Response
    {

        $messageDelete = $this->translator->trans('The recipe has been deleted');
        $projectDir = $this->appKernel->getProjectDir();
        unlink($projectDir . '/public/uploads/picture/recipe/'.$recipe->getPicture());
        $this->em->remove($recipe);
        $this->addFlash("danger", $messageDelete);

        $this->em->flush();

        return $this->redirectToRoute('recipe_list');

    }
}
