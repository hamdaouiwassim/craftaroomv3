# Customer Functionalities

- **Access & guard**: Customer-only routes prefixed `/customer/*` with `auth` + `isCustomer`.
- **Dashboard**: `/customer/dashboard` shows favorites/reviews/cart stats and recent activity (`Customer\DashboardController@index`).
- **Orders**: `/customer/orders` and `/customer/orders/{id}` render order list/detail placeholders (`Customer\OrderController`) ready for future integration.
- **Cart**: `/customer/cart` (and public `/cart/*` endpoints) expose session-based cart add/update/remove/clear/count flows handled by `CartController`.
- **Profile**: `/customer/profile` renders `customer.profile`; updates go through global `/profile` routes (`ProfileController`) with avatar, contact, language, and currency fields.
- **Favorites**: `/customer/favorites` lists saved products; toggling and checks use shared `favorites.toggle|check` endpoints.
- **Catalog & reviews**: Public catalog at `/` and `/products`/`/products/{id}` (`LandingController`) with search and pagination. Customers can submit/edit/delete reviews via `reviews.store|update|destroy`.
