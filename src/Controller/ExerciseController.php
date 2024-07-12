<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Form\ExerciseType;
use App\Repository\ExerciseLogRepository;
use App\Repository\ExerciseRepository;
use App\Repository\MuscleGroupRepository;
use App\Service\ExerciseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExerciseController extends AbstractController
{

    #[Route('/exercise/create', name: 'create_exercise', methods: ['GET', 'POST'])]
    public function store(Request $request, ExerciseService $exerciseService): Response
    {
        $exercise = new Exercise();

        $form = $this->createForm(ExerciseType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $exerciseService->save($exercise);
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('create_exercise');
            }

            return $this->redirectToRoute('index_exercise');
        }

        return $this->render('exercise/create.html.twig', [
            'form' => $form,
        ]);

    }

    #[Route('/exercises', name: 'index_exercise', methods: ['GET'])]
    public function index(ExerciseRepository $exerciseRepository): Response
    {
        $exercises = $exerciseRepository->findAll();

        return $this->render('exercise/index.html.twig', [
            'exercises' => $exercises,
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/exercise/{id}', name: 'edit_exercise', methods: ['GET','POST'])]
    public function update(Request $request, ExerciseService $exerciseService, $id): Response
    {
        $exercise = $exerciseService->getExerciseById($id);

        $form = $this->createForm(ExerciseType::class, $exerciseService->getExerciseById($id));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $exercise = $form->getData();
                $exercise->setName(mb_ucfirst($exercise->getName()));
                $exerciseService->update($exercise, $id);

            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('edit_exercise', ['id' => $id]);
            }

            return $this->redirectToRoute('index_exercise');
        }

        return $this->render('exercise/create.html.twig', [
            'form' => $form,
        ]);

    }

    #[Route('/exercise/{id}', name: 'delete_exercise', methods: ['DELETE'])]
    public function destroy(Request $request, ExerciseService $exerciseService, $id)
    {
        $exercise = $exerciseService->getExerciseById($id);

        try {
            $exerciseService->deleteExercise($exercise);
            $this->addFlash('success', 'Exercise deleted successfully');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('index_exercise');
    }

    #[Route('/muscle_group/{id}/exercises', name: 'exercises_for_muscle_group', methods: ['GET'])]
public function exercisesForMuscleGroup(MuscleGroupRepository $muscleGroupRepository, ExerciseRepository $exerciseRepository, $id): Response
{
    $muscleGroup = $muscleGroupRepository->find($id);

    $exercises = $exerciseRepository->findBy(['muscleGroup' => $muscleGroup]);

    return $this->render('exercise/exercises_muscle_group.html.twig', [
        'muscleGroup' => $muscleGroup,
        'exercises' => $exercises,
    ]);
}
    /**
     * @Route("/{id}/logs", name="exercise_logs", methods={"GET"})
     */
    #[Route('/exercise/{id}/logs', name: 'exercise_logs', methods: ['GET'])]
    public function exerciseLogs(Exercise $exercise, ExerciseLogRepository $exerciseLogRepository, int $id): Response
 {
     $user = $this->getUser();
     $userId = $user->getId();

        $isTrainer = in_array('ROLE_TRAINER', $user->getRoles());
        // Retrieve exercise logs for the exercise ID and user ID
        $exerciseLogs = $exerciseLogRepository->findAllExerciseLogsForExerciseAndUser($id, $userId, $isTrainer);

        return $this->render('exercise/logs.html.twig', [
            'exercise' => $exercise,
            'exerciseLogs' => $exerciseLogs,
            'isTrainer' => $isTrainer,
        ]);
    }
}
