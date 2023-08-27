<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

//TODO: Rename this controller and add endpoint for upload file
class RegistrationController extends AbstractController
{
    public function index(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $decoded = json_decode($request->getContent());
        $email = $decoded->email;
        $plaintextPassword = $decoded->password;
        $name = $decoded->name;

        $user = new User();
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);
        $user->setEmail($email);
        $user->setName($name);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(['message' => 'Registered Successfully']);
    }

    public function updateProfile(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        //TODO: Put in a separate service
        $decoded = json_decode($request->getContent(), true);

        $user = $this->getUser();

        //TODO: implement changing password
        $form = $this->createFormBuilder($this->getUser(), ['csrf_protection' => false])
            ->add('name', TextType::class)
            ->add('email', TextType::class)
            ->getForm();

        $form->submit($decoded, false);

        if ($form->isSubmitted() && $form->isValid()) {
            // perform some action...
            $entityManager->flush();
//            return new JsonResponse($serializer->serialize($user, 'json'));
            return new JsonResponse($serializer->serialize($user, 'json', [AbstractNormalizer::GROUPS => ['basic']]), 200, [], true);
        }

        $errors = [];

        foreach ($form->getErrors(true) as $formError) {
            $errors[] = $formError->getMessage();
        }

        $data = [
            $form->getName() => ['errors' => $errors],
        ];

        return new JsonResponse($data, 400);
    }

    public function test(Request $request): Response
    {
        print_r($request->getContent());
        return new Response('OK');
    }
}