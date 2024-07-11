<?php

namespace App\Service;

use App\Entity\MuscleGroup;
use App\Repository\MuscleGroupRepository;

class MuscleGroupService
{
    private MuscleGroupRepository $muscleGroupRepository;

    public function __construct(MuscleGroupRepository $muscleGroupRepository)
    {
        $this->muscleGroupRepository = $muscleGroupRepository;
    }

    public function save(MuscleGroup $muscleGroup): array
    {
        try {
            $existingMuscleGroup = $this->muscleGroupRepository->findByName($muscleGroup->getName());
            if ($existingMuscleGroup) {
                throw new \Exception("This muscle group already exists.");
            }
            $this->muscleGroupRepository->saveMuscleGroup($muscleGroup);
            return ["success" => true];
        } catch (\Exception $exception) {
            return ['error' => true, 'message' => $exception->getMessage()];
        }
    }

}