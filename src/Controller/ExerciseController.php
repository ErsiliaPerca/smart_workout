<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Form\ExerciseType;
use App\Repository\ExerciseRepository;
use App\Service\ExerciseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExerciseController extends AbstractController
{
    #[Route('/exercise', name: 'app_exercise')]
    public function index(): Response
    {
        return $this->render('exercise/index.html.twig', [
            'controller_name' => 'ExerciseController',
        ]);
    }

   #[Route('/exercise/create', name: 'create_exercise')]
public function store(Request $request, ExerciseService $exerciseService): Response
   {
       $exercise = new Exercise();

       $form = $this->createForm(ExerciseType::class, $exercise);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           $name = $form->get('name')->getData();


           if (!$exerciseService->validate($name)) {
               $this->addFlash('error', 'This exercise already exists.');
               return $this->redirectToRoute('create_exercise');
           }

           $exerciseService->save($exercise);

           return $this->redirectToRoute('app_exercise');
       }

       return $this->render('exercise/create.html.twig', [
           'form' => $form,
       ]);

   }

    #[Route('/exercise/list', name: 'list_exercise')]
    public function list(ExerciseRepository $exerciseRepository): Response
    {
        $exercises = $exerciseRepository->findAll();

        return $this->render('exercise/list.html.twig', [
            'exercises' => $exercises,
        ]);
    }

}
