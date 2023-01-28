<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Formation;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//use Symfony\Component\Validator\Constraints\DateTime;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('playlist')
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
                'required' => false
            ])
            ->add('VideoId', FileType::class, [
                'required' => false,
                'label' => 'selection image'
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