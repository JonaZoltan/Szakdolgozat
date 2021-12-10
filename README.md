# BaseYii

Egy alap modul a jelenlegi keretből. Lepucolva belőle minden speciális modult, táblát. Későbbi projektek alapja lehessen. 


- Az 'alap modul menü' 1:1 táblával osztályokkal.  (insertben Admin: Gabi és Én) 
- Dashboard
- Üres (!) Migrate tábla 
- Capability: csak az alapmodulhoz tartozó értékek.
- APPS model fájlból (és folderből) is ami nem az alaphoz tartozik (TP, stb)
- SQL fájl: base.sql (script, nem bin) --> MIGRATE
- Logó: Trivium csere Szitár-Net


1. Teljesen virgin yii2 keret install 
2. Modul strzktúra (Modules, Translate, DB connect, Cachek, Dashboard(?), stb.)
3. Pluginek: Azok és csak azok a kiterjesztések feltelepítése,  amik elengedhetetlenek (kartik?) 
4. Bootstrap Theme 4.
5. DB migrálás és system modulok megépítése/felhasználva a fw alapokat. users: átnézni! Nem biztos, hogy minden oszlop kell!