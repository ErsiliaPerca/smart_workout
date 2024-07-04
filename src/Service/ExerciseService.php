<?php

namespace App\Service;


use App\Entity\Exercise;
use App\Repository\ExerciseRepository;

class ExerciseService
{
    private ExerciseRepository $exerciseRepository;

    public function __construct(ExerciseRepository $exerciseRepository)
    {
        $this->exerciseRepository = $exerciseRepository;
    }

    public function getExerciseById($exerciseId): object
    {
        return $this->exerciseRepository->find($exerciseId);

    }

//    public function getAllExercises()
//    {
//        return $this->exerciseRepository->findAll();
//    }

    /**
     * @throws \Exception
     */
    public function save(Exercise $exercise): void
    {
        $existingExercise = $this->exerciseRepository->findByName($exercise->getName());
        if ($existingExercise) {
            throw new \Exception("Exercise already exists");
        }
        $this->exerciseRepository->saveExercise($exercise);
    }

    /**
     * @throws \Exception
     */
    public function update(Exercise $exercise, int $id): void
    {
        $existingExercise = $this->exerciseRepository->findByNameExcludingId($exercise->getName(),  $exercise->getId());
        if ($existingExercise) {
            throw new \Exception("Another exercise with the same name already exists");
        }
        $this->exerciseRepository->saveExercise($exercise);
    }

   public function deleteExercise(Exercise $exercise): void
   {
      $this->exerciseRepository->delete($exercise);
   }
}