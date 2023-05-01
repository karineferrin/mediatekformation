<?php

namespace App\Controller\Form;

use App\Entity\Categorie;
use App\Entity\Playlist;
use App\Entity\Formation;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Builder permettant de définir les champs du formulaire d'ajout ou d'édition
 * d'une formation
 * @author karinefer
 */

class FormationType extends AbstractType
{
    /**
     * Ajout des champs pour le formulaire "formformation"
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('playlist', EntityType::class, [
                'class' => Playlist::class,
                'choice_label' => 'name',
                'multiple' => false,
                'required' => true
            ])
            ->add('publishedAt', DateType::class,[
                'widget' => 'single_text',
                'data' =>isset($options ['data']) &&
                    $options ['data']-> getPublishedAt() != null ? $options ['data']-> getPublishedAt() : new  DateTime('now'),
                'label' => 'date'
            ])    
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'required' => false
            ])
            ->add('videoId', FileType::class, [
                'required' => false,
                'label' => 'selection image',
                'data_class' => null
            ])
            ->add('description')    
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
