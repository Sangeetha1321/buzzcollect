<?php

class IMap {

	var $CI;
	var $connection;
	var $current_mail;

	public function __construct($host, $username, $password) 
	{
		$this->CI =& get_instance();
		$this->connection = imap_open($host,$username,$password);
		if(!$this->connection) {
			$this->CI->template->error("Could not connect to iMap Server. 
				Output: " . imap_last_error());
		}
	}

	public function search($options = array()) 
	{
		$search_string = "";
		if(isset($options['unseen'])) {
			$search_string .= "UNSEEN ";
		}
		if(isset($options['subject'])) {
			$search_string .= 'SUBJECT "'.$options['subject'].'" ';
		}

		try {
			$results = imap_search($this->connection,$search_string);
		} catch(Exception $e) {
			$this->CI->template->error("Invalid Search: " . $e->getMessage());
		}
		return $results;
	}

	public function get_header_info($mail) 
	{
		$headerInfo = imap_headerinfo($this->connection,$mail);

		$header = new STDClass;
		$header->subject = $headerInfo->subject;
		$header->from_mail = $headerInfo->from[0]->mailbox;
		$header->from_host = $headerInfo->from[0]->host;
		$header->from = $header->from_mail . "@" . $header->from_host;
		
		return $header;
	}

	public function get_mail_body($mail) 
	{
		$structure = imap_fetchstructure($this->connection,$mail);
		if(isset($structure->parts)) {
			$content = $this->get_structure_parts($structure->parts, $mail);
		} else {

		}
		return $body;
	}

	public function get_structure_parts($parts, $mail) 
	{
		$content = array();
		foreach($parts as $part) {
			if(isset($part->ifdisposition) && $part->ifdisposition == 1) {
				if(strcmp($part->disposition, "attachment") == 0) {
					// Handle Attachment.
				}
			}
			if(isset($part->ifdisposition) && $part->ifdisposition == 0) {
				$body = imap_fetchbody($this->connection, $mail);
			}
		}
	}

	public function getmsg($mid) 
	{
	    $mbox = $this->connection;
	    // output all the following:
	    global $charset,$htmlmsg,$plainmsg,$attachments;
	    $htmlmsg = $plainmsg = $charset = '';
	    $attachments = array();

	    // BODY
	    $s = imap_fetchstructure($mbox,$mid);
	    if (!isset($s->parts) || !$s->parts)  // simple
	        $this->getpart($mbox,$mid,$s,0);  // pass 0 as part-number
	    else {  // multipart: cycle through each part
	        foreach ($s->parts as $partno0=>$p)
	            $this->getpart($mbox,$mid,$p,$partno0+1);
	    }

	    return array(
	    	"charset" => $charset,
	    	"htmlmsg" => $htmlmsg,
	    	"plainmsg" => $plainmsg,
	    	"attachments" => $attachments,
	    );
	}

	public function getpart($mbox,$mid,$p,$partno) 
	{
	    // $partno = '1', '2', '2.1', '2.1.3', etc for multipart, 0 if simple
	    global $htmlmsg,$plainmsg,$charset,$attachments;

	    $mbox = $this->connection;

	    // DECODE DATA
	    $data = ($partno)?
	        imap_fetchbody($mbox,$mid,$partno,FT_PEEK):  // multipart
	        imap_body($mbox,$mid,FT_PEEK);  // simple
	    // Any part may be encoded, even plain text messages, so check everything.
	    if ($p->encoding==4)
	        $data = quoted_printable_decode($data);
	    elseif ($p->encoding==3)
	        $data = base64_decode($data);

	    // PARAMETERS
	    // get all parameters, like charset, filenames of attachments, etc.
	    $params = array();
	    if (isset($p->parameters) && $p->parameters)
	        foreach ($p->parameters as $x)
	            $params[strtolower($x->attribute)] = $x->value;
	    if (isset($p->dparameters) && $p->dparameters)
	        foreach ($p->dparameters as $x)
	            $params[strtolower($x->attribute)] = $x->value;

	    // ATTACHMENT
	    // Any part with a filename is an attachment,
	    // so an attached text file (type 0) is not mistaken as the message.
	    /*if ( (isset($params['filename']) && $params['filename']) || (isset($params['name']) && $params['name']) ) {
	        // filename may be given as 'Filename' or 'Name' or both
	        $filename = ($params['filename'])? $params['filename'] : $params['name'];
	        // filename may be encoded, so see imap_mime_header_decode()
	        $attachments[$filename] = $data;  // this is a problem if two files have same name
	    }*/

	    // TEXT
	    if ($p->type==0 && $data) {
	        // Messages may be split in different parts because of inline attachments,
	        // so append parts together with blank row.
	        if (strtolower($p->subtype)=='plain')
	            $plainmsg .= trim($data) ."\n\n";
	        else
	            $htmlmsg .= $data ."<br><br>";
	        $charset = $params['charset'];  // assume all parts are same charset
	    }

	    // EMBEDDED MESSAGE
	    // Many bounce notifications embed the original message as type 2,
	    // but AOL uses type 1 (multipart), which is not handled here.
	    // There are no PHP functions to parse embedded messages,
	    // so this just appends the raw source to the main message.
	    elseif ($p->type==2 && $data) {
	        $plainmsg .= $data."\n\n";
	    }

	    // SUBPART RECURSION
	    if (isset($p->parts) && $p->parts) {
	        foreach ($p->parts as $partno0=>$p2)
	            $this->getpart($mbox,$mid,$p2,$partno.'.'.($partno0+1));  // 1.2, 1.2.1, etc.
	    }
	}

	public function mark_as_read($mail) 
	{
		$status = imap_setflag_full($this->connection, $mail, 
			"\\Seen");
		if(!$status) {
			$this->template->error("Could not set Flag");
		}
	}

	public function extract_gmail_message($body) 
	{
		// find position of this string: "gmail_extra"
		$strpos = strpos($body, "gmail_extra");
		if($strpos === false) {
			return $body;
		} else {
			// Cut off 
			$body = strstr($body, 'gmail_extra', true);
			return $body;
		}
		return $body;
	}

	public function extract_outlook_message($body) 
	{
		// find position of this string: "gmail_extra"
		$strpos = strpos($body, 'divRplyFwdMsg');
		if($strpos === false) {
			return $body;
		} else {
			// Cut off 
			$body = strstr($body, 'divRplyFwdMsg', true);
			return $body;
		}
		return $body;
	}

}

?>