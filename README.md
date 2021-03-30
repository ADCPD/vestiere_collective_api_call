# To summarize

> I did'nt understand if the data is already provided or i will have to be provided by the api call, so I created mine and I formatted the structure of the call
> 
> I used the postman tool to do the api call. I have a bug to inject an array of parameters as a prameter for the action. I ended up bypassing the problem and I went through injecting the parameters into url. I know that is not the best solution to do
>
> I added an external API call to retrieve the real value of the currencies
> 
> I configured stripe api to test the payments
> 
> I created a historical entity to save the transactions on the items. I save all the action to pay or impeyed
> I would have preferred to use event dispatcher to do this. just, I passed the test time
> 
> I have left you attached my docker config & the dump of database

> You found backup data base into [migration file](./migrations/stripe_db.sql)

> If you get a probleme to generate the project after build docker : drop the file [data](.docker/data) into ` .docker folder`
> 

#SYMFONY STRIPE INTEGRATION

This repository is a small tutorial which explains how to integrate stripe using Symfony 5 project
and how to accept payments with Stripe

##### RUN PROJECT DOCKER

```
docker-compose build
docker-compose up    // voir les logs
docker-compose up -d // lancer les container sans les logs
```

If the containers are already created:

```
make start

> set your admin password
```

##### RUN DOCKER PHP CONTAINER 

```
make php_container

> set your admin password
> composer install  // to generate vendors
```

##### CREATE DATABASE IF NOT EXIST 

```
php app/console doctrine:database:create --if-not-exists
php app/console doctrine:schema:update --force
php app/console doctrine:fixtures:load
```

##### INSTALL STRIPE composant 

STRIPE DASHBOARD DEBUG :  https://dashboard.stripe.com/test/logs

```
composer require stripe/stripe-php
```

STRIPE DOC : https://stripe.com/docs/payments/accept-a-payment?integration=elements
Expose root : 

```PHP
    /**
     * @Route(
     *  "/create-checkout-session", 
     *  name="stripe-create-checkout-session", 
     *  options={"expose"=true},
     *  methods={"GET","POST"} 
     *  )
     */
    public function create(
        StripeService $stripeService
        ): Response
    {
        #your code here
    }
```
Next you have to generate js Route

```
bin/console fos:js-routing:dump --format=json --target=public/bundles/js/fos_js_routes.json
```

MAPPING : 

CHECKOUT PROCESS into  [StripeController](./src/Controller/StripeController.php) and [StripeService](./src/Services/StripeService.php)

CHECKOUT JS PROCESS into [checkout.html.twig](./templates/default/checkout.html.twig)

Generate migration :
```
php bin/console doctrine:migrations:migrate
```
Generate Fixture :
```
php bin/console doctrine:fixtures:load
```