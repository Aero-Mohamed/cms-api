<?php

namespace App\Http\Controllers\Schema;

use App\Dtos\Schema\AttributeEntityData;
use App\Dtos\Schema\CreateAttributeData;
use App\Dtos\Schema\UpdateAttributeData;
use App\Enums\SystemRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttributeResource;
use App\Models\Attribute;
use App\Models\Entity;
use App\Repositories\Schema\Contracts\AttributeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 * @group Attributes
 */
class AttributeController extends Controller
{
    /**
     * @param AttributeRepositoryInterface $attributeRepository
     */
    public function __construct(
        protected AttributeRepositoryInterface $attributeRepository
    ) {
        $this->middleware('role:' . SystemRoleEnum::ADMIN->value);
    }

    /**
     * Get all Attributes
     *
     * Returns a list of all attributes.
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
     *       "name": "Title",
     *       "slug": "title",
     *       "data_type": "string",
     *       "is_required": true,
     *       "is_unique": true,
     *       "default_value": null,
     *       "created_at": "2025-07-25T15:00:00.000000Z",
     *       "updated_at": "2025-07-25T15:00:00.000000Z"
     *     }
     *   ],
     *   "errors": null
     * }
     */
    public function index(): JsonResponse
    {
        $attributes = $this->attributeRepository->getAll();

        return $this->success(
            data: AttributeResource::collection($attributes)
        );
    }

    /**
     * Create an Attribute
     *
     * This endpoint allows creating a new attribute.
     * @authenticated
     *
     * @param CreateAttributeData $data
     * @return JsonResponse
     *
     * @bodyParam name string required The name of the attribute. Example: Title
     * @bodyParam slug string required The slug of the attribute. Example: title
     * @bodyParam data_type string required The data type of the attribute. Example: string
     * @bodyParam is_required boolean required Whether the attribute is required. Example: true
     * @bodyParam is_unique boolean required Whether the attribute is unique. Example: true
     * @bodyParam default_value string The default value of the attribute. Example: Default Title
     *
     * @response 201 {
     *   "success": true,
     *   "status_code": 201,
     *   "message": "Attribute created successfully",
     *   "data": {
     *     "id": 1,
     *     "name": "Title",
     *     "slug": "title",
     *     "data_type": "string",
     *     "is_required": true,
     *     "is_unique": true,
     *     "default_value": null,
     *     "created_at": "2025-07-25T15:00:00.000000Z",
     *     "updated_at": "2025-07-25T15:00:00.000000Z"
     *   },
     *   "errors": null
     * }
     */
    public function store(CreateAttributeData $data): JsonResponse
    {
        $attribute = $this->attributeRepository->create($data);

        return $this->success(
            data: new AttributeResource($attribute),
            message: 'Attribute created successfully',
            statusCode: ResponseAlias::HTTP_CREATED
        );
    }

    /**
     * Show attribute
     *
     * This endpoint allows to get a specific attribute by id.
     * @authenticated
     *
     * @param Attribute $attribute
     * @return JsonResponse
     *
     * @response 200 {
     *   "success": true,
     *   "status_code": 200,
     *   "message": null,
     *   "data": {
     *     "id": 1,
     *     "name": "Title",
     *     "slug": "title",
     *     "data_type": "string",
     *     "is_required": true,
     *     "is_unique": true,
     *     "default_value": null,
     *     "created_at": "2025-07-25T15:00:00.000000Z",
     *     "updated_at": "2025-07-25T15:00:00.000000Z"
     *   },
     *   "errors": null
     * }
     */
    public function show(Attribute $attribute): JsonResponse
    {
        return $this->success(
            data: new AttributeResource($attribute)
        );
    }

    /**
     * Update Attribute
     *
     * This endpoint allows updating an attribute's information.
     * @authenticated
     *
     * @param UpdateAttributeData $data
     * @param Attribute $attribute
     * @return JsonResponse
     *
     * @bodyParam name string The name of the attribute. Example: Updated Title
     * @bodyParam slug string The slug of the attribute. Example: updated-title
     * @bodyParam data_type string The data type of the attribute options are: string, integer, float, boolean, date. Example: string
     * @bodyParam is_required boolean Whether the attribute is required. Example: true
     * @bodyParam is_unique boolean Whether the attribute is unique. Example: true
     * @bodyParam default_value string The default value of the attribute. Example: Default Title
     *
     * @response 200 {
     *   "success": true,
     *   "status_code": 200,
     *   "message": "Attribute updated successfully",
     *   "data": {
     *     "id": 1,
     *     "name": "Updated Title",
     *     "slug": "updated-title",
     *     "data_type": "string",
     *     "is_required": true,
     *     "is_unique": true,
     *     "default_value": null,
     *     "created_at": "2025-07-25T15:00:00.000000Z",
     *     "updated_at": "2025-07-26T13:45:00.000000Z"
     *   },
     *   "errors": null
     * }
     */
    public function update(UpdateAttributeData $data, Attribute $attribute): JsonResponse
    {
        $attribute = $this->attributeRepository->update($attribute, $data);

        return $this->success(
            data: new AttributeResource($attribute),
            message: 'Attribute updated successfully'
        );
    }

    /**
     * Delete attribute
     *
     * This endpoint allows to delete a specific attribute by id.
     * @authenticated
     *
     * @param Attribute $attribute
     * @return JsonResponse
     *
     * @response 200 {
     *   "success": true,
     *   "status_code": 200,
     *   "message": "Attribute deleted successfully",
     *   "data": null,
     *   "errors": null
     * }
     */
    public function destroy(Attribute $attribute): JsonResponse
    {
        $this->attributeRepository->delete($attribute);

        return $this->success(
            message: 'Attribute deleted successfully'
        );
    }

    /**
     * Get Entity Attributes
     *
     * Returns a list of attributes associated with a specific entity.
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
     *       "name": "Title",
     *       "slug": "title",
     *       "data_type": "string",
     *       "is_required": true,
     *       "is_unique": true,
     *       "default_value": null,
     *       "created_at": "2025-07-25T15:00:00.000000Z",
     *       "updated_at": "2025-07-25T15:00:00.000000Z"
     *     }
     *   ],
     *   "errors": null
     * }
     */
    public function getAttributesForEntity(Entity $entity): JsonResponse
    {
        $attributes = $this->attributeRepository->getAttributesForEntity($entity);

        return $this->success(
            data: AttributeResource::collection($attributes)
        );
    }

    /**
     * Attach Attribute to Entity
     *
     * This endpoint allows to attach an attribute to an entity.
     * @authenticated
     *
     * @param AttributeEntityData $data
     * @return JsonResponse
     *
     * @bodyParam attribute_id integer required The ID of the attribute. Example: 1
     * @bodyParam entity_id integer required The ID of the entity. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "status_code": 200,
     *   "message": "Attribute attached to entity successfully",
     *   "data": null,
     *   "errors": null
     * }
     *
     * @response 400 {
     *   "success": false,
     *   "status_code": 400,
     *   "message": "Attribute is already attached to this entity",
     *   "data": null,
     *   "errors": null
     * }
     */
    public function attachToEntity(AttributeEntityData $data): JsonResponse
    {
        $attribute = Attribute::findOrFail($data->attribute_id);
        $entity = Entity::findOrFail($data->entity_id);

        $result = $this->attributeRepository->attachToEntity($attribute, $entity);

        if ($result) {
            return $this->success(
                message: 'Attribute attached to entity successfully'
            );
        }

        return $this->error(
            message: 'Attribute is already attached to this entity',
            statusCode: Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * Detach Attribute from Entity
     *
     * This endpoint allows to detach an attribute from an entity.
     * @authenticated
     *
     * @param AttributeEntityData $data
     * @return JsonResponse
     *
     * @bodyParam attribute_id integer required The ID of the attribute. Example: 1
     * @bodyParam entity_id integer required The ID of the entity. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "status_code": 200,
     *   "message": "Attribute detached from entity successfully",
     *   "data": null,
     *   "errors": null
     * }
     *
     * @response 400 {
     *   "success": false,
     *   "status_code": 400,
     *   "message": "Attribute is not attached to this entity",
     *   "data": null,
     *   "errors": null
     * }
     */
    public function detachFromEntity(AttributeEntityData $data): JsonResponse
    {
        $attribute = Attribute::findOrFail($data->attribute_id);
        $entity = Entity::findOrFail($data->entity_id);

        $result = $this->attributeRepository->detachFromEntity($attribute, $entity);

        if ($result) {
            return $this->success(
                message: 'Attribute detached from entity successfully'
            );
        }

        return $this->error(
            message: 'Attribute is not attached to this entity',
            statusCode: Response::HTTP_BAD_REQUEST
        );
    }
}
