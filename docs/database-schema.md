# Database Schema

Overview of the database tables used by the application, including the **concept** feature (designer role) and related entities.

---

## Core Tables

### `users`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| name | string, nullable | |
| firstname | string, nullable | |
| lastname | string, nullable | |
| facebook_id | string, nullable | |
| google_id | string, nullable | |
| apple_id | string, nullable, unique | |
| role | integer, unsigned, default 0 | |
| language | integer, unsigned, default 0 | |
| loginType | integer, unsigned, default 0 | |
| type | integer, unsigned, default 0 | |
| phone | string, nullable | |
| photoUrl | string, nullable | |
| currency_id | bigint, nullable | FK → currencies |
| email | string, unique | |
| email_verified_at | timestamp, nullable | |
| password | string | |
| remember_token | string, nullable | |
| created_at, updated_at | timestamps | |

---

### `categories`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| name | string | |
| status | enum('active','inactive'), default 'active' | |
| type | enum('main','sub'), default 'main' | |
| description | text, nullable | |
| category_id | bigint, nullable | Self-ref for sub-categories |
| created_at, updated_at | timestamps | |

---

### `rooms`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| name | string | |
| photoUrl_id | bigint, unsigned, nullable | |
| created_at, updated_at | timestamps | |

---

### `metals`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| ref | string, nullable | |
| name | string | |
| image_url | string, nullable | |
| photoUrl_id | bigint, unsigned, nullable | |
| created_at, updated_at | timestamps | |

---

### `metal_options`
Sub-options per metal (e.g. finish/variant).

| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| metal_id | bigint (FK) | → metals, cascade on delete |
| ref | string, nullable | |
| name | string | |
| image_url | string, nullable | |
| created_at, updated_at | timestamps | |

---

### `media`
Polymorphic-style attachment storage (name, url, type, attachment_id).

| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| name | string | |
| url | string | |
| type | enum | 'avatar', 'product', 'threedmodel', 'material', 'category', **'concept'**, **'concept_threedmodel'**, 'other' |
| attachment_id | bigint, unsigned | ID of product, concept, user, etc. |
| created_at, updated_at | timestamps | |

---

## Products (constructor/shop)

### `products`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| name | string | |
| price | float | |
| size | string | |
| category_id | bigint (FK) | → categories |
| reel | string, nullable | |
| currency | string | |
| description | text | |
| user_id | bigint (FK) | → users |
| status | enum('active','inactive'), default 'active' | |
| created_at, updated_at | timestamps | |

### `metal_product` (pivot)
| Column | Type | Notes |
|--------|------|-------|
| product_id | bigint (FK) | → products |
| metal_id | bigint (FK) | → metals |
| created_at, updated_at | timestamps | |

### `product_room` (pivot)
| Column | Type | Notes |
|--------|------|-------|
| product_id | bigint (FK) | → products |
| room_id | bigint (FK) | → rooms |
| created_at, updated_at | timestamps | |

### `measures` (product)
| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| size | enum('SMALL','MEDIUM','LARGE') | |
| product_id | bigint (FK) | → products |
| created_at, updated_at | timestamps | |

### `dimensions` (product)
| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| length, width, height | float | |
| unit | enum('CM','FT','INCH'), nullable | |
| measure_id | bigint (FK) | → measures |
| created_at, updated_at | timestamps | |

### `weights` (product)
| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| weight_value | float | |
| weight_unit | enum('KG','LB') | |
| measure_id | bigint (FK) | → measures |
| created_at, updated_at | timestamps | |

---

## Concepts (designer role)

### `concepts`
Designer concepts (no price/currency).

| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| name | string | |
| size | string, nullable, default 'N/A' | |
| category_id | bigint (FK) | → categories, cascade on delete |
| reel | string, nullable | |
| description | text | |
| user_id | bigint (FK) | → users, cascade on delete |
| status | enum('active','inactive'), default 'active' | |
| created_at, updated_at | timestamps | |

### `concept_room` (pivot)
| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| concept_id | bigint (FK) | → concepts, cascade on delete |
| room_id | bigint (FK) | → rooms, cascade on delete |
| created_at, updated_at | timestamps | |

### `concept_metal` (pivot)
| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| concept_id | bigint (FK) | → concepts, cascade on delete |
| metal_id | bigint (FK) | → metals, cascade on delete |
| created_at, updated_at | timestamps | |

### `concept_metal_option` (designer customization)
Stores the designer’s chosen sub-metal(s) (metal_option_id) per metal for each concept. Multiple options per metal are allowed.

| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| concept_id | bigint (FK) | → concepts, cascade on delete |
| metal_id | bigint (FK) | → metals, cascade on delete |
| metal_option_id | bigint (FK) | → metal_options, cascade on delete |
| unique(concept_id, metal_id, metal_option_id) | | No duplicate option for same metal; multiple rows per metal allowed |
| created_at, updated_at | timestamps | |

### `concept_measures`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| size | enum('SMALL','MEDIUM','LARGE') | |
| concept_id | bigint (FK) | → concepts, cascade on delete |
| created_at, updated_at | timestamps | |

### `concept_dimensions`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| length, width, height | float | |
| unit | enum('CM','FT','INCH'), nullable | |
| concept_measure_id | bigint (FK) | → concept_measures, cascade on delete |
| created_at, updated_at | timestamps | |

### `concept_weights`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| weight_value | float | |
| weight_unit | enum('KG','LB') | |
| concept_measure_id | bigint (FK) | → concept_measures, cascade on delete |
| created_at, updated_at | timestamps | |

---

## Other Tables

### `favorites`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| user_id | bigint (FK) | → users |
| product_id | bigint (FK) | → products |
| unique(user_id, product_id) | | One favorite per user per product |
| created_at, updated_at | timestamps | |

### `currencies`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| (other columns per migration) | | |

### `addresses`, `countries`, `reviews`, `materials`, `threedmodels`, `team_members`, etc.
Defined in their respective migrations; not listed here for brevity.

---

## Relationships (summary)

- **Concept** belongs to **User** (designer), **Category**; has many **Room** and **Metal** via pivots; has many **ConceptMeasure** → **ConceptDimension** / **ConceptWeight**; has many **ConceptMetalOption** (chosen metal_option(s) per metal; multiple allowed per metal).
- **Product** belongs to **User**, **Category**; has many **Room**, **Metal** via pivots; has many **Measure** → **Dimension** / **Weight**.
- **Media** references entities via `type` + `attachment_id` (e.g. `type = 'concept'`, `attachment_id = concept.id`).
- **MetalOption** belongs to **Metal**. **ConceptMetalOption** links **Concept** + **Metal** + **MetalOption** (designer’s choice(s) per metal; multiple options per metal allowed).

---

## Applying migrations

```bash
php artisan migrate
```

To refresh and re-seed:

```bash
php artisan migrate:fresh --seed
```
