<?php

namespace App\Controller;

use App\Components\Controller\FormErrorControllerTrait;
use App\Components\Storage\EntityStorageInterface;
use App\Entity\Message;
use App\Repository\MessageRepository;
use Form\Type\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MessageController extends AbstractController
{
    use FormErrorControllerTrait;
    #[Route('/messages', name: 'all_messages', methods: 'GET')]
    public function index(SerializerInterface $serializer, MessageRepository $repository): JsonResponse
    {
        $userMessages = $repository->findByUser($this->getUser()->getId());

        return new JsonResponse($serializer->serialize($userMessages, 'json'), 200, [], true);
    }

    #[Route('/messages', name: 'send_message', methods: 'POST')]
    public function create(Request $request, SerializerInterface $serializer, EntityStorageInterface $storage): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $message = new Message();
        $message->setSenderId($this->getUser()->getId());

        $form = $this->createForm(MessageType::class, $message);
        $form->submit($data, false);

        if ($form->isSubmitted() && $form->isValid()) {
            $storage->save($message);
            return new JsonResponse($serializer->serialize($message, 'json'), 200, [], true);
        }

        return $this->buildFormErrorResponse($form);
    }
}