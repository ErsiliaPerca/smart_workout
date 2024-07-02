<?php

namespace App\Controller;

use App\Entity\MuscleGroup;
use App\Form\MuscleGroupType;
use App\Repository\MuscleGroupRepository;
use App\Service\MuscleGroupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MuscleGroupController extends AbstractController
{
    #[Route('/muscle-group', name: 'app_muscle_group')]
    public function index(): Response
    {
        return $this->render('muscle_group/index.html.twig', [
            'controller_name' => 'MuscleGroupController',
        ]);
    }

    #[Route('/muscle-group/create', name: 'create_muscle_group')]
    public function store(Request $request, MuscleGroupService $muscleGroupService): Response
    {
        $muscleGroup = new MuscleGroup();

        $form = $this->createForm(MuscleGroupType::class, $muscleGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $name = $form->get('name')->getData();

            if (!$muscleGroupService->validate($name)) {
                $this->addFlash('error', 'This muscle group already exists.');
                return $this->redirectToRoute('create_muscle_group');
            }

            $muscleGroupService->save($muscleGroup);


            return $this->redirectToRoute('app_muscle_group');
        }

        return $this->render('muscle_group/create.html.twig', [
            'form' => $form,
        ]);

    }

}
