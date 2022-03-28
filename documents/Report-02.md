# Report 2.

Týždeň 6. (22.3 - 29.3.2022)

Meno: Tomáš Figura

Názov projekt: Expense Tracker

### Týždenný plán
- Metódy na vytváranie transakcií, účtov, adresára a ukladanie nastavení - 6 h
- Vytvorenie frontendu pre podstránky Účty, Nastavenia, Kategórie, Transakcie - 6h

### Týždenné zhotovenie
- Vytvoril som základný layout s postranným menu, v ktorom je tlačítko na prihlásenie pomocou Google účta. Následne po úspešnom prihlásení sa zobrazia položky menu.  
- Implementoval som jednotný spôsob zobrazovania modálneho okna pomocou Livewire, Alpine.js a naštýloval som ho pomocou CSS frameworku Tailwind. Taktiež som nainštaloval balíček Powergrid na zobrazovanie uložených záznamov kategórií v interaktívnej tabuľke.
- Nainštaloval som balíček na zjednodušené vytváranie interaktívnych formulárov Filament.
- Vytvoril som súbory pre zobrazenie modálových okien pre Transakcie, Kategórie, Adresár, Nastavenia a Účty.
- Nakonfiguroval som kontrólery pre jednotlivé modálové okná. Podmienené zobrazenie polí pre vytváranie transakcií v závislosti od typu transakcie. Formuláre som odtestoval a poupravoval som stĺpce v tabuľkách aby všetko fungovalo.
- Po uložení záznamov sa skryje modálové okno a zobrazí sa notifikácia.

### Plány na ďalší týždeň (30.3 - 4.4)
- Implementácia typov položiek Akcia, Kryptomena - 4 h
- Implementácia typov transakcií Výdaj, Prijem, Prevod, Nákup, Dlžoba - 6 h
- Frontend na pridávanie položiek k účtom a transakciám - 2h
- Stránka so štatistikami - 3h
- Filtrovanie transakcií - 2h

### Zhrnutie
Vytvoril som cesty, modálové okná, a kontrólery pre Transkacie, Účty, Nastavenia a Adresár. Naštýloval som formuláre a odstestoval som ukladanie údajov. Na projekte som tento týždeň strávil 14 hodín. Zobrazenie uložených údajov naštýlujem až keď dokončím pridávanie typov položiek. Nastavil som na lokále custom doménu pre projekt http://www.expense-tracker.localhost/

Narazil som na viacero problémov, hlavne kvôli tomu že som prvý krát pracoval s kombináciou knižníc Livewire a Alpine.js. Podstatnú dĺžku času som strávil pozeraním návodov a čítaním dokumentácií. Napriklad jeden z problémov bol nefungujúca cesta na Livewire komponenty, ktorý som vyriešil nastavením custom domény. V budúcnosti musím poriešiť chybu ktorá nastáva pri renderovaní vyberača času a dátumu transakcie.