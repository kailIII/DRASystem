<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuario_trabajador")
 * @UniqueEntity(fields = "username", targetClass = "UserBundle\Entity\Usuario", message="fos_user.username.already_used")
 * @UniqueEntity(fields = "email", targetClass = "UserBundle\Entity\Usuario", message="fos_user.email.already_used")
 * 
 * @UniqueEntity(fields = {"nit","dpi"}, targetClass = "UserBundle\Entity\UsuarioTrabajador", message="El dpi o nit debe ser único")
 * Esta entidad cubre los tipos de Asistente, Supervisor y Gerente.
 *
 * @author  Pablo Díaz soporte@newtonlabs.com.gt
 */
class UsuarioTrabajador extends Usuario
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var date fecha de ingreso a la empresa.
     * @ORM\Column(name="fechaIngreso", type="date")
     */
    protected $fechaIngreso;

    /**
     * @var date fecha de egreso de la empresa.
     * @ORM\Column(name="fechaEgreso", type="date",nullable=true)
     */
    protected $fechaEgreso;

    /**
     * @var string DPI del trabajador
     * @ORM\Column(name="dpi",type="string",length=20, unique=true)
     */
    protected $dpi;

    /**
     * Número de identificación tributaria.
     *
     * @var string
     * @ORM\Column(name="nit", type="string",length=20,unique=true)
     */
    protected $nit;

    /**
     * @var int
     * @ORM\Column(name="telefono", type="string",length=15,nullable=true)
     */
    protected $telefono;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="DatosPrestaciones", mappedBy="usuario")
     */
    protected $datosPrestaciones;

    /**
     * Set fechaIngreso.
     *
     * @param \DateTime $fechaIngreso
     *
     * @return UsuarioTrabajador
     */
    public function setFechaIngreso($fechaIngreso)
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    /**
     * Get fechaIngreso.
     *
     * @return \DateTime
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * Set fechaEgreso.
     *
     * @param \DateTime $fechaEgreso
     *
     * @return UsuarioTrabajador
     */
    public function setFechaEgreso($fechaEgreso)
    {
        $this->fechaEgreso = $fechaEgreso;

        return $this;
    }

    /**
     * Get fechaEgreso.
     *
     * @return \DateTime
     */
    public function getFechaEgreso()
    {
        return $this->fechaEgreso;
    }

    /**
     * Set dpi.
     *
     * @param string $dpi
     *
     * @return Usuario
     */
    public function setDpi($dpi)
    {
        $this->dpi = $dpi;

        return $this;
    }

    /**
     * Get dpi.
     *
     * @return string
     */
    public function getDpi()
    {
        return $this->dpi;
    }

    /**
     * Set nit.
     *
     * @param string $nit
     *
     * @return Usuario
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit.
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set telefono.
     *
     * @param int $telefono
     *
     * @return Usuario
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono.
     *
     * @return int
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    public function __toString()
    {
        return $this->nombre.' '.$this->apellidos;
    }

    /**
     * Add datosPrestacione.
     *
     * @param \UserBundle\Entity\DatosPrestaciones $datosPrestacione
     *
     * @return UsuarioTrabajador
     */
    public function addDatosPrestaciones(\UserBundle\Entity\DatosPrestaciones $datosPrestaciones)
    {
        $this->datosPrestaciones[] = $datosPrestaciones;

        return $this;
    }

    /**
     * Remove datosPrestacione.
     *
     * @param \UserBundle\Entity\DatosPrestaciones $datosPrestacione
     */
    public function removeDatosPrestaciones(\UserBundle\Entity\DatosPrestaciones $datosPrestaciones)
    {
        $this->datosPrestaciones->removeElement($datosPrestaciones);
    }

    /**
     * Get datosPrestaciones.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDatosPrestaciones()
    {
        return $this->datosPrestaciones;
    }
}
