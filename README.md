# ECF Symfony - partie 1

Ce repo contient une application de gestion d'une bibliothèque.
Il s'agit d'un ECF pour la validation du titre pro web Dev / Web mobile

## Prérequis

- Linux, MacOS ou Windows
- Bash
- PHP 8
- Composer
- symfony-CLI
- Mariadb 10

## Installation

```
git clone https://github.com/hugo-michel/ecf
cd ecf
composer install
```

Créez une base de données et un utilisateur dédié pour cette base de données.

## Configuration

Créez un fichier `.env` à la racine du projet :

```
APP_ENV=dev
App_DEBUG=true
APP_SECRET=98aeb0581fe939d58566d1eff95851ee
DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=15&charset=utf8"
```

Pensez à changer la variable `APP_SECRET` et les codes d'accès dans la variable `DATABASE_URL`.

**ATTENTION : `APP_SECRET` doit être une chaine de caractère de 32 caractères en hexadecimal.**

## Migration et fixtures

Pour que l'application soit utilisable, vous devez créer le schéma de BDD et charger les données.

Creer le fichier dofilo.sh dans le dossier bin et le rendre executable :

```
bin/dofilo.sh
sudo chmod +x ./dofilo.sh
```

Ensuite copier/coller ces quatres lignes : 
```
php bin/console doctrine:database:drop --force --if-exists
php bin/console doctrine:database:create --no-interaction
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction --group=test
```

## Utilisation

Lancez le serveur web de developpement

```
symfony serve
```

Puis ouvrez la page suivante : [https://localhost:8000](https://localhost:8000).

