# Report 4.

Týždeň 7. (5.4 - 11.4.2022)

Meno: Tomáš Figura

Názov projekt: Expense Tracker
URL: https://www.polkadot-hub.eu/public

### Týždenný plán
- Úprava a vymazanie záznamov transakcii, účtov, kategórií - 6h 
- Implementácia pridávania položiek k účtom a transakciam spolu s podporou kombinácii mien - 2 h
- Nastavenie budgetov pri kategóriách a ich kontrola pri vytváraní transakcií - 3h
- Nahrávanie súborov pri vytváraní transakcie - 1h
+ Stránka so štatistikami - 2h

### Týždenné zhotovenie

- Pri 'Predaji' pridal repeater ktorý po vybratí zdrojového účta vypíše v ponuke možné položky ktoré účet vlastní a počty ktoré je možné predať 6ef539297e326ba65d08a751becd5e604fb62602. 

- Zmenil som pravidlá vo formulári tak aby boli povinné iba nutné polia 9c1dd6c4c40ebbde7af9d7f687dea30eed110545 
- Prerobil som vypisovanie údajov v tabuľkách, tak aby prebiehali rovnako v každom komponente. Pridal som tabuľky aje pre podrstránky ktoré ich ešte nemali 7d87becfeff8375465d1b49a92b5f742367d1806.
- Úprava záznamov transakcií v modálovom okne a s vypísaním položiek ktoré  transakcie obsahujú b78ce497811399f4c5d01665411dbad5803397d7 
- Opravenie zobrazovanie správ po vykonaní akcie 59a20d347aa1fa1efcd058c1f8ce93b78d3e0c16, 5824ddd5db5b13ca21e789b15deaea58e3a8f58f  
- Resetovanie formulárov v modálovom okne po uprave záznamu 4b79abd561f73371ff2bdeb8e4358fccc12ff2ef 
- Mazanie záznamov transakcií 6b061bad46fc628b05a8fbd0ca3d277cdc83e6a, 71a3e071f4b32413b03561d57af7ee22488bd77c,
9bd17a5b98fa6f3fcb78b86098855e5b44adf57b,
e7b4509a385f743c3f270405b116559535794273   
- Zmena typov vzťahov na správne pre Modely b59aa9009b629f19454d85dbb2e98a5c1e3fe946 
- Refaktorizícia vytvárania transakcií 13b73f2781bc5027dd5a42babf1c96e88c83bb9e 
- Konverzia výslednej sumy transakcie na hlavnú menu účtu d2110d371758224a1962743231f4ec876f37d5ca, 40511e08f77af571ee7dcfb5ec806483423c218b  
- Update záznamov transakcií 3743a985a6d37f46c6ae85ad453acc81c8f3bc5e 
- Inštalovanie balíčka pre zobrazovanie grafov a vytvorenie dvoch
77e7f0541f9943de6b11fe577df0d02465416112 
- Mazanie kategorií a ich update 4338aafaa477732bad7fc576e577a1f8d3af68c4 
- Zobrazovanie údajov nastavení používatela v inputoch a ich úprava bb69387c58196d7bcb5838fb265f87811ecd931b, b93e2bea41aefeb6c6b1a47acccec83975f0aadd  
- Nahrávanie súborov k transakciám b3db02de47a6380d4afa1bee9ea6cbcbecf4414d 

### Plány na ďalší týždeň
- Odhlasovanie sa - 2h
- Nastavenie preferovaného zobrazovania času a následná aplikácia na stránkach - 1h
- Opakujúce sa transakcie - 3h
- Import excelu z VÚB exportu a vytvorenie transakcií k jednotlivým položkám - 3h
- Vyladenie Frontendu - 1h
- Nastavenie budgetov a ich kontrola - 2h
- Pridanie ďalších grafov

### Zhrnutie
Tento týždeň som sa venoval hlavne refaktorizácií a CRUD metódam, kde  pri vymazaní transakcie je nutné vymazať položky transakcie, odpočítať položky účtu a znížiť celkovú hodnotu účtu. 

Pri update som musel implementovať náčítania položiek do repeatra a všetkých ďalších polí.

Hľadal som riešenie na problém kde pri reaktívnych formulároch som nebol schopný získať hodnotu poľa mimo repeatera. Tento problém som vyriešil získavaním hodnoty priamo z livewire komponentu.

Pri ukladaní, mazaní a uprave sa skonvertujú meny na hlavnú menu učtu kde sa zapíše totálna cena.

Spravil som dva grafy pre zobrazenie kategórií a vývoju hodnoty transakcií.

### Problémy

Musel som riešiť viacero postranných taskov ktoré som nemal v pláne a aj tak som na tom strávil 10+ hodín, kedže som nebol spokojný s odterajšou implemntáciou a za niektorými taskami sa skrývalo toho viac ako som čakal pri plánovaní.

Problémy som mal aj s implemtáciou CID na webserver z Githubu na čom som strávil as 2 hodiny. Riešil som aj úpravu frontendu kedže som zistil že sa na menšom rozlišení stránka rozbíja.

Mal som problém rozbehania Google autentifikácie na webserveri a inštaláciou balíčkov.

tento týždeň som nestihol kontrolu budgetov a chcem dokončiť zobrazovanie obrázkov pri úprave transakcie (nahrávanie funguje).

Tento týždeň som strávil na projekte 12 hodín spolu s rozbehávaním na aplikácie na webserveri.

### Doterajšie výsledky

+ Funguje prihlasovanie, nastavenie zapamätania prihlásenia, úprava údajov používateľa.

+ Dajú sa vytvárať, upravovať, mazať transakcie typov Prijem, Výdaj, Nákup, Predaj pričom k Nákupu a Predaju sa dajú pridávať aj položky. Transakcie sa dajú aj filtrovať podľa viacerých možností. Funguje aj stránkovanie

+ Dajú sa vytvárať existujúce účty na ktoré sa dajú pridať peniaze alebo ine položky.

+ CRUD pre kategórie.

+ Stránka pre zobrazovanie štatistík s dvoma grafmi

+ Adresár pre vytváranie záznamov

+ CRUD metódy prebiehajú pomocou reaktívnych modálových formulárov


### Ďalšie plány

+ Budgety pre kategórie, opakujúce sa transkacie, import bankových výpisov, načítavanie cien z API pre položky, emailové reporty
+ Responzivita frontendu, Pridať ďalšie grafy
+ Možnosť sťahovania nahraných súborov