# Report 5.

Týždeň 8. (12.4 - 18.4.2022)

Meno: Tomáš Figura

Názov projekt: Expense Tracker
URL: https://www.polkadot-hub.eu/public

### Týždenný plán
- Odhlasovanie sa - Dokončené v predošlom týždni
- Nastavenie preferovaného zobrazovania času a následná aplikácia na stránkach - 45min
- Opakujúce sa transakcie - 2h
- Import excelu z VÚB exportu a vytvorenie transakcií k jednotlivým položkám - 1h 30m
- Vyladenie Frontendu - 1h
- Nastavenie budgetov a ich kontrola - 3h

### Týždenné zhotovenie

- Fixol som aby sa frontend zobrazoval správne aj na menších rozlíšeniach a zmenil som menu na fixed ab84d64402339ecd6a265b13b7695151627d3201, a47c4ff3499cf717f7da8ed0929d1db782ebc529 

- Zobrazovanie času podľa toho aké si používateľ nastavil - 1cf99b05b8e967c8c1878f51545d26d46857a9c0 

- Vytvoril som command ktory bude cronjob spúštať v intervaloch a bude kontrolovať či prešiel dostatočne dlhý čas na replikovanie transakcie beca073c52184743d8983b468b98e576eee205d8 

- Pri vytváraní transakcie výdaju som pridal možnosť opakovania transakcie s výberom 0e7baabdbc556f769ad63b25e5bb8a8cf443d4f2 

- Modálové okno a logika na import .csv súboru exportu z VUB 6a3c6b0d09a8178783611c2b7193d6e812882a61 

- Vytvoril som možnosť vytvorenia budgetov na podstránke Kategórií, po vytvorení sa zobrazujú v tabuľke pričom sa dajú aj mazať - a12a0c0af13fa86230fcf143603f169e6ac8929d 

- Command ktorý bude spúštať cron job a kontroluje či aktivý budget neprekročil dlžku intervalu. V takom pripade sa vynuluje dosiahnutá hodnota kategórie a nastaví sa začiatočný čas nového intervalu - 3774f6c4f546628131edc3f4c75732d64de67e53 

- Pri výtváraní a mazaní transakcie výdaju sa zvačšuje/zmenšuje dosiahnutá hodnota budgetu 3774f6c4f546628131edc3f4c75732d64de67e53 


### Plány na ďalší týždeň
- Implementácia načítania údajov o akciách z API - 3h 
- Implementácia načítania údajov o kryptomenách z API - 3h
- Implementácia spustenia asynchrónneho procesu na získanie údajov po kliknutí používateľom - 1 h
- Odosielanie e-mailových reportov - 2h

### Zhrnutie

Spravil som vytváranie a kontrolu budgetov, import csv je pripraveny ale nie dokončený lebo som za víkend nevykonal žiadnu transakciu kartou, čiže mi nedošlo csv na email. Spravil som prikazy ktore budu fungovat na kontrolu budgetov a opakovanie transkacie. Vyladil som frontend aby sa zobrazoval spravne na menšom rozlišení

Problémy som nemal žiadne a na riešení som strávil 8h 15min