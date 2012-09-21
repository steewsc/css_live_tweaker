<?php
  session_start();
  // Sledeca linija proverava da li je korsnik vec ulogovan ili ne,
  // tj. da li je TOKEN snimljen u $_SESSION
  if(isset($_SESSION['token'])){
    echo 'Korisnik je vec ulogovan, a token je: ' . $_SESSION['token'];
  } else {
    // ovde treba da prikaze login stranu ili da uradi redirect na nju.
    // login strana treba isto kao i ova da za prvu liniju ima session_start();
    //	pa posle sve ostalo.
    // I kada se korisnik uloguje, tj. kad ti API vrati njegov TOKEN uradis sledece:
    $_SESSION['token'] = "dhfjdshfjhkdshkjhfdhskjfdsj";
    //"dhfjdshfjhkdshkjhfdhskjfdsj" je token, koji je API vratio.
  }
?>
