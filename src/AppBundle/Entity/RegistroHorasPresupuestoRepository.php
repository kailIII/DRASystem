<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * RegistroHorasRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RegistroHorasPresupuestoRepository extends EntityRepository
{
    public function findByCliente($proyecto, $cliente)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->select('registro')
            ->from('AppBundle:RegistroHorasPresupuesto', 'registro')
            ->where('registro.proyecto = :proyecto')
            ->andWhere('registro.cliente = :cliente')
            ->setParameter('proyecto', $proyecto)
            ->setParameter('cliente', $cliente)
            ;

        return $qb->getQuery()->getResult();
    }

    public function findByUsuario($proyecto, $usuario)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->select('registro')
            ->from('AppBundle:RegistroHorasPresupuesto', 'registro')
            ->where('registro.proyecto = :proyecto')
            ->andWhere('registro.usuario = :usuario')
            ->setParameter('proyecto', $proyecto)
            ->setParameter('usuario', $usuario)
            ;

        return $qb->getQuery()->getResult();
    }

    public function calcularHorasPresupuestoPorActividad($proyecto, $actividad)
    {
        $qb = $this->createQueryBuilder('r');
        $qb
            ->select('sum(distinct(r.horasPresupuestadas))')
            ->innerJoin('AppBundle:Area', 'area', 'with', 'r.area = area.id')
            ->innerJoin('AppBundle:Actividad', 'act', 'with', 'act.area = area.id ')
            ->where('r.proyecto = :proyecto')
            ->andWhere('act = :actividad')
            ->setParameter('proyecto', $proyecto)
            ->setParameter('actividad', $actividad);

        return $qb->getQuery()->getSingleScalarResult();
    }

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
            ->from('AppBundle:RegistroHorasPresupuesto', 'registro')
            ->Where('registro.proyecto = :proyecto')
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
            ->Where('registro.fechaCreacion >= :fechaInicio')
            ->andWhere('registro.fechaCreacion <= :fechaFinal')
            ->andWhere('registro.usuario = :usuario')
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
            ->from('AppBundle:RegistroHorasPresupuesto', 'registro')
            ->Where('registro.fechaCreacion >= :fechaInicio')
            ->andWhere('registro.fechaCreacion <= :fechaFinal')
            ->andWhere('registro.usuario = :usuario')
            ->setParameter('fechaInicio', $fechaInicio)
            ->setParameter('fechaFinal', $fechaFinal)
            ->setParameter('usuario', $usuario);

        return $qb->getQuery()->getResult();
    }

    public function findByFechaAndClienteExtra($fechaInicio, $fechaFinal, $cliente, $extra)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->select('registro')
            ->from('AppBundle:RegistroHorasPresupuesto', 'registro')
            ->Where('registro.fechaCreacion >= :fechaInicio')
            ->andWhere('registro.fechaCreacion <= :fechaFinal')
            ->andWhere('registro.cliente = :cliente')
            ->setParameter('fechaInicio', $fechaInicio)
            ->setParameter('fechaFinal', $fechaFinal)
            ->setParameter('cliente', $cliente);

        return $qb->getQuery()->getResult();
    }

    public function findByFechaAndPresupuestoExtra($fechaInicio, $fechaFinal, $presupuesto, $extra)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->select('registro')
            ->from('AppBundle:RegistroHorasPresupuesto', 'registro')
            ->Where('registro.fechaCreacion >= :fechaInicio')
            ->andWhere('registro.fechaCreacion <= :fechaFinal')
            ->andWhere('registro.proyecto = :proyectoPresupuesto')
            ->setParameter('fechaInicio', $fechaInicio)
            ->setParameter('fechaFinal', $fechaFinal)
            ->setParameter('proyectoPresupuesto', $presupuesto);

        return $qb->getQuery()->getResult();
    }
}
