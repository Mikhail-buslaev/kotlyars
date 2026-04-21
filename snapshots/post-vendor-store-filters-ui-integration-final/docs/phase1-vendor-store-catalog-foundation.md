# Goal

Lock Phase 1 as an isolated backend catalog foundation for Vendor Store in OpenCart 3.0.3.9 + Journal 3, without installing TMD Auction or introducing auction runtime logic. The implementation keeps `product_type` and governed `category_code` as backend source-of-truth fields, adds reusable attribute/filter registries, and exposes catalog-side compatibility contracts for a future auction layer.

# Assumptions

- Phase 1 stays additive and reversible: no OpenCart core file edits, no Journal 3 rewrites, no TMD Auction dependency.
- `product_type` is the main source of truth for fixed-price vs auction behavior; auction is not represented as a parallel product tree.
- OpenCart categories remain native storefront taxonomy, while governed `category_code` is the canonical backend leaf for validation and future integrations.
- Shared semantics reuse native OpenCart fields where practical: `price`, `brand`, `weight`, `quantity`, and `location` remain core-backed; typed extension storage is used only for non-native governed values.
- Jewellery remains category-mapping only in Phase 1.

# File structure

- `docs/phase1-vendor-store-catalog-foundation.md`
- `upload/system/config/kotlyars_catalog.php`
- `upload/system/config/kotlyars_catalog_foundation.ocmod.xml`
- `upload/admin/controller/extension/module/kotlyars_catalog_foundation.php`
- `upload/admin/controller/extension/module/kotlyars_catalog_foundation_event.php`
- `upload/admin/model/extension/module/kotlyars_catalog_foundation.php`
- `upload/admin/language/en-gb/extension/module/kotlyars_catalog_foundation.php`
- `upload/admin/view/template/extension/module/kotlyars_catalog_foundation.twig`
- `upload/admin/view/template/extension/module/kotlyars_catalog_foundation_product_tab.twig`
- `upload/catalog/model/extension/module/kotlyars_catalog_foundation.php`
- `upload/catalog/controller/extension/module/kotlyars_catalog_foundation_event.php`

# Data model

- `oc_kotlyars_catalog_product`
  Stores one row per product for `product_type` and governed `category_code`.
- `oc_kotlyars_catalog_product_value`
  Stores typed extension-backed attribute values keyed by `product_id + attribute_code`.
- `oc_kotlyars_catalog_category_map`
  Optional bridge from governed `category_code` to actual OpenCart `category_id` values for stricter alignment validation.
- Code registry in `upload/system/config/kotlyars_catalog.php`
  Holds the locked category registry, attribute dictionary, category-to-attribute map, filter dictionary, filter visibility map, and auction placeholder contract.

# Implementation steps

1. Add the locked Phase 1 registry in `upload/system/config/kotlyars_catalog.php`.
2. Create an installable admin module that provisions schema and registers cleanup/catalog events.
3. Extend the admin product form through `upload/system/config/kotlyars_catalog_foundation.ocmod.xml`, then register that XML into `oc_modification` during install or repair.
4. Validate category/type compatibility and required category-aware attributes before product save.
5. Expose catalog-side contract fields via model events without changing theme templates or checkout runtime.
6. Leave auction runtime fields as null placeholders owned by Phase 2 / TMD Auction integration.

# Code

- Registry blueprint:
  `upload/system/config/kotlyars_catalog.php` defines product types, governed category leaves, shared attribute semantics, category attribute rules, and reusable filter definitions.
- Install / uninstall SQL:
  `upload/admin/model/extension/module/kotlyars_catalog_foundation.php` creates the three Phase 1 tables and registers three safe events.
- Admin governance:
  `upload/admin/controller/extension/module/kotlyars_catalog_foundation.php` and `upload/admin/view/template/extension/module/kotlyars_catalog_foundation_product_tab.twig` provide the product governance tab and module shell.
- Save / load integration:
  `upload/system/config/kotlyars_catalog_foundation.ocmod.xml` wires product add/edit/getForm/validateForm without touching core files on disk, and the module registers it into `Extensions > Modifications`.
- Catalog compatibility contract:
  `upload/catalog/model/extension/module/kotlyars_catalog_foundation.php` appends `product_type`, availability flags, governed category metadata, filter index data, and Phase 2 auction placeholders to product arrays.

# Risks / compatibility notes

- The OCMOD must be registered into `oc_modification` and refreshed in OpenCart admin before the product form tab appears.
- `category_code` is enforced even when actual OpenCart category-to-code mappings are not configured; `oc_kotlyars_catalog_category_map` remains the safe Phase 1 bridge for stricter alignment later.
- Cart / checkout runtime is intentionally not overridden in Phase 1. Instead, catalog contracts expose `can_add_to_cart` and `can_checkout` so Phase 2 can bind them safely once auction runtime exists.
- Product copy flow is not yet extended to clone Phase 1 governance rows; if copy support becomes required before Phase 2, it should be added as a narrow follow-up patch.
- Journal 3 storefront rendering is untouched; only backend contracts and product arrays are extended.
