Composer install dans le dossier app

```bash
composer install
```

Configurer les variables d'environnement :

```bash
cp .env.example .env
```

Modifiez le fichier `.env` pour configurer vos paramètres de base de données.

Ouvrez un terminal dans le dossier du projet et lancez les containers :

```bash
docker-compose up -d
```