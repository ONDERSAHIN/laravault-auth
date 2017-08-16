<?php namespace CodyBuell\LaravaultAuth;

use Auth;
use Illuminate\Support\ServiceProvider;
use CodyBuell\LaravaultAuth\LaravaultAuthUserProvider;

class LaravaultAuthServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {

      // register 'vault' auth provider
      Auth::provider('vault', function($app, array $config) {
        return new LaravaultAuthUserProvider();
      });

      // publish configuration
      $this->publishes([
        __DIR__.'/../config/laravault-auth.php' => config_path('laravault-auth.php'),
      ]);

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
      //
    }

}
