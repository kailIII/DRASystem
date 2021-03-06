<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class RegistrationTrabajadorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // agregar campos personalizados
        $builder->add('nombre', null, [
                'label' => 'Nombre/s',
                'attr' => [
                    'placeholder' => 'Nombre/s',
                    'class' => 'form-control input-lg',
                ],
                'required' => true,

            ])
            ->add('apellidos', null, [
                'label' => 'Apellidos/s',
                'attr' => [
                    'placeholder' => 'Apellidos',
                    'class' => 'form-control input-lg',
                ],
                'required' => true,
            ])
            ->add('username', null, [
                'label' => 'Usuario',
                'translation_domain' => 'FOSUserBundle',
                'attr' => [
                    'class' => 'form-control input-lg',
                    'placeholder' => 'Nombre de Usuario',
                ],
                'constraints' => [
                    new Callback([$this, 'validarNombreUsuario']),
                ],
                'required' => true,
            ])
            ->add('email', 'email', [
                'label' => 'Correo',
                'translation_domain' => 'FOSUserBundle',
                'required' => true,
                'attr' => [
                    'class' => 'form-control input-lg',
                    'placeholder' => 'Correo electrónico @diazreyes.com',
                ],
                'required' => true,
            ])

            ->add('plainPassword', 'repeated', [
                'label' => 'Contraseña',
                'type' => 'password',
                'options' => ['translation_domain' => 'FOSUserBundle'],
                'first_options' => [
                    'label' => 'Contraseña',
                    'attr' => [
                        'class' => 'form-control input-lg',
                        'placeholder' => 'Contraseña',
                    ],
                ],
                'second_options' => [
                    'label' => 'Repetir Contraseña',
                    'attr' => [
                        'class' => 'form-control input-lg',
                        'placeholder' => 'Repetir Contraseña',
                    ],
                ],
                'invalid_message' => 'fos_user.password.mismatch',
            ])
            ->add('direccion', null, [
                'label' => 'Dirección*',
                'attr' => [
                    'class' => 'form-control input-lg',
                    'placeholder' => 'Dirección',
                ],
                'required' => true,
            ])
           ->add('dpi', 'number', [
                'label' => 'DPI *',
                'attr' => [
                    'class' => 'form-control input-lg',
                     'placeholder' => 'Documento Personal de Identificación',
                ],
                'required' => true,

            ])
           ->add('nit', 'number', [
                'label' => 'NIT *',
                'attr' => [
                    'class' => 'form-control input-lg',
                    'placeholder' => 'Número de Identificación Tributaria sin guión',
                ],
                'required' => true,
                'constraints' => [
                    new Callback([$this, 'validarNIT']),
                ],

            ])
            ->add('telefono', 'number', [
                'label' => 'Teléfono',
                'translation_domain' => 'FOSUserBundle',
                'attr' => [
                    'class' => 'form-control input-lg',
                    'placeholder' => 'Teléfono',
                ],
                'required' => false,
            ])
            ->add('numeroIgss', 'number', [
                'label' => 'Número de afiliación IGSS',
                'attr' => [
                    'class' => 'form-control input-lg',
                    'placeholder' => 'Número afiliación IGSS (sin guión)',
                ],
                'required' => false,
            ])
            ->add('codigo', 'entity', [
                'class' => 'UserBundle:Codigo',
                'label' => false,
                'property' => 'codigoCompleto',
                'required' => true,
                'empty_value' => 'Seleccionar Código',
                'attr' => [
                    'class' => 'select2',
                ],

            ])
            ->add('submit', 'submit', [
                'label' => 'Guardar y agregar puesto',
                'attr' => [
                    'class' => 'btn btn-primary btn-block',
                ],
            ])

            ;

        $builder->addEventListener(
                FormEvents::POST_SUBMIT,
                [$this, 'onPostData']
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\UsuarioTrabajador',
        ));
    }
    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'user_registration';
    }

    /* Validar que el NIT no tenga guiones
    *
    * @param Array                     $data    contiene los datos del formulario
    * @param ExecutionContextInterface $context
    */
    public function validarNIT($nit, ExecutionContextInterface $context)
    {
        if (strpos($nit, '-') !== false) {
            $context->buildViolation('El NIT no puede tener guión')
                ->atPath('cliente_new')
                ->addViolation();
        }
    }

    /**
     * Validar que el nombre de usuario no tenga espacios en blanco.
     *
     * @param array                     $data    contiene los datos del formulario
     * @param ExecutionContextInterface $context
     */
    public function validarNombreUsuario($username, ExecutionContextInterface $context)
    {
        if (preg_match('/\s/', $username)) {
            $context->buildViolation('El nombre de usuario no puede tener espacios en blanco')
                ->atPath('trabajador_registration')
                ->addViolation();
        }
    }

    /**
     * Forma de setear imagen default.
     *
     * @param FormEvent $event Evento después de mandar la información del formulario
     */
    public function onPostData(FormEvent $event)
    {
        $usuario = $event->getData();

        //split name and substring to first letter.
        //explode params(delimiter, string, limit)
        //returns array of strings
        $first = substr(explode(' ', $usuario->getNombre(), 2)[0], 0, 1);
        $last = substr(explode(' ', $usuario->getApellidos(), 2)[0], 0, 1);
        $usuario->setInitials($first.$last);
    }
}
