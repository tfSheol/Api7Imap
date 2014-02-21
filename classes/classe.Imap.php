<?php

/**
 * Api Imap pour gmail.
 * 
 * @author Teddy.fontaine@epitech.eu
 * @version 1.0
 * 
 * todo :
 * - Rajouter des fonctions pour les pices jointes.
 * - Corriger le problème d'encodage de certains e-mails.
 */
class Imap
{
        /**
         * @var object boite mail
         */
	private $_mail = null;
        
        /**
         * @var string répertoire de sauvegarde
         */
        private $_saveDirPath = null;
	
        /**
         * Connexion à l'adresse e-mail.
         * 
         * @param string $user le nom d'utilisateur
         * @param string $password le mot de passe
         */
	public function __construct($saveDirPath, $user, $password)
	{
            $this->_mail = imap_open('{imap.gmail.com:993/imap/ssl}INBOX',
                            $user, $password);
            
            $this->_saveDirPath = $savedirpath = substr($saveDirPath, -1) == "/" 
                    ? $saveDirPath : $saveDirPath."/";
	}
        
        /**
         * Ferme la boite mail ouverte
         */
	public function closeMailBox()
	{
            imap_close($this->_mail);
	}
	
        /**
         * Retourne tous les dossiers de l'adresse
         * mail.
         * Sous la forme :
         * {imap.gmail.com:993}INBOX
         * ... etc.
         * 
         * @return string
         */
	public function getMailBoxDirectory()
	{
            return imap_list($this->_mail, "{imap.gmail.com:993/imap/ssl}",
                            "*");
	}
	
        /**
         * Retourne le dossier passé en paramètre.
         * 
         * @param string $folder dossier
         * @return string
         */
	public function getCleanFolder($folder)
	{
            return str_replace('{imap.gmail.com:993/imap/ssl}', '', $folder);
	}
        
        /**
         * Retourne la date dernière
         * modification du contenu de
         * la boîte aux lettres
         * (date et heure courant).
         * 
         * @return string
         */
        public function getLastDateModification()
        {
            $nbmsg_date = imap_mailboxmsginfo($this->_mail);
            return intval($nbmsg_date->Date);
        }
        
        /**
         * Retourne le pilote.
         * 
         * @return string
         */
        public function getDriver()
        {
            $nbmsg_driver = imap_mailboxmsginfo($this->_mail);
            return intval($nbmsg_driver->Driver);
        }
        
        /**
         * Retourne le nom de la boite mail.
         * 
         * @return string
         */
        public function getNameMailbox()
        {
            $nbmsg_mailbox = imap_mailboxmsginfo($this->_mail);
            return intval($nbmsg_mailbox->Mailbox);
        }
	
        /**
         * Retourne le nombre de messages
         * en général.
         * 
         * @return int
         */
	public function getNumMessages()
	{
            return intval(imap_num_msg($this->_mail));
	}
	
        /**
         * Retourne le nombre de messages
         * non lus.
         * 
         * @return int
         */
	public function getNumUnreadMessages()
	{
            $nbmsg_unread = imap_mailboxmsginfo($this->_mail);
            return intval($nbmsg_unread->Unread);
	}
	
        /**
         * Retourne le nombre de nouveaux
         * messages.
         * 
         * @return int
         */
	public function getNumNewMessages()
	{
            $nbmsg_news = imap_mailboxmsginfo($this->_mail);
            return intval($nbmsg_news->Recent);
	}
	
        /**
         * Retourne le nombre de messages
         * supprimés.
         * 
         * @return int
         */
        public function getNumDeletedMessages()
        {
           $nbmsg_deleted = imap_mailboxmsginfo($this->_mail);
           return intval($nbmsg_deleted->Deleted);
        }
        
        /**
         * Retourne la tailel de la boîte
         * mails.
         * 
         * @return int
         */
        public function getNumSize()
        {
            $nbmsg_size = imap_mailboxmsginfo($this->_mail);
            return intval($nbmsg_size->Size);
        }
        
        /**
         * Retourne l'header de tous
         * les messages dans un tableau
         * 
         * $...[0]->subject /
         * $...[0]->date /
         * $...[0]->message_id /
         * $...[0]->toaddress /
         * $...[0]->to[0]->mailbox /
         * $...[0]->to[0]->host /
         * $...[0]->fromaddress /
         * $...[0]->from[0]->personal /
         * $...[0]->from[0]->mailbox /
         * $...[0]->from[0]->host /
         * $...[0]->reply_to[0]->personal /
         * $...[0]->reply_to[0]->mailbox /
         * $...[0]->reply_to[0]->host /
         * $...[0]->senderaddress /
         * $...[0]->sender[0]->personal /
         * $...[0]->sender[0]->mailbox /
         * $...[0]->sender[0]->host /
         * $...[0]->Recent /
         * $...[0]->Unseen /
         * $...[0]->Flagged /
         * $...[0]->Answered /
         * $...[0]->Deleted /
         * $...[0]->Draft /
         * $...[0]->Msgno /
         * $...[0]->MailDate /
         * $...[0]->Size /
         * $...[0]->udate
         * 
         * @return object
         */
	public function getAllMessages()
	{
            $mailsId = imap_search($this->_mail, 'ALL');
            return $this->_getMessages($mailsId);
	}
	
        /**
         * Retourne l'header dans un tableau
         * des messages qui n'ont pas de réponse.
         * 
         * $...[0]->subject /
         * $...[0]->date /
         * $...[0]->message_id /
         * $...[0]->toaddress /
         * $...[0]->to[0]->mailbox /
         * $...[0]->to[0]->host /
         * $...[0]->fromaddress /
         * $...[0]->from[0]->personal /
         * $...[0]->from[0]->mailbox /
         * $...[0]->from[0]->host /
         * $...[0]->reply_to[0]->personal /
         * $...[0]->reply_to[0]->mailbox /
         * $...[0]->reply_to[0]->host /
         * $...[0]->senderaddress /
         * $...[0]->sender[0]->personal /
         * $...[0]->sender[0]->mailbox /
         * $...[0]->sender[0]->host /
         * $...[0]->Recent /
         * $...[0]->Unseen /
         * $...[0]->Flagged /
         * $...[0]->Answered /
         * $...[0]->Deleted /
         * $...[0]->Draft /
         * $...[0]->Msgno /
         * $...[0]->MailDate /
         * $...[0]->Size /
         * $...[0]->udate
         * 
         * @return object
         */
	public function getAnsweredMessages()
	{
            $mailsId = imap_search($this->_mail, 'ANSWERED');
            return $this->_getMessages($mailsId);
	}
	
        /**
         * Retourne l'header dans un tableau
         * des messages qui ont un BCC (cci)
         * spécifié en paramètre.
         * 
         * $...[0]->subject /
         * $...[0]->date /
         * $...[0]->message_id /
         * $...[0]->toaddress /
         * $...[0]->to[0]->mailbox /
         * $...[0]->to[0]->host /
         * $...[0]->fromaddress /
         * $...[0]->from[0]->personal /
         * $...[0]->from[0]->mailbox /
         * $...[0]->from[0]->host /
         * $...[0]->reply_to[0]->personal /
         * $...[0]->reply_to[0]->mailbox /
         * $...[0]->reply_to[0]->host /
         * $...[0]->senderaddress /
         * $...[0]->sender[0]->personal /
         * $...[0]->sender[0]->mailbox /
         * $...[0]->sender[0]->host /
         * $...[0]->Recent /
         * $...[0]->Unseen /
         * $...[0]->Flagged /
         * $...[0]->Answered /
         * $...[0]->Deleted /
         * $...[0]->Draft /
         * $...[0]->Msgno /
         * $...[0]->MailDate /
         * $...[0]->Size /
         * $...[0]->udate
         * 
         * @param string $bcc BCC
         * @return object
         */
	public function getMessagesWithBcc($bcc)
	{
            $mailsId = imap_search($this->_mail, 'BCC "' . $bcc . '"');
            return $this->_getMessages($mailsId);
	}
	
        /**
         * Retourne l'header dans un tableau
         * des messages qui sont dans la boite
         * mails avant $date.
         * Date spécifié en paramètre.
         * 
         * $...[0]->subject /
         * $...[0]->date /
         * $...[0]->message_id /
         * $...[0]->toaddress /
         * $...[0]->to[0]->mailbox /
         * $...[0]->to[0]->host /
         * $...[0]->fromaddress /
         * $...[0]->from[0]->personal /
         * $...[0]->from[0]->mailbox /
         * $...[0]->from[0]->host /
         * $...[0]->reply_to[0]->personal /
         * $...[0]->reply_to[0]->mailbox /
         * $...[0]->reply_to[0]->host /
         * $...[0]->senderaddress /
         * $...[0]->sender[0]->personal /
         * $...[0]->sender[0]->mailbox /
         * $...[0]->sender[0]->host /
         * $...[0]->Recent /
         * $...[0]->Unseen /
         * $...[0]->Flagged /
         * $...[0]->Answered /
         * $...[0]->Deleted /
         * $...[0]->Draft /
         * $...[0]->Msgno /
         * $...[0]->MailDate /
         * $...[0]->Size /
         * $...[0]->udate
         * 
         * @param string $date Date
         * @return object
         */
	public function getMessagesBefore($date)
	{
            $mailsId = imap_search($this->_mail, 'BEFORE "'.$date.'"');
            return $this->_getMessages($mailsId);
	}
	
        /**
         * Retourne l'header dans un tableau
         * des e-mails qui ont un corps
         * spécifié en paramètre.
         * 
         * $...[0]->subject /
         * $...[0]->date /
         * $...[0]->message_id /
         * $...[0]->toaddress /
         * $...[0]->to[0]->mailbox /
         * $...[0]->to[0]->host /
         * $...[0]->fromaddress /
         * $...[0]->from[0]->personal /
         * $...[0]->from[0]->mailbox /
         * $...[0]->from[0]->host /
         * $...[0]->reply_to[0]->personal /
         * $...[0]->reply_to[0]->mailbox /
         * $...[0]->reply_to[0]->host /
         * $...[0]->senderaddress /
         * $...[0]->sender[0]->personal /
         * $...[0]->sender[0]->mailbox /
         * $...[0]->sender[0]->host /
         * $...[0]->Recent /
         * $...[0]->Unseen /
         * $...[0]->Flagged /
         * $...[0]->Answered /
         * $...[0]->Deleted /
         * $...[0]->Draft /
         * $...[0]->Msgno /
         * $...[0]->MailDate /
         * $...[0]->Size /
         * $...[0]->udate
         * 
         * @param string $body Corps
         * @return object
         */
	public function getMessagesWithBody($body)
	{
            $mailsId = imap_search($this->_mail, 'BODY "'.$body.'"');
            return $this->_getMessages($mailsId);
	}
	
        /**
         * Retourne l'header dans un tableau
         * des messages qui ont un CC
         * spécifié en paramètre.
         * 
         * $...[0]->subject /
         * $...[0]->date /
         * $...[0]->message_id /
         * $...[0]->toaddress /
         * $...[0]->to[0]->mailbox /
         * $...[0]->to[0]->host /
         * $...[0]->fromaddress /
         * $...[0]->from[0]->personal /
         * $...[0]->from[0]->mailbox /
         * $...[0]->from[0]->host /
         * $...[0]->reply_to[0]->personal /
         * $...[0]->reply_to[0]->mailbox /
         * $...[0]->reply_to[0]->host /
         * $...[0]->senderaddress /
         * $...[0]->sender[0]->personal /
         * $...[0]->sender[0]->mailbox /
         * $...[0]->sender[0]->host /
         * $...[0]->Recent /
         * $...[0]->Unseen /
         * $...[0]->Flagged /
         * $...[0]->Answered /
         * $...[0]->Deleted /
         * $...[0]->Draft /
         * $...[0]->Msgno /
         * $...[0]->MailDate /
         * $...[0]->Size /
         * $...[0]->udate
         * 
         * @param string $cc CC
         * @return object
         */
	public function getMessagesWithCc($cc)
	{
            $mailsId = imap_search($this->_mail, 'CC "'.$cc.'"');
            return $this->_getMessages($mailsId);
	}
	
        /**
         * Retourne l'header dans un tableau
         * des messages supprimés.
         * 
         * $...[0]->subject /
         * $...[0]->date /
         * $...[0]->message_id /
         * $...[0]->toaddress /
         * $...[0]->to[0]->mailbox /
         * $...[0]->to[0]->host /
         * $...[0]->fromaddress /
         * $...[0]->from[0]->personal /
         * $...[0]->from[0]->mailbox /
         * $...[0]->from[0]->host /
         * $...[0]->reply_to[0]->personal /
         * $...[0]->reply_to[0]->mailbox /
         * $...[0]->reply_to[0]->host /
         * $...[0]->senderaddress /
         * $...[0]->sender[0]->personal /
         * $...[0]->sender[0]->mailbox /
         * $...[0]->sender[0]->host /
         * $...[0]->Recent /
         * $...[0]->Unseen /
         * $...[0]->Flagged /
         * $...[0]->Answered /
         * $...[0]->Deleted /
         * $...[0]->Draft /
         * $...[0]->Msgno /
         * $...[0]->MailDate /
         * $...[0]->Size /
         * $...[0]->udate
         * 
         * @return object
         */
	public function getDeletedMessages()
	{
            $mailsId = imap_search($this->_mail, 'DELETED');
            return $this->_getMessages($mailsId);
	}
	
        /**
         * Retourne l'header dans un tableau
         * des messages avec un destinataire
         * spécifié en paramètre.
         * 
         * $...[0]->subject /
         * $...[0]->date /
         * $...[0]->message_id /
         * $...[0]->toaddress /
         * $...[0]->to[0]->mailbox /
         * $...[0]->to[0]->host /
         * $...[0]->fromaddress /
         * $...[0]->from[0]->personal /
         * $...[0]->from[0]->mailbox /
         * $...[0]->from[0]->host /
         * $...[0]->reply_to[0]->personal /
         * $...[0]->reply_to[0]->mailbox /
         * $...[0]->reply_to[0]->host /
         * $...[0]->senderaddress /
         * $...[0]->sender[0]->personal /
         * $...[0]->sender[0]->mailbox /
         * $...[0]->sender[0]->host /
         * $...[0]->Recent /
         * $...[0]->Unseen /
         * $...[0]->Flagged /
         * $...[0]->Answered /
         * $...[0]->Deleted /
         * $...[0]->Draft /
         * $...[0]->Msgno /
         * $...[0]->MailDate /
         * $...[0]->Size /
         * $...[0]->udate
         * 
         * @param string $from From
         * @return object
         */
	public function getMessagesFrom($from)
	{
            $mailsId = imap_search($this->_mail, 'FROM "'.$from.'"');
            return $this->_getMessages($mailsId);
	}
	
        /**
         * Retourne l'header dans un tableau
         * des nouveaux messages.
         * 
         * $...[0]->subject /
         * $...[0]->date /
         * $...[0]->message_id /
         * $...[0]->toaddress /
         * $...[0]->to[0]->mailbox /
         * $...[0]->to[0]->host /
         * $...[0]->fromaddress /
         * $...[0]->from[0]->personal /
         * $...[0]->from[0]->mailbox /
         * $...[0]->from[0]->host /
         * $...[0]->reply_to[0]->personal /
         * $...[0]->reply_to[0]->mailbox /
         * $...[0]->reply_to[0]->host /
         * $...[0]->senderaddress /
         * $...[0]->sender[0]->personal /
         * $...[0]->sender[0]->mailbox /
         * $...[0]->sender[0]->host /
         * $...[0]->Recent /
         * $...[0]->Unseen /
         * $...[0]->Flagged /
         * $...[0]->Answered /
         * $...[0]->Deleted /
         * $...[0]->Draft /
         * $...[0]->Msgno /
         * $...[0]->MailDate /
         * $...[0]->Size /
         * $...[0]->udate
         * 
         * @return object
         */
	public function getNewMessages()
	{
            $mailsId = imap_search($this->_mail, 'NEW');
            return $this->_getMessages($mailsId);
	}
	
        /**
         * Retourne l'header dans un tableau
         * des messages reçu à une date
         * spécifiée en paramètre.
         * 
         * $...[0]->subject /
         * $...[0]->date /
         * $...[0]->message_id /
         * $...[0]->toaddress /
         * $...[0]->to[0]->mailbox /
         * $...[0]->to[0]->host /
         * $...[0]->fromaddress /
         * $...[0]->from[0]->personal /
         * $...[0]->from[0]->mailbox /
         * $...[0]->from[0]->host /
         * $...[0]->reply_to[0]->personal /
         * $...[0]->reply_to[0]->mailbox /
         * $...[0]->reply_to[0]->host /
         * $...[0]->senderaddress /
         * $...[0]->sender[0]->personal /
         * $...[0]->sender[0]->mailbox /
         * $...[0]->sender[0]->host /
         * $...[0]->Recent /
         * $...[0]->Unseen /
         * $...[0]->Flagged /
         * $...[0]->Answered /
         * $...[0]->Deleted /
         * $...[0]->Draft /
         * $...[0]->Msgno /
         * $...[0]->MailDate /
         * $...[0]->Size /
         * $...[0]->udate
         * 
         * @param string $date Date
         * @return object
         */
	public function getMessagesOnDate($date)
	{
            $mailsId = imap_search($this->_mail, 'ON "'.$date.'"');
            return $this->_getMessages($mailsId);
	}
	
        /**
         * Retourne l'header dans un tableau
         * des messages avec un "sujet"
         * spécifié en paramètre.
         * 
         * $...[0]->subject /
         * $...[0]->date /
         * $...[0]->message_id /
         * $...[0]->toaddress /
         * $...[0]->to[0]->mailbox /
         * $...[0]->to[0]->host /
         * $...[0]->fromaddress /
         * $...[0]->from[0]->personal /
         * $...[0]->from[0]->mailbox /
         * $...[0]->from[0]->host /
         * $...[0]->reply_to[0]->personal /
         * $...[0]->reply_to[0]->mailbox /
         * $...[0]->reply_to[0]->host /
         * $...[0]->senderaddress /
         * $...[0]->sender[0]->personal /
         * $...[0]->sender[0]->mailbox /
         * $...[0]->sender[0]->host /
         * $...[0]->Recent /
         * $...[0]->Unseen /
         * $...[0]->Flagged /
         * $...[0]->Answered /
         * $...[0]->Deleted /
         * $...[0]->Draft /
         * $...[0]->Msgno /
         * $...[0]->MailDate /
         * $...[0]->Size /
         * $...[0]->udate
         * 
         * @param string $subject Sujet
         * @return object
         */
	public function getMessagesWithSubject($subject)
	{
            $mailsId = imap_search($this->_mail, 'SUBJECT "'.$subject.'"');
            return $this->_getMessages($mailsId);
	}
	
        /**
         * Retourne l'header dans un tableau
         * des messages contenant le destinataire
         * spécifié en paramètre.
         * 
         * $...[0]->subject /
         * $...[0]->date /
         * $...[0]->message_id /
         * $...[0]->toaddress /
         * $...[0]->to[0]->mailbox /
         * $...[0]->to[0]->host /
         * $...[0]->fromaddress /
         * $...[0]->from[0]->personal /
         * $...[0]->from[0]->mailbox /
         * $...[0]->from[0]->host /
         * $...[0]->reply_to[0]->personal /
         * $...[0]->reply_to[0]->mailbox /
         * $...[0]->reply_to[0]->host /
         * $...[0]->senderaddress /
         * $...[0]->sender[0]->personal /
         * $...[0]->sender[0]->mailbox /
         * $...[0]->sender[0]->host /
         * $...[0]->Recent /
         * $...[0]->Unseen /
         * $...[0]->Flagged /
         * $...[0]->Answered /
         * $...[0]->Deleted /
         * $...[0]->Draft /
         * $...[0]->Msgno /
         * $...[0]->MailDate /
         * $...[0]->Size /
         * $...[0]->udate
         * 
         * @param string $to Destinataire
         * @return object
         */
	public function getMessagesTo($to)
	{
            $mailsId = imap_search($this->_mail, 'TO "'.$to.'"');
            return $this->_getMessages($mailsId);
	}
	
        /**
         * Retourne l'header dans un tableau
         * des messages qui ont étaient
         * envoyés.
         * 
         * $...[0]->subject /
         * $...[0]->date /
         * $...[0]->message_id /
         * $...[0]->toaddress /
         * $...[0]->to[0]->mailbox /
         * $...[0]->to[0]->host /
         * $...[0]->fromaddress /
         * $...[0]->from[0]->personal /
         * $...[0]->from[0]->mailbox /
         * $...[0]->from[0]->host /
         * $...[0]->reply_to[0]->personal /
         * $...[0]->reply_to[0]->mailbox /
         * $...[0]->reply_to[0]->host /
         * $...[0]->senderaddress /
         * $...[0]->sender[0]->personal /
         * $...[0]->sender[0]->mailbox /
         * $...[0]->sender[0]->host /
         * $...[0]->Recent /
         * $...[0]->Unseen /
         * $...[0]->Flagged /
         * $...[0]->Answered /
         * $...[0]->Deleted /
         * $...[0]->Draft /
         * $...[0]->Msgno /
         * $...[0]->MailDate /
         * $...[0]->Size /
         * $...[0]->udate
         * 
         * @return object
         */
	public function getSeenMessages()
	{
            $mailsId = imap_search($this->_mail, 'SEEN');
            return $this->_getMessages($mailsId);
	}
	
        /**
         * Retourne l'header dans un tableau
         * des messages qui n'ont pas eu de
         * réponse.
         * 
         * $...[0]->subject /
         * $...[0]->date /
         * $...[0]->message_id /
         * $...[0]->toaddress /
         * $...[0]->to[0]->mailbox /
         * $...[0]->to[0]->host /
         * $...[0]->fromaddress /
         * $...[0]->from[0]->personal /
         * $...[0]->from[0]->mailbox /
         * $...[0]->from[0]->host /
         * $...[0]->reply_to[0]->personal /
         * $...[0]->reply_to[0]->mailbox /
         * $...[0]->reply_to[0]->host /
         * $...[0]->senderaddress /
         * $...[0]->sender[0]->personal /
         * $...[0]->sender[0]->mailbox /
         * $...[0]->sender[0]->host /
         * $...[0]->Recent /
         * $...[0]->Unseen /
         * $...[0]->Flagged /
         * $...[0]->Answered /
         * $...[0]->Deleted /
         * $...[0]->Draft /
         * $...[0]->Msgno /
         * $...[0]->MailDate /
         * $...[0]->Size /
         * $...[0]->udate
         * 
         * @return object
         */
	public function getUnansweredMessages()
	{
            $mailsId = imap_search($this->_mail, 'UNANSWERED');
            return $this->_getMessages($mailsId);
	}
	
        /**
         * Retourne l'header dans un tableau
         * des messages reçus à la date 
         * spécifiée.
         * 
         * $...[0]->subject /
         * $...[0]->date /
         * $...[0]->message_id /
         * $...[0]->toaddress /
         * $...[0]->to[0]->mailbox /
         * $...[0]->to[0]->host /
         * $...[0]->fromaddress /
         * $...[0]->from[0]->personal /
         * $...[0]->from[0]->mailbox /
         * $...[0]->from[0]->host /
         * $...[0]->reply_to[0]->personal /
         * $...[0]->reply_to[0]->mailbox /
         * $...[0]->reply_to[0]->host /
         * $...[0]->senderaddress /
         * $...[0]->sender[0]->personal /
         * $...[0]->sender[0]->mailbox /
         * $...[0]->sender[0]->host /
         * $...[0]->Recent /
         * $...[0]->Unseen /
         * $...[0]->Flagged /
         * $...[0]->Answered /
         * $...[0]->Deleted /
         * $...[0]->Draft /
         * $...[0]->Msgno /
         * $...[0]->MailDate /
         * $...[0]->Size /
         * $...[0]->udate
         * 
         * @param string $date Date
         * @return object
         */
	public function getMessagesSince($date)
	{
            $mailsId = imap_search($this->_mail, 'SINCE "'.$date.'"');
            return $this->_getMessages($mailsId);
	}
	
        /**
         * Retourne l'header dans un tableau
         * des messages non lus.
         * 
         * $...[0]->subject /
         * $...[0]->date /
         * $...[0]->message_id /
         * $...[0]->toaddress /
         * $...[0]->to[0]->mailbox /
         * $...[0]->to[0]->host /
         * $...[0]->fromaddress /
         * $...[0]->from[0]->personal /
         * $...[0]->from[0]->mailbox /
         * $...[0]->from[0]->host /
         * $...[0]->reply_to[0]->personal /
         * $...[0]->reply_to[0]->mailbox /
         * $...[0]->reply_to[0]->host /
         * $...[0]->senderaddress /
         * $...[0]->sender[0]->personal /
         * $...[0]->sender[0]->mailbox /
         * $...[0]->sender[0]->host /
         * $...[0]->Recent /
         * $...[0]->Unseen /
         * $...[0]->Flagged /
         * $...[0]->Answered /
         * $...[0]->Deleted /
         * $...[0]->Draft /
         * $...[0]->Msgno /
         * $...[0]->MailDate /
         * $...[0]->Size /
         * $...[0]->udate
         * 
         * @return object
         */
	public function getUnreadMessages()
	{
            $mailsId = imap_search($this->_mail, 'UNSEEN');
            return $this->_getMessages($mailsId);
	}
	
        /**
         * Retourne le corps du message
         * avec toutes les informations
         * en brute.
         * 
         * @param int $id UID
         * @return string
         */
	public function getBodyMessage($id)
	{
            return imap_body($this->_mail, intval($id));
	}
        
        /**
         * Retourne le message contenu
         * dans le corps.
         * 
         * @param int $id UID
         * @return string
         */
        public function getSimpleBodyMessage($id)
        {
            $mails = imap_header($this->_mail, $id);
            $elements = imap_mime_header_decode($mails->subject);
            
            if ($elements[0]->charset == 'default')
            {
                $part = 1;
            }
            else
            {
                $part = 5;
            }

            return imap_fetchbody($this->_mail, intval($id), $part,
                   FT_INTERNAL);
        }
        
        /**
         * Met un flag lus sur le
         * message dont l'id est placé
         * en paramètre.
         * 
         * @param int $id uid du message
         */
        public function setSeenMessage($id)
        {
            imap_setflag_full($this->_mail, intval($id), '\\Seen');
        }
        
        /**
         * Décode et retourne le sujet ou
         * le charset du message.
         * 
         * @param string $string text
         * @param int $part text ou charset
         * @return string
         */
        public function getHeaderDecode($string, $part)
        {
            $elements = imap_mime_header_decode($string);
            
            if ($part == 'text') {
            return ($elements[0]->text);
            }
            else if ($part == 'charset')
            {
                return $elements[0]->charset;
            }
            else
            {
                return "Erreur!";
            }
        }
        
        /**
         * Retourne le message décodé
         * et supprime le flag //seen.
         * 
         * @param int $id UID
         * @param string $message Message de getSimpleBodyMessage
         * @return string
         */
        public function getBodyFullDecode($id, $message)
        {
            $part = imap_fetchstructure($this->_mail, intval($id));
            
            switch ($part->encoding)
            {
                case 0: //text
                    $message = quoted_printable_decode($message);
                    break;
                case 1: //multipart
                    $message = imap_8bit($message);
                    break;
                case 2: //message
                    $message = imap_binary($message);
                    break;
                case 3: //application
                    $message = quoted_printable_decode($message);
                    break;
                case 4: //audio
                    $message = quoted_printable_decode($message);
                    break;
                case 5: //image
                    $message = quoted_printable_decode($message);
                    break;
                case 6: //video
                    $message = quoted_printable_decode($message);
                    break;
                case 7: //other
                    $message = imap_base64($message);
                    break;
            }
            
            return imap_utf8($message);                
        }
	
        /**
         * Retourne les infos voulues
         * sous forme d'object.
         * Retourne 0 si $mailsId == false.
         * 
         * @param type $mailsId UID
         * @return object
         */
	private function _getMessages($mailsId)
        {
            $i = 0;
            if ($mailsId !== false) {
            foreach ($mailsId as $mailId) {
                $results[$i] = imap_header($this->_mail, $mailId);
                $i++;
            }
            return ($results);
        }
        else
        {
            return 0;
        }
    }
}