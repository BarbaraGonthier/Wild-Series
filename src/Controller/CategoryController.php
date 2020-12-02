<?php

// src/Controller/CategoryController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categories", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * The controller for the category add form
     * Correspond à la route /categories/ et au name "category_new"
     * @Route("/new", name="new")
     */
    public function new(Request $request) : Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('category_index');
        }
        return $this->render('category/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }
    /**
     * Correspond à la route /categories/ et au name "category_index"
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * Correspond à la route /categories/ et au name "category_show"
     * @Route("/{categoryName}", methods={"GET"}, name="show")
     * @param string $categoryName
     * @return Response
     */
    public function show(string $categoryName): Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category], ['id' => 'DESC'], 5);

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program in '.$categoryName.' category found in program\'s table.'
            );
        }

        return $this->render('category/show.html.twig', [
            'programs' => $programs,
        ]);
    }
}
