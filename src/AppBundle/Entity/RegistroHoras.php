<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RegistroHoras.
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class RegistroHoras
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var int
     *
     * @ORM\Column(name="horasInvertidas", type="integer")
     */
    private $horasInvertidas;

    /**
     * @var [type]
     *
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\Usuario", inversedBy="registroHoras")
     * @ORM\JoinTable(name="horas_usuario")
     */
    private $usuarios;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Actividad")
     *
     * @var [type]
     */
    private $actividad;

    public function __construct()
    {
        $this->usuarios = new \Doctrine\Common\Collections\ArrayCollection();
    }
    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fecha.
     *
     * @param \DateTime $fecha
     *
     * @return RegistroHoras
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha.
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set horasInvertidas.
     *
     * @param int $horasInvertidas
     *
     * @return RegistroHoras
     */
    public function setHorasInvertidas($horasInvertidas)
    {
        $this->horasInvertidas = $horasInvertidas;

        return $this;
    }

    /**
     * Get horasInvertidas.
     *
     * @return int
     */
    public function getHorasInvertidas()
    {
        return $this->horasInvertidas;
    }

    /**
     * Add usuario.
     *
     * @param \UserBundle\Entity\Usuario $usuario
     *
     * @return RegistroHoras
     */
    public function addUsuario(\UserBundle\Entity\Usuario $usuario)
    {
        $this->usuarios[] = $usuario;

        return $this;
    }

    /**
     * Remove usuario.
     *
     * @param \UserBundle\Entity\Usuario $usuario
     */
    public function removeUsuario(\UserBundle\Entity\Usuario $usuario)
    {
        $this->usuarios->removeElement($usuario);
    }

    /**
     * Get usuarios.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }

    /**
     * Set actividad.
     *
     * @param \AppBundle\Entity\Actividad $actividad
     *
     * @return RegistroHoras
     */
    public function setActividad(\AppBundle\Entity\Actividad $actividad = null)
    {
        $this->actividad = $actividad;

        return $this;
    }

    /**
     * Get actividad.
     *
     * @return \AppBundle\Entity\Actividad
     */
    public function getActividad()
    {
        return $this->actividad;
    }

    public function __toString()
    {
        return $this->getActividad();
    }
}
