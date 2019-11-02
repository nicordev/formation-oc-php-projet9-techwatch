<?php

namespace App\Form;

use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagType extends AbstractType
{
    public const SHOW_RSS_SOURCES_INPUT = "showRssSources";
    public const SHOW_TWIT_LISTS_INPUT = "showTwitLists";

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');

        if ($options[self::SHOW_RSS_SOURCES_INPUT]) {
            $builder->add('rssSources', CollectionType::class, [
                'entry_type' => RssSourceType::class,
                'entry_options' => ['label' => false],
                'required' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ]);
        }

        if ($options[self::SHOW_TWIT_LISTS_INPUT]) {
            $builder->add('twitLists', CollectionType::class, [
                'entry_type' => TwitListType::class,
                'entry_options' => ['label' => false],
                'required' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
            self::SHOW_RSS_SOURCES_INPUT => false,
            self::SHOW_TWIT_LISTS_INPUT => false
        ]);
    }
}
