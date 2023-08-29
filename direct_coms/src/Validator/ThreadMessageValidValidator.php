<?php

namespace App\Validator;

use App\Entity\Message;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ThreadMessageValidValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ThreadMessageValid) {
            throw new UnexpectedTypeException($constraint, ThreadMessageValid::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) to take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!$value instanceof Message) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, Message::class);
        }

        /**
         * @var Message $message
         * @var Message $value
         */
        $message = $this->context->getObject();
        $messageUsers = [$message->getSenderId(), $message->getRecipientId()];
        $userDiff = array_diff($messageUsers, [$value->getRecipientId(), $value->getSenderId()]);

        if (!empty($userDiff)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}