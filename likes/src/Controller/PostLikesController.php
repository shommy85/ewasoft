<?php

namespace App\Controller;

use App\Components\Controller\FormErrorControllerTrait;
use App\Components\Storage\EntityStorageInterface;
use App\Entity\PostUserLikes;
use App\Form\Type\PostUserLikeType;
use App\Repository\PostUserLikesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PostLikesController extends AbstractController
{
    use FormErrorControllerTrait;

    #[Route('/post-likes', name: 'create_post_like', methods: 'POST')]
    public function create(Request $request, SerializerInterface $serializer, EntityStorageInterface $storage, PostUserLikesRepository $repository): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $postUserLike = new PostUserLikes();
        $postUserLike->setUserId($this->getUser()->getId());
        $form = $this->createForm(PostUserLikeType::class, $postUserLike, ['validation_groups' => ['Default', 'create']]);

        $form->submit($data, false);

        if ($form->isSubmitted() && $form->isValid()) {
            $storage->save($postUserLike);
            return new JsonResponse($serializer->serialize($postUserLike, 'json'), 200, [], true);
        }

        if ($this->errorExists($form, 'unique_post_user')) {
            $postUserLike = $repository->findOneBy(['postId' => $postUserLike->getPostId(), 'userId' => $postUserLike->getUserId()]);
            return new JsonResponse($serializer->serialize($postUserLike, 'json'), 200, [], true);
        }

        return $this->buildFormErrorResponse($form);
    }

    #[Route('/post-likes', name: 'delete_post_like', methods: 'DELETE')]
    public function delete(Request $request, SerializerInterface $serializer, PostUserLikesRepository $repository, EntityStorageInterface $storage): Response
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $form = $this->createForm(PostUserLikeType::class);

        $form->submit($data, false);

        if ($form->isSubmitted() && $form->isValid()) {
            $postUserLike = $repository->findOneBy(['postId' => $data['postId'], 'userId' => $this->getUser()->getId()]);
            //$this->denyAccessUnlessGranted('ROLE_USER', $postUserLike, 'Action not allowed');
            if ($postUserLike) $storage->delete($postUserLike);

            return new Response(null, 204);
        }

        return $this->buildFormErrorResponse($form);
    }
}