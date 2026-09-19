# Rock Band 4 collection

A mobile-friendly, four-column browser for Johnny’s 1,067-song collection. PHP reads a SQLite database; vanilla JavaScript provides intersecting decade/genre filters and reversible column sorting. Styling draws from Rollerfeet and Rollerfeet Sprite Locker.

Live: https://rollerfeet.com/jaireaux/rb4.php

## Run

Requires PHP 8 with PDO SQLite, plus Python 3 to build the database.

```
python3 scripts/build_database.py
RB4_DATABASE="$PWD/catalog.sqlite" php -S localhost:8080 -t public
```

Open http://localhost:8080/rb4.php. No npm dependencies or API credentials required.

## Data

`data/songs.json` contains artist, song, release year, and genre, reconciled from video OCR against https://rb4.app/ (catalog metadata: https://cdn.rb4.app/songList.json). Seven omitted entries were recovered through frame review. Years refer to song releases, not DLC releases. Artist/song names are factual catalog metadata; no recordings or artwork are included.

The build enforces unique artist/song pairs, year bounds and SQLite integrity. `scripts/validate_catalog.py` is also a Windmill-compatible validation script.

## Deployment

Upload `public/rb4.php` to the web root’s `jaireaux/` directory. Put `catalog.sqlite` in `jaireaux/rb4-data/` together with the supplied `.htaccess` (Require all denied), or set `RB4_DATABASE` to an absolute path outside the web root if your host permits it. Verify direct HTTP access is forbidden before deployment. Keep the database readable by PHP. Upload a new database under a temporary name, validate it, then rename atomically when updating. The site is read-only and does not expose a database-management interface.

The page requests no search-engine indexing; its URL and source are public.
