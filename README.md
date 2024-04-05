# Symfony_JungleBloom
# Description:

PlantEcom est une application web développée avec le framework Symfony. Il s'agit d'une boutique en ligne spécialisée dans la vente de plantes et de produits associés. L'application permet aux utilisateurs de parcourir une sélection de plantes, de les ajouter à leur panier et de passer des commandes en toute sécurité. Les administrateurs peuvent gérer les produits, les utilisateurs et les commandes via une interface d'administration sécurisée.

## Screenshots :
![alt text](<docs/Capture d'écran 2024-01-04 142916.png>) ![alt text](<docs/Capture d'écran 2024-04-05 124948.png>)
![alt text](<docs/Capture d'écran 2024-04-05 122436.png>)
![alt text](<docs/Capture d'écran 2024-04-05 122519.png>)
![alt text](<docs/Capture d'écran 2024-04-05 124704.png>)
![alt text](<docs/Capture d'écran 2024-04-05 122608.png>)
![alt text](<docs/Capture d'écran 2024-04-05 122642.png>)
![alt text](<docs/Capture d'écran 2024-04-05 124756.png>)
![alt text](<docs/Capture d'écran 2024-04-05 124810.png>)
![alt text](<docs/Capture d'écran 2024-04-05 124835.png>)


# Fonctionnalités

    - Gestion des produits : Ajouter, modifier et supprimer des produits avec des informations détaillées telles que le nom, la description, le prix, les images, etc.
    - Gestion des utilisateurs : Inscription, connexion et gestion des profils utilisateurs avec des fonctionnalités d'authentification et d'autorisation sécurisées.
    - Interface d'administration : Interface sécurisée pour les administrateurs permettant la gestion complète des produits, des utilisateurs et des commandes.
    - TODO (!!) : Gestion des commandes : Passage de commandes, suivi des statuts de commande et gestion des détails de livraison.

# Prérequis

    PHP >= 7.4
    Composer
    Symfony CLI
    MySQL

# Installation

    Clonez ce dépôt sur votre machine locale.
    Exécutez la commande composer install pour installer les dépendances.
    Copiez le fichier .env.example en .env et configurez votre base de données MySQL.
    Exécutez les migrations avec la commande php bin/console doctrine:migrations:migrate.
    (Optionnel) Chargez les fixtures pour des données initiales avec la commande php bin/console doctrine:fixtures:load.
    Démarrez le serveur Symfony avec la commande symfony server:start.

# Utilisation

    Accédez à l'application dans votre navigateur à l'adresse http://localhost:8000.
    Connectez-vous à l'interface d'administration avec les identifiants par défaut (admin@admin.com / password) pour accéder aux fonctionnalités d'administration.
    Parcourez les produits, ajoutez-les à votre panier et passez des commandes.
    Explorez les fonctionnalités d'administration pour gérer les produits, les utilisateurs et les commandes.


## Auteur

    Sylvain Cdr

## Licence

Ce projet est sous licence MIT.
