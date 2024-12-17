<?php

namespace App\Dto;

class PostSearchByCriteriaDto
{
    public array $filters = [];
    public ?string $sort = null;
    public string $direction = 'asc';
    public int $limit = 10;
    public int $page = 1;
    public ?string $with = null;
    public ?string $commentFilter = null;

    public function __construct(
        array $filters = [],
        ?string $sort = null,
        string $direction = 'asc',
        int $limit = 10,
        int $page = 1,
        ?string $with = null,
        ?string $commentFilter = null
    ) {
        $this->filters = $filters;
        $this->sort = $sort;
        $this->direction = $direction;
        $this->limit = $limit;
        $this->page = $page;
        $this->with = $with;
        $this->commentFilter = $commentFilter;
    }
}
