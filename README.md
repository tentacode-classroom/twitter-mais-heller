# twitter-mais-heller

# Configuration BDD

##### Pour pouvoir lancer l'application il faut configurer sa base de données dans le fichier **.env** à la racine du projet 

La ligne suivante : 
`DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name`
* db_user : l'identifiant de connexion à votre BDD
* db_password : le mot de passe qui correspond à l'utilisateur
* db_name : le nom de la base qui contiendra les tables du projet

#### Pour pouvoir utiliser l'envoi de mail, il faut ajouter la ligne suivante à la fin du fichier **.env**

`MAILER_URL=gmail://example@gmail.com:password@localhost`

Il vous faudra simplement remplacer l'adresse gmail par la votre ainsi que le mot de passe que vous utilisez pour vous connecter à votre boîte mail.

<!> ATTENTION <!> 
Pour que Google accepte d'envoyer les mails, il faut vous rendre dans les paramètres Google de votre compte > Aller dans "Connexion et Sécurité" > "Applications ayant accès au compte" > Activer le paramètre "Autoriser les applications moins sécurisées"
<!> sans cette étape l'envoi de mail ne fonctionnera pas <!>