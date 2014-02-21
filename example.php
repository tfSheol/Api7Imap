<?php

/**
 * Test d'utilisation de l'api.
 * 
 * @author teddy.fontaine@epitech.eu
 */

require_once ('./includes/functions.php');

$time = new Tools();
$time->calcTime_start();
$imap = new Imap('./', '<email@gmail.com>', '<mot_de_passe>');

$mails = $imap->getUnreadMessages();

foreach ($mails as $data)
{
    $from = $data->from[0]->mailbox.'@'.$data->from[0]->host;
    $subject = $imap->getHeaderDecode($data->subject, 'text');
    $message = $imap->getBodyFullDecode($data->Msgno,
               $imap->getSimpleBodyMessage($data->Msgno));
    
    echo $from.'<br />';
    echo $subject.'<br />';
    echo $message.'<br />';
    
    $imap->setSeenMessage($data->Msgno);
}

$imap->closeMailBox();
$time->calcTime_end();