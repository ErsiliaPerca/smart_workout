<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{

    /**
     * @throws \Exception
     */
    #[Route('/user/register', name: 'register_user')]
    public function store(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        $errorMessage = null;
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated

            $user = $form->getData();
            $existingUser = $userRepository->findOneBy(['email' => $user->getEmail()]);

            if ($existingUser) {

                $errorMessage = 'This email is already registered.';
            } else {
                $password = $passwordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($password);
                $user->setRoles(["ROLE_USER"]);

                $userRepository->saveUser($user);


                // ... perform some action, such as saving the task to the database

                return $this->redirectToRoute('index_user');
            }
        }
        return $this->render('user/register.html.twig', [
            'form' => $form,
            'errorMessage' => $errorMessage,
        ]);

    }

    #[Route('/user/{id}', name: 'show_user', requirements: ['id' => '\d+'])]
    public function show(UserRepository $userRepository, int $id): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id ' . $id
            );
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/users', name: 'index_user')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }
}
