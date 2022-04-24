# Report 5.

Týždeň 9. (19.4 - 15.4.2022)

Meno: Tomáš Figura

Názov projekt: Expense Tracker
URL: https://www.polkadot-hub.eu/public

### Týždenný plán
- Implementácia načítania údajov o akciách z API - 1h 
- Implementácia načítania údajov o kryptomenách z API - 1h
- Implementácia spustenia asynchrónneho procesu na získanie údajov po kliknutí používateľom - 5 h
- Zobrazenie položiek akcií, kryptomien a financií pri zobrazení účtov - 3h
- Import VUB .csv exportu ako transakcií - 3h

### Týždenné zhotovenie

- Získavanie cien kryptomien a akcií z API pomocou príkazu e76ccdd45ec457a1e96c518dd3e8931389c2cabb
- Spúšťanie commandu na kontrolu budgetov každých 5 min 2535bccf4a9445610b097a63afcf82d522a77883
- Nastavenie aby bežal na hostingu task scheduler
- Vytvorenie Job-u pre queue ktorý bude získavať aktuálne hodnoty položiek 757578132c8b7b6d50d796767a4c62548efc7982, 51e88f82f9d68e28bf4a5687dcf541454dbeff24, dffc16e952e41ba432618df4d2829a205107c8cd
- Pridanie tlačítka do sekcie účtov ktoré dispatchne job a pridanie poľa do tabuľky na indikáciu či sa získavaju ceny akurát d6020c86a57e37c236e2e8a55348c5afed55c0b8, d4582d68569697bc3940128deda78e7bc85ebdf3,
a296529e006a9da6900c112547b7a035625f5722
- CSV import dokončenie 9bd121cfafdeb2eea1105aa5969924b2bf2bc668
- Zobrazenie položiek účtov pre používateľa v tabuľkach a zoradenie od najväčšej hodnoty
2e3be273e201e8a31bff21d2ad724189d653e205, 36d3b024e2b457743d72b68cdb7514f5e3c266a0


### Plány na ďalší týždeň
- Responzivita a doladiť frontend - 3h
- Testovanie - 3 hodín
- Získavanie cien položiek pravidelne každý deň aby sa dal vytvoriť graf vývoja hodnoty celkoveho majetku - 2h


### Zhrnutie

Používateľ može naimportovať príjmi a výdaje z .csv exportu VUB banky. 

Vie si pozrieť zoznam všetkých svojich položiek a ich hodnoty (sú podľa nej zoradené).

Po stlačení tlačítka sa vytvorí job ktorý po spracovaní workerom získa aktuálne ceny akcií a kryptomien ktoré vlastní, pričom podľa toho zmení celkovú hodnotu účtov.

Je nastavený task scheduler ktorý kontroluje budgety a opakujúce sa transakcie.

### Problémy

Hlavne s nastavovaním workerov na hostingu, následne keď sa mi to podarilo tak som zistil že vykonanie jobu je limitované na 60 sekund čo nie je dostatočné, lebo cez API mam povolených iba 5 requestov za minútu.

Potom som mal problém so zobrazovaním správy po úspešnom vytvorení transakcie alebo importe transakcií.

Na zadaní som strávil 13 hodín tento týždeň.