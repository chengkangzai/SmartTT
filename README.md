# SmartTT

SmartTT is a simple, fast, and powerful tool for managing your travel agencies and bring it to online business with
ease!

## Getting Started

### Requirement

- [Composer](https://getcomposer.org/doc/00-intro.md)
- [PHP8](https://www.php.net/downloads.php#v8.0.11)
- [Node 14](https://nodejs.org/en/download/)
- [NPM](https://www.npmjs.com/get-npm)
- [MySQL](https://www.mysql.com/products/workbench/)

#### Setting up

Go into the repo

```shell
cd SmartTT
```

Copy and Setting up the environment file

```shell
cp .env.example .env
```

Now you have to fill up the environment file by your IDE/Code Editor

#### Installing Dependencies

Assume you have installed Composer and Node and NPM

Installing Composer dependencies

```shell
 composer install
```

Installing NPM dependencies and build it

```shell
npm install
npm run dev
```

#### Setting up laravel

Generate Application Key

```shell
php artisan key:generate
```

Migrate the database and seed the data

```shell
php artisan migrate --seed
```

#### FINALLY

Server the application

```shell
php artisan server
```

visit `http://localhost:8000` to see the website

##### Running the tests

```shell
composer test
```

### Algolia

To set up Algolia, you need to register the application on Algolia and get the API key and Application ID.
Otherwise, you will not be able to use the search feature.
After you get the API key and Application ID, you can set them in the .env file as shown below.

```shell
ALGOLIA_APP_ID=xxxxx
ALGOLIA_SECRET=xxxxx
```

### Stripe API

To set up Stripe, you need to register the application on Stripe and get the API key.
Otherwise, you will not be able to use the payment feature.
After you get the API key, you can set it in the .env file as shown below.

```shell
STRIPE_KEY=xxxxx
STRIPE_SECRET=xxxxx
STRIPE_WEBHOOK_SECRET=xxxxx
```

### Microsoft Graph API

To set up Microsoft Graph to synchronize calendar, you need to register the application on Microsoft Graph and get the
API key.
Otherwise, you will not be able to use the calendar feature.
After you get the API key, you can set it in the .env file as shown below.

```shell
OAUTH_APP_ID=xxxx
OAUTH_APP_SECRET=xxxx
OAUTH_REDIRECT_URI=xxxx
OAUTH_SCOPES='openid profile offline_access user.read mailboxsettings.read calendars.readwrite'
OAUTH_AUTHORITY=https://login.microsoftonline.com/common
OAUTH_AUTHORIZE_ENDPOINT=/oauth2/v2.0/authorize
OAUTH_TOKEN_ENDPOINT=/oauth2/v2.0/token
```

### Dialogflow

To set up Dialogflow, you need to register the application on Dialogflow and get the service account json file from
Google cloud Platform.
Otherwise, you will not be able to use the chatbot feature.
After you get the API key, you can set it in the .env file as shown below.

```shell
GOOGLE_CLOUD_PROJECT=<project-name>
GOOGLE_APPLICATION_CREDENTIALS=<full-path-to-service-account-json-file>
```

### Amazon S3

To set up Amazon S3, you need to register the application on Amazon S3 and get the API key.
Otherwise, you will not be able to use the storage feature.
After you get the API key, you can set it in the .env file as shown below.

```shell
AWS_ACCESS_KEY_ID=xxxxx
AWS_SECRET_ACCESS_KEY=xxxxx
AWS_DEFAULT_REGION=xxxxx
AWS_BUCKET=xxxxx
```
