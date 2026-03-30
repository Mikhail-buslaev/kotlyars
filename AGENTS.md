# AGENTS.md

## Project
Kotlyars backend foundation project.

## Working context
This repository contains the local development baseline for the Kotlyars project.

Current stable baseline:
- OpenCart 3.0.3.9
- Journal 3.2.8
- Journal license activated
- demo import completed
- Docker-based local environment
- storefront works
- admin works
- storage moved outside web root
- snapshots are part of the workflow

This baseline is considered stable and must not be broken.

---

## Core objective
The current task scope is:

**Phase 1 = backend catalog foundation for Vendor Store**

This phase must build the catalog governance layer only.

It must cover:
- category mapping
- attribute system
- filter system
- product_type logic

This phase must prepare a clean backend foundation for later auction compatibility, but must NOT install or implement auction runtime.

---

## Hard phase boundary
At this stage, you must work on **Phase 1 only**.

Do:
- backend architecture
- module structure
- data model
- schema design
- admin governance
- category-aware mapping logic
- Events / OCMOD integration points
- compatibility-safe implementation

Do not:
- install TMD Auction
- implement bidding logic
- build custom auction runtime
- duplicate auction state logic
- redesign Journal 3
- modify OpenCart core files
- introduce fragile theme-dependent assumptions
- move into frontend work beyond backend data contracts

If a requirement belongs to auction runtime, do not implement it now.
Document it as a future integration boundary.

---

## Architectural principles
Prefer:
- OpenCart-native structures where practical
- OCMOD
- Events
- isolated extension/module architecture
- data-first design
- compatibility with Journal 3
- clean separation between catalog foundation and future auction runtime

Avoid:
- core overrides
- editing vendor logic directly
- speculative business logic
- hardcoded template logic
- per-template filter behavior
- custom auction engine
- coupling backend governance to theme implementation details

---

## Domain scope for Phase 1

### 1. Diamonds
- natural
- coloured

### 2. Gemstones
- ruby
- sapphire
- emerald

### 3. Precious Metals
- gold
  - gold_by_weight
  - gold_bars_rounds_by_brand
  - industrial_gold
  - vintage_gold_bars_rounds
- silver
  - silver_bars
  - industrial_silver
  - silver_rounds
- platinum
  - platinum_bars_rounds
  - industrial_platinum

### 4. Fine Art
- drawings_pastels
- paintings_mixed_media
- fine_art_photography
- prints_multiples
- sculptures
- watercolour_paintings

### 5. Jewellery
- category mapping only
- no attribute design
- no filter design

### 6. Auction compatibility
- mapping assumptions only
- no runtime implementation
- no rewrite of TMD Auction logic

---

## Product type policy
Phase 1 must introduce a clean product_type governance layer.

Supported values:
- fixed_price
- auction

Rules:
- product_type must be stored explicitly as backend source of truth
- product_type must affect admin governance
- product_type must affect catalog filtering
- product_type must affect backend product page data contract
- product_type may affect cart and checkout availability contracts
- auction in Phase 1 is only a catalog classification and compatibility placeholder

Preferred rule:
- treat `product_type` as the main source of truth for fixed-price vs auction behavior
- do not treat auction as an independent parallel catalog system

---

## Attribute governance policy
You must build a reusable attribute dictionary and mapping model.

Requirements:
- reusable definitions
- category-to-attribute mapping
- required vs optional rules
- numeric / enum / text typing
- shared semantics without duplication across domains

Shared semantic attributes that must be handled cleanly:
- shape
- carat
- colour
- clarity
- cut
- certificate
- price
- intensity
- brand
- weight
- quantity
- location
- creator_brand
- item_type

Domain rules:
- Diamonds:
  - natural and coloured share common logic where practical
  - natural_only attribute: colour
  - coloured_only attributes: colour, intensity
- Gemstones:
  - ruby, sapphire, emerald use:
    - shape
    - carat
    - clarity
    - intensity
    - certificate
    - price
- Precious Metals:
  - common: price
  - variable:
    - brand
    - weight
    - quantity
    - type
  - industrial_gold must be fixed_price_only
- Fine Art:
  - attributes:
    - location
    - creator_brand
    - item_type
    - price
- Jewellery:
  - category mapping only
  - no invented attribute logic

---

## Filter governance policy
You must build a reusable filter dictionary and mapping model.

Requirements:
- reusable filter definitions
- filter-to-category mapping
- range filters for numeric values
- select filters for enums
- category-specific visibility rules
- no hardcoded per-template logic
- backend-ready structure for future Journal 3 integration

---

## Auction compatibility boundary
Future auction integration will use TMD Auction.

Phase 1 must not implement auction runtime.

Treat these only as future mapped contracts:
- current_bid
- start_price
- bid_increment
- end_time
- bid_history
- status

Ownership boundary:
- catalog foundation owns:
  - category structure
  - product_type
  - category mapping
  - attribute mapping
  - filter mapping
  - catalog-side validation and governance
- future TMD Auction layer owns:
  - bidding runtime
  - bid state
  - auction timing/state machine
  - bid history
  - runtime auction status

Do not duplicate TMD-owned logic.

---

## File and implementation strategy
Use a modular extension structure with isolated admin and catalog logic.

Preferred deliverables:
- clear file structure
- install/uninstall schema
- models
- admin controllers
- language files
- views where necessary for admin governance
- config/mapping arrays
- event entry points
- OCMOD only where truly needed

Do not produce fake full production code where architecture-level code is sufficient.
Do produce implementation-oriented skeletons and real structural direction.

---

## Required output structure for major architecture tasks
When producing architecture or implementation plans for this repository, use exactly this section order:

1. Goal
2. Assumptions
3. File structure
4. Data model
5. Implementation steps
6. Code
7. Risks / compatibility notes

---

## Execution order
Unless explicitly instructed otherwise, execute work in this order:

1. architecture lock
2. module / extension file structure
3. schema design
4. product_type storage and governance
5. category mapping registry
6. attribute dictionary and category mapping
7. filter dictionary and category mapping
8. admin-side validation and save/load flow
9. Events / OCMOD integration points
10. Phase 2 handoff assumptions

Do not jump ahead into later phases.

---

## Compatibility guardrails
You must preserve:
- OpenCart 3.0.3.9 compatibility
- Journal 3 compatibility
- current Docker-based local baseline stability

You must not:
- break admin
- break storefront
- create direct dependency on TMD Auction in Phase 1
- create code that assumes a specific frontend template implementation beyond backend contracts

---

## Change management
Before major structural changes:
- review existing repository structure
- preserve current working baseline
- prefer additive isolated changes
- keep implementation reversible
- avoid broad uncontrolled edits

When making code changes:
- keep them narrow in scope
- explain why each structural piece exists
- identify Phase 2 integration assumptions clearly
- note any risks to Journal 3 or OpenCart compatibility

---

## Success criteria
A successful Phase 1 result must produce:
- locked category mapping strategy
- clean product_type architecture
- reusable attribute dictionary and mapping model
- reusable filter dictionary and mapping model
- admin governance for category-aware data
- clean separation between catalog foundation and future auction runtime
- no core overrides
- no theme breakage
- no auction runtime duplication

---

## Instruction priority
If there is any ambiguity:
1. preserve stable baseline
2. stay within Phase 1
3. keep architecture backend-first and data-first
4. avoid TMD Auction runtime implementation
5. avoid core modification
6. maintain Journal 3 compatibility