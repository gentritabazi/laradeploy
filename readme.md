### Introduction

**Laradeploy** offers you to automate deployment using a GitHub webhook.

Make a **git push** to GitHub deploy the new modifications to a remote server.

You can configure which branch this package pulls from.

This package is useful for both development and production servers.

### How it works
GitHub sends a POST request to a specific URL on the server.

That URL triggers the execution of a deployment shell script.

### Installation & Configuration

First, install package via composer:

```bash
composer require gentritabazi01/laradeploy
```

Copy config **laradeploy.php** file:

Run ``php artisan vendor:publish --provider="GentritAbazi\Laradeploy\Providers\LaradeployServiceProvider"`` to publish the **laradeploy.php** config file.

Configure **laradeploy.php** as needed.

Create the shell script at **scripts/deploy.sh**:

```bash
#!/bin/bash

php artisan down
git fetch -av
git reset --hard origin/master

composer install --no-interaction --no-dev --prefer-dist
php artisan route:cache
php artisan config:cache
php artisan event:cache
php artisan view:cache
php artisan migrate --force
php artisan up
```

Create a GitHub webhook:

On GitHub, on your repository page, select the **Settings** tab, then **Webhook**s in the left navigation.

Click **Add webhook**:

**Payload URL**: http://<your-server.com>/deploy

**Secret**: A long random string (Same secret you set to **config/laradeploy.php**).