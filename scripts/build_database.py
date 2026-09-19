import json, pathlib, sqlite3, sys
root = pathlib.Path(__file__).resolve().parents[1]
rows = json.loads((root / 'data/songs.json').read_text())
assert len(rows) == 1067
out = pathlib.Path(sys.argv[1]) if len(sys.argv) > 1 else root / 'catalog.sqlite'
assert not out.exists(), 'Refusing to overwrite an existing database'
db = sqlite3.connect(out)
db.execute('CREATE TABLE songs (id INTEGER PRIMARY KEY, artist TEXT NOT NULL, song TEXT NOT NULL, year INTEGER NOT NULL CHECK(year BETWEEN 1900 AND 2100), genre TEXT NOT NULL, UNIQUE(artist,song))')
db.executemany('INSERT INTO songs(artist,song,year,genre) VALUES(?,?,?,?)', rows)
db.execute('CREATE INDEX songs_year_genre ON songs(year,genre)')
db.commit()
assert db.execute('PRAGMA integrity_check').fetchone()[0] == 'ok'
print('Validated database:', db.execute('SELECT COUNT(*) FROM songs').fetchone()[0], 'songs')
