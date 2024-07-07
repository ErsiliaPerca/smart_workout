<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Workout;
use App\Form\WorkoutType;
use App\Repository\WorkoutRepository;
use App\Service\WorkoutService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class WorkoutController extends AbstractController
{
    #[Route('/workout', name: 'app_workout', methods: ['GET'])]
    public function index(WorkoutService $workoutService): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $workouts = $workoutService->findWorkoutsByUser($user);

        return $this->render('workout/index.html.twig', [
            'workouts' => $workouts,
        ]);
    }

    /**
     * @throws \Exception
     */

    #[IsGranted('ROLE_USER')]
    #[Route('/workout/create', name: 'create_workout')]
    public function store(Request $request, WorkoutService $workoutService): Response
    {
        $workout = new Workout();

        $form = $this->createForm(WorkoutType::class, $workout);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Workout $workout */
            $workout = $form->getData();

            /** @var User $user */
            $user = $this->getUser();
            if ($user) {
                $workout->setPerson($user);
            }

            $workoutService->save($workout);

            return $this->redirectToRoute('app_workout');
        }

        return $this->render('workout/create.html.twig', [
            'form' => $form,
        ]);

    }

    #[Route('/workout/{id}', name: 'delete_workout', methods: ['DELETE'])]
    public function destroy(Request $request, WorkoutService $workoutService, $id): Response
    {
       $workout = $workoutService->getWorkoutById($id);
        try {
            $workoutService->deleteWorkout($workout);
            $this->addFlash('success', 'Workout deleted successfully');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_workout');

    }


}
