# GRR version ANF

Cette image utilise [NGINX Unit](https://unit.nginx.org/), qui permet de servir à la fois l'application, le contenu statique et de gérer le routage des requêtes en une seule brique légère.

Elle contient quelques ajouts et modifications afin de l'adapter à un déploiement cloud [^1]. L'ensemble de ses modifications ont été faîtes à [LA RACHE](https://www.la-rache.com/) et ne sont en aucun cas *production ready* !

[^1]: Voir [The Twelve Factors](https://12factor.net/)

## Configuration au démarrage

La configuration de la connexion à la base de donnée a lieu au démarrage via les fichiers [connect-envsubst.sh](connect-envsubst.sh) et [connect.php](connect.php).

## Ajout d'un script de migration

Un mécanisme simple simple simule les migrations de base via les fichiers [migrations.sh](migrations.sh) et [migrations.sql](migrations.sql). 

## Ajout d'un endpoint */healthz*

Une [route](configuration.json#L11-21) qui [pointe](configuration.json#L71-74) vers un [script idiot](healthz.php) vérifiant la possibilité de connexion à la base SQL jouera le rôle de *healthckeck*.

## Ajout d'un endpoint de */metrics*

Un [listener](configuration.json#L6-L7) sur le port 9090 et une [route](configuration.json#L43-54) qui [pointe](configuration.json#L86-92) vers un [script benêt](prometheus.php) permet d'exposer des métriques applicatives concernant NGINX Unit et l'application GRR (nombre d'utilisateurs, d'entrées, de sites…).
