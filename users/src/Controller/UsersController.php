<?php

namespace App\Controller;

use App\Components\Controller\FormErrorControllerTrait;
use App\Components\Storage\StorageService;
use App\Components\Storage\UserProfileImageUploader;
use App\Entity\User;
use App\Form\UserImageFormType;
use App\Form\UserRegisterType;
use App\Form\UserUpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class UsersController extends AbstractController
{
    use FormErrorControllerTrait;
    #[Route('/users/register', name: 'register_user', methods: 'POST')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, SerializerInterface $serializer, StorageService $storage): Response
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $user = new User();
        $form = $this->createForm(UserRegisterType::class, $user);

        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            );
            $user->setPassword($hashedPassword);

            $storage->save($user);
            return new JsonResponse($serializer->serialize($user, 'json', [AbstractNormalizer::GROUPS => ['basic']]), 200, [], true);
        }

        return $this->buildFormErrorResponse($form);
    }

    #[Route('/users/me', name: 'update_user', methods: 'PATCH')]
    public function updateProfile(Request $request, StorageService $storage, SerializerInterface $serializer): Response
    {
        //TODO: Put in a separate service
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $user = $this->getUser();
        $form = $this->createForm(UserUpdateType::class, $user);

        $form->submit($data);

        //TODO: implement changing password
        if ($form->isSubmitted() && $form->isValid()) {
            $storage->save($user);
            return new JsonResponse($serializer->serialize($user, 'json', [AbstractNormalizer::GROUPS => ['basic']]), 200, [], true);
        }

        return $this->buildFormErrorResponse($form);
    }

    #[Route('/users/me/profile-image', name: 'update_user_image', methods: 'POST')]
    public function changeProfileImage(Request $request, FormFactoryInterface $formFactory, UserProfileImageUploader $imageUploader, StorageService $storage, SerializerInterface $serializer)
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $form = $formFactory->createNamed('', UserImageFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $profileImage = $form->get('image')->getData();
            $fileName = $imageUploader->upload($profileImage, $user->getProfileImage());

            $user->setProfileImage($fileName);
            $storage->save($user);

            return new JsonResponse($serializer->serialize($user, 'json', [AbstractNormalizer::GROUPS => ['profile_image']]), 200, [], true);
        }

        return $this->buildFormErrorResponse($form);
    }
}