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
            $errors[] = $formError->getCause();
        }

        return $errors;
    }

    protected function errorExists(FormInterface $form, $payload): bool
    {
        $errors = $this->extractFormErrors($form);
        foreach ($errors as $error) {
            if ($error->getConstraint()->payload == $payload) {
                return true;
            }
        }

        return false;
    }

    protected function buildFormErrorResponse(FormInterface $form): JsonResponse
    {
        $errors = $this->extractFormErrors($form);

        $data = [
            $form->getName() => ['errors' => array_map(function ($cause) {return $cause->getMessage();}, $errors)]
        ];

        return new JsonResponse($data, 400);
    }
}