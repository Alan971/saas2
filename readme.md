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
