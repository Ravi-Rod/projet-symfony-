<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('stock')
            ->add('libelle')
            ->add('User') 
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'Nom',
                'placeholder' => 'veuillez choisir une catégorie',
                'query_builder' =>  fn(CategorieRepository $pr) => $pr->createQueryBuilder('P')->orderBy('P.Nom', 'ASC')
                 ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
