![](https://avatars0.githubusercontent.com/u/4995607?v=3&s=100)![](https://i.ibb.co/qDqbCms/Untitled-2.jpg)

TrainMe [![Build Status](https://travis-ci.com/nfqakademija/trainme.png?branch=master)](https://travis-ci.com/nfqakademija/trainme)
============

# Intro

TrainMe - tai asmeninio trenerio paieškos ir treniruočių rezervavimo sistema, kuri klientui leidžia rasti patinkantį trenerį, rezervuoti norimą treniruotės laiką ar atšaukti rezervuotą treniruotę. Užsiregistravęs treneris gali įvesti savo darbo laiką, keisti asmeninę informaciją bei peržiūrėti klientų rezervuotas treniruotes kalendoriuje. Projekto sprendžiama problema - trenerio laiko ir treniruočių rezervacijos bei komunikacijos su klientais supaprastinimas.

# Paleidimo instrukcija

### Reikės dokerio

Naudosime naujausią dokerio versiją, kuri įgalina virtualizaciją be Virtualbox ar Vmware.
 Tam reikės, kad jūsų kompiuterio procesorius palaikytų [Hypervisor](https://en.wikipedia.org/wiki/Hypervisor).
 Nėra dėl ko nerimauti, dabartiniai kompiuteriai kone visi turi šį palaikymą.

Parsisiunčiate ir įsidiegiate įrankį iš [čia](https://docs.docker.com/install/linux/docker-ce/ubuntu/). Iškart įdiegus reikia pasidaryti, kad `docker` būtų galima naudoti be root teisių, kaip tai padaryti rasite [čia]( https://docs.docker.com/install/linux/linux-postinstall/#manage-docker-as-a-non-root-user).

Parsisiunčiate ir įsidiegiate `docker-compose` iš [čia](https://github.com/docker/compose/releases).

Taip pat reikia įsidiegti [Kitematic](https://github.com/docker/kitematic/releases).
 Šis įrankis padės geriau organizuoti dokerio konteinerius. 

#### Versijų reikalavimai
* docker: `18.x-ce`
* docker-compose: `1.20.1`

### Projekto paleidimas

* Pasileidžiama infrastruktūrą per `docker`į:
```bash
scripts/start.sh
```

* Įsidiegiame PHP ir JavaScript bibliotekas:
```bash
scripts/install-prod.sh
```

* Atsinaujiname `.env` failą ir susikuriame duomenų bazę su duomenimis:
```bash
php bin/console d:d:c
php bin/console d:s:c
php bin/console d:f:l -n
```

* Pabaigus, gražiai išjungiame:
```bash
scripts/stop.sh
```

### Patogiai darbo aplinkai

* _Development_ režimas (detalesnė informacija apie klaidas, automatiškai generuojami JavaScript/CSS):
```bash
scripts/install-dev.sh
```

* Jei norite pridėti PHP biblioteką arba dirbti su Symfony karkasu per komandinę eilutę:
```bash
scripts/backend.sh
```

* Jei norite pridėti JavaScript/CSS biblioteką arba dirbti su Symfony Encore komponentu per komandine eilutę:
```bash
scripts/frontend.sh
```

* Jei norite dirbti su MySql duomenų baze:
```bash
scripts/mysql.sh
```

* Jei nesuprantate, kas vyksta su infrastruktūra, praverčia pažiūrėti į `Log`'us:
```bash
scripts/logs.sh
```

* Jei kažką stipriai sugadinote ir niekaip nepavyksta atstatyti.
  Viską pravalyti (**naudokite atsakingai**) galima su:
```bash
scripts/clean-and-start-fresh.sh
```

### Komanda
#### Mentorius
Laurynas Valenta

#### Studentai
Ignas Dailydė, Gintautas Plonis
