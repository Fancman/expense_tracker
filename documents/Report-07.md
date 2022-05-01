# Report 7.

Týždeň 10. (25.4 - 2.5.2022)

Meno: Tomáš Figura

Názov projekt: Expense Tracker
URL: https://www.polkadot-hub.eu/public

### Týždenný plán
- Doladiť frontend - 3h
- Testovanie - 1 hodín
- Získavanie cien položiek pravidelne každý deň aby sa dal vytvoriť graf vývoja hodnoty celkoveho majetku - 2h

### Týždenné zhotovenie

- Upravil som frontend zobrazovanie tabuliek d0f267f42c99531d4a9846980f59a50f5ab89e94 
- Vytvoril som tabuľky ktoré zobrazujú v sekcií "Učet" všetky položky Akcií, Kryptomien a Financií, ktoré su podľa mena pogroupované a je vypočítana ich celková hodnota 8336751acfd6ce663d705236b8eb9a292f7685ea 
- Commandy na ukladanie hodnoty účtov každý deň bd543cc7a44f43ab1614617a3171e47afb785011, a9f197334bc119c05b0dd8ea279b95e987bf954a  
- Graf vývoja hodnoty portfólia z každodenných uložených hodnôt účtov a koláčový graf zobrazujúci podiel jednotlivých položiek v portfóliu - 3cf1931628ed827e56afda74774d2258390799d7 


### Plány na ďalší týždeň
- Responzivita a ďalšie dolaďovanie frontendu - 2h
- Pospúšťať CRON úlohy na hostingu a workerov na raspbery pi - 2h 
- Zapisovanie hodnôt položiek každý deň - 1h
- Vypočítanie a zobrazenie celkovej straty/profitu pri účtoch a položkách - 1h
- Ukladanie konverných kurzov v databáze a ich obnovovanie - 1h


### Zhrnutie

Vytvorenie SQL dotazu pre zobrazovanie tabuliek s položkami mi zabralo dlhší čas lebo som nemal skúsenosti s použitím RAW queries v Eloquent query buielderi. V sekcií účtu sa zobrazujú všetky položky. Zobrazený je aj graf s nimi a vývojom hodnoty.
Ďalej mi zabralo zas veľa času rozbehať workerov na hostingu bez toho aby sa vypli po 60 sekundách čo sa mi ale nepodarilo, tak som sa rozhodol rozbehať workerov na raspberry pi. Na projekte som strávil tento týžden približne 7 hodín.