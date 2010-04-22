<?php
/**
 * $Project: Pastebin $
 * $Id: layout.php,v 1.1 2006/04/27 16:22:39 paul Exp $
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
 
echo "<?xml version=\"1.0\" encoding=\"".$charset_code[$charset]['http']."\"?>\n";


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<!--
pastebin.com Copyright 2006 Paul Dixon - email suggestions to lordelph at gmail.com
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo $page['title'] ?></title>
<meta name="ROBOTS" content="NOARCHIVE"/>
<link rel="stylesheet" type="text/css" media="screen" href="/pastebin.css?ver=4" />

<?php if (isset($page['post']['codecss']))
{
	echo '<style type="text/css">';
	echo $page['post']['codecss'];
	echo '</style>';
}
?>
<script type="text/javascript" src="/pastebin.js?ver=3"></script>
</head>


<body onload="initPastebin()">
<div style="display:none;">
<h1 style="display: none;">pastebin - collaborative debugging</h1>
<p style="display: none;">pastebin is a collaborative debugging tool allowing you to share
and modify code snippets while chatting on IRC, IM or a message board.</p>
<p style="display: none;">This site is developed to XHTML and CSS2 W3C standards.  
If you see this paragraph, your browser does not support those standards and you 
need to upgrade.  Visit <a href="http://www.webstandards.org/upgrade/" target="_blank">WaSP</a>
for a variety of options.</p>
</div>

<div id="titlebar"><?php 
	echo $page['title'];
	if ($subdomain=='')
	{
		echo " <a href=\"{$CONF['this_script']}?help=1\">View Help</a>";
	}
	else
	{
		echo " <a href=\"{$CONF['this_script']}?help=1\">What's a private pastebin?</a>";
	}
?>
</div>



<div id="menu">

<?php echo '<h1>'.t('Recent Posts').'</h1>'?>

<ul>
<?php  
	foreach($page['recent'] as $idx=>$entry)
	{
		if ($entry['pid']==$pid)
			$cls=" class=\"highlight\"";
		else
			$cls="";
			
		echo "<li{$cls}><a href=\"{$entry['url']}\">";
		echo $entry['poster'];
		echo "</a><br/>{$entry['agefmt']}</li>\n";
	}

	echo "<li><a rel=\"nofollow\" href=\"{$CONF['this_script']}\">".t('Make new post').'</a></li>';
?>
</ul>
<h1>At last!</h1>
<p>You should find pastebin <b>much</b>
	faster! Enjoy...</p>

<!--
<h1>Translations?</h1>

<p>Anyone interested in
a <a href="http://blog.dixo.net/2006/05/10/translate-pastebin/">translated
version of pastebin?</a>. If you'd like to volunteer as a translator or
just want to give some feedback on the idea, please 
<a href="http://blog.dixo.net/2006/05/10/translate-pastebin/">leave a comment</a>
</p>
-->

<?php
if ($subdomain=='')
{
	echo '<h1>'.t('Free subdomains').'</h1><p>';
	
	echo t('Want your own xyz.pastebin.com sub-domain for your community? '.
		'Just type the address into your browser address bar.');
	
	echo " <a href=\"{$CONF['this_script']}?help=1\">".t('See help for details').
		'</a></p>';
}

echo '<h1>'.t('About').'</h1><p>';

echo t('Pastebin is a tool for collaborative debugging or editing,');
echo " <a href=\"{$CONF['this_script']}?help=1\">".t('See help for details').
		'</a>. ';	
		
echo t('For latest news see my <a title="View pastebin related posts on my blog" href="http://blog.dixo.net/category/pastebin/">blog</a>.');

echo '</p>';


echo '<p>'.t('Please send feedback below...').'</p>';

if (isset($page['thankyou']))
{
	echo "<p style=\"color:red;\">{$page['thankyou']}</p>";
}
else
{
?>
<a name="feedback"></a>
<form method="post" action="<?php echo $CONF['this_script'].'#feedback' ?>">
<textarea name="msg" rows="5" style="width:90%;margin-left:5%;font-size:8pt;font-family:Arial;"></textarea>
<input type="hidden" name="feedback" value="1"/>
<input style="width:90%;margin-left:5%" type="submit" value="<?php echo t('send feedback') ?>"/>
</form><br/>
<?php } ?>

<!--

<form method="get" action="http://www.php.net/search.php">
<h1>PHP Manual</h1>
<input type="text" size="9" name="pattern"/>
<input type="hidden" name="show" value="quickref"/>
<input type="submit" value="go"/>
</form>

<form method="get" action="http://www.mysql.com/search/?">
<h1>MySQL Manual</h1>
<input type="hidden" name="base" value="http://dev.mysql.com"/>
<input type="hidden" name="lang" value="en"/>
<input type="hidden" name="doc" value="1"/>
<input type="hidden" name="m" value="o"/>
<input type="text" size="9" name="q"/>
<input type="submit" value="go"/>
</form>
-->

<?php

echo '<h1>'.t('Credits').'</h1><p>';
	
	echo t('Software developed by ');
	echo '<a title="My blog, mainly about software development" href="http://blog.dixo.net">Paul Dixon</a></p>';

?>

<!--  
<p>
    <a href="http://validator.w3.org/check/referer"><img
        src="http://www.w3.org/Icons/valid-xhtml10"
        alt="Valid XHTML 1.0!" height="31" width="88" border="0"/></a>
</p>
-->

</div>


<div id="content">

<?php
/*
 * Google AdWords block is below - if you re-use this script, be sure
 * to configure your own AdWords client id!
 */
if (strlen($CONF['google_ad_client'])) 
{
?>
<script type="text/javascript"><!--
google_ad_client = "<?php echo $CONF['google_ad_client'] ?>";
google_ad_width = 728;
google_ad_height = 90;
google_ad_format = "728x90_as";
google_ad_type = "text_image";
google_ad_channel ="";
google_color_border = "D9D0C3";
google_color_bg = "D9D0C3";
google_color_link = "474C7F";
google_color_url = "888888";
google_color_text = "000000";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br/>
<br/>
<?php
}

///////////////////////////////////////////////////////////////////////////////
// show processing errors
//
if (count($pastebin->errors))
{
	echo '<h1>'.t('Errors').'</h1><ul>';
	foreach($pastebin->errors as $err)
	{
		echo "<li>$err</li>";
	}
	echo "</ul>";
	echo "<hr />";
}

if (!empty($page['delete_message']))
{
	echo "<h1>{$page['delete_message']}</h1><br/>";
}

if (isset($_REQUEST["diff"]))
{
	
	$newpid=$pastebin->cleanPostId($_REQUEST['diff']);
	
	$newpost=$pastebin->getPost($newpid);
	if (count($newpost))
	{
		$oldpost=$pastebin->getPost($newpost['parent_pid']);	
		if (count($oldpost))
		{
			$page['pid']=$newpid;
			$page['current_format']=$newpost['format'];
			$page['editcode']=$newpost['code'];
			$page['posttitle']='';
	
			//echo "<div style=\"text-align:center;border:1px red solid;padding:5px;margin-bottom:5px;\">Diff feature is in BETA! If you have feedback, send it to lordelph at gmail.com</div>";
			
			echo "<h1>";
			printf(t('Difference between<br/>modified post %s by %s on %s and<br/>'.
				'original post %s by %s on %s'),
				"<a href=\"".$pastebin->getPostUrl($newpost['pid'])."\">{$newpost['pid']}</a>",
				$newpost['poster'],
				$newpost['postdate'],
				'<a href="'.$pastebin->getPostUrl($oldpost['pid'])."\">{$oldpost['pid']}</a>",
				$oldpost['poster'],
				$oldpost['postdate']);
				
			echo "<br/>";	
			
			echo t('Show');
			echo " <a title=\"".t('Don\'t show inserted or changed lines')."\" style=\"padding:1px 4px 3px 4px;\" id=\"oldlink\" href=\"javascript:showold()\">".t('old version')."</a> | ";
			echo "<a title=\"".t('Don\'t show lines removed from old version')."\" style=\"padding:1px 4px 3px 4px;\" id=\"newlink\" href=\"javascript:shownew()\">".t('new version')."</a> | ";
			echo "<a title=\"".t('Show both insertions and deletions')."\"  style=\"background:#880000;padding:1px 4px 3px 4px;\" id=\"bothlink\" href=\"javascript:showboth()\">".t('both versions')."</a> ";
			echo "</h1>";
			
			$newpost['code']=preg_replace('/^'.$CONF['highlight_prefix'].'/m', '', $newpost['code']);
			$oldpost['code']=preg_replace('/^'.$CONF['highlight_prefix'].'/m', '', $oldpost['code']);
			
			$a1=explode("\n", $newpost['code']);
			$a2=explode("\n", $oldpost['code']);
			
			$diff=new Diff($a2,$a1, 1);
			
			echo "<table cellpadding=\"0\" cellspacing=\"0\" class=\"diff\">";
			echo "<tr><td></td><td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td></td></tr>";
			echo $diff->output;
			echo "</table>";
		}
		
	}
	
	
}

///////////////////////////////////////////////////////////////////////////////
// show a post
//

if (isset($_GET['help']))
	$page['posttitle']="";
	
if (strlen($page['post']['posttitle']))
{
		echo "<h1>{$page['post']['posttitle']}";
		if (strlen($page['post']['parent_pid']))
		{
			echo ' (';
			printf(t("modification of post by %s"),
				"<a href=\"{$page['post']['parent_url']}\" title=\"".t('view original post')."\">{$page['post']['parent_poster']}</a>");
			
			echo " <a href=\"{$page['post']['parent_diffurl']}\" title=\"".t('compare differences')."\">".t('view diff')."</a>)";
		}
		
		echo "<br/>";
		
		if (strlen($page['post']['token']) && ($page['token']==$page['post']['token']))
		{
			echo "<a href=\"{$page['post']['deleteurl']}\" title=\"".t('delete post')."\">".t('delete post')."</a> | ";
		}
		
		$followups=count($page['post']['followups']);
		if ($followups)
		{
			echo t('View followups from ');
			$sep="";
			foreach($page['post']['followups'] as $idx=>$followup)
			{
				echo $sep."<a title=\"posted {$followup['postfmt']}\" href=\"{$followup['followup_url']}\">{$followup['poster']}</a>";
				$sep=($idx<($followups-2))?", ":(' '.t('and').' ');	
			}
			
			echo " | ";
		}
		
		if ($page['post']['parent_pid']>0)
		{
			echo "<a href=\"{$page['post']['parent_diffurl']}\" title=\"".t('compare differences')."\">".t('diff')."</a> | ";
		} 
		
		echo "<a href=\"{$page['post']['downloadurl']}\" title=\"".t('download file')."\">".t('download')."</a> | ";
		
		echo "<span id=\"copytoclipboard\"></span>";
		
		echo "<a href=\"/\" title=\"".t('make new post')."\">".t('new post')."</a>";
		
		echo "</h1>";
}
if (isset($page['post']['pid']))
{
	echo "<div class=\"syntax\">".$page['post']['codefmt']."</div>";
	echo "<br /><b>".t('Submit a correction or amendment below')." (<a href=\"{$CONF['this_script']}\">".t('click here to make a fresh posting')."</a>)</b><br/>";
	echo t('After submitting an amendment, you\'ll be able to view the differences between the old and new posts easily').'.';
}	



if (isset($_GET['help']))
{
	h1('What is pastebin?');
	p('pastebin is here to help you collaborate on debugging code snippets. '.
		'If you\'re not familiar with the idea, most people use it like this:');
	
	echo '<ul>';
	
	li('<a href="/">submit</a> a code fragment to pastebin, getting a url like http://pastebin.com/1234');
	li('paste the url into an IRC or IM conversation');
	li('someone responds by reading and perhaps submitting a modification of your code');
	li('you then view the modification, maybe using the built in diff tool to help locate the changes');
	
	
	echo '</ul>';


	h1('How can I view the differences between two posts?');	
	
	p('When you view a post, you have the opportunity of editing the text - '.
		'<strong>this creates a new post</strong>, but when you view it, you\'ll be given a '.
		'\'diff\' link which allows you to compare the changes between the old and the new version');	
	p('This is a powerful feature, great for seeing exactly what lines someone changed');
	
	
	h1('How can I delete a post?');	
	p('If you clicked the "remember me" checkbox when posting, you will be able to delete '.
	'post from the same computer you posted from - simply view the post and click the "delete post" link.');
	p('In other cases, contact us and we will delete it for you');
	
	h1('What\'s a private pastebin and how do I get one?');
	
	p('You get a private pastebin simply by thinking up a domain name no-one else is using, '.
	'e.g. http://private.pastebin.com or http://this-is-my.pastebin.com. Posts made into a '.
	'subdomain only show up on that domain, making it easy for you to collaborate without the '.
	'\'noise\' of the regular service at <a href="http://pastebin.com">http://pastebin.com</a>');
	
	p('All you need to do is change the web address in your browser to access a private pastebin, '.
		'or you can simply enter the domain you\'d like below.')
	?>
	
	<form method="get" action="<?php echo $CONF['this_script']?>">
	<input type="hidden" name="help" value="1"/>
	<p><?php echo t('Go to')?> http://<input type="text" name="goprivate" value="<?php echo stripslashes($_GET['goprivate']) ?>" size="10"/>.pastebin.com 
	<input type="submit" name="go" value="<?php echo t('Go')?>"/></p>
	<?php if (isset($_GET['goprivate'])) { p('Please use only characters a-z,0-9, dash \'-\' and period \'.\'. Your name must start and end with a letter or number.'); } ?>
	</form>
	<?php
	
	p('Please note that there is no password protection - subdomains are accessible to anyone '.
	'who knows the domain name you\'ve chosen, but we do not publish a list of domains used.');
	
	h1('Subdomains for your language...');
	
	p('If a subdomain matches a language name, the required syntax highlighting is selected '.
	'for you, so ruby.pastebin.com will preselect Ruby automatically. ');
	
	echo '<p>';
	
	$sep="";
	foreach($CONF['all_syntax'] as $langcode=>$langname)
	{
		if ($langcode=='text')
			$langname="Plain Text";
		echo "{$sep}<a title=\"{$langname} Pastebin\" href=\"http://{$langcode}.pastebin.com\">{$langname}</a>";
		$sep=", ";
	}	
		
	echo '</p>';
	
		
		
	
	h1('And this is all free?');
	p('It will always be free, our hosting and maintenance costs are paid for through advertising.');
	
	
	h1('Can I get the source?');
	p('The source code to this site is available under a GPL licence. '.
		'You can <a title="Pastebin source code, 245Kb" href="pastebin.tar.gz">download it here</a>');
	
	p('More news available on my <a title="View pastebin related posts on my blog" href="http://blog.dixo.net/category/pastebin/">blog</a>.');

	
	h1('I have some feedback, who do I contact?');
	printf('<p>'.t('Use the feedback box in the sidebar or email %s').'</p>',
		'<script type="text/javascript">eval(unescape(\'%64%6f%63%75%6d%65%6e%74%2e%77%72%69%74%65%28%27%3c%61%20%68%72%65%66%3d%22%6d%61%69%6c%74%6f%3a%70%61%75%6c%40%65%6c%70%68%69%6e%2e%63%6f%6d%22%20%3e%50%61%75%6c%20%44%69%78%6f%6e%3c%2f%61%3e%27%29%3b\'))</script>');
	
}
else
{
?>
<form name="editor" method="post" action="<?php echo $CONF['this_script']?>">
<input type="hidden" name="parent_pid" value="<?php echo $page['post']['pid'] ?>"/>

<br/> 
<?php

echo t('Syntax highlighting:').'<select name="format">';

//show the popular ones
foreach ($CONF['all_syntax'] as $code=>$name)
{
	if (in_array($code, $CONF['popular_syntax']))
	{
		$sel=($code==$page['current_format'])?"selected=\"selected\"":"";
		echo "<option $sel value=\"$code\">$name</option>";
	}
}

echo "<option value=\"text\">----------------------------</option>";

//show all formats
foreach ($CONF['all_syntax'] as $code=>$name)
{
	$sel=($code==$page['current_format'])?"selected=\"selected\"":"";
	if (in_array($code, $CONF['popular_syntax']))
		$sel="";
	echo "<option $sel value=\"$code\">$name</option>";
	
}
?>
</select><br/>
<br/>

<?php printf(t('To highlight particular lines, prefix each line with %s'),$CONF['highlight_prefix']); ?>
<br/>
<textarea id="code" class="codeedit" name="code2" cols="80" rows="10" onkeydown="return catchTab(this,event)"><?php 
echo htmlentities($page['post']['editcode'], ENT_COMPAT,$CONF['htmlentity_encoding']) ?></textarea>

<div id="namebox">
	
<label for="poster"><?php echo t('Your Name')?></label><br/>
<input type="text" maxlength="24" size="24" id="poster" name="poster" value="<?php echo $page['poster'] ?>" />
<input type="submit" name="paste" value="<?php echo t('Send')?>"/>
<br />
<?php echo '<input type="checkbox" name="remember" value="1" '.$page['remember'].' />'.t('Remember me'); ?>

</div>


<div id="expirybox">


<div id="expiryradios">
<label><?php echo t('How long should your post be retained?') ?></label><br/>

<input type="radio" id="expiry_day" name="expiry" value="d" <?php if ($page['expiry']=='d') echo 'checked="checked"'; ?> />
<label id="expiry_day_label" for="expiry_day"><?php echo t('a day') ?></label>

<input type="radio" id="expiry_month" name="expiry" value="m" <?php if ($page['expiry']=='m') echo 'checked="checked"'; ?> />
<label id="expiry_month_label" for="expiry_month"><?php echo t('a month') ?></label>

<input type="radio" id="expiry_forever" name="expiry" value="f" <?php if ($page['expiry']=='f') echo 'checked="checked"'; ?> />
<label id="expiry_forever_label" for="expiry_forever"><?php echo t('forever') ?></label>
</div>

<div id="expiryinfo"></div>
	
</div>

<div id="end"></div>

</form>
<?php 
} 
?>

</div>
</body>
</html>
