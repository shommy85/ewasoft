<?php

namespace Form\Type;

use App\Entity\Message;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('recipientId', IntegerType::class)
            ->add('content', TextType::class)
            ->add('threadMessage', EntityType::class, [
                'class' => Message::class
            ])
        ;
    }
}