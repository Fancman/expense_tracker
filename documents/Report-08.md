# Report 7.

Týždeň 11. (3.5 - 9.5.2022)

Meno: Tomáš Figura

Názov projekt: Expense Tracker
URL: https://www.polkadot-hub.eu/public
Prihlasovacie údaje: test@test.com:test (meno:heslo)

### Týždenný plán
- Pospúšťať CRON úlohy na hostingu a workerov na raspbery pi - 3h 
- Zapisovanie hodnôt položiek každý deň - 1h
- Vypočítanie a zobrazenie celkovej straty/profitu pri účtoch a položkách - 2h
- Registracia a prihlasenie - 2h
- Mazanie účtov - 1h

### Týždenné zhotovenie

- Vývoj portfólia a veštky položky - graf - 3cf1931628ed827e56afda74774d2258390799d7 
- Registrácia a prihlásenie - 7bb806e399777bbc26d190d29d77b0a35d9e6291 
- Konfigurácia dockeru 7b16e487de258953b21e96b00c5d9a7b24eaf8c6 
- Nastavenie invalid_name v prípade že api nenašla položku dee76a6e8fca8bb1ae2a6bb8cabc076661f771fc 
- Výpočet aktualnej hodnoty účtov a výpis percentuálnych zmien položiek profit/loss e6b588e2690767dc76735a5c8c09729cc0871139 cd8481046dc286a4bcc4344c186397a2e46a1fc3 
- Príkaz na získanie cien položiek 0169f2cc8512486e694afa93a68ff2a5c4e3730e 
- Mazanie účtu spolu s transakciami a položkami fd33df4fdd21b264c6ab513119b1105eb8bab493 
- Ďalšie commity sú snaženie sa rozbehania cron jobov a queue workera

### Zhrnutie týždňa

Vytvoril som prihlasovanie pomocou mena a hesla na žiadosti spolužiakov, na stránke účtov som vytvoril grafy vývoja portfólia a pri položkách tiež. Scheduler kontroluje každých 5 minút expiráciu budgetov, opakovanie transkacií. O 22:00 začne získavať ceny položiek z API, o 23:00 vypočíta hodnoty účtov a uloží ich pre daný deň a 15 minút na to prepočíta hodnoty účtov. pridal som možnosť mazania účtov spolu s ich transakciami a položkami.

Snažil som sa rozbehať Docker ktorý by automaticky po pushe pullol projekt z githubu ale nepodarilo sa mi to rozbehat. Toto som chcl spraviť kvoli tomu že worker na hostingu ma obmedzný execution time. Nakoniec som radšej setupol ručne projekt na raspberry pi mašine spustil som scheduler worker.

S workerom som sa babral asi 3 hodiny lebo som mal problém že joby failovali hneď po vytvorení.

Tento týždeň som strávil na projekte 9 hodín.

### Celkové zhrnutie 

Implementované fetures:

- Prihlasovanie/registrácia s menom a heslom
- Prihlasovanie pomocou Google účtu
- Zápis, mazanie, úprava transakcie prijem, výdaj, nákup, predaj, dlžoba, transfer
- Importovanie VUB export csv
- Filtrovanie transakcií
- Vytvorenie a mazanie účtov
- Graf Vývoju portfólia a jeho zloženie
- Hodnota účtov a zisk/strata
- Zoznam položie a percentuálny rozdiel aktualnej ceny a nákupnej
- Vytváranie, mazanie a úprava kategórií
- Vyvtváranie a mazanie budgetov a ich kontrola pri vytváraní transakcie
- Graf výdajov a pomer typov transakcií
- Vytváranie, mazanie záznamov do adresára
- Nastavovanie user settings
- Získavanie aktuálnych cien položiek z API a výpočet hodnôt účtov