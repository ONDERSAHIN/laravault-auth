<?php namespace CodyBuell\LaravaultAuth\Middleware;

use Auth;
use Closure;

class LogoutOnSessionTimeout {
  public function handle($request, Closure $next) {

    // we need session data so process the request first
    $response = $next($request);

    // now check if the vault session has expired
    $now    = new \DateTime;
    $ttlend = session('vaultuser_lease_expiration');

    // if vault session expired, logout and flush session
    if (Auth::check() && $now > $ttlend) {
      session()->flush();
      Auth::logout();
    }

    return $response;

  }
}
