# twitter-mais-heller
[![Build Status](https://travis-ci.org/tentacode-classroom/twitter-mais-heller.svg?branch=master)](https://travis-ci.org/tentacode-classroom/twitter-mais-heller)


## Installation

Ce projet nécessite PHP 7.2, Composer et MariaDB

### Cloner le projet
Placez vous dans le repertoire parent puis executez :
```bash
clone git@github.com:tentacode-classroom/twitter-mais-heller.git
```

### Installer composer et ses dépendances :
```bash
composer i
```

### Executer la commande d'installation dans votre projet
```bash
php bin/console app:install
```
Note : Le programme timeout lorsque le serveur est lancé mais le projet est fonctionnel 

#### Pour pouvoir utiliser l'envoi de mail, il faut ajouter la ligne suivante à la fin du fichier **.env**

`MAILER_URL=gmail://example@gmail.com:password@localhost`

Il vous faudra simplement remplacer l'adresse gmail par la votre ainsi que le mot de passe que vous utilisez pour vous connecter à votre boîte mail.

<!> ATTENTION <!> 
Pour que Google accepte d'envoyer les mails, il faut vous rendre dans les paramètres Google de votre compte > Aller dans "Connexion et Sécurité" > "Applications ayant accès au compte" > Activer le paramètre "Autoriser les applications moins sécurisées"
<!> sans cette étape l'envoi de mail ne fonctionnera pas <!>