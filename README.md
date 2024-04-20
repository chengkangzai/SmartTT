# SmartTT
Welcome to the GitHub page for SmartTT! This web-based application is crafted to help travel agencies streamline their operations and provide customers with a smooth booking experience. As the travel industry begins to bounce back from the COVID-19 pandemic disruptions, SmartTT stands out as a vital tool for boosting efficiency and enhancing service delivery.

## Introduction
The travel sector has been hit hard by the pandemic, with restrictions greatly reducing face-to-face interactions and travel. Traditional booking methods like phone calls and walk-ins have become inefficient. SmartTT revolutionizes this process by offering an online platform where everything from booking to payment can be handled with ease.

## Demo
Check out SmartTT live in action here: [Demo](https://smarttt.chengkangzai.com/).

## Goals / Features
- [x] **User Authentication** : Secure login systems for all users.
- [x] **Profile Management**: Easily manage user profiles.
- [x] **Role-Based Authorization**: Ensures users only access appropriate features.
- [x] **Tour and Trips Management**: Create and manage listings.
- [x] **Booking Management**: Efficient handling of all bookings.
- [x] **Automated Invoices and Receipts**: Automates documentation.
- [x] **Sales Reporting**: Detailed insights into your business performance.
- [x] **Manual Flight Management**: Oversee flight arrangements manually.
- [x] **Calendar Synchronization**: Currently supports Microsoft Calendar (Gmail and iCloud coming soon).
- [x] **Automated Chatbot**: Guides users in choosing tours.
- [x] **Multi-language Support**: Available in English, Chinese, and Malay. Contributions for more languages are welcome! 
- [ ] Google Flight API integration.
- [ ] Social media scheduling for announcements.
- [ ] Comprehensive API for third-party integrations.
- [ ] Development of a mobile application.


## Getting Started

### Requirement

- [Composer](https://getcomposer.org/doc/00-intro.md)
- [PHP8](https://www.php.net/downloads.php#v8.0.11)
- [Node 14](https://nodejs.org/en/download/)
- [NPM](https://www.npmjs.com/get-npm)
- [MySQL](https://www.mysql.com/products/workbench/)

### Develepment / Deployment

1. Clone the repository with git or GitHub Desktop.
2. Go into the repo

```shell
git clone https://github.com/chengkangzai/SmartTT.git
cd SmartTT
```

3. Copy the example environment file and Setting up the environment file

```shell
cp .env.example .env
```

4. Now you have to fill up the environment file by your IDE/Code Editor

5. Installing Dependencies

> [!IMPORTANT]  
> Please ensure you have installed Composer, Node and npm.

6. Installing dependencies

```shell
composer install
npm install
npm run dev
```
7. Set up database

```shell
php artisan key:generate
php artisan migrate --seed
php artisan queue:work
```

8. Server the application

```shell
php artisan server
```

visit `http://localhost:8000` to see the website

##### Running the tests

```shell
composer test
```

<details>
  <summary>Additional Enviroment File Configuration</summary>

### [Algolia](https://www.algolia.com/)

To set up Algolia, you need to register the application on Algolia and get the API key and Application ID.
Otherwise, you will not be able to use the search feature.
After you get the API key and Application ID, you can set them in the .env file as shown below.

```shell
ALGOLIA_APP_ID=xxxxx
ALGOLIA_SECRET=xxxxx
```

### [Stripe API](https://stripe.com/) (Optional, if you dont collect money online)

To set up Stripe, you need to register the application on Stripe and get the API key.
Otherwise, you will not be able to use the payment feature.
After you get the API key, you can set it in the .env file as shown below.

```shell
STRIPE_KEY=xxxxx
STRIPE_SECRET=xxxxx
STRIPE_WEBHOOK_SECRET=xxxxx
```

### [Microsoft Graph API](https://learn.microsoft.com/en-us/graph/overview) (Optinal, this will disallow customer to sync event to their calendar)

To set up Microsoft Graph to synchronize calendar, you need to register the application on Microsoft Graph and get the
API key.
Otherwise, you will not be able to use the calendar feature.
After you get the API key, you can set it in the .env file as shown below.
The feautre require you to have SSL certificate to work as the microsoft policy.

```shell
OAUTH_APP_ID=xxxx
OAUTH_APP_SECRET=xxxx
OAUTH_REDIRECT_URI=https://127.0.0.1:8000/dashboard/msOAuth/callback
OAUTH_SCOPES='openid profile offline_access user.read mailboxsettings.read calendars.readwrite'
OAUTH_AUTHORITY=https://login.microsoftonline.com/common
OAUTH_AUTHORIZE_ENDPOINT=/oauth2/v2.0/authorize
OAUTH_TOKEN_ENDPOINT=/oauth2/v2.0/token
```

### [Dialogflow](https://cloud.google.com/dialogflow) (Optional, this will power the ChatBot)

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

### SMTP (Email)
To set up Email service, you should enter your credential for the system to send email. 
Otherwise, you will not be able to use the email feature.
Your .env should look somewhat like below.
```shell
MAIL_MAILER=smtp
MAIL_HOST=<SMTP host>
MAIL_PORT=<SMTP port>
MAIL_USERNAME=<email address>
MAIL_PASSWORD=<email password>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="admin@smartTT.com"
MAIL_FROM_NAME=${APP_NAME}
```
</details>


## Changelog

Please see [CHANGELOG](https://github.com/chengkangzai/SmartTT/releases) for more information on what has changed recently.

## Contributing & Code of Conduct

Please see [CONTRIBUTING](https://github.com/chengkangzai/SmartTT/blob/master/.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](https://github.com/chengkangzai/SmartTT/blob/master/.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [All Contributors](https://github.com/chengkangzai/SmartTT/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/chengkangzai/SmartTT/blob/master/LICENSE) for more information.



