# Report 3.

Týždeň 7. (30.3 - 4.4.2022)

Meno: Tomáš Figura

Názov projekt: Expense Tracker

### Týždenný plán
- Implementácia typov položiek Akcia, Kryptomena - (Nebolo potrebné)
- Implementácia typov transakcií Výdaj, Prijem, Prevod, Nákup, Dlžoba - 3 h
- Frontend na pridávanie položiek k účtom a transakciám - 1h
- Stránka so štatistikami - (Odkladám na ďalší týždeň, namiesto toho som implementoval ukladanie položiek)
- Filtrovanie transakcií - 1h 30min
- Ukladanie položiek pri Účte a Transakciách - 3h

### Týždenné zhotovenie
- Vytvoril som tabuľku pre vypisovanie transakcií a Účtov, kedže balíček Powergrid nepracoval podľa mojích predstáv 304ad8e34e4d805dd93740c40547bf8c4ccf65f2.
- Na každej podstranke sa zobrazuje hlavička s dynamickým názvom 77da5b12c6ddb28dc3420ef350997388e0aabce9.
- Do modálového okna na vytváranie transakcií som pridal možnosť vytvárania položiek a to Akcií a Kryptomien 40bf42d4d9eb32f654fe69876ff1b98431745db1.
- Implementoval som odlišné správanie pri vytvárani nákupu a predaja. Položky sa vytvoria v tabulke transaction_items a account_items.df76d4e067da3c2d83fa7c3603fea07575c26260 Následne sa zmení odpočíta alebo pripočíta hodnota ku stavu peňazí pre účet d3a4ba0f46da70020558ee1e0abe9cf4d5ba2561.  
- Pridal som možnosť pridávania položiek aj k účtom. Rozhodol som sa vytvoriť nový typ položky a to "Peniaze", kedže jeden účet može mať viacero mien 56e73061dfae8fdcb446366ad6153325a395ab01.
- Implementoval som príjem a výdaj po ktorých sa zmení aj hodnota celého účtu 5ed34c5dad7084de5fdc6904d7694fcf5fb82e8e.
- Nastavil som pagináciu pre zoznam transakcií spolu s linkovaním na strany záznamov 4eeb26d81bdc393afac16049044836243affe729.  Možnosť filtrovania transakcií podľa názvu 84423c76943aea70cfe35107c617d73f636e7f87, času vzniku transakcie 857991a57164242033ea6e9b9d0a6d364ebac48d a typu transakcie 846f6af5493366e9e595fb7ad9c4f2cbcd8e89aa

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