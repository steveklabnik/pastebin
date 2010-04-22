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
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
 
 
///////////////////////////////////////////////////////////////////////////////
// includes
//
require_once('pastebin/config.inc.php');
require_once('geshi/geshi.php');
require_once('pastebin/diff.class.php');
require_once('pastebin/pastebin.class.php');




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
	global $CONF;
	
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


$all=array();
$min=9999;
$max=0;

$db=new DB;
$db->_query("select * from original_phrase");
while ($db->_next_record())
{
	$o=$db->_f('original');
	$min=min(strlen($o), $min);
	$max=max(strlen($o), $max);
	
	$all[]=$o;	
}

for ($l=1; $l<$max; $l++)
{
	$keys=array();	
	$ok=true;
	
	foreach($all as $str)
	{
		$key=substr($str,0,$l);
		
		if (isset($keys[$key]))
		{
			$ok=false;
			break;
		}	
		
		$keys[$key]=1;
	}
	
	if ($ok)
	{
		echo "Best length is $l";
		
		ksort($keys);
		foreach($keys as $key=>$dummy)
		{
			echo "<li>$key</li>";
		}
		
		break;	
	}
}



		