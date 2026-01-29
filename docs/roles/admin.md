# Admin Functionalities

- **Access & guard**: All routes under `/admin/*` use `auth` + `isAdmin`. Admins also hit `/dashboard` which renders `admin.dashboard` with platform stats.
- **Product management**: Full CRUD at `admin.products.*` via `ProductController` (`canManageProducts` middleware). Admins can manage every product, not just their own. File uploads are split endpoints for photos, reels, and 3D models (`products.upload-photos|reel|model`), protected by `IncreaseUploadLimits`.
- **Categories**: CRUD at `admin.categories.*` (`CategoryController`). Supports search, main/sub categories, status toggles, and icon upload stored under `uploads/category_icons`.
- **Users**: CRUD at `admin.users.*` (`App\Http\Controllers\Admin\UserController`). Includes role assignment (0=admin, 1=designer, 2=customer, 3=constructor), currency/language selection, password reset, and soft prevention of self-delete.
- **Team members**: CRUD at `admin.team-members.*` (`App\Http\Controllers\Admin\TeamMemberController`). Handles profile photo upload, ordering, active toggle, and social links.
- **Favorites**: `/admin/favorites` shows the admin’s favorited products (`FavoriteController@index`).
- **Profile**: Global profile routes (`/profile` edit/update/destroy) go through `ProfileController` and render `admin.profile.edit` for admins, supporting avatar upload, contact info, language, and currency.
- **Shared authenticated actions**: Admins can also use global review routes (`reviews.store|update|destroy`), favorite toggles (`favorites.toggle|check`), and cart endpoints (`cart.*`) like any signed-in user.
