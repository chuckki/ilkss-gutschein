<?php

namespace App\Form;

use App\Entity\Gutschein;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GutscheinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gs_name')
            ->add('gs_betrag')
            ->add('gs_nummer')
            ->add('gs_bemerkung')
            ->add('gs_date')
            ->add('isPayed')
            ->add('hash')
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gutschein::class,
        ]);
    }
}
