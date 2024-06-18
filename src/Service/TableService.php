<?php

namespace App\Service;

use App\Repository\TableARepository;
use App\Repository\TableBRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

readonly class TableService
{
    public function __construct(
        private CacheInterface     $cache,
        private PaginatorInterface $paginator,
        private TableARepository   $tableARepository,
        private TableBRepository   $tableBRepository,
    )
    {
    }

    public function getDataFromTableA()
    {
        $cacheKey = 'data_from_table_a';

        return $this->cache->get($cacheKey, function (ItemInterface $item) {
            $item->expiresAfter(3600);
            return $this->tableARepository->findAll();
        });
    }

    public function getDataFromTableB()
    {
        $cacheKey = 'data_from_table_b';

        return $this->cache->get($cacheKey, function (ItemInterface $item) {
            $item->expiresAfter(3600);
            return $this->tableBRepository->findAll();
        });
    }

    public function clearCache($cacheKey): void
    {
        $this->cache->delete($cacheKey);
    }

    public function getPagination(array $dataFromTable, int $request): PaginationInterface
    {
        return $this->paginator->paginate(
            $dataFromTable,
            $request,
            10
        );
    }
}
