<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * RegistroHorasRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RegistroHorasRepository extends EntityRepository
{
    /**
     * Método que devuleve los registros de un Proyecto.
     *
     * @param ProyectoPresupuesto $proyecto
     *
     * @return RegistroHoras
     */
    public function findByProyecto($proyecto)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->select('registro')
            ->from('AppBundle:RegistroHoras', 'registro')
            ->Where('registro.proyectoPresupuesto = :proyecto')
            ->setParameter('proyecto', $proyecto);

        return $qb->getQuery()->getResult();
    }

    public function findByFechaAndUsuario($fechaInicio, $fechaFinal, $usuario)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->select('registro')
            ->from('AppBundle:RegistroHoras', 'registro')
            ->Where('registro.fechaHoras >= :fechaInicio')
            ->andWhere('registro.fechaHoras <= :fechaFinal')
            ->andWhere('registro.ingresadoPor = :usuario')
            ->setParameter('fechaInicio', $fechaInicio)
            ->setParameter('fechaFinal', $fechaFinal)
            ->setParameter('usuario', $usuario);

        return $qb->getQuery()->getResult();
    }
    public function findByFechaAndUsuarioExtra($fechaInicio, $fechaFinal, $usuario, $extra)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->select('registro')
            ->from('AppBundle:RegistroHoras', 'registro')
            ->Where('registro.fechaHoras >= :fechaInicio')
            ->andWhere('registro.fechaHoras <= :fechaFinal')
            ->andWhere('registro.ingresadoPor = :usuario')
            ->andWhere('registro.horasExtraordinarias = :extra')
            ->setParameter('fechaInicio', $fechaInicio)
            ->setParameter('fechaFinal', $fechaFinal)
            ->setParameter('usuario', $usuario)
            ->setParameter('extra', $extra);

        return $qb->getQuery()->getResult();
    }

    public function findByFechaAndClienteExtra($fechaInicio, $fechaFinal, $cliente, $extra)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->select('registro')
            ->from('AppBundle:RegistroHoras', 'registro')
            ->Where('registro.fechaHoras >= :fechaInicio')
            ->andWhere('registro.fechaHoras <= :fechaFinal')
            ->andWhere('registro.cliente = :cliente')
            ->andWhere('registro.horasExtraordinarias = :extra')
            ->setParameter('fechaInicio', $fechaInicio)
            ->setParameter('fechaFinal', $fechaFinal)
            ->setParameter('cliente', $cliente)
            ->setParameter('extra', $extra);

        return $qb->getQuery()->getResult();
    }

    public function findByFechaAndPresupuestoExtra($fechaInicio, $fechaFinal, $presupuesto, $extra)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->select('registro')
            ->from('AppBundle:RegistroHoras', 'registro')
            ->Where('registro.fechaHoras >= :fechaInicio')
            ->andWhere('registro.fechaHoras <= :fechaFinal')
            ->andWhere('registro.proyectoPresupuesto = :proyectoPresupuesto')
            ->andWhere('registro.horasExtraordinarias = :extra')
            ->setParameter('fechaInicio', $fechaInicio)
            ->setParameter('fechaFinal', $fechaFinal)
            ->setParameter('proyectoPresupuesto', $presupesto)
            ->setParameter('extra', $extra);

        return $qb->getQuery()->getResult();
    }
}
