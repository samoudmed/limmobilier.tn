<?php

// src/Service/PaginationService.php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Pagination {

    private $entityManager;
    private $requestStack;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack) {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    public function handlePagination() {
        $request = $this->requestStack->getCurrentRequest();
        $session = $request->getSession();
        $params = $session->get('params', []);

        if ($request->isMethod('POST')) {
            $params = $request->request->all();
            if ($params['action'] == 'Chercher') {
                $session->set('params', $params);
            }
        }

        return $params;
    }

    public function paginate(string $entityClass, $joins, int $currentPage = 1, ?array $params = null, int $pageSize = 40): array {
        // Get your repository
        $repository = $this->entityManager->getRepository($entityClass);

        // Start building the query
        $queryBuilder = $repository->createQueryBuilder('e');

        foreach ($joins as $join) {
            $queryBuilder->leftJoin($join['join'], $join['alias']);
        }
        // Add WHERE conditions based on provided parameters
        if ($params !== null) {
            foreach ($params as $key => $value) {
                if (($value !== null) && ($value !== '')) {
                    // Add WHERE clause dynamically
                    switch ($key) {
                        case 'fullName':
                            $queryBuilder->andWhere(
                                    $queryBuilder->expr()->orX(
                                            $queryBuilder->expr()->like('CONCAT(e.firstName, \' \', e.lastName)', ':param'),
                                            $queryBuilder->expr()->like('CONCAT(e.lastName, \' \', e.firstName)', ':param')
                                    )
                            )->setParameter('param', '%' . trim($value) . '%');
                            break;
                        case 'id':
                            $queryBuilder->andWhere("e.$key = :$key")
                                    ->setParameter($key, trim($value));
                            break;
                        case 'number':
                            $queryBuilder->andWhere("e.$key = :$key")
                                    ->setParameter($key, trim($value));
                            break;
                        case 'country':
                            $queryBuilder->andWhere("e.$key = :$key")
                                    ->setParameter($key, trim($value));
                            break;
                        case 'createdAt':
                            $queryBuilder->andWhere("e.$key LIKE :$key")
                                    ->setParameter($key, '%'.trim($value).'%');
                            break;
                        case 'action':
                            break;
                        default:
                            if (is_array($value)) {
                                $column = key($value);
                                $searchValue = current($value);
                                // Use the extracted column and search value in the query builder
                                if ($column == 'c.fullName' && ($searchValue !== null) && ($searchValue !== '')) {
                                    $queryBuilder->andWhere(
                                            $queryBuilder->expr()->orX(
                                                    $queryBuilder->expr()->like('CONCAT(c.firstName, \' \', c.lastName)', ':param'),
                                                    $queryBuilder->expr()->like('CONCAT(c.lastName, \' \', c.firstName)', ':param')
                                            )
                                    )->setParameter('param', '%' . trim($searchValue) . '%');
                                }
                            } else {
                                $queryBuilder->andWhere("e.$key LIKE :$key")
                                        ->setParameter($key, '%'.trim($value).'%');
                            }

                            break;
                    }
                }
            }
        }
        $queryBuilder->orderBy('e.id', 'DESC');
        // Count total items
        $totalItemsQuery = clone $queryBuilder;
        $totalItemsQuery->select('COUNT(e.id)');
        $totalItems = $totalItemsQuery->getQuery()->getSingleScalarResult();

        // Proceed with pagination only if total items count is greater than 0
        if ($totalItems > 0) {
            // Calculate offset
            $offset = ($currentPage - 1) * $pageSize;

            // Paginate the query
            $paginatorQuery = clone $queryBuilder;
            $paginatorQuery->setFirstResult($offset)
                    ->setMaxResults($pageSize);
            $paginator = $paginatorQuery->getQuery()->getResult();

            // Calculate page count
            $pageCount = ceil($totalItems / $pageSize);

            $startPage = max(1, $currentPage - 5);
            $endPage = min($pageCount, $currentPage + 5);
            $pagesInRange = range($startPage, $endPage);

            // Return paginated data and pagination metadata
            return [
                'items' => $paginator,
                'currentPage' => $currentPage,
                'pageSize' => $pageSize,
                'totalItems' => $totalItems,
                'pageCount' => $pageCount,
                'pagesInRange' => $pagesInRange,
            ];
        } else {
            // If no items found, return empty result
            return [
                'items' => [],
                'currentPage' => $currentPage,
                'pageSize' => $pageSize,
                'totalItems' => 0,
                'pageCount' => 0,
                'pagesInRange' => [],
            ];
        }
    }
}
