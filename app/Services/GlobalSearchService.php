<?php

namespace App\Services;

use App\Repositories\GlobalSearchRepository;

class GlobalSearchService
{
    protected GlobalSearchRepository $global_search_repository;

    public function __construct(GlobalSearchRepository $globalSearchRepository)
    {
        $this->global_search_repository = $globalSearchRepository;
    }


    /**
     * @param string $term
     * @return array
     */
    public function search(string $term): array
    {
        return $this->global_search_repository->search($term);
    }
}
