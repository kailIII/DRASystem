<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class DatosPrestacionesType extends AbstractType
{
    private $usuario;

    /**
     * Constructor para cargar valor iniciales con el usuario logueado.
     *
     * @param UsuarioTrabajador $usuario
     */
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
            ->add('sueldo', 'money', [
                'currency' => 'GTQ',
                'label' => 'Sueldo*',
                'attr' => [
                    'placeholder' => 'Sueldo Base',
                    'class' => 'form-control input-lg',
                ],
                'grouping' => true,

            ])
            ->add('bonificacionIncentivo', 'money', [
                'currency' => 'GTQ',
                'label' => 'Bonificación Ley*',
                'attr' => [
                    'placeholder' => 'Bonificación Ley',
                    'class' => 'form-control input-lg',
                ],
                'required' => true,
                'grouping' => true,

            ])
            ->add('otraBonificacion', 'money', [
                'currency' => 'GTQ',
                'label' => 'Otra bonificación',
                'attr' => [
                    'placeholder' => 'Otra bonificación',
                    'class' => 'form-control input-lg',
                ],
                'required' => false,
                'grouping' => true,
            ])
            ->add('gasolina', 'money', [
                'currency' => 'GTQ',
                'label' => 'Gasolina',
                'attr' => [
                    'placeholder' => 'Gasolina',
                    'class' => 'form-control input-lg',
                ],
                'required' => false,
                'grouping' => true,
            ])

            ->add('otrasPrestaciones', 'money', [
                'currency' => 'GTQ',
                'label' => 'Otras prestaciones',
                'attr' => [
                    'placeholder' => 'Otras prestaciones',
                    'class' => 'form-control input-lg',
                ],
                'required' => false,
            ])
            ->add('viaticos', 'money', [
                'currency' => 'GTQ',
                'label' => 'Viáticos',
                'attr' => [
                    'placeholder' => 'Viáticos',
                    'class' => 'form-control input-lg',
                ],
                'required' => false,
            ])
            ->add('otros', 'money', [
                'currency' => 'GTQ',
                'label' => 'Otros',
                'attr' => [
                    'placeholder' => 'Otros',
                    'class' => 'form-control input-lg',
                ],
                'required' => false,
                'grouping' => true,
            ])
            ->add('depreciacion', 'money', [
                'currency' => 'GTQ',
                'label' => 'Depreciación',
                'attr' => [
                    'placeholder' => 'Depreciación',
                    'class' => 'form-control input-lg',
                ],
                'required' => false,
                'grouping' => true,
            ])
           ->add('usuario', 'entity', [
                'class' => 'UserBundle:UsuarioTrabajador',
                'data' => $this->usuario,
                'attr' => [
                    'class' => 'select2',
                ],
            ])
            ->addEventListener(FormEvents::POST_SET_DATA, [$this, 'onPreData'])

        ;

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            [$this, 'onPostData']
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\DatosPrestaciones',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'userbundle_datosprestaciones';
    }
    /**
     * Pre Set Data from depending the current status
     * of gastos indirectos.
     *
     * @param FormEvent $event
     */
    public function onPreData(FormEvent $event)
    {
        $form = $event->getForm();
        $gasto = $form->getData()->getGastosIndirectos();

        // Check whether the user has chosen to display his email or not.
        // If the data was submitted previously, the additional value that is
        // included in the request variables needs to be removed.
        if (null === $gasto || $gasto <= 0) {
            $form->add('gastos', 'checkbox', [
                'label' => 'Aplica gastos indirectos de Q480 semanales',
                'required' => false,
                'mapped' => false,
            ]);
        } else {
            $form->add('gastosIndirectos', 'number', [
                'label' => 'Gastos Indirectos',
            ]);
        }
    }

    /**
     * Forma de validar el correo de un catedrático.
     *
     * @param FormEvent $event Evento después de mandar la información del formulario
     */
    public function onPostData(FormEvent $event)
    {
        $datosPrestaciones = $event->getData();

        if (isset($event->getForm()['gastos'])) {
            if ($event->getForm()['gastos']->getData() === true) {
                $datosPrestaciones->setGastosDRA();
            }
        }
        $datosPrestaciones->calcularPrestaciones();
    }
}
