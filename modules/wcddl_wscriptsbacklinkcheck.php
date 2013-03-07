<?php
/*BEGIN_INFO
Backlink Checker by WScripts.net
END_INFO*/

if(!defined("WCDDL_GUTS"))
	exit;
	
$modEnabled = true; //Change to false if don't use
$modBacklink_link='wscripts.net';


if($modEnabled) {  //start of $modenabled
$add = array('backlinkcheck' => "Backlink Checker",);
$core->admin_links = array_merge($core->admin_links, $add);

function get_backlink_page( $url )
{
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "spider", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 10,      // timeout on connect
        CURLOPT_TIMEOUT        => 10,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
	
    return $header;
}

function backlinkcheck() {
	global $core,$modBacklink_link,$message;
	$cc=0;
	$page = $_GET['page'];
			if(!$page) { $page = 1; }
			$offset = ($page - 1) * 30;
			$query = mysql_query("SELECT * FROM wcddl_sites") or die(mysql_error());
			$result = mysql_num_rows($query);
			$maxpages = ceil($result/30);

		if(isset($_POST['asub']) && isset($_POST['ad']) && !empty($_POST['ad'])) {
			foreach($_POST['ad'] as $addKey => $addVal) {
				$fetch = mysql_query("SELECT url FROM wcddl_sites WHERE id = '".mysql_real_escape_string($addVal)."'");
				$fetch = mysql_fetch_array($fetch);
				
						mysql_query("insert into wcddl_blacklist (url,reason,dat) values ('$fetch[url]','No linkback','".time()."')") or die(mysql_error());
						$mB=$mB.'<span style="color:green">'.$fetch['url'].' blacklisted.</span><br />';
		
			}
			echo $mB;
		}
		if(isset($_POST['site']) || isset($_GET['site'])) {
			if(isset($_GET['site'])) $site=$_GET['site'];
				else $site=$_POST['site'];
			$site=str_replace("http://","",$site);
			$site=str_replace("www.","",$site);
				$fetch = mysql_query("SELECT id,url FROM wcddl_sites WHERE url = '".mysql_real_escape_string($site)."'");
				if(mysql_num_rows($fetch)==0) {
					$mB = "This site is not in your database";
				}
				else {
					$fetch = mysql_fetch_array($fetch);
	
						$file = get_backlink_page($fetch['url']);
						$status=strpos($file['content'],$modBacklink_link);
						if(!$status) {
							$mB=$mB.'<span style="color:red">'.$fetch['url'].' doesn\'t have your link.</span><br />';
							mysql_query("update wcddl_sites set backlink='1' where id='$fetch[id]'") or die(mysql_error());
						}
						else {
							$mB=$mB.'<span style="color:green">'.$fetch['url'].' have your link.</span><br />';
							mysql_query("update wcddl_sites set backlink='2' where id='$fetch[id]'") or die(mysql_error());
						}
				}
		
			echo $mB;
		}
		if(isset($_POST['esub']) && isset($_POST['ad']) && !empty($_POST['ad'])) {
			foreach($_POST['ad'] as $addKey => $addVal) {
				$fetch = mysql_query("SELECT url FROM wcddl_sites WHERE id = '".mysql_real_escape_string($addVal)."'");
				$fetch = mysql_fetch_array($fetch);
				
						$file = get_backlink_page($fetch['url']);
						$status=strpos($file['content'],$modBacklink_link);
						if(!$status) {
							$mB=$mB.'<span style="color:red">'.$fetch['url'].' doesn\'t have your link.</span><br />';
							mysql_query("update wcddl_sites set backlink='1' where id='$addVal'") or die(mysql_error());
						}
						else {
							$mB=$mB.'<span style="color:green">'.$fetch['url'].' have your link.</span><br />';
							mysql_query("update wcddl_sites set backlink='2' where id='$addVal'") or die(mysql_error());
						}
		
			}
			echo $mB;
		}
	
		$get = "SELECT * FROM wcddl_sites ORDER BY url ASC limit $offset,30";
		$get = mysql_query($get) or die(mysql_error());
		$html ='<center><form action="index.php?go=backlinkcheck" method="post" name="check"><input type="text" name="site" /> <br /> <input type="submit" value="check" /></form></center><table width="100%"><form action="index.php?go=backlinkcheck" method="post" name="queue">
		<tr><td>Name</td><td>Url</td><td>Backlink</td><td>Select</td></tr>';
		while($got = mysql_fetch_assoc($get)) {
			$html.= '<tr>
			<td>'.$got['name'].'</td>
			<td><a href="'.$got['url'].'" target="_blank">'.$got['url'].'</a></td>
			<td>';
			if($got['backlink']=="0") $bl='<span style="color:orange">Not Checked</span>';
			elseif($got['backlink']=="1") $bl='<span style="color:red">No Backlink</span>';
			elseif($got['backlink']=="2") $bl='<span style="color:green">Backlink found</span>';
			$html .= $bl;
			$html .= '</td>
			<td align="center"><input type="checkbox" name="ad[]" value="'.$got['id'].'"></td>
			</tr>';
		}
		$html .= '<tr>
		<td colspan="4"><input type="submit" value="Check Selected" name="esub"><input type="submit" value="Blacklist Selected" name="asub"><input type="button" value="Select All" id="sbutton" onclick="jamez();"></td>';
		$html .= '</tr>';
		$html .= '</table></form>';

		
	for($i = 1; $i<=$maxpages ; $i++) 
	{
        if($page == $i) 
		{
            $html .= "<strong>[ $i ]</strong> ";
        }
        else 
	    {
            $html .= "<a href = 'index.php?go=backlinkcheck&page=$i'>[ $i ]</a> ";
        }
    }
	$html .= '</center>';	
	
	echo $html;
}



if($_GET['go']=='backlinkcheck') $core->attachHook("adminFunctions","backlinkcheck");

} //end of $modenabled
?>