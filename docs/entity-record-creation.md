# Entity Record Creation

This document describes how to use the generic entity record creation functionality.

## Overview

The CMS-API provides a generic way to create records for any entity type. The system uses the entity's schema to validate the input data and then stores the record in the database.

## Components

The implementation consists of the following components:

1. **EntityFormSchemaService**: Generates validation rules based on entity attributes and relationships
2. **EntityRecordRepository**: Handles the creation of entity records in the database
3. **EntityController**: Provides an API endpoint for creating entity records

## API Endpoint

To create a record for an entity, send a POST request to:

```
POST /api/content/{entitySlug}
```

Where `{entitySlug}` is the slug of the entity you want to create a record for.

### Authentication

All requests require API authentication. Include your API token in the Authorization header:

```
Authorization: Bearer {your-api-token}
```

### Request Body

The request body should contain the values for the entity's attributes. The field names should match the attribute slugs.

Example:

```json
{
  "title": "Sample Title",
  "content": "This is the content of the record",
  "published": true,
  "publish_date": "2025-07-26"
}
```

### Response

A successful response will have a 201 status code and include the ID of the created record:

```json
{
  "message": "Record created successfully",
  "data": {
    "record_id": 123,
    "entity_id": 456,
    "entity_slug": "article"
  }
}
```

### Error Responses

- **404 Not Found**: If the entity with the given slug doesn't exist
- **422 Validation Error**: If the input data doesn't pass validation
- **500 Server Error**: If there's an error creating the record

## Validation

The system automatically generates validation rules based on the entity's attributes:

- Required attributes will be validated as required
- Data types will be validated (string, integer, float, date, boolean)
- Unique attributes will be validated for uniqueness
- Relationships will be validated to ensure they reference existing records

## Example Usage

### Creating an Article

```
POST /api/content/article
Authorization: Bearer {your-api-token}
Content-Type: application/json

{
  "title": "My First Article",
  "content": "This is the content of my first article",
  "published": true,
  "publish_date": "2025-07-26",
  "author": 1
}
```

Response:

```json
{
  "message": "Record created successfully",
  "data": {
    "record_id": 1,
    "entity_id": 1,
    "entity_slug": "article"
  }
}
```

## Implementation Details

The implementation follows these steps:

1. The controller finds the entity by slug
2. It generates validation rules using the EntityFormSchemaService
3. It validates the input data against these rules
4. It creates a record using the EntityRecordRepository
5. It returns a response with the created record's information

The EntityRecordRepository creates a record in the database and:
1. Stores the attribute values as EntityValue objects
2. Creates RecordRelationship entries for any relationships specified in the input data

### Handling Relationships

When creating a record, you can include relationship fields in the request body:

- For one-to-one relationships and the target side of one-to-many relationships, provide a single ID
- For many-to-many relationships and the source side of one-to-many relationships, provide an array of IDs

Example with relationships:

```json
{
  "title": "My First Article",
  "content": "This is the content of my first article",
  "published": true,
  "publish_date": "2025-07-26",
  "author": 1,
  "tags": [1, 2, 3]
}
```

In this example:
- "author" is a one-to-one relationship field with a single ID
- "tags" is a many-to-many relationship field with an array of IDs
