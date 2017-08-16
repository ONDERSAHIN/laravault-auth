<?php namespace CodyBuell\LaravaultAuth\Models;

use Illuminate\Contracts\Auth\Authenticatable;

class LaravaultUser implements Authenticatable {

  //////////////////////////////////////////////////////////////////////////////
  //                                                                          //
  // Preparation                                                              //
  //                                                                          //
  //////////////////////////////////////////////////////////////////////////////

  /**
   * Class Initialization
   */
  public function __construct() {
    //
  }

  //////////////////////////////////////////////////////////////////////////////
  //                                                                          //
  // Auth Contract Fulfillment                                                //
  //                                                                          //
  //////////////////////////////////////////////////////////////////////////////

  /**
   * Get Auth Identifier Name
   *
   * Return the name of unique identifier for the user (e.g. "id").
   *
   * @return string
   */
  public function getAuthIdentifierName() {

    return 'username';

  }

  /**
   * Get Auth Identifier
   *
   * Return the unique identifier for the user (e.g. their ID, 123).
   *
   * @return mixed
   */
  public function getAuthIdentifier() {

    return $this->username;

  }

  /**
   * Get Auth Password
   *
   * Returns the (hashed) password for the user.
   *
   * @return string
   */
  public function getAuthPassword() {
    //
  }

  /**
   * Get Remember Token
   *
   * Return the token used for the "remember me" functionality.
   *
   * @return string
   */
  public function getRememberToken() {
    //
  }

  /**
   * Set Remember Token
   *
   * Store a new token user for the "remember me" functionality, called on logout.
   *
   * @param  string  $value
   * @return void
   */
  public function setRememberToken($value) {
    //
  }

  /**
   * Get Remember Token
   *
   * Return the name of the column / attribute used to store the "remember me" token.
   *
   * @return string
   */
  public function getRememberTokenName() {
    //
  }

  //////////////////////////////////////////////////////////////////////////////
  //                                                                          //
  // Dynamic Getters and Setters                                              //
  //                                                                          //
  //////////////////////////////////////////////////////////////////////////////

  /**
   * Dynamically access the user's attributes.
   *
   * @param  string  $key
   * @return mixed
   */
  public function __get($key) {
    return session('vaultuser_'.$key);
  }

  /**
   * Dynamically set an attribute on the user.
   *
   * @param  string  $key
   * @param  mixed  $value
   * @return void
   */
  public function __set($key, $value) {
    $key = 'vaultuser_'.$key;
    session([$key => $value]);
  }

  /**
   * Dynamically check if a value is set on the user.
   *
   * @param  string  $key
   * @return bool
   */
  public function __isset($key) {
    return session()->has('vaultuser_'.$key);
  }

  /**
   * Dynamically unset a value on the user.
   *
   * @param  string  $key
   * @return void
   */
  public function __unset($key) {
    session()->forget('vaultuser_'.$key);
  }

}
