<?php

namespace CostoBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CostoRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CostoRepository extends EntityRepository
{
    /**
     * Query para buscar solo un costo por usuario.
     * Devuelve un costo o null.
     *
     * @param DATE    $fechaInicio
     * @param DATE    $fechaFinal
     * @param Usuario $usuario
     *
     * @return Array Costo de un elemento.
     */
    public function findByFechaAndUsuario($fechaInicio, $fechaFinal, $usuario)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->select('costo.costo')
            ->from('CostoBundle:Costo', 'costo')
            ->where('costo.fechaInicio >= :fechaInicio')
            ->andWhere('costo.fechaFinal <= :fechaFinal')
            ->andWhere('costo.usuario = :usuario')
            ->setParameter('fechaInicio', $fechaInicio)
            ->setParameter('fechaFinal', $fechaFinal)
            ->setParameter('usuario', $usuario);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * query para buscar todos las entidades costo que coincidan con la fecha
     * ingresada.
     * Puede retornar más de una entdidad.
     *
     * @param DATE $fechaInicio
     * @param DaTe $fechaFinal
     *
     * @return Array Costo              [
     */
    public function getQueryCostoPorFecha($fechaInicio, $fechaFinal)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->select('costo.costo')
            ->from('CostoBundle:Costo', 'costo')
            ->where('costo.fechaInicio >= :fechaInicio')
            ->andWhere('costo.fechaFinal <= :fechaFinal')
            ->setParameter('fechaInicio', $fechaInicio)
            ->setParameter('fechaFinal', $fechaFinal);

        return $qb->getQuery()->getResult();
    }
}
