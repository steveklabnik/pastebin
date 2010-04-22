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
 
require_once('pastebin/config.inc.php');
 
class OldDB
{
	var $dblink=null;
	var $dbresult;
	var $cachedir;
	
	/**
	* Constructor - establishes DB connection
	*/
	function DB()
	{
		
		$this->cachedir=$_SERVER['DOCUMENT_ROOT'].'/../cache/';
		
	}
	
	function _connect()
	{
		global $CONF;
		$this->dblink=mysql_pconnect(
			$CONF["dbhost"],
			$CONF["dbuser"],
			$CONF["dbpass"])
			or die("Unable to connect to database");
	
		mysql_select_db($CONF["dbname"], $this->dblink)
			or die("Unable to select database {$GLOBALS[dbname]}");
	}
	
	/**
	* execute query - show be regarded as private to insulate the rest of
	* the application from sql differences
	* @access private
	*/
	function query($sql)
	{
		global $CONF;
		
		if (is_null($this->dblink))
			$this->_connect();
		
		
		//been passed more parameters? do some smart replacement
		if (func_num_args() > 1)
		{
			//query contains ? placeholders, but it's possible the
			//replacement string have ? in too, so we replace them in
			//our sql with something more unique
			$q=md5(uniqid(rand(), true));
			$sql=str_replace('?', $q, $sql);
			
			$args=func_get_args();
			for ($i=1; $i<=count($args); $i++)
			{
				$sql=preg_replace("/$q/", "'".preg_quote(mysql_real_escape_string($args[$i]))."'", $sql,1);
				
			}
		
			//we shouldn't have any $q left, but it will help debugging if we change them back!
			$sql=str_replace($q, '?', $sql);
		}
		
		$this->dbresult=mysql_query($sql, $this->dblink);
		if (!$this->dbresult)
		{
			die("Query failure: ".mysql_error()."<br />$sql");
		}
		
		return $this->dbresult;
	}
	

	
	/**
	* get next record after executing _query
	* @access private
	*/
	function next_record()
	{
		$this->row=mysql_fetch_array($this->dbresult);
		return $this->row!=FALSE;
	}
	function num_rows()
	{
		return 	mysql_num_rows($this->dbresult);
	}
	
  	/**
	* get result column $field
	* @access private
	*/
	function f($field)
    {
    	return $this->row[$field];
    }
 
 	/**
	* get last insertion id
	* @access private
	*/
	function get_insert_id()
	{
		return mysql_insert_id($this->dblink);
	}
	
	/**
	* get last error
	* @access public
	*/
	function get_db_error()
	{
		return mysql_last_error();
    }
}



if (isset($_GET['pid']))
{
	$olddb=new OldDB();
	$id=intval($_GET['pid']);
	
	$olddb->query("select newid from pastebin where pid='$id'");
	if ($olddb->next_record())
	{
		$newid=$olddb->f("newid");
		if (strlen($newid))
		{
			header("Location:http://{$_SERVER['HTTP_HOST']}/$newid");
		}
		else
		{
			die("Sorry, unknown post id, probably expired.");
		}
			
	}
}

/*
this is a one-time maintenance routine to transition from 
mysql storage to file storage - uncomment if you need it

if (isset($_GET['fix']))
{
	$olddb=new OldDB();
	$olddb2=new OldDB();
	$newdb=new DB;

	set_time_limit(86400);

	$start=time();

	echo "preparing query...<br>";
	flush();
	
	$olddb->query("select pid from pastebin where newid is null order by pid");
	$count=$olddb->num_rows();
	$done=0;
	
	echo "processing...<br>";
	flush();
	
	while ($olddb->next_record())
	{
		$pid=$olddb->f("pid");
		
		$olddb2->query("select *,unix_timestamp(posted) as unix_posted from pastebin where pid=$pid");
		if ($olddb2->next_record())
		{
		
			//get the old post and add it	
			$newdb->now($olddb2->f("unix_posted"));
			$newid=$newdb->addPost(
				$olddb2->f("poster"),
				$olddb2->f("domain"),
				$olddb2->f("format"),
				$olddb2->f("code"),
				$olddb2->f("parent_pid"),
				$olddb2->f("expiry_flag"));
				
			//need to store the new id
			$olddb2->query("update pastebin set newid='$newid' where pid='$pid'");
		}
	
		//progress
		$done++;
		
		$percent=round(($done*100)/$count);
		if ($percent>$last_percent)
		{
			printf("%d%% done<br>",$percent);
			$last_percent=$percent;	
			flush();
		}
		
		
	}
	
	echo "completed<br>";
	flush();
}

*/