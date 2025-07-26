<?php

namespace App\Repositories\Content\Contracts;

use App\Models\Entity;
use App\Models\Record;

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
}
