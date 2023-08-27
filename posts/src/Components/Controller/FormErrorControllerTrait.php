<?php

namespace App\Components\Controller;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

trait FormErrorControllerTrait
{
    protected function extractFormErrors(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->getErrors(true) as $formError) {
            $errors[] = $formError->getMessage();
        }

        return $errors;
    }

    protected function buildFormErrorResponse(FormInterface $form): JsonResponse
    {
        $errors = $this->extractFormErrors($form);

        $data = [
            $form->getName() => ['errors' => $errors],
        ];

        return new JsonResponse($data, 400);
    }
}