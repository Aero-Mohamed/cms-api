<?php

namespace App\Repositories\Content\Contracts;

use App\Models\Entity;
use App\Models\Record;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface EntityRecordRepositoryInterface
{
    /**
     * Create a new record for the given entity with the provided data
     *
     * @param Entity $entity The entity to create a record for
     * @param array $data The data for the record's attributes
     * @return Record The created record
     */
    public function create(Entity $entity, array $data): Record;

    /**
     * Get a record with specific attributes and all relations with their attributes
     *
     * @param Entity $entity The entity to get the record from
     * @param int $recordId The ID of the record to retrieve
     * @return array The record data with attributes and relations
     */
    public function findById(Entity $entity, int $recordId): array;

    /**
     * Get a paginated list of records for the given entity with their attributes
     *
     * @param Entity $entity The entity to get records from
     * @param int $perPage Number of records per page
     * @param int $page Current page number
     * @return LengthAwarePaginator Paginated records
     */
    public function index(Entity $entity, int $perPage = 15, int $page = 1): LengthAwarePaginator;

    /**
     * Retrieve specific values for records with their corresponding data and attributes.
     *
     * @param Entity $entity The entity associated with the records
     * @param LengthAwarePaginator $records The paginated collection of records to process
     * @return Collection A collection of processed values for the given records
     */
    public function recordsValues(Entity $entity, LengthAwarePaginator $records): Collection;
}
