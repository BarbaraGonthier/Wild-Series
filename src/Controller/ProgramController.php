<?php

// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/programs", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * Correspond à la route /programs/ et au name "program_index"
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * Correspond à la route /programs/ et au name "program_show"
     * @Route("/{id}", requirements={"id"="\d+"}, methods={"GET"}, name="show")
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' =>$id]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,

        ]);
    }
    /**
     * Correspond à la route /programs/ et au name "program_season_show"
     * @Route("/{programId}/seasons/{seasonNumber}", methods={"GET"}, name="showBySeason")
     * @param int $programId
     * @param int $seasonNumber
     * @return Response
     */
    public function showBySeason(int $programId, int $seasonNumber): Response
    {
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' =>$programId]);

        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program' => $programId,'number' => $seasonNumber]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$programId.' found in program\'s table.'
            );
        }
        if (!$season) {
            throw $this->createNotFoundException(
                'No season with number : '.$seasonNumber.' found in season\'s table.'
            );
        }

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season[0],
        ]);
    }
}
