<?php

namespace App\Form;

use App\Entity\Gutschein;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GutscheinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'gs_name',
            TextType::class,
            array(
                'label'    => "Name",
                'required' => true,
                'attr'     => ['maxlength' => 20],
                'row_attr' => [
                    'class' => 'myclass'
                ],
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
            HiddenType::class,
            array(
                'label'    => "Gutscheinnummer",
                'required' => true,
                'empty_data' => date("y").'-'.dechex(time()),
                'attr'     => ['maxlength' => 20],
            )
        )->add(
            'kurstyp',
            ChoiceType::class,
            [
                'choices' => [
                    'Betrag'        => 1,
                    'Grundkurs'     => 2,
                    'Schnupperkurs' => 3,
                ],
                'placeholder' => 'Gutschein wählen',
            ]
        )->add(
                'gs_bemerkung',
                TextareaType::class,
                array(
                    'required' => false,
                    'label' => "Bemerkung (intern)"
                )
            )->add(
                'gs_date',
                DateType::class,
                [
                    'label'        => "Ausstellungsdatum",
                    'widget'       => 'single_text',
                    'html5'        => false,
                    'input_format' => 'dd.mm.yyyy',
                    'format'       => 'dd.MM.yyyy',
                    // adds a class that can be selected in JavaScript
                    'attr'         => ['class' => 'js-datepicker'],
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
