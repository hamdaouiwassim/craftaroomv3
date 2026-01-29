# Constructor Functionalities

- **Access & guard**: Routes under `/constructor/*` with `auth` + `isConstructor`. Dashboard at `/constructor/dashboard` renders `constructor.index`.
- **Profile**: `/constructor/profile` renders `constructor.profile`; updates use global `/profile` endpoints (`ProfileController`) with avatar, contact, language, and currency fields.
- **Product management**: CRUD via `constructor.products.*`. Index (`ConstructorController::products`) lists only the constructor’s items with search and status filtering (defaults to `active`). Create/edit/show use `ProductController` and enforce ownership through `canAccessProduct()`. Supports rooms/metals selection, measures (dimensions/weight/size), currency, photos, reel, and 3D model uploads (`products.upload-photos|reel|model`).
- **Favorites**: `/constructor/favorites` displays the constructor’s favorite products.
- **Catalog & interactions**: Constructors can browse public products, toggle favorites, post/update/delete reviews, and use the session cart endpoints (`cart.*`) like other authenticated users.
