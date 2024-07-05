<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Workout;
use App\Repository\WorkoutRepository;

class WorkoutService
{
    private WorkoutRepository $workoutRepository;

    public function __construct(WorkoutRepository $workoutRepository)
    {
        $this->workoutRepository = $workoutRepository;
    }

    /**
     * @throws \Exception
     */
    public function save(Workout $workout): void
    {
        $this->workoutRepository->saveWorkout($workout);

    }

    public function findWorkoutsByUser(User $user){
       return $this->workoutRepository->findByUserId($user);

    }

}