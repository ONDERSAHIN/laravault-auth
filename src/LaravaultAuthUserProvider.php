<?php namespace CodyBuell\LaravaultAuth;

use Unirest\Request as Unirequest;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use CodyBuell\LaravaultAuth\Models\LaravaultUser as User;

class LaravaultAuthUserProvider implements UserProvider {

  /**
   * Retrieve by ID
   *
   * Called after a successful authentication of a user. Get and return a user
   * by their unique identifier.
   *
   * @param  mixed  $identifier
   * @return \Illuminate\Contracts\Auth\Authenticatable|null
   */
  public function retrieveById($identifier) {

    // everything is stored in and accessed via session, so a new user instance
    // will return the logged in users data... crappy? likely...
    $user = new User();
    return $user;

  }

  /**
   * Retrieve by Token
   *
   * Get and return a user by their unique identifier and "remember me" token.
   *
   * @param  mixed   $identifier
   * @param  string  $token
   * @return \Illuminate\Contracts\Auth\Authenticatable|null
   */
  public function retrieveByToken($identifier, $token) {
    //
  }

  /**
   * Update Remember Token
   *
   * Save the given "remember me" token for the given user called on logout
   * after setRememberToken on Authenticatable...
   *
   * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
   * @param  string  $token
   * @return void
   */
  public function updateRememberToken(Authenticatable $user, $token) {
    //
  }

  /**
   * Retrive by Credentials
   *
   * Retrieve a user by the given credentials. The method called when
   * initially authenticating a user via the login page. Get and return a
   * user object that implements Authenticatabe by looking up the given
   * credentials (username)...
   *
   * @param  array  $credentials ['username' => 'un', 'password' => 'pw']
   * @return \Illuminate\Contracts\Auth\Authenticatable|null
   */
  public function retrieveByCredentials(array $credentials) {

    $user = new User();
    $user->username = $credentials['username'];

    return $user;

  }

  /**
   * Validate Credentials
   *
   * Validate a user against the given credentials. Called after
   * retrieveByCredentials and recieves the Authenticatable $user object
   * returned by it. Check that given credentials belong to the given user.
   *
   * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
   * @param  array  $credentials
   * @return bool
   */
  public function validateCredentials(Authenticatable $user, array $credentials) {

    // define some vars for our call to vault
    $endpoint = config('laravault-auth.url').':'.config('laravault-auth.port').'/v1/auth/ldap/login/'.$user->username;
    $headers  = [
      'Accept'       => 'application/json',
      'Content-Type' => 'application/json',
    ];
    $payload  = [
      'password' => $credentials['password'],
    ];

    // build the json payload
    $body     = Unirequest\Body::json($payload);

    // contact vault, authenticate user
    $response = Unirequest::post($endpoint, $headers, $body);

    // if successful set attributes and return true
    if ($response->code == 200) {

      // determin the lease expiration, take 30 seconds off from whatever vault provides as the ttl
      $now    = new \DateTime();
      $length = $response->body->auth->lease_duration - 30;
      $expir  = $now->modify('+'.$length.' seconds');

      // set session variables
      $user->policies         = $response->body->auth->policies;
      $user->client_token     = $response->body->auth->client_token;
      $user->accessor         = $response->body->auth->accessor;
      $user->request_id       = $response->body->request_id;
      $user->lease_expiration = $expir;

      return true;

    // else return false
    } else {

      // clear out anything that was set
      session()->flush();

      return false;
    }

  }

}
