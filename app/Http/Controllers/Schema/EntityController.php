<?php

namespace App\Http\Controllers\Schema;

use App\Dtos\Schema\CreateEntityData;
use App\Dtos\Schema\CreateEntityRelationshipData;
use App\Dtos\Schema\UpdateEntityData;
use App\Enums\SystemRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\EntityRelationshipResource;
use App\Http\Resources\EntityResource;
use App\Models\Entity;
use App\Models\EntityRelationship;
use App\Repositories\Schema\Contracts\EntityRepositoryInterface;
use App\Services\Schema\Actions\InvalidateEntityCacheAction;
use App\Services\Schema\Actions\InvalidateEntityRelationshipCacheAction;
use App\Services\Schema\EntityFormSchemaService;
use App\Services\Schema\Support\EntityFormCacheKeyGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 * @group Entities
 */
class EntityController extends Controller
{
    /**
     * @param EntityRepositoryInterface $entityRepository
     * @param InvalidateEntityCacheAction $invalidateEntityCacheAction
     * @param InvalidateEntityRelationshipCacheAction $invalidateRelationCacheAction
     */
    public function __construct(
        protected EntityRepositoryInterface $entityRepository,
        protected InvalidateEntityCacheAction $invalidateEntityCacheAction,
        protected InvalidateEntityRelationshipCacheAction $invalidateRelationCacheAction,
        protected EntityFormSchemaService $formSchemaService,
    ) {
        $this->middleware('role:' . SystemRoleEnum::ADMIN->value);
    }

    /**
     * Get all Entities
     *
     * Returns a list of all entities.
     * @authenticated
     *
     * @return JsonResponse
     *
     * @response 200 {
     *   "success": true,
     *   "status_code": 200,
     *   "message": null,
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Product",
     *       "slug": "product",
     *       "description": "Product entity",
     *       "created_at": "2025-07-25T15:00:00.000000Z",
     *       "updated_at": "2025-07-25T15:00:00.000000Z"
     *     }
     *   ],
     *   "errors": null
     * }
     */
    public function index(): JsonResponse
    {
        $entities = $this->entityRepository->getAll();

        return $this->success(
            data: EntityResource::collection($entities)
        );
    }

    /**
     * Create an Entity
     *
     * This endpoint allows to create a new entity.
     * @authenticated
     *
     * @param CreateEntityData $data
     * @return JsonResponse
     *
     * @bodyParam name string required The name of the entity. Example: Product
     * @bodyParam slug string required The slug of the entity. Example: product
     * @bodyParam description string The description of the entity. Example: Product entity
     *
     * @response 201 {
     *   "success": true,
     *   "status_code": 201,
     *   "message": "Entity created successfully",
     *   "data": {
     *     "id": 1,
     *     "name": "Product",
     *     "slug": "product",
     *     "description": "Product entity",
     *     "created_at": "2025-07-25T15:00:00.000000Z",
     *     "updated_at": "2025-07-25T15:00:00.000000Z"
     *   },
     *   "errors": null
     * }
     */
    public function store(CreateEntityData $data): JsonResponse
    {
        $entity = $this->entityRepository->create($data);
        $schema = $this->formSchemaService->generateFormSchema($entity);

        return $this->success(
            data: (new EntityResource($entity))->additional($schema),
            message: 'Entity created successfully',
            statusCode: ResponseAlias::HTTP_CREATED
        );
    }

    /**
     * Show entity
     *
     * This endpoint allows getting a specific entity by id.
     * @authenticated
     *
     * @param Entity $entity
     * @return JsonResponse
     *
     * @response 200 {
     *   "success": true,
     *   "status_code": 200,
     *   "message": null,
     *   "data": {
     *     "id": 1,
     *     "name": "Product",
     *     "slug": "product",
     *     "description": "Product entity",
     *     "created_at": "2025-07-25T15:00:00.000000Z",
     *     "updated_at": "2025-07-25T15:00:00.000000Z"
     *   },
     *   "errors": null
     * }
     */
    public function show(Entity $entity): JsonResponse
    {
        $schema = $this->formSchemaService->generateFormSchema($entity);
        return $this->success(
            data: (new EntityResource($entity))->additional($schema)
        );
    }

    /**
     * Update Entity
     *
     * This endpoint allows to update an entity's information.
     * @authenticated
     *
     * @param UpdateEntityData $data
     * @param Entity $entity
     * @return JsonResponse
     *
     * @bodyParam name string The name of the entity. Example: Updated Product
     * @bodyParam slug string The slug of the entity. Example: updated-product
     * @bodyParam description string The description of the entity. Example: Updated product entity
     *
     * @response 200 {
     *   "success": true,
     *   "status_code": 200,
     *   "message": "Entity updated successfully",
     *   "data": {
     *     "id": 1,
     *     "name": "Updated Product",
     *     "slug": "updated-product",
     *     "description": "Updated product entity",
     *     "created_at": "2025-07-25T15:00:00.000000Z",
     *     "updated_at": "2025-07-26T13:45:00.000000Z"
     *   },
     *   "errors": null
     * }
     */
    public function update(UpdateEntityData $data, Entity $entity): JsonResponse
    {
        $entity = $this->entityRepository->update($entity, $data);

        return $this->success(
            data: new EntityResource($entity),
            message: 'Entity updated successfully'
        );
    }

    /**
     * Delete entity
     *
     * This endpoint allows to delete a specific entity by id.
     * @authenticated
     *
     * @param Entity $entity
     * @return JsonResponse
     *
     * @response 200 {
     *   "success": true,
     *   "status_code": 200,
     *   "message": "Entity deleted successfully",
     *   "data": null,
     *   "errors": null
     * }
     */
    public function destroy(Entity $entity): JsonResponse
    {
        $this->invalidateEntityCacheAction->handler($entity);
        $this->entityRepository->delete($entity);


        return $this->success(
            message: 'Entity deleted successfully'
        );
    }

    /**
     * Create Entity Relationship
     *
     * This endpoint allows to create a new relationship between entities.
     * @authenticated
     *
     * @param CreateEntityRelationshipData $data
     * @return JsonResponse
     *
     * @bodyParam type string required The type of relationship (1:1, 1:N, N:N). Example: 1:N
     * @bodyParam from_entity_id integer required The ID of the source entity. Example: 1
     * @bodyParam to_entity_id integer required The ID of the target entity. Example: 2
     * @bodyParam name string optional The name of the relationship (auto-generated if not provided). Example: has_many
     * @bodyParam inverse_name string optional The inverse name of the relationship (auto-generated if not provided). Example: belongs_to
     *
     * @response 201 {
     *   "success": true,
     *   "status_code": 201,
     *   "message": "Relationship created successfully",
     *   "data": {
     *     "id": 1,
     *     "type": "1:N",
     *     "name": "has_many",
     *     "inverse_name": "belongs_to",
     *     "from_entity_id": 1,
     *     "to_entity_id": 2
     *   },
     *   "errors": null
     * }
     */
    public function createRelationship(CreateEntityRelationshipData $data): JsonResponse
    {
        $relationship = $this->entityRepository->createRelationship($data);
        $this->invalidateRelationCacheAction->handler($relationship);

        return $this->success(
            data: $relationship,
            message: 'Relationship created successfully',
            statusCode: ResponseAlias::HTTP_CREATED
        );
    }

    /**
     * Delete Entity Relationship
     *
     * This endpoint allows deleting a specific relationship by id.
     * @authenticated
     *
     * @param EntityRelationship $relationship
     * @return JsonResponse
     *
     * @response 200 {
     *   "success": true,
     *   "status_code": 200,
     *   "message": "Relationship deleted successfully",
     *   "data": null,
     *   "errors": null
     * }
     */
    public function deleteRelationship(EntityRelationship $relationship): JsonResponse
    {
        $this->invalidateRelationCacheAction->handler($relationship);
        $this->entityRepository->deleteRelationship($relationship);

        return $this->success(
            message: 'Relationship deleted successfully'
        );
    }

    /**
     * Get Entity Relationships
     *
     * This endpoint allows retrieving all relationships for a specific entity.
     * @authenticated
     *
     * @param Entity $entity
     * @return JsonResponse
     *
     * @response 200 {
     *   "success": true,
     *   "status_code": 200,
     *   "message": null,
     *   "data": [
     *     {
     *       "id": 1,
     *       "type": "1:N",
     *       "name": "has_many",
     *       "inverse_name": "belongs_to",
     *       "from_entity_id": 1,
     *       "to_entity_id": 2
     *     }
     *   ],
     *   "errors": null
     * }
     */
    public function getRelationships(Entity $entity): JsonResponse
    {
        $relationships = $this->entityRepository->getEntityRelationships($entity);

        return $this->success(
            data: EntityRelationshipResource::collection($relationships)
        );
    }
}
