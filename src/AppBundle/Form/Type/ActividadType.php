<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ActividadType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', null, [
                'required' => true,
                'label' => 'Nombre de la actividad *',
            ])
            ->add('area', 'entity', [
                'label' => 'Área *',
                'class' => 'AppBundle:Area',
                'required' => true,
                'attr' => [
                    'class' => 'select2'
                ]
            ])
            ->add('abreviatura', null, [
                'label' => 'Abreviatura (opcional)',
                'required' => false,
            ])
            ->add('actividadNoCargable', null, [
                'label' => 'Actividad No cargable',
                'required' => false,
                'data' => false,
                'attr' => [
                    'help_text' => 'Si marca esta opción, no contarán las horas para el costo',
                ],
            ])

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Actividad',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_actividad';
    }
}
