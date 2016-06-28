<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Form\Type\RegistroActividadHorasType;

class RegistroHorasType extends AbstractType
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
             ->add('proyectoPresupuesto', 'entity', [
                'class' => 'AppBundle:ProyectoPresupuesto',
                'required' => true,
                'empty_value' => 'Seleccione el presupuesto asignado',

            ])
             ->add('fechaHoras', 'date', [
                'label' => 'Fecha de las horas invertidas',
                'input' => 'datetime',
                'widget' => 'choice',
                'model_timezone' => 'America/Guatemala',
                'view_timezone' => 'America/Guatemala',
                'format' => 'dd-MMM-yyyy',
                'data' => new \DateTime(),
                'attr' => [

                ],
                 'required' => true,

            ])
            ->add('cliente', 'entity', [
                'class' => 'AppBundle:Cliente',
                'required' => true,
                'empty_value' => 'Seleccione el cliente',

            ])
            ->add('horasActividad', 'bootstrap_collection', [
                    'type' => new RegistroActividadHorasType(),
                    'label' => 'Registro de Actividad y Horas',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'add_button_text' => 'Agregar Actividad',
                    'delete_button_text' => 'Eliminar Actividad',
                    'sub_widget_col' => 6,
                    'button_col' => 12,
                    'by_reference' => false, //esta linea también es importante para que se guarde la ref
                    'cascade_validation' => true,
                    'attr' => [
                            'class' => 'select2',
                        ],
                   

                ])
            ->add('ingresadoPor', 'entity', [
                'class' => 'UserBundle:Usuario',
                'property' => 'codigoString',
                'data' => $this->usuario,
                'attr' => [
                    'help_text' => 'AS para asistente, EN para encargado, SU para supervisor, GE para gerente, SC para socio',
                ],

            ])
             ->add('horasExtraordinarias', null, [
                'label' => 'Horas Extraordinarias',
                'attr' => [
                    'help_text' => 'Marque esta opción si las horas que está ingresando son extraordinarias',
                ],
            ])

            ->add('submit', 'submit', [
                    'label' => 'Guardar',
                    'attr' => [
                        'class' => 'btn btn-primary btn-block',
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
            'data_class' => 'AppBundle\Entity\RegistroHoras',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_registrohoras';
    }
}
