<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Repositories\Content\Contracts\EntityRecordRepositoryInterface;
use App\Repositories\Schema\Contracts\EntityRepositoryInterface;
use App\Services\Schema\Contracts\EntityFormSchemaServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @group Entity Content Management
 *
 * APIs for managing entity records
 */
class GenericEntityController extends Controller
{
    /**
     * @param EntityRepositoryInterface $entityRepository
     * @param EntityRecordRepositoryInterface $recordRepository
     * @param EntityFormSchemaServiceInterface $formSchemaService
     */
    public function __construct(
        protected EntityRepositoryInterface $entityRepository,
        protected EntityRecordRepositoryInterface $recordRepository,
        protected EntityFormSchemaServiceInterface $formSchemaService,
    ) {
        $this->middleware('auth:api');
    }

    /**
     * Create Entity Record
     *
     * Creates a new record for the specified entity type.
     *
     * @authenticated
     *
     * @urlParam entitySlug string required The slug of the entity to create a record for. Example: article
     *
     *
     * @param Request $request
     * @param string $entitySlug The slug of the entity to create a record for
     * @return JsonResponse
     * @throws ValidationException
     *
     * @response 201 {
     *   "message": "Record created successfully",
     *   "data": {}
     * }
     *
     * @response 404 {
     *   "message": "Entity with slug 'invalid-slug' not found"
     * }
     *
     * @response 422 {
     *   "message": "Validation failed",
     *   "errors": {
     *     "title": ["The title field is required."],
     *     "content": ["The content field is required."]
     *   }
     * }
     */
    public function store(Request $request, string $entitySlug): JsonResponse
    {
        // Find the entity by slug
        $entity = $this->entityRepository->findBySlug($entitySlug);

        if (!$entity) {
            throw ValidationException::withMessages([
                'entity' => "Entity with slug '{$entitySlug}' not found"
            ]);
        }

        // Generate validation rules for this entity
        $schema = $this->formSchemaService->generateFormSchema($entity);
        $validationRules = $schema['validation_rules'];

        // Validate the request data
        $validator = Validator::make($request->all(), $validationRules);
        $validatedData = $validator->validate();

        // Create the record
        $record = $this->recordRepository->create($entity, $validatedData);

        return $this->success(
            data: $this->recordRepository->findById($entity, $record->getKey()),
        );
    }

    /**
     * Get Entity Record with Relations
     *
     * Retrieves a record for the specified entity type with all its attributes and relations.
     *
     * @authenticated
     *
     * @urlParam entitySlug string required The slug of the entity to retrieve a record from. Example: article
     * @urlParam recordId integer required The ID of the record to retrieve. Example: 1
     *
     * @param string $entitySlug The slug of the entity to retrieve a record from
     * @param int $recordId The ID of the record to retrieve
     * @return JsonResponse
     * @throws ValidationException
     *
     * @response 200 {
     *   "data": {}
     * }
     *
     * @response 404 {
     *   "message": "Entity with slug 'invalid-slug' not found"
     * }
     *
     * @response 404 {
     *   "message": "Record not found"
     * }
     */
    public function show(string $entitySlug, int $recordId): JsonResponse
    {
        $entity = $this->entityRepository->findBySlug($entitySlug);

        if (!$entity) {
            throw ValidationException::withMessages([
                'entity' => "Entity with slug '{$entitySlug}' not found"
            ]);
        }

        // Get the record with relations
        try {
            $record = $this->recordRepository->findById($entity, $recordId);
        } catch (ModelNotFoundException $e) {
            throw ValidationException::withMessages([
                'record' => "Record not found"
            ]);
        }

        return $this->success(
            data: $record,
        );
    }
}
