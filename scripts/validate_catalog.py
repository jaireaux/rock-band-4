from collections import Counter

def main(songs: list):
    assert len(songs) == 1067, 'Expected 1067 songs'
    seen = set()
    for row in songs:
        assert len(row) == 4
        artist, song, year, genre = row
        assert all(isinstance(x, str) and x.strip() for x in [artist, song, genre])
        assert 1900 <= int(year) <= 2100
        key = (artist.casefold().strip(), song.casefold().strip())
        assert key not in seen, f'Duplicate: {artist} / {song}'
        seen.add(key)
    return {'songs': len(songs), 'unique_artist_song_pairs': len(seen), 'decades': dict(Counter(str(int(r[2])//10*10) for r in songs)), 'genres': dict(Counter(r[3] for r in songs))}
