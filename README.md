Ouvrez un terminal dans le dossier du projet et lancez les containers :

```bash
docker-compose up -d
```

Créez un nouveau projet Symfony dans le dossier `app` :

```bash
docker-compose exec php composer create-project symfony/skeleton .
```