Laravault-Auth
==============

Laravel package for using Hashicorp's Vault as an Authentication Provider.  Authenticates users against an instance of Vault, stores user information in the session, and handles closing of the laravel session when the vault ttl expires.

Installation
------------

1. Install the LaravaultAuth package manually or with composer:

   - Manually

     ```
     git clone https://github.com/codybuell/laravault-auth.git vendor/codybuell/laravault-auth

     # edit composer.json, under autoload -> psr-4 append:

     "CodyBuell\\LaravaultAuth\\": "vendor/codybuell/laravault-auth/src"
     ```

  - Composer

        composer require codybuell/laravault-auth

2. Add provider to `config/app.php` providers array:

        CodyBuell\LaravaultAuth\LaravaultAuthServiceProvider::class,

3. Publish config and configure `config/laravault-auth.php`:

        php artisan vendor:publish

4. Set 'vault' as your auth provider in `config/auth.php`:

        'providers' => [
            'users' => [
                'driver' => 'vault',
            ],
        ]

5. Set auth to use username rather than email. In `app/Http/Controllers/Auth/LoginController.php` add:

        /**
         * Use usernames instead of emails.
         */
        public function username() {
          return 'username';
        }

Usage
-----

### Accessing Vault Client Token

### Accessing User Attributes

Todo
----

- write middleware that checks session vaultuser_lease_expiration and logs out if current time is past
- write tests
