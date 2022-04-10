# Report 4.

Týždeň 7. (5.4 - 11.4.2022)

Meno: Tomáš Figura

Názov projekt: Expense Tracker

### Týždenný plán
- Úprava a vymazanie záznamov transakcii, účtov, kategórií - 3h 
- ~~Implementácia pridávania položiek k účtom a transakciam~~ spolu s podporou kombinácii mien - 3 h
- Nastavenie budgetov pri kategóriách a ich kontrola pri vytváraní transakcií - 3h
- Nahrávanie súborov pri vytváraní transakcie - 1h
+ Stránka so štatistikami - 2h

### Týždenné zhotovenie


### Plány na ďalší týždeň
- Úprava a vymazanie záznamov transakcii, účtov, kategórií - 3h 
- ~~Implementácia pridávania položiek k účtom a transakciam~~ spolu s podporou kombinácii mien - 3 h
- Nastavenie budgetov pri kategóriách a ich kontrola pri vytváraní transakcií - 3h
- Nahrávanie súborov pri vytváraní transakcie - 1h
+ Implementovať typ transakcie dlžoba a transfer - 2h
+ Stránka so štatistikami - 2h

### Zhrnutie
Neimplementoval som typ transakcie dlžoba lebo tento task bude možný využívať až po funkcionalite úpravy transakcie any sa dala dlžoba ukončiť ako vybavená. Spravil som frontend pre pridávanie položiek k účtom a transakciam. Dokončil som úlohu ktorú som mal robiť ďalší týžden a to ukladanie položiek do databázy. Namiesto nej budem robiť štatistiku a chýbajuce dva typy transakcií.

Implementoval som odlišné úkladanie údajov pri Príjme, Výdaji, Nákupe a Predaji. Prerobil som zobrazovanie záznamov transakcií a nastavil pagináciu spolu s filtrovaním. Pri vytváraní účtu som nastavil možnosť pridávania peňazí ako položiek. Polia formulára pri vytváraní transakcie sa dynamicky menia podľa vybraného typu transakcie (možnosť pridania položiek sa zobrazí iba pre Predaj a Nákup).

Nespravil som úlohu "Implementácia typov položiek Akcia, Kryptomena" kedže oba typy položky majú aj tak rovnaké polia a správanie.

Na projekte som stravil tento týždeň 8 hodín a 30 minút.

Narazil som na viaceré problémy:
+ Pri nastavovaní aby sa po nastavení typu transakcie na predaj a vybratia účtu načítali všetky položky ktoré má používateľ nakupené a množstvo sa nastavilo na počet položiek ktoré má nakúpené, nefunguje prepojenie medzi repeaterom a poliami formulára nad ním.
+ Datepicker knižnica sa mi nechcela načítať
+ Tailwind negeneroval CSS kód
+ V schémach tabuliek chýbali timestampy
+ Livewire neoodosielal požiadavku po nastavení dátumu