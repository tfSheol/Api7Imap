<?php

/**
 * Différents fonctions outils
 * pour voir le temps d'exécution,
 * etc ...
 */
class Tools
{
    private $_timestart = NULL;
    
    /**
     * Début du calcul du temps d'exécution.
     */
    public function calcTime_start()
    {
        $this->_timestart = microtime(true);
    }
    
    /**
     * Fin du calcul du temps d'exécution.
     */
    public function calcTime_end()
    {
        $timeend = microtime(true);
        $time = $timeend - $this->_timestart;
        $page_load_time = number_format($time, 3);

        echo "Generated in ".$page_load_time." sec";
    }
}

?>
