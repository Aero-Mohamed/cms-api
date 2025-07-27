<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Http\Resources\GenericModelResource;
use App\Models\Entity;
use App\Repositories\Content\Contracts\EntityRecordRepositoryInterface;
use App\Repositories\Schema\Contracts\AttributeRepositoryInterface;
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
     * @param AttributeRepositoryInterface $attributeRepository
     * @param EntityRecordRepositoryInterface $recordRepository
     * @param EntityFormSchemaServiceInterface $formSchemaService
     */
    public function __construct(
        protected EntityRepositoryInterface $entityRepository,
        protected AttributeRepositoryInterface $attributeRepository,
        protected EntityRecordRepositoryInterface $recordRepository,
        protected EntityFormSchemaServiceInterface $formSchemaService,
    ) {
        $this->middleware('auth:api');
    }

    /**
     * List Entity Records
     *
     * Returns a paginated list of records for the specified entity type.
     *
     * @authenticated
     *
     * @urlParam entitySlug string required The slug of the entity to list records from. Example: article
     * @queryParam page integer Page number for pagination. Default: 1
     * @queryParam per_page integer Number of records per page. Default: 15
     *
     * @param Request $request
     * @param string $entitySlug The slug of the entity to list records from
     * @return JsonResponse
     * @throws ValidationException
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Example Title",
     *       "content": "Example content",
     *       "created_at": "2023-01-01T00:00:00.000000Z",
     *       "updated_at": "2023-01-01T00:00:00.000000Z"
     *     }
     *   ],
     *   "meta": {
     *     "current_page": 1,
     *     "from": 1,
     *     "last_page": 1,
     *     "per_page": 15,
     *     "to": 1,
     *     "total": 1
     *   }
     * }
     *
     * @response 404 {
     *   "message": "Entity with slug 'invalid-slug' not found"
     * }
     */
    public function index(Request $request, string $entitySlug): JsonResponse
    {
        $entity = $this->entityRepository->findBySlug($entitySlug);

        if (!$entity) {
            throw ValidationException::withMessages([
                'entity' => "Entity with slug '{$entitySlug}' not found"
            ]);
        }

        $perPage = (int) $request->input('per_page', 15);
        $page = (int) $request->input('page', 1);

        $records = $this->recordRepository->index($entity, $perPage, $page);

        return $this->success(
            data: GenericModelResource::collectionWithData($records, [
                'entityValues' => $this->recordRepository->recordsValues($entity, $records),
                'entityAttributes' => $this->attributeRepository->getAttributesForEntity($entity),
            ])
        );
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
