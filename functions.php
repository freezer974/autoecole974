<?php

/**
 * Function to create and display error and success messages
 * @access public
 * @param string session name
 * @param string message
 * @param string display class
 * @return string message
 */
function flash( $name = '', $message = '', $class = 'success fadeout-message' )
{
    //We can only do something if the name isn't empty
    if( !empty( $name ) )
    {
        //No message, create it
        if( !empty( $message ) && empty( $_SESSION[$name]))
        {
            if( !empty( $_SESSION[$name] ) )
            {
                unset( $_SESSION[$name] );
            }
            if( !empty( $_SESSION[$name.'_class'] ) )
            {
                unset( $_SESSION[$name.'_class'] );
            }

            $_SESSION[$name] = $message;
            $_SESSION[$name.'_class'] = $class;
        }
        //Message exists, display it
        elseif( !empty( $_SESSION[$name] ) && empty( $message ))
        {
            $class = !empty( $_SESSION[$name.'_class'] ) ? $_SESSION[$name.'_class'] : 'success';
            echo '<div class="alert alert-'.$class.' alert-dismissible fade show" id="msg-flash" role="alert">'
                    .$_SESSION[$name]
                    .'<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                 </div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name.'_class']);
        }
    }
}

function ChaineAvecMajuscule($titre){
    return ucfirst(strtolower($titre));
}

function delete($bdd, $table, $entity, $id){
    $delete = 'DELETE FROM ' . $table . ' WHERE ' . $entity . ' = :id';
    $req = $bdd->prepare($delete);
    $req->bindValue(':id', $id, PDO::PARAM_INT);
    $req->execute();
    $req->closeCursor();

    return true;
}

function countPagePourTable($count, $nbElementParPage){
    return ceil($count / $nbElementParPage);
}

function connexion_role($role){

    if (is_array($role)):
        foreach($role AS $r):
            if (ChaineAvecMajuscule($r) == $_SESSION['role']):
                return true;
            endif;
        endforeach;
    endif;
    if (is_string($role)):
        if (ChaineAvecMajuscule($role) == $_SESSION['role']):
            return true;
        endif;
    endif;
    

    flash('message', 'Vous n\'êtes pas autorisé à faire ça', 'danger' );
    header('Location: /index.php');
}

function redirection_page($page = null){

    if (empty($page)):
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
    else:
        $referer = $page;
    endif;
    header('Location: '  . $referer);
    Exit();
}

?>