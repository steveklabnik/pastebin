<?php
/**
 * $Project: Pastebin $
 * $Id: pastebin.php,v 1.3 2006/04/27 16:21:10 paul Exp $
 * 
 * Pastebin Collaboration Tool
 * http://pastebin.com/
 *
 * This file copyright (C) 2006 Paul Dixon (paul@elphin.com)
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the Affero General Public License 
 * Version 1 or any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * Affero General Public License for more details.
 * 
 * You should have received a copy of the Affero General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.s
 */
 
 
///////////////////////////////////////////////////////////////////////////////
// includes
//
require_once('pastebin/config.inc.php');
require_once('geshi/geshi.php');
require_once('pastebin/diff.class.php');
require_once('pastebin/pastebin.class.php');

/**
* This array contains information needed to support a particular character set
* the http entry is the name of the encoding used in HTTP headers and the
* htmlentities entries is the value which must be passed to htmlentities() to
* ensure characters are correctly encoded
*/
$charset_code=array(
	'latin1'=>array('http'=>'iso-8859-1', 'htmlentities'=>'ISO-8859-1'),
	'1251'=>array('http'=>'windows-1251', 'htmlentities'=>'cp1251')
);

/**
* Which character set to use?
*/
$charset='latin1';

/**
* configure character set
*/
$CONF['htmlentity_encoding']=$charset_code[$charset]['htmlentities'];
$CONF['http_charset']=$charset_code[$charset]['http'];

set_time_limit(180);

if (isset($_GET['maintain']))
{
	$CONF["maintainer_mode"]=1;
}

//////////////////////////////////////////////
// translation support
/*
create table original_phrase
(
	original_phrase_id int not null auto_increment,
	original text not null,
	used datetime not null,
	
	primary key(original_phrase_id),
	unique (original(128))
	
);

create table translated_phrase
(
	original_phrase_id int not null,
	lang char(2) not null,
	updated datetime not null,
	
	translated text,
	
	primary key(original_phrase_id,lang)
);

*/

function t($str)
{
	//global $CONF;
	
	//if in maintainance mode, record this string in the translation db
	if ($CONF['maintainer_mode'])
	{
		$db=new DB;
		
		$db->_query("select * from original_phrase where original=?", $str);
		if ($db->_next_record())
		{
			//update timestamp	
			$original_phrase_id=$db->_f('original_phrase_id');
			$db->_query("update original_phrase set used=now() where original_phrase_id=$original_phrase_id");
		}
		else
		{
			//create new record	
			$db->_query("insert into original_phrase(original,used) values (?, now())", $str);
		}
	}
	
	//if using english ui, just return the string
	
	//if a translation is available, use that
	
	//otherwise, use english
	
	return $str;	
	
}

//html helpers using above translation system

function h1($str)
{
	echo '<h1>'.t($str).'</h1>';	
}
function p($str)
{
	echo '<p>'.t($str).'</p>';	
}
function li($str)
{
	echo '<li>'.t($str).'</li>';	
}

///////////////////////////////////////////////////////////////////////////////
// magic quotes are anything but magic - lose them!
//
if (get_magic_quotes_gpc())
{
	function callback_stripslashes(&$val, $name) 
	{
		if (get_magic_quotes_gpc()) 
			$val=stripslashes($val);
	}


	if (count($_GET))
		array_walk ($_GET, 'callback_stripslashes');
	if (count($_POST))
		array_walk ($_POST, 'callback_stripslashes');
	if (count($_COOKIE))
		array_walk ($_COOKIE, 'callback_stripslashes');
}

///////////////////////////////////////////////////////////////////////////////
// user submitted the "private pastebin" form? redirect them...
//
if ($_GET['goprivate'])
{
	$sub=trim(strtolower($_GET['goprivate']));
	if (preg_match('/^[a-z0-9][a-z0-9\.\-]*[a-z0-9]$/i', $sub))
	{
		header("Location: http://{$sub}.pastebin.com");
		exit;
	}
}

///////////////////////////////////////////////////////////////////////////////
// create our pastebin object
//
$pastebin=new Pastebin($CONF);





///////////////////////////////////////////////////////////////////////////////
// process new posting
//
$errors=array();
if (isset($_POST['paste']))
{
	//process posting and redirect
	$id=$pastebin->doPost($_POST);
	if ($id)
	{
		$pastebin->redirectToPost($id);
		exit;
	}

}


///////////////////////////////////////////////////////////////////////////////
// process download
//
if (isset($_GET['dl'])) 
{
	$pid=$pastebin->cleanPostId($_GET['dl']);
	
	if (!$pastebin->doDownload($pid))
	{
		//not fount
		echo "Pastebin entry $pid is not available";
	}
	exit;
}

	

///////////////////////////////////////////////////////////////////////////////
// if we get this far, we're going to be displaying some HTML, so let's kick
// off here...
$page=array();

//figure out some nice defaults
$page['current_format']=$CONF['default_highlighter'];
$page['expiry']=$CONF['default_expiry'];
$page['remember']='';	

//see if we can come up with a better default using the subdomain
if (strlen($CONF['subdomain']) && isset($CONF['all_syntax'][$CONF['subdomain']]))
{
	//cool, domain is something like ruby.pastebin.com, so lets go with that
	//as a default
	$page['current_format']=$CONF['subdomain'];
}

//are we remembering the user?
$cookie=$pastebin->extractCookie();
if ($cookie)
{
	//initialise bits of page with cookie data
	$page['remember']='checked="checked"';
	$page['current_format']=$cookie['last_format'];
	$page['poster']=$cookie['poster'];
	$page['expiry']=$cookie['last_expiry'];
	$page['token']=$cookie['token'];
}




//send feedback mail?
if (isset($_POST['feedback']) && strlen($_POST['msg']))
{
	$matches=array();
	$spam=false;
	
	//more than two links?
	preg_match_all('{http://}', $_POST['msg'], $matches);
	$spam=$spam || count($matches[0])>2;
	
	//[url=][/url] ?
	$spam=$spam || preg_match('{\[url=}i', $_POST['msg']);
	$spam=$spam || preg_match('{<a href=}i', $_POST['msg']);
	
	
	if (!$spam)
	{
		@mail($CONF['feedback_to'], "[pastebin] Feedback", $_POST['msg'], "From: {$CONF['feedback_sender']}");
		$page['thankyou']=t('Thanks for your feedback, if you included an email address in your message, we\'ll get back to you asap.');
	}
	else
	{
		$page['thankyou']=t('Sorry, that looked a bit too much like spam - go easy on the links there.');
	}
}

///////////////////////////////////////////////////////////////////////////////
// erase a post
//
if (isset($_REQUEST['erase']))
{
	$pid=$pastebin->cleanPostId($_REQUEST['erase']);
	$post=$pastebin->getPost($pid);
	if (!empty($post['token']) && !empty($cookie['token']) && $post['token']==$cookie['token'])
	{
		$pastebin->deletePost($pid);
		$page['delete_message']=t('Your post has been deleted');
	}
	else
	{
		$page['delete_message']=t('You cannot delete this post - contact us if you need further assistance');
		$_REQUEST["show"]=$pid;
	}
}

//add list of recent posts
$list=isset($_REQUEST["list"]) ? intval($_REQUEST["list"]) : 10;
$page['recent']=$pastebin->getRecentPosts($list);


///////////////////////////////////////////////////////////////////////////////
// show a post
//
if (isset($_REQUEST["show"]))
{
	$pid=$pastebin->cleanPostId($_REQUEST['show']);
	
	//get the post
	$page['post']=$pastebin->getPost($pid);
	
	
	
	//ensure corrent format is selected
	$page['current_format']=$page['post']['format'];
}
else
{
	 $page['posttitle']='New Posting';
}

//use configured title
$page['title']=	$CONF['title'];

//on a subdomain, label it as private...
if (strlen($CONF['subdomain']))
{
	$page['title']=$CONF['subdomain']. ' private pastebin - collaborative debugging tool';
}
elseif ($page['current_format']!='text')
{
	//give the page a title which features the syntax used..
	$page['title']=$CONF['all_syntax'][$page['current_format']] . " ".$page['title'];
}

header("Content-Type: text/html; charset=".$CONF['http_charset']);

///////////////////////////////////////////////////////////////////////////////
// HTML page output
//
include("layout.php");

// clean up older posts 
$pastebin->doGarbageCollection();

DB::dumpDiagnostics();


  


