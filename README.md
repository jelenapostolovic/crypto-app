# Crypto Currency Application

## O projektu

Ova aplikacija je Symfony aplikacija koja prikuplja podatke i omogucava pretragu i upravljanje podacima o kriptovalutama sa javnog API-ja [CoinGecko API](https://api.coingecko.com/api/v3/), skladišti ih u relacijskoj bazi podataka, i omogućava RESTful API pristup tim podacima. Dodala sam i osnovne CRUD operacije, kao i unapređeni korisnički interfejs za jednostavniji pregled i interakciju sa podacima.

# Sympfony aplikacija je konfigurisana (Doctrine, Serializer, Routing, HTTP Client)

### Lokalna instalacija

1. **Klonirajte repozitorijum**
   ```bash
   git clone <URL_REPO>
   cd crypto

## Instalirajte zavisnosti 
    composer install

# Kreirajte .env fajl iz .env.example i takodje konfigurisite bazu u env fajlu na ovaj nacin 
DATABASE_URL="mysql://root:@127.0.0.1:3306/crypto_currency"

1. ***Pokrenite aplikaciju koristeći XAMPP**
   # Pokrenite Apache kao i MySQL
   # Da biste mogli da radite sa bazom podataka neophodno je koristiti http://localhost/phpmyadmin/ gde smo kreirali bazu crypto_currency i u njoj tabelu crypto_carrency

2. # Kreirajte bazu podataka 
php bin/console doctrine:database:create
# Nakon toga pokrenite migracije 
php bin/console make:migration
php bin/console doctrine:migrations:migrate
php bin/console doctrine:schema:update --force

### Fetcovanje podataka uz sledece komande
# Kreiranje Symfony CLI komande za fetcovanje podataka iz API-a i store-ovanje u bazu, nakon cega ce se kreirati src/Command/FetchCryptoDataCommand.php 
php bin/console make:command app:fetch-crypto-data
# Nakon pokretanja ove komande podaci ce biti sacuvani u bazi
php bin/console app:fetch-crypto-data

4. # symfony server:start
Pristup aplikaciji na http://localhost:8000/

# PHP anotacija za definisanje ruta
Odlucila sam se za ovaj nacin pisanja ruta kako bih omogucila citljivost i centralizaciju, jednostavnost i odrzavanje, kako projekat nije veliki moguce je koristiti ovaj nacin pisanja ruta. Ukoliko bih projekat krenuo da se usloznjava rute bih prebacila u config/routes/routes.yaml i na taj nacin bih omogucila centralizaciju svih ruta na jednom mestu.

# Sto se tice funkcionalnosti, moguca je pretraga cryptocurrency-a po ID Symbolu, Min i Max Price kao i prikaz svih crypto, potom Recent Updates koji prikazuje poslednje dve crypto koje su azurirane i po Lowest Price koji pokazuje pet crypta po najnizoj ceni. Takodje moguce je pomocu forme kreirati crypto, potom update-ovati, klikom na dugme update otvorice se nova stranica zajedno sa podacima odabrane crypto koju zelimo da update-ujemo, takodje moguce je i obrisati crypto na dugme delete. Na dnu stranice mozete posetiti link koji vas vodi do Crypto Trading Platforme.

# AUTOR: Jelena Postolovic