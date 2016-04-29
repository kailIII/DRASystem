<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ProyectoPresupuesto.
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity("nombrePresupuesto")
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
     * @ORM\Column(name="nombre_presupuesto", type="string")
     */
    private $nombrePresupuesto;

    /**
     * Honorarios a recolectar (opcional).
     *
     * @var float
     * @ORM\Column(name="honorarios", type="float", nullable = true)
     */
    private $honorarios;

    /**
     * @ORM\OneToMany(targetEntity="RegistroHorasPresupuesto", mappedBy="proyecto" ,cascade={"persist","remove"})
     */
    private $presupuestoIndividual;

    /**
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\UsuarioSocio", inversedBy="presupuestos")
     * @ORM\JoinTable(name="presupuesto_socios")
     */
    private $socios;
    /**
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\UsuarioTrabajador", inversedBy="presupuestos")
     * @ORM\JoinTable(name="presupuestos_gerente")
     */
    private $gerentes;

    public function __construct()
    {
        $this->socios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->presupuestoIndividual = new \Doctrine\Common\Collections\ArrayCollection();
        $this->gerentes = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function __toString()
    {
        return $this->nombrePresupuesto;
    }

    /**
     * Set honorarios.
     *
     * @param float $honorarios
     *
     * @return ProyectoPresupuesto
     */
    public function setHonorarios($honorarios)
    {
        $this->honorarios = $honorarios;

        return $this;
    }

    /**
     * Get honorarios.
     *
     * @return float
     */
    public function getHonorarios()
    {
        return $this->honorarios;
    }

    /**
     * Add socio.
     *
     * @param \UserBundle\Entity\UsuarioSocio $socio
     *
     * @return ProyectoPresupuesto
     */
    public function addSocio(\UserBundle\Entity\UsuarioSocio $socio)
    {
        $this->socios[] = $socio;

        return $this;
    }

    /**
     * Remove socio.
     *
     * @param \UserBundle\Entity\UsuarioSocio $socio
     */
    public function removeSocio(\UserBundle\Entity\UsuarioSocio $socio)
    {
        $this->socios->removeElement($socio);
    }

    /**
     * Get socios.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSocios()
    {
        return $this->socios;
    }

    /**
     * Add gerente.
     *
     * @param \UserBundle\Entity\UsuarioTrabajador $gerente
     *
     * @return ProyectoPresupuesto
     */
    public function addGerente(\UserBundle\Entity\UsuarioTrabajador $gerente)
    {
        $this->gerentes[] = $gerente;

        return $this;
    }

    /**
     * Remove gerente.
     *
     * @param \UserBundle\Entity\UsuarioTrabajador $gerente
     */
    public function removeGerente(\UserBundle\Entity\UsuarioTrabajador $gerente)
    {
        $this->gerentes->removeElement($gerente);
    }

    /**
     * Get gerentes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGerentes()
    {
        return $this->gerentes;
    }
}
