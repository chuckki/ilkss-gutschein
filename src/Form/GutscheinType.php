<?php

namespace App\Form;

use App\Entity\Gutschein;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
                    'class' => 'myclass',
                    'placeholder' => 'interner Name - wird nicht gedruckt'
                ],
            )
        )->add(
            'gs_betrag',
            IntegerType::class,
            array(
                'label'    => "Betrag",
                'required' => true,
                'row_attr' => [
                    'placeholder' => 'Betrag in Cent'
                ],
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
                    'Betrag - Kite'        => 1,
                    'Betrag - Wing in Planung'        => 0,
                    'Kitesurf-Schnupperkurs' => 3,
                    '3h Kitesurftraining' => 7,
                    'Kitesurf-Grundkurs'     => 2,
                    'Kitesurf-Aufsteigerkurs'     => 4,
                    'Wingsurf-Grundkurs'     => 5,
                    'Wingsurf-Aufsteigerkurs'     => 6,
                ],
                'group_by' => function ($choice, $key, $value) {

                    if ($choice === 1 || $choice === 0) {
                        return 'Betrag';
                    }
                    if ($choice <= 4 || $choice === 7) {
                        return 'Kitesurfen';
                    }

                    return 'Wingsurfen';
                },
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
            )->add(
                'isPayed',
                CheckboxType::class,
                [
                    'label_attr' => ['class' => 'switch-custom'],
                    'required' => false,
                ]
            )->add(
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
