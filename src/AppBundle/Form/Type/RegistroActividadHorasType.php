<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistroActividadHorasType extends AbstractType
{
    private $usuario;

    public function __construct($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('actividad', 'entity', [
                'class' => 'AppBundle:Actividad',
                'required' => true,
                'empty_value' => 'Seleccione la actividad',
                'attr' => [
                    'class' => 'select2',
                ],
            ])
            ->add('horasInvertidas', null, [
                'label' => 'Horas invertidas',
                'required' => true,
            ])
            ->add('horasExtraordinarias', 'checkbox', [
                'required' => false,
                'label' => 'Las horas realizadas fueron extraordinarias',
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_registrohoras_actividad_horas';
    }
}
