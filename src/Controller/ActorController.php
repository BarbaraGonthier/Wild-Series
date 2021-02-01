<?php


namespace App\Controller;

use App\Entity\Actor;
use App\Form\ActorType;
use App\Repository\ActorRepository;
use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/actors", name="actor_")
 */
class ActorController extends AbstractController
{
    /**
     * Correspond à la route /actors/ et au name "actor_show"
     * @Route("/{id}", requirements={"id"="\d+"}, methods={"GET"}, name="show")
     * @param Actor $actor
     * @return Response
     */
    public function show(Actor $actor): Response
    {
        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
        ]);
    }

    /**
     * @Route("edit/{id}", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param Actor $actor
     * @return Response
     */
    public function edit(Request $request, Actor $actor): Response
    {
        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('success', 'The actor has been edited');

            return $this->redirectToRoute('program_index');
        }

        return $this->render('actor/edit.html.twig', [
            'actor' => $actor,
            'form' => $form->createView(),
        ]);
    }

}