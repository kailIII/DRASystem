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
}
