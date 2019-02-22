<?php
   //Ensure that a session exists (just in case)
    if( !session_id() )
    {
        session_start();
    }
    require_once('functions.php');

    if (empty($_SESSION['role'])):
        $_SESSION['role'] = '';
    endif;
    
    /*if (empty($_SESSION) && (!empty($_COOKIE))):
        $_SESSION['auto'] = 'on';
        $_SESSION['action'] = 'connexion';
        $_SESSION['email'] = $_COOKIE['email'];
        $_SESSION['password'] = $_COOKIE['password'];
        redirection_page(); 
    endif;*/
?>