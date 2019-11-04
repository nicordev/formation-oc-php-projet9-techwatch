<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\TwitList;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TwitListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('target')
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TwitList::class,
        ]);
    }
}
