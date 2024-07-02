<?php

namespace App\Service;

use App\Repository\MuscleGroupRepository;

class MuscleGroupService
{
    private MuscleGroupRepository $muscleGroupRepository;

    public function __construct(MuscleGroupRepository $muscleGroupRepository)
    {
        $this->muscleGroupRepository = $muscleGroupRepository;
    }

    public function validate(string $name): bool
    {
        $existingMuscleGroup = $this->muscleGroupRepository->findByName($name);
        return $existingMuscleGroup === null;
    }

    public function save(\App\Entity\MuscleGroup $muscleGroup): void
    {
        $this->muscleGroupRepository->saveMuscleGroup($muscleGroup);
    }
}