# Literature Review

---

- [Use Case Context](#Use-Case-Context)

## Dynamic Content Modeling in Modern CMS Platforms
This document provides an overview of how popular CMS platforms handle **dynamic content types**, **custom fields**, and **relationships** at the **database level**.

<a href="Use-Case-Context"></a>
## 🎯 Use Case Context

In a generic content management system (CMS), users (Admins) should be able to:
- Define custom **entities** (e.g., Product, Article)
- Add dynamic **fields** (text, number, relation, etc.)
- Create **relationships** between entities (1:1, 1:N, N:N)
- Let Operators input content using those dynamic models

---

## 🔍 Comparison of CMSs

| CMS            | Storage Type           | Dynamic Fields         | Table Per Entity         | Relationships          | Runtime Schema Updates  |
|----------------|------------------------|------------------------|--------------------------|------------------------|-------------------------|
| **Strapi**     | SQL (PostgreSQL, etc.) | Real columns per field | ✅ Yes                    | ✅ SQL FK / Join Tables | ❌ No (Restart Required) |
| **Directus**   | SQL (Schema-first)     | Metadata tables        | ✅ Yes                    | ✅ Metadata + FKs       | ✅ Yes                   |
| **Sanity.io**  | NoSQL (Document store) | JSON documents         | ❌ No (Single collection) | ✅ Embedded Refs        | ✅ Yes                   |
| **KeystoneJS** | SQL (via Prisma)       | Real columns per field | ✅ Yes                    | ✅ Prisma FKs           | ❌ No (Rebuild Required) |

---

## 📦 Platform Summaries

### 1. Strapi (Headless CMS)
- **Database Strategy**: Each content type generates a new SQL table.
- **Fields**: Become real database columns.
- **Relations**: Managed via foreign keys or join tables.
- **Pros**: Strong DB structure, great performance.
- **Cons**: Schema changes require server restart.

---

### 2. Directus
- **Database Strategy**: Uses existing SQL tables + internal metadata.
- **Fields**: Defined in `directus_fields`, not actual DB columns.
- **Relations**: Described via metadata and foreign keys.
- **Pros**: True runtime flexibility, DB-first.
- **Cons**: More complex data modeling.

---

### 3. Sanity.io
- **Database Strategy**: NoSQL (GROQ), schema-less JSON documents.
- **Fields**: Stored as JSON with developer-defined JS schema.
- **Relations**: Via embedded references.
- **Pros**: Extremely flexible, good for unstructured content.
- **Cons**: Not suitable for relational-heavy models.

---

### 4. KeystoneJS
- **Database Strategy**: Uses Prisma to generate SQL schema from JS code.
- **Fields**: Real SQL columns.
- **Relations**: Via Prisma’s 1:1, 1:N, N:N modeling.
- **Pros**: Developer-friendly, strong typing with Prisma.
- **Cons**: No runtime flexibility – rebuild required for schema changes.

---

## 🧠 Conclusion

Based on the Task requirement that i need to use (MySQL) database. I would consider two main strategies:

### Option 1: Metadata-Driven (like Directus)
- One table for entries (`values`)
- Store schema in metadata tables (`entities`, `attributes`)
- All fields and types resolved dynamically
- Focus on Flexibility

### Option 2: Schema-Driven (like Strapi/Keystone)
- Generate real SQL tables for each entity
- Use migrations or schema builders for field updates
- Better performance, more complex
- Focus on Structure Performance

---

## 🔗 References
- [Strapi](https://strapi.io)
- [Directus](https://directus.io)
- [Sanity](https://www.sanity.io)
- [KeystoneJS](https://keystonejs.com)
