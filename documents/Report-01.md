# Report 1.

Týždeň 5. (15.3 -21.3.2022)
Meno: Tomáš Figura
Názov projekt: Expense Tracker

### Týždenný plán
- Vytvorenie základnej kostry projektu a nastavenie vývojového prostredia - 2h
- Vytvorenie modelov, ciest, tried - 4h
- Vytvorenie migrácii tabuliek s príslušnými poľami a ich importovanie do databázy - 4h
- Prihlasovanie pomocou OAuth, zistenie fungovania, implementácia - 2h
- Ukladanie prihlásenia po ukončení stránky - 2h

### Týždenné zhotovenie
- Vytvoril som prázdny laravel projekt v priečinku webových aplikácií programu XAMPP. Vymenil som PHP verziu 8 za najnovšiu 8.1 a narazil som na problém. Phpmyadmin verzia nebola kompatibilná s touto verziou PHP. Nainštaloval a nakonfiguroval som Xdebug rozšírenie.
- Vytvoril som prázdnu databázu "expense_tracker". Vygeneroval som všetky modely z Entitno-Relačného diagramu, doplnil som chýbajúce polia ktore som pridal aj do migračného suboru. Spustil som migrácie a všetky tabuľky sa vytvorili v databáze. 
- Nainštaloval som balíček na prihlasovanie pomocou externých stránok, vytvoril kontroler, zadefinoval cesty pre prihlásenie, odhlásenie a callback prihlásenia.  Vygeneroval  som v google console CLIENT_ID a CLIENT_SECRET ktoré som vložil  do .env suboru. Následne som v Google Console pridal zadefinovanú callback url do výnimky.
- Nainštaloval som balík tailwind a nakonfiguroval som automatické sledovanie zmien súborov, spolu s generáciou css súborov
- Nainštaloval som balíčky AlpineJs a Livewire
- Vytvoril som jednoduchý template, na ktorom je možné vidieť tlačidlá "Login" a "Logout". Ak používateľ nie je prihlásený, tak vidí tlačidlo "Login", na ktoré keď klikne tak sa mu otvorí Google stránka s možnosťami výberu google účta na prihlásenie. Po úspšnej autentifikácií je používateľ presmerovaný na "home" stránku a v hlavičke stránky je zobrazené jeho meno s tlačidlom "Logout". Email a "google_id" používateľa sa uložia do tabuľky "users". Pri prihlásení sa kontroluje príznak "remember_login", ak je true tak používateľ ostane prihlásený aj po ukončení stránky. 

### Plány na ďalší týždeň
- Metódy na vytváranie transakcií, účtov, adresára a ukladanie nastavení - 6 h
- Vytvorenie frontendu pre podstránky Účty, Nastavenia, Kategórie, Transakcie - 6h

### Zhrnutie
Vytvoril som kontróler iba pre triedu "User", ostatné kontrólery budem vytvárať priebežne, z dôvodu aby som nemal príliš veľa súborov v priečinku. Tak isto aj jednotlivé cesty budem vytvárať priebežne. Na projekte som strávil 12 hodín.

Mal som problém s tým že mi google neakceptoval moju callback url. Nad riešením problému som strávil asi hodinu času. Ďalšie problémy vznikli po nainštalovaní najnovšej PHP verzie, kde ďalšie sučasti neboli kompatibilné.