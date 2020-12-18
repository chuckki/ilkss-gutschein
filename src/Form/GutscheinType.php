<?php

namespace App\Form;

use App\Entity\Gutschein;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class GutscheinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'gs_name',
            TextType::class,
            array(
                'label'    => "Name",
                'required' => true,
                'attr'     => ['maxlength' => 20],
            )
        )->add(
            'gs_betrag',
            IntegerType::class,
            array(
                'label'    => "Betrag",
                'required' => true,
            )
        )->add(
            'gs_nummer',
            TextType::class,
            array(
                'label'    => "Gutscheinnummer",
                'required' => true,
                'data'     => date("y").'-'.dechex(time()),
                'attr'     => ['maxlength' => 20],
            )
        )->add(
            'gs_bemerkung',
            TextType::class,
            array(
                'label' => "Bemerkung (max 20 Zeichen)",
                'attr'  => ['maxlength' => 20],
            )
        )->add(
            'gs_date',
            DateType::class,
            [
                'label'  => "Ausstellungsdatum",
                'widget' => 'single_text',
                'html5'  => false,
                'input_format' => 'dd-MM-yyyy',
                'format' => 'd.m.y',
                // adds a class that can be selected in JavaScript
                'attr'   => ['class' => 'js-datepicker'],
            ]
        )->add('isPayed')->add(
            'hash',
            HiddenType::class,
            array(
                'data' => dechex(time()).'-'.bin2hex(random_bytes(10)),
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Gutschein::class,
            ]
        );
    }
}
