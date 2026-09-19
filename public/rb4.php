<?php
$dbPath = getenv('RB4_DATABASE') ?: dirname(__DIR__, 2) . '/rb4-data/catalog.sqlite';
try {
    if (!is_file($dbPath)) throw new RuntimeException('Catalog unavailable');
    $db = new PDO('sqlite:' . $dbPath, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $db->exec('PRAGMA query_only = ON');
    $songs = $db->query('SELECT artist, song, year, genre FROM songs')->fetchAll(PDO::FETCH_NUM);
} catch (Throwable $e) {
    http_response_code(503);
    exit('The song catalog is temporarily unavailable. Please try again shortly.');
}
?>
<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Rock Band 4 · Rollerfeet</title>
<style>
:root{color-scheme:light;--ink:#17152d;--muted:#686578;--purple:#6758e8;--line:#e7e5ee;--paper:#f6f5fa}*{box-sizing:border-box}body{margin:0;background:var(--paper);color:var(--ink);font:15px/1.45 system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}header{background:linear-gradient(115deg,#0f172a,#1e3a8a 62%,#059669);color:white;padding:24px max(20px,calc((100vw - 1100px)/2)) 32px}.brand{color:#dce8ff;text-decoration:none;font-size:13px;font-weight:750;letter-spacing:.12em;text-transform:uppercase}h1{font-size:clamp(30px,5vw,44px);letter-spacing:-.045em;margin:12px 0 2px}header p{margin:0;color:#dce8ff}main{max-width:1140px;margin:0 auto;padding:22px 20px 40px}.toolbar{display:flex;align-items:center;gap:14px;justify-content:space-between;margin-bottom:18px}.count{font-weight:700}.filter{position:relative}summary,button,select,input{font:inherit}summary{list-style:none;cursor:pointer;background:white;border:1px solid var(--line);border-radius:12px;padding:12px 18px;font-weight:750;min-height:46px}summary::-webkit-details-marker{display:none}summary:after{content:' ⌄';color:var(--purple)}.filter[open] summary{border-color:var(--purple)}.panel{position:absolute;right:0;top:56px;width:285px;padding:20px;background:white;border:1px solid var(--line);border-radius:18px;box-shadow:0 16px 48px #30266326;z-index:4}.panel label{display:block;font-weight:700;margin-bottom:6px}.panel select{width:100%;min-height:46px;border:1px solid #cbc8d8;background:white;border-radius:9px;padding:8px;margin-bottom:16px;color:var(--ink)}button{cursor:pointer}#reset{border:0;background:#eeebff;color:#4938bb;border-radius:9px;padding:12px;width:100%;font-weight:700}.table-card{border:1px solid var(--line);border-radius:20px;background:white;box-shadow:0 8px 30px #30266308;overflow:clip}table{border-collapse:collapse;width:100%;table-layout:fixed}th{background:#eeedf5;text-align:left;position:sticky;top:0;z-index:2}th button{border:0;background:transparent;color:var(--muted);font-size:12px;letter-spacing:.07em;text-transform:uppercase;font-weight:750;min-height:52px;width:100%;padding:14px 18px;text-align:left;white-space:nowrap}th[aria-sort] button{color:#4938bb}.arrow{margin-left:4px}td{padding:15px 18px;border-top:1px solid #efedf4;vertical-align:top;overflow-wrap:anywhere}td:first-child{font-weight:650}td:nth-child(3){font-variant-numeric:tabular-nums;color:var(--muted)}td:last-child{color:var(--muted);font-size:13px}tr:hover td{background:#faf9ff}th:nth-child(1){width:29%}th:nth-child(2){width:39%}th:nth-child(3){width:12%}th:nth-child(4){width:20%}:focus-visible{outline:3px solid #b1a8ff;outline-offset:3px}#empty{text-align:center;padding:36px;color:var(--muted)}footer{color:var(--muted);font-size:12px;margin-top:20px}footer a{color:#4938bb}.sr{position:absolute;width:1px;height:1px;overflow:hidden;clip-path:inset(50%)}@media(max-width:600px){header{padding:20px 16px 24px}main{padding:18px 10px 30px}.toolbar{padding:0 4px}.count{font-size:14px}th button{padding:12px 7px;font-size:10px;letter-spacing:.02em}td{padding:13px 7px;font-size:13px}td:last-child{font-size:12px}th:nth-child(1){width:28%}th:nth-child(2){width:36%}th:nth-child(3){width:15%}th:nth-child(4){width:21%}.table-card{border-radius:14px}.panel{width:min(285px,calc(100vw - 30px))}}
</style></head><body>
<header><a class="brand" href="https://rollerfeet.com">Rollerfeet / The collection</a><h1>Rock Band 4</h1><p>A little less scrolling. A little more rock.</p></header>
<main><div class="toolbar"><div id="count" class="count" role="status" aria-live="polite"></div><details class="filter"><summary id="filter-label">Filters</summary><div class="panel"><label for="decade">Decade</label><select id="decade"><option value="">All decades</option></select><label for="genre">Genre</label><select id="genre"><option value="">All genres</option></select><button id="reset" type="button">Clear filters</button></div></details></div>
<div class="table-card"><table><caption class="sr">Song collection. Select a column heading to sort; select it again to reverse the order.</caption><thead><tr><?php foreach (['Artist','Song','Year','Genre'] as $i => $name): ?><th scope="col"><button type="button" data-column="<?= $i ?>"><?= $name ?><span class="arrow" aria-hidden="true"></span></button></th><?php endforeach; ?></tr></thead><tbody id="songs"></tbody></table><p id="empty" hidden>No songs match these filters.</p></div>
<noscript>Please enable JavaScript to browse the song collection.</noscript><footer>1,067 songs in Johnny’s collection · Years and genres from <a href="https://rb4.app/">RB4.app</a>.<br><a href="https://github.com/jaireaux/rock-band-4">Source &amp; catalog</a></footer></main>
<script id="catalog" type="application/json"><?= json_encode($songs, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR) ?></script>
<script>
'use strict';
const songs=JSON.parse(document.getElementById('catalog').textContent),decade=document.getElementById('decade'),genre=document.getElementById('genre'),body=document.getElementById('songs');
const collator=new Intl.Collator('en',{numeric:true,sensitivity:'base'});let column=0,direction=1;
const decadeOf=row=>Math.floor(Number(row[2])/10)*10;
[...new Set(songs.map(decadeOf))].sort((a,b)=>a-b).forEach(x=>decade.add(new Option(x+'s',x)));
[...new Set(songs.map(row=>row[3]))].sort(collator.compare).forEach(x=>genre.add(new Option(x,x)));
function render(){
 const rows=songs.filter(row=>(!decade.value||decadeOf(row)===Number(decade.value))&&(!genre.value||row[3]===genre.value));
 rows.sort((a,b)=>{const cmp=column===2?Number(a[2])-Number(b[2]):collator.compare(a[column],b[column]);return direction*(cmp||collator.compare(a[0],b[0])||collator.compare(a[1],b[1]));});
 const fragment=document.createDocumentFragment();for(const row of rows){const tr=document.createElement('tr');for(const value of row){const td=document.createElement('td');td.textContent=value;tr.append(td);}fragment.append(tr);}body.replaceChildren(fragment);
 document.getElementById('count').textContent=rows.length.toLocaleString()+' of '+songs.length.toLocaleString()+' songs';document.getElementById('empty').hidden=rows.length>0;
 document.getElementById('filter-label').textContent='Filters'+((decade.value||genre.value)?' · '+[decade.value,genre.value].filter(Boolean).length:'');
 document.querySelectorAll('[data-column]').forEach(button=>{const active=Number(button.dataset.column)===column;button.querySelector('.arrow').textContent=active?(direction===1?'↑':'↓'):'';button.parentElement.removeAttribute('aria-sort');if(active)button.parentElement.setAttribute('aria-sort',direction===1?'ascending':'descending');});
}
document.querySelectorAll('[data-column]').forEach(button=>button.addEventListener('click',()=>{const next=Number(button.dataset.column);direction=next===column?-direction:1;column=next;render();}));
decade.addEventListener('change',render);genre.addEventListener('change',render);document.getElementById('reset').addEventListener('click',()=>{decade.value='';genre.value='';render();});
document.addEventListener('keydown',event=>{if(event.key==='Escape')document.querySelector('details').open=false;});
render();
</script></body></html>
