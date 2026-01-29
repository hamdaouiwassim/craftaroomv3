# Designer Functionalities

- **Access & guard**: Routes prefixed with `/designer/*`, protected by `auth` + `isDesigner` middleware. Dashboard at `/designer/dashboard` renders `designer.index`.
- **Profile**: `/designer/profile` shows `designer.profile` with role-themed layout; profile updates use shared `/profile` routes handled by `ProfileController`.
- **Product management**: Full CRUD via `designer.products.*` routes mapped to `ProductController`. Ownership enforced by `canAccessProduct()`—designers can only see and modify their own products. Index supports search + status filter (default `active`), create/edit forms include rooms, metals, measures, currency, photos, reels, and 3D model uploads via dedicated endpoints (`products.upload-photos|reel|model`).
- **Favorites**: `/designer/favorites` lists the designer’s saved products (`FavoriteController@index`).
- **Catalog & interactions**: Designers can browse public catalog (`/products`, `/products/{id}`), add/remove favorites (`favorites.toggle|check`), submit/edit/delete reviews, and use the session-based cart endpoints (`cart.*`) like any authenticated user.
