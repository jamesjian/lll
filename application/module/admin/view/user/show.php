<?php
if ($session<>NULL) {
    $str = "the session id is " . $session['session_id'].' and ' . 'expires is ' . $session['expires'];
}
echo $str;
