<?php

namespace App\Service\Pagination;

class PaginationService
{
    const PERPAGE = 9;

    public function totalElement( $allElement): int
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
}
