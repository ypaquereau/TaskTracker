<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Model\UserModel;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userModel = new UserModel();
        $form = $this->createForm(RegistrationType::class, $userModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist(User::create($userModel));
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->renderForm('registration/register.html.twig', [
            'registrationForm' => $form
        ]);
    }
}
