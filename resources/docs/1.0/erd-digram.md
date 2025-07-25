# ERD Digram

---


## Digram

![ERD Diagram](/docs-assets/img/erd-digram.png)


## Digram Code

I have used [Ereser.io](https://eraser.io) for building the digram with the following code

```dbml
// title
title Dynamic CMS Data Model

// define tables
entities [icon: database, color: blue]{
  id bigint pk
  name varchar
  slug varchar unique
  description text
  created_at timestamp
  updated_at timestamp
}

attributes [icon: list, color: orange]{
  id bigint pk
  entity_id bigint
  name varchar
  slug varchar
  data_type enum
  is_required boolean
  is_unique boolean
  default_value text
  input_type varchar
  created_at timestamp
  updated_at timestamp
}

records [icon: file-text, color: green]{
  id bigint pk
  entity_id bigint
  created_at timestamp
  updated_at timestamp
}

entity_values [icon: edit, color: yellow]{
  id bigint pk
  record_id bigint
  attribute_id bigint
  value text
  created_at timestamp
  updated_at timestamp
}

entity_relationships [icon: link, color: purple]{
  id bigint pk
  from_entity_id bigint
  to_entity_id bigint
  type enum
  name varchar
  inverse_name varchar
  created_at timestamp
  updated_at timestamp
}

record_relationships [icon: shuffle, color: red]{
  id bigint pk
  relationship_id bigint
  from_record_id bigint
  to_record_id bigint
  created_at timestamp
  updated_at timestamp
}

// define relationships
attributes.entity_id > entities.id
records.entity_id > entities.id
entity_relationships.from_entity_id > entities.id
entity_relationships.to_entity_id > entities.id
entity_values.record_id > records.id
entity_values.attribute_id > attributes.id
record_relationships.relationship_id > entity_relationships.id
record_relationships.from_record_id > records.id
record_relationships.to_record_id > records.id

```
