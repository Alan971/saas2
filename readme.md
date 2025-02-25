# Tutoriel de création d'une application SaaS avec Symfony et Stripe

Ce tutoriel vous guide dans la création d'une application SaaS avec Symfony, y compris la gestion de l'authentification des utilisateurs, la vérification des emails,  la génération de chemin d'accès pour chaque groue d'utilisateur et la mise en place des paiements avec Stripe.

## Installation de Symfony

1. Créez un nouveau projet Symfony :

    ```bash
    symfony new tutoSaas --webapp
    cd tutoSaas
    ```

2. Démarrez le Docker Symfony pour obtenir une base de données et un mailer :

    ```bash
    docker-compose up -d
    ```

    <u>**Important !!!**</u>
    
    Ajouter cette ligne dans le fichier .env.local:
    ```bash
    DATABASE_URL="postgresql://root:********@host.docker.internal:32778/saas2?serverVersion=16&charset=utf8"
    ```
    On ne peut accéder à la base de donnée postgreSQL depuis symfony CLI et le site web qu'avec cette ligne de code.

## Création d'un utilisateur

1. Créez un utilisateur :

    ```bash
    symfony console make:user
    ```

## Mise en place de la vérification des emails

1. Installez le package pour la vérification des emails :

    ```bash
    composer require symfonycasts/verify-email-bundle
    ```

2. Controle du fonctionnement de mailpit :
    Ce simulateur de mail est fourni par défaut avec Symfony.
    il démarre avec docker-compose et est accessible à l'adresse http://localhost:8025
    il faut ajouter dans .env.local le paramètre MAILER_DSN=smtp://mailer:1025

3. Envoi et réception de mails :
    lors de l'envoi du mail à l'enregistrement, le mail est stocké pour l'envoi mais pas envoyé immédiatement.
    avec la barre symfony on peut voir les mails qui ont été envoyés mais pas encore reçus.
    il faut donc l'envoyer manuellement.
    ```bash
    symfony console messenger:consume async
    ```

## Mise en place du formulaire d'enregistrement

1. Créez le formulaire d'enregistrement :

    ```bash
    symfony console make:registration-form
    ```

    Ce formulaire permet de vérifier l'email en envoyant un email de confirmation.

2. Pour la démonstration, utilisez l'email `test@test.com`. Vous pouvez changer cette adresse plus tard. Le nom "Bot" est utilisé pour l'envoi des emails.

## Création du formulaire de connexion

1. Créez le formulaire de connexion :

    ```bash
    symfony console make:auth
    ```

    Choisissez l'option **1. Login form Authenticator**.

    - Nom de classe : `AppAuthenticator`
    - Nom du contrôleur : `SecurityController`

    > **Note :** `make:auth` est déprécié, il est préférable d'utiliser `make:security`.

## Migration des données

1. Créez une migration pour la base de données :

    ```bash
    symfony console make:migration
    ```

2. Exécutez la migration :

    ```bash
    symfony console doctrine:migrations:migrate
    ```

## Test d'enregistrement

1. Ouvrez la page d'enregistrement :

    ```bash
    http://127.0.0.1:8000/register
    ```

2. Entrez les informations suivantes :

    - Email : `test@test.com`
    - Mot de passe : `test`

3. Après avoir validé, une erreur s'affiche car la page d'accueil n'existe pas 
encore. Toutefois, dans la debug bar de Symfony, vous verrez qu'un email est en cours d'envoi (en attente dans la queue).

4. Dans la debug bar, recherchez **sfServer** pour ouvrir la boîte mail (`mailpit` dans le container Docker) 
et exécutez la commande suivante pour consommer le message :

    ```bash
    symfony console messenger:consume -vv
    ```

5. Vous recevrez l'email de confirmation, cliquez sur le lien et vous pourrez maintenant vous connecter à l'application via :

    ```bash
    http://127.0.0.1:8000/login
    ```

## Création de la page d'accueil

1. Créez le contrôleur pour la page d'accueil :

    ```bash
    symfony console make:controller Home
    ```
## Création de la bare de navigation en bootstrap et du theme du site

1. Créez le fichier `_navbar.html.twig` :
Je ne rentre pas dans les détails mais elle est reproductible.
il suffit de copier le contenu du fichier `_navbar.html.twig` 
ainsi que les lignes d'accès aux routes bootstrap dans le fichier `base.html.twig`.

2. quelque modifications ont été apportées dans le css pour le rendu bootstrap.

## partie admin

1. description des roles
dans le fichier `security.yaml`, on édite les roles et les permissions.
pour l'instant on a deux roles :

- ROLE_USER : utilisateur
- ROLE_ADMIN : administrateur
- ROLE_SUPER_ADMIN : administrateur super

2. création du contrôleur AdminAccount pour la gestion des comptes

    ```bash
    symfony console make:controller AdminAccount
    ```
