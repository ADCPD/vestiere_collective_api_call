> phpmyadmin: http://localhost:8080/index.php
  HOST: http://localhost:88 
  GUIDE : https://gist.github.com/subfuzion/08c5d85437d5d4f00e58
>
Preparer les containers 
`` docker-compose build ``

Lancer les containers
`` docker-compose up -d ``

To run the console command inside the Docker execute the following command in your terminal:
`` docker-compose run php composer install ``

The above command will be executed on php container where we have the access to the PHP-FPM service and the Composer. To run any other command:
`` c bin/console doctrine:schema:update --force ``

Drop  all intalled container
`` docker-compose stop $(docker-compose ps -a -q) ``

Si probleme de connexion  avec la base de données dans .ENV: 
------------

Remplacer : 
`` DATABASE_URL=mysql://sf4:sf4@127.0.0.1:3306/db_my_project ``
par 
`` DATABASE_URL=mysql://sf4:sf4@mysql:3306/db_sf4 `` 

To manipulate the repo where the project is created : 
`` docker-compose exec php bash ``

Purging All Unused or Dangling Images, Containers, Volumes, and Networks
`` docker system prune ``

Remove one or more specific images
`` docker images -a ``

Remove one or more specific containers
`` docker ps -a ``

Resolve conflict master 
``
git checkout master
git merge --no-ff hotfix1
git commit -m "resolved conflict and master merged"
git push origin master
``
Return real path 
```
pwd
```

Set 0777 acces controle to var/cache repository
```
docker-compose exec php bash // access to container php
cd var
chmod -R 0777 .
```
[voir plus](https://stackoverflow.com/questions/16955980/git-merge-master-into-feature-branch)

# Run project 
- Build container
`` sudo docker-compose build ``
-  Display the running processes
`` sudo docker-compose build ``
- start docker with -watch event
`` sudo docker-compose top ``
or Start project 
`` sudo docker-compose start``

#### SQL 

Creating a new database using mysql program
```
docker-compose exec mysql bash
mysql -u root -p
CREATE DATABASE db_citymed  
```

##### JWT PRIVATE & PUBLIC KEY 

``
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
``

  php bin/console fos:user:promote admin ROLE_ADMIN
  php bin/console fos:user:change-password admin admin2020!