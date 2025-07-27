# 📚 CMS API Guide — Bookstore Management System

---

- [Overview](#overview)
- [Authentication](#authentication)
- [Entity Creation](#entity-creation)
- [Create Entity Relationships](#create-entity-relationships)
- [Create Entity Attributes](#create-entity-attributes)
- [Attach Attributes to Entities](#attach-attributes-to-entities)
- [Verify Entity Configuration](#verify-entity-configuration)
- [Create Records as Operator](#create-records-as-operator)


<a name="overview"></a>
## Overview

This guide walks you through using the CMS API to configure a **Bookstore Management System**. You'll learn how to:


- Authenticate as an **admin** to configure the system
- Create custom entities: `Book`, `Customer`, `Borrow`
- Define and attach attributes to each entity
- Establish relationships between entities (e.g., which customer borrowed which book)
- Authenticate as an **operator** to manage content records
- Create actual records (books, customers, and borrow transactions) using the API


---


<a name="authentication"></a>
## 🛡️ Authentication

### 🔐 Log in as Admin

Use the credentials seeded into the system:

- **Email**: `admin@example.test`
- **Password**: `password`

### 📩 API Endpoint

```
POST /api/auth/login
```

### 🧾 Sample Request

```json
{
  "email": "admin@example.test",
  "password": "password"
}
```

### ✅ Sample Response

```json
{
  "success": true,
  "status_code": 200,
  "message": null,
  "data": {
    "id": 1,
    "name": "Admin",
    "email": "admin@example.com",
    "access_token": "eyJ0eXAiOiJKV1QiL.....",
    "expires_in": 31536000
  },
  "errors": null
}
```

> **Note:** Save the `access_token` from the response. You'll need it in the `Authorization` header for all subsequent requests:

```
Authorization: Bearer <access_token>
```

---

<a name="entity-creation"></a>
## 🏗️ Entity Creation

Create the following entities:

- `Book`: `Title (string)`, `Author (string)`
- `Customer`: `Name (string)`, `Birthdate (date)`, `Phone (string)`
- `Borrow`: `BookID (integer)`, `CustomerID (integer)`, `StartDate (date)`, `EndDate (date)`

Each entity can be created using:

```
POST /api/admin/entities
```

### 📦 Sample: Create Book Entity

```json
{
  "name": "Book",
  "slug": "book",
  "description": "book entity"
}
```

> Assume returned ID: **1**

### 👤 Sample: Create Customer Entity

```json
{
  "name": "Customer",
  "slug": "customer",
  "description": "customer entity"
}
```

> Assume returned ID: **2**

### 🔄 Sample: Create Borrow Entity

```json
{
  "name": "Borrow",
  "slug": "borrow",
  "description": "borrow entity"
}
```

> Assume returned ID: **3**

---

<a name="create-entity-relationships"></a>
## 🔗 Create Entity Relationships

Now define the following relationships:

- **Book has many Borrow records**
- **Customer has many Borrow records**

### 🔁 API Endpoint

```
POST /api/admin/entities/relationships
```

---

### 📚 1. Book → Borrow (1:N)

#### 🧾 Request Body

```json
{
  "type": "1:N",
  "from_entity_id": 1,
  "to_entity_id": 3
}
```

#### ✅ Sample Response

```json
{
  "success": true,
  "status_code": 201,
  "message": "Relationship created successfully",
  "data": {
    "type": "1:N",
    "from_entity_id": 1,
    "to_entity_id": 3,
    "name": "book_borrows",
    "inverse_name": "borrow_book",
    "id": 1
  },
  "errors": null
}
```

---

### 👥 2. Customer → Borrow (1:N)

#### 🧾 Request Body

```json
{
  "type": "1:N",
  "from_entity_id": 2,
  "to_entity_id": 3
}
```

#### ✅ Sample Response

```json
{
  "success": true,
  "status_code": 201,
  "message": "Relationship created successfully",
  "data": {
    "type": "1:N",
    "from_entity_id": 2,
    "to_entity_id": 3,
    "name": "customer_borrows",
    "inverse_name": "borrow_customer",
    "id": 2
  },
  "errors": null
}
```


---

<a name="create-entity-attributes"></a>
## 🧩 Create Entity Attributes

Each field (attribute) must be explicitly created using the following endpoint:

```
POST /api/admin/attributes
```

---

### 📘 Book Attributes

#### 📝 Title (string)

```json
{
  "name": "Title",
  "slug": "title",
  "data_type": "string",
  "is_required": true,
  "is_unique": false,
  "default_value": ""
}
```

> **Response**:  
> Attribute created with `id: 1`

#### ✍️ Author (string)

```json
{
  "name": "Author",
  "slug": "author",
  "data_type": "string",
  "is_required": true,
  "is_unique": false,
  "default_value": ""
}
```

> **Response**:  
> Attribute created with `id: 2`

---

### 👤 Customer Attributes

#### 👤 Name (string)

```json
{
  "name": "Name",
  "slug": "name",
  "data_type": "string",
  "is_required": true,
  "is_unique": false,
  "default_value": ""
}
```

> **Response**:  
> Attribute created with `id: 3`

#### 🎂 Date of Birth (date)

```json
{
  "name": "Date of Birth",
  "slug": "birthdate",
  "data_type": "date",
  "is_required": true,
  "is_unique": false,
  "default_value": ""
}
```

> **Response**:  
> Attribute created with `id: 4`

#### ☎️ Phone (string, unique)

```json
{
  "name": "Phone",
  "slug": "phone",
  "data_type": "string",
  "is_required": true,
  "is_unique": true,
  "default_value": ""
}
```

> **Response**:  
> Attribute created with `id: 5`

---

### 🔄 Borrow Attributes

#### 📅 Start Date (date)

```json
{
  "name": "Start Date",
  "slug": "start_date",
  "data_type": "date",
  "is_required": true,
  "is_unique": false,
  "default_value": ""
}
```

> **Response**:  
> Attribute created with `id: 6`

#### 📅 End Date (date)

```json
{
  "name": "End Date",
  "slug": "end_date",
  "data_type": "date",
  "is_required": true,
  "is_unique": false,
  "default_value": ""
}
```

> **Response**:  
> Attribute created with `id: 7`

---

<a name="attach-attributes-to-entities"></a>
## 🔗 Attach Attributes to Entities

After creating the attributes, you must attach them to their respective entities using:

```
POST /api/admin/attributes/attach
```

### 📘 Book Entity (ID: 1)

#### 🔹 Attach Title (attribute_id: 1)

```json
{
  "attribute_id": 1,
  "entity_id": 1
}
```

#### 🔹 Attach Author (attribute_id: 2)

```json
{
  "attribute_id": 2,
  "entity_id": 1
}
```

> ✅ Both requests return:

```json
{
  "success": true,
  "status_code": 200,
  "message": "Attribute attached to entity successfully",
  "data": null,
  "errors": null
}
```

---

### 👤 Customer Entity (ID: 2)

#### 🔹 Attach Name (attribute_id: 3)

```json
{
  "attribute_id": 3,
  "entity_id": 2
}
```

#### 🔹 Attach Date of Birth (attribute_id: 4)

```json
{
  "attribute_id": 4,
  "entity_id": 2
}
```

#### 🔹 Attach Phone (attribute_id: 5)

```json
{
  "attribute_id": 5,
  "entity_id": 2
}
```

---

### 🔄 Borrow Entity (ID: 3)

#### 🔹 Attach Start Date (attribute_id: 6)

```json
{
  "attribute_id": 6,
  "entity_id": 3
}
```

#### 🔹 Attach End Date (attribute_id: 7)

```json
{
  "attribute_id": 7,
  "entity_id": 3
}
```

> ✅ All responses follow the same success format.

---

<a name="verify-entity-configuration"></a>
## ✅ Verify Entity Configuration

You can verify that attributes and relationships are correctly attached by retrieving the full entity definition.

### 📍 Endpoint

```
GET /api/admin/entities/:id
```

### 🔎 Example: Show Book Entity (ID: 1)

```
GET /api/admin/entities/1
```

### ✅ Sample Response

```json
{
  "success": true,
  "status_code": 200,
  "message": null,
  "data": {
    "id": 1,
    "name": "Book",
    "slug": "book",
    "description": "book entity",
    "created_at": "2025-07-27T15:04:38.000000Z",
    "updated_at": "2025-07-27T15:04:38.000000Z",
    "fields": [
      {
        "id": 1,
        "name": "title",
        "label": "Title",
        "type": "text",
        "required": true,
        "default_value": null
      },
      {
        "id": 2,
        "name": "author",
        "label": "Author",
        "type": "text",
        "required": true,
        "default_value": null
      }
    ],
    "validation_rules": {
      "title": [
        "required",
        "string"
      ],
      "author": [
        "required",
        "string"
      ],
      "book_borrows.*": "integer|exists:records,id,entity_id,3",
      "book_borrows": [
        "nullable",
        "array",
        "min:0"
      ]
    },
    "relationship_fields": [
      {
        "name": "book_borrows",
        "label": "Book borrows",
        "related_entity": "borrow",
        "relationship_type": "1:N",
        "is_source": true,
        "type": "select",
        "multiple": true
      }
    ]
  },
  "errors": null
}
```

> ✅ This confirms:
> - Fields (title, author) are correctly attached
> - Validation rules are generated
> - Relationship to Borrow entity is registered


<a name="create-records-as-operator"></a>
## 📄 Create Records as Operator

First, authenticate using the **Operator** account:

- **Email**: `operator@example.com`
- **Password**: `password`

---

###  Create a Book Record

**Endpoint:** `POST /api/content/book`

**Payload:**

```json
{
  "title": "Head First Software Architecture",
  "author": "Raju Gandhi, Mark Richards, Neal Ford"
}
```

**Response:**

```json
{
  "success": true,
  "status_code": 200,
  "message": null,
  "data": {
    "id": 1,
    "title": "Head First Software Architecture",
    "author": "Raju Gandhi, Mark Richards, Neal Ford",
    "created_at": "2025-07-27T15:32:40.000000Z",
    "updated_at": "2025-07-27T15:32:40.000000Z"
  },
  "errors": null
}
```

---

### Create a Customer Record

**Endpoint:** `POST /api/content/customer`

**Payload:**

```json
{
  "name": "Mohamed Hassan",
  "birthdate": "1999-01-02",
  "phone": "01068636409"
}
```

**Response:**

```json
{
  "success": true,
  "status_code": 200,
  "message": null,
  "data": {
    "id": 2,
    "name": "Mohamed Hassan",
    "birthdate": "1999-01-02",
    "phone": "01068636409",
    "created_at": "2025-07-27T15:34:34.000000Z",
    "updated_at": "2025-07-27T15:34:34.000000Z"
  },
  "errors": null
}
```

---

### Create a Borrow Record

**Endpoint:** `POST /api/content/borrow`

**Payload:**

```json
{
  "start_date": "2025-07-27",
  "end_date": "2025-07-31",
  "borrow_book": 1,
  "borrow_customer": 2
}
```

**Response:**

```json
{
  "success": true,
  "status_code": 200,
  "message": null,
  "data": {
    "id": 3,
    "start_date": "2025-07-27",
    "end_date": "2025-07-31",
    "borrow_book": {
      "id": 1,
      "title": "Head First Software Architecture",
      "author": "Raju Gandhi, Mark Richards, Neal Ford",
      "created_at": "2025-07-27T15:32:40.000000Z",
      "updated_at": "2025-07-27T15:32:40.000000Z"
    },
    "borrow_customer": {
      "id": 2,
      "name": "Mohamed Hassan",
      "birthdate": "1999-01-02",
      "phone": "01068636409",
      "created_at": "2025-07-27T15:34:34.000000Z",
      "updated_at": "2025-07-27T15:34:34.000000Z"
    },
    "created_at": "2025-07-27T15:36:44.000000Z",
    "updated_at": "2025-07-27T15:36:44.000000Z"
  },
  "errors": null
}
```
