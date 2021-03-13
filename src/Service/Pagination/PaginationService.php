<?php

namespace App\Service\Pagination;

use Doctrine\ORM\EntityManagerInterface;

class PaginationService
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    const PERPAGE = 9;

    public function totalElement($allElement): int
    {
        return count($allElement);
    }
    public function totalPage(int $totalElement)
    {
        return ($totalElement == $this::PERPAGE) ? 1 :  floor($totalElement / $this::PERPAGE) + 1;;
    }

    public function pageCurrent(int $pageCurrent, $totalPage)
    {
        $pageCurrent = ($pageCurrent > $totalPage) ? $totalPage : $pageCurrent;
        $pageCurrent = ($pageCurrent == 0) ? 1 : $pageCurrent;
        return $pageCurrent;
    }

    public function pagination(string $entity = null, $pageCurrent, $colunm = null, int $id = null)
    {
        if ($colunm == null) {
            return $this->all($entity, $pageCurrent);
        }
        return $this->allBy($entity, $colunm, $id, $pageCurrent);
    }

    private function all($entity, $pageCurrent)
    {
        $alias = $this->alias($entity);
        $query = $this->em->createQuery("SELECT DISTINCT $alias FROM $entity  $alias ORDER BY $alias.updatedAt DESC");
        $query->setMaxResults($this::PERPAGE)
            ->setFirstResult(($pageCurrent-1)*$this::PERPAGE);
        return $query->getResult();
    }


    private function allBy($entity, $colunm, $id, $pageCurrent)
    {
        $alias = $this->alias($entity);
        $aliasColumn = $this->aliasColumn($colunm);
        $query =  $this->em->createQuery("SELECT $alias
         FROM $entity $alias 
         JOIN $alias.$colunm $aliasColumn
         WHERE $aliasColumn.id = ?1 
         ");
        return  $query->setMaxResults($this::PERPAGE)
            ->setFirstResult(($pageCurrent-1)*$this::PERPAGE)
            ->setParameter(1, $id)
            ->getResult();
    }


    private function alias(string $entity)
    {
        return strtolower((explode("\\", $entity)[2][0]));
    }


    private function aliasColumn(string $entity)
    {
        return strtolower($entity[0]);
    }
}
