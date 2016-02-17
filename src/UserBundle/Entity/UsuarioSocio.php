<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;
use UserBundle\Entity\Usuario;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuario_socio")
 * @UniqueEntity(fields = "username", targetClass = "UserBundle\Entity\Usuario", message="fos_user.username.already_used")
 * @UniqueEntity(fields = "email", targetClass = "UserBundle\Entity\Usuario", message="fos_user.email.already_used")
 *
 *  El usuario Socio no necesita guardar todas los campos de un usuario trabajador
 * Solo necesita los básicos de FOSUserBundle
 * @author  Pablo Díaz soporte@newtonlabs.com.gt
 */
class UsuarioSocio extends Usuario
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __toString()
    {
        return $this->nombre.' '.$this->apellidos;
    }
}