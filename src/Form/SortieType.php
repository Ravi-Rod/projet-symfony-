<?php

namespace App\Form;

use App\Entity\Sortie;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('quantite')
        ->add('prix')
        ->add('date', DateType::class)
        ->add('produit', EntityType::class, [
            'class' => Produit::class,
            'choice_label' => 'libelle',
            'placeholder' => 'veuillez choisir un produit',
            'query_builder' =>  fn(ProduitRepository $pr) => $pr->createQueryBuilder('P')->orderBy('P.libelle', 'ASC')
             ])
        ->getForm()
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
