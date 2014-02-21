<?php

/**
 * Charge toutes les classes de
 * tous les fichiers présents
 * dans le dossier "includes".
 * Il est important de mettre
 * les noms des classes en
 * majuscule, dépend simplement
 * de la version de votre php.
 * 
 * @param string $classe
 */
function loadclasse($classe)
{
    require 'classes/classe.'.$classe.'.php';
}

spl_autoload_register('loadclasse');

?>
