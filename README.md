# Tips
## Din assistent i jakten på miljonerna

### Om

Tips är ett enkelt litet desktopprogram för det svenska stryktipset och europatipset. Primär målgrupp är inte vanliga slutanvändare, sådana hänvisas till Svenska spel med flera aktörer som har välutvecklade och lättanvända lösningar för olika spelformer. Detta system vänder sig istället till programmeringskunniga personer som vill utveckla ny funktionalitet och laborera med statistik, främst via modulsystem.

### Features

- Integrering med Svenska spels API
- Parallell exekvering
- Modulsystem
- Kompletta R-system

![Tips](https://raw.githubusercontent.com/dougherty-dev/Tips/refs/heads/main/readme/tips.avif)

### Systemkrav

- PHP 8.4 (FPM för parallell exekvering)
- SQLite 3

Optionellt krävs även FANN 2.1 för beräkningar med neuralt nätverk. Installera med `pecl install fann` för PHP tillsammans med fannlib för din maskin.

En utvecklingsmiljö är definierad via composer. Den består av:

- PHPStan
- CodeSniffer
- PHPMD
- PhpMetrics
- PHPUnit
- PHPDoc

Installera med `composer install`. Det är inte på något sätt obligatoriskt, men underlättar vid utveckling.

### Kom igång

Definiera först en ny host för din webbserver, peka / mot `src`. Stöd för relativa URI:er finns för närvarande inte (i javascript).

Standardkonfigurationen ges av ett mindre dataset `src/_data/dist` med tillhörande databas och filer. Mappen `dist` med innehåll bör kopieras till `skarp`, som därefter kan byggas ut med nya (och äldre) omgångar. Ytterligare en mapp `_test` används av PHPUnit, men har ingen annan relevans.

Sajten bör nu kunna laddas, och en frontsida med en rad tabeller visas. Tabellerna omfattar omgångsdata, favoriter, odds, resultat med mera.

En rad andra segment är definierade i flikar längst upp på sidan.

#### Preferenser

I filken för preferenser finns fält för PHP och dess FCGI-form. Dessa måste fyllas i med rätt sökväg för att kunna generera tipsrader via bakgrundsprocesser.

Det finns även ett fält för API-nyckel till Svenska spel. Detta behövs om man vill hämta speldata, i annat fall gäller manuell inmatning.

För parallell exekvering över flera kärnor finns en inställning kallad parallella trådar. Välj en lämplig uppsättning för ditt system. Utan parallellitet kan det ta flera sekunder att tugga igenom beräkningar med moduler och annat, annars vanligen under sekunden.

### Moduler

Ett antal moduler följer med systemet. Dessa kan aktiveras i valfri ordning.

- Autospik: tar automatiskt fram spikar för n antal topprankade matcher
- System: nyttjar reducerade system med 12 eller 11 rätts garanti, samt medger manuell inmatning av spikar och garderingar i upp till åtta fält
- Distribution: plottar fördelning av sannolikhetssummor och väljer ut rader inom ett visst intervall
- Kluster: begränsar urvalet av rader till vissa segment av en lämplig projektion av tipsrummet
- HG: definierar mest sannolika halvgarderingar och begränsar utvalet efter antal rätt i HG-raden
- TT: topptipset som modulkomplement med liknande funktionalitet
- Andel: begränsar urval efter teckenfördelning
- FANN: neuralt nätverk för bestämning av troligt utfall medelst halvgarderingar och spikar, en vassare form av HG
- Vinstgraf: Grafisk framställning av vinstrader i en projektion av tipsrummet

Smidig förinställning av moduler med vissa värden kan ges av scheman definierade i fliken Scheman.

![Tips](https://raw.githubusercontent.com/dougherty-dev/Tips/refs/heads/main/readme/distribution.avif)

![Tips](https://raw.githubusercontent.com/dougherty-dev/Tips/refs/heads/main/readme/topptipset.avif)

### Data

Systemet får mer mening med mycket data, som man får hämta själv på annat håll. Den databaskunnige har därefter inga problem med att tanka in relevanta data i databasen. Nätet erbjuder historiska data för dessa tipsformer.

Nya data hämtas via Svenska spel, om man har en API-nyckel. Data för omgång, matcher, odds, streck med mera fylls då i automatiskt, varefter man kan välja att spara.

Saknar man API-nyckel går det bra att fylla i för hand. Man nyttjar då en befintlig omgång och skriver in nytt omgångsnummer (som man måste ta reda på). Spara, varefter man kan redigera vidare med att fylla i matcher med mera. Man vill förmodligen ha en API-nyckel.

### Generering

Efter att fullständiga data har matats in, kan man välja att generera tipsrader. En generator plockar fram samtliga 1.5 miljoner rader i sekvens, samt jämför med kriterier definierade av aktiva moduler. En graf presenteras i fliken Genererat, i form av en projektion av det trettondimensionella rummet i två dimensioner. Grafen sparas permanent i filken Spelat om man väljer att spara.

Man kan definiera flera spel per omgång, här kallat sekvenser. Efter lagt spel definierar man därmed en ny sekvens, och kan då generera rader på nytt.

Data för moduler Distribution och System (garderingar) sparas per omgång och sekvens, övriga har en fix uppsättning.

Raderna sparas kompakt i databasen, men även i en textfil som kan skickas till Svenska spel, för närvarande med manuell uppladdning. Dessa filer finns under `src/_data/(mapp)/genererade/(år)/(spelform)`.

![Tips](https://raw.githubusercontent.com/dougherty-dev/Tips/refs/heads/main/readme/system.avif)

### Resultat

Efter avslutad omgång kan man hämta (eller manuellt mata in) resultat, varvid eventuella vinstrader presenteras. Grafen uppdateras med ett vinstkors och eventuella vinstmarkörer. Ytterligare två grafer visar så kallad vinstspridning samt en kombinationsgraf omfattande andra grafiska moduler (här enbart Kluster).

![Tips](https://raw.githubusercontent.com/dougherty-dev/Tips/refs/heads/main/readme/resultat.avif)

Den grafiska framställningen kan avslöja egenheter för spelet i sig, men särskilt för vissa omgångar, och kan då nyttjas för analys.

![Tips](https://raw.githubusercontent.com/dougherty-dev/Tips/refs/heads/main/readme/graf.avif)

### Investera

De spel man lägger bokförs i filken Investera, om man väljer detta tillval då man sparar raderna. Man kan på så sätt följa tillväxten av kapital över tid, samt se en grafisk presentation över vinsterna. Det givna exemplet i `dist` är fiktivt, och i verkligheten kan det hända att tillväxten är mer negativ. Spela inte för mycket!

![Tips](https://raw.githubusercontent.com/dougherty-dev/Tips/refs/heads/main/readme/invest1.avif)

![Tips](https://raw.githubusercontent.com/dougherty-dev/Tips/refs/heads/main/readme/invest2.avif)

### Programmering

Drygt 450 klasser nyttjas, varav några är aningen för komplexa, men det speglar då den underliggande komplexiteten i algoritmen. I snitt ligger cyklomatisk komplexitet på 2.77 per klass, med 0.07 potentiella buggar per klass. Det ser grönt ut.

Linters ger inga anmärkningar, men självklart finns buggar, särskilt i randvillkor som aldrig testas i verklig miljö. Särskilt för små datamängder kan dessa komma att manifesteras. Detta är för hugade spekulanter att nysta i.

![Tips](https://raw.githubusercontent.com/dougherty-dev/Tips/refs/heads/main/readme/phpmetrics.avif)
