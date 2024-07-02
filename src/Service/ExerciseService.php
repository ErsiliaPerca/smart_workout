<?php

namespace App\Service;


use App\Repository\ExerciseRepository;

class ExerciseService
{
    private ExerciseRepository $exerciseRepository;

    public function __construct(ExerciseRepository $exerciseRepository)
    {
        $this->exerciseRepository = $exerciseRepository;
    }

    public function validate(string $name): bool
    {
        $existingExercise = $this->exerciseRepository->findByName($name);
        return $existingExercise === null;
    }

    public function save(\App\Entity\Exercise $exercise): void
    {
        $this->exerciseRepository->saveExercise($exercise);
    }
}