<?php

namespace App\Controller;

use App\Components\Communication\LikesService;
use App\Components\Communication\PostsService;
use App\Service\ReverseProxyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/posts/{postId}', name: 'api_show_post', requirements: ['postId' => '\d+'], methods: 'GET')]
    public function showPost(Request $request, PostsService $postsService, LikesService $likesService, int $postId): Response
    {
        $postData = $postsService->getPost($postId);
        $likedPosts = $likesService->getUserLikedPosts();

        $postData['liked'] = in_array($postData['id'], $likedPosts);

        return new JsonResponse($postData);
    }

    #[Route('/api/{host}/{path}', name: 'api', requirements: ['path' => '.+'])]
    public function index(Request $request, ReverseProxyService $reverseProxyService, $host, $path = ''): Response
    {
        $remotePath = $host. ($path? '/'.$path: '');
        return $reverseProxyService->makeProxyRequest($request, $host, $remotePath);
    }
}