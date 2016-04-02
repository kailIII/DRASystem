<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProyectoPresupuesto.
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ProyectoPresupuesto
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
     * @var string
     *
     * @ORM\Column(name="nombrePresupuesto", type="string")
     */
    private $nombrePresupuesto;

     /**
     * @var [type]
     *
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\Usuario", inversedBy="registroHoras")
     * @ORM\JoinTable(name="horas_usuario")
     */
    private $usuarios;

    /**
     * @ORM\OneToMany(targetEntity="RegistroHorasPresupuesto", mappedBy="proyecto" ,cascade={"persist","remove"})
     */
    private $presupuestoIndividual;

    public function __construct()
    {   
        $this->usuarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->presupuestoIndividual = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add presupuestoIndividual.
     *
     * @param \AppBundle\Entity\RegistroHorasPresupuesto $presupuestoIndividual
     *
     * @return ProyectoPresupuesto
     */
    public function addPresupuestoIndividual(\AppBundle\Entity\RegistroHorasPresupuesto $presupuestoIndividual)
    {
        $this->presupuestoIndividual[] = $presupuestoIndividual;
        $presupuestoIndividual->setProyecto($this); //esta línea es muy importante para que se guarda la relación

        return $this;
    }

    /**
     * Remove presupuestoIndividual.
     *
     * @param \AppBundle\Entity\RegistroHorasPresupuesto $presupuestoIndividual
     */
    public function removePresupuestoIndividual(\AppBundle\Entity\RegistroHorasPresupuesto $presupuestoIndividual)
    {
        $this->presupuestoIndividual->removeElement($presupuestoIndividual);
    }

    /**
     * Get presupuestoIndividual.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPresupuestoIndividual()
    {
        return $this->presupuestoIndividual;
    }

    /**
     * Set nombrePresupuesto.
     *
     * @param string $nombrePresupuesto
     *
     * @return ProyectoPresupuesto
     */
    public function setNombrePresupuesto($nombrePresupuesto)
    {
        $this->nombrePresupuesto = $nombrePresupuesto;

        return $this;
    }

    /**
     * Get nombrePresupuesto.
     *
     * @return string
     */
    public function getNombrePresupuesto()
    {
        return $this->nombrePresupuesto;
    }

   
    /**
     * Add usuario
     *
     * @param \UserBundle\Entity\Usuario $usuario
     *
     * @return ProyectoPresupuesto
     */
    public function addUsuario(\UserBundle\Entity\Usuario $usuario)
    {
        $this->usuarios[] = $usuario;

        return $this;
    }

    /**
     * Remove usuario
     *
     * @param \UserBundle\Entity\Usuario $usuario
     */
    public function removeUsuario(\UserBundle\Entity\Usuario $usuario)
    {
        $this->usuarios->removeElement($usuario);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }

    public function __toString()
    {
        return $this->nombrePresupuesto;
    }

}
