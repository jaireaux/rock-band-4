# Initial production validation

- PHP syntax validation passed locally and on IONOS.
- SQLite integrity check passed; 1,067 records, 1,067 unique artist/song pairs.
- Windmill job 01a0ba41-e137-c502-89b3-9c9d20aef0fe succeeded using u/morgan/rb4_validate_catalog: 22 genres, 8 decades.
- Production HTTP 200; direct database request HTTP 403.
- Local and deployed PHP/database SHA-256 values matched.
- LNM headless Chrome rendered all 1,067 rows at a 390x844 viewport.
- DOM interaction checks passed all eight sort orders, single active arrow and aria-sort, intersecting filters (1980s + New Wave = 39 songs), empty results, and reset.
- Actual iPhone Safari visual verification remains for user review.

Ollama qwen3:4b supplied an implementation checklist. Its generic and conflicting mobile suggestions were reviewed, not applied blindly. Codex authored the implementation, checked behavior, and deployed it. Windmill performed catalog validation; it did not build the website.
