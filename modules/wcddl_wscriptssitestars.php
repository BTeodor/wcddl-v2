<?php
/*BEGIN_INFO
RateMod + Organized queue by WScripts.net
END_INFO*/
if(!defined("WCDDL_GUTS"))
	exit;
$modEnabled = true; //Change to false if don't use

if($modEnabled) {  //start of $modenabled
$add = array('sitestars' => "Site Stars",);
$core->admin_links = array_merge($core->admin_links, $add);

function sitestars() {
global $core;
$slimit = '50'; $pg = ($core->page-1)*$slimit;
		if(isset($_POST['bwurl']))
			$bwurl = $_POST['bwurl'];
		if(isset($_POST['bwname']))
			$bwname = $_POST['bwname'];
		if(isset($_POST['bwemail']))
			$bwemail = $_POST['bwemail'];
		if(isset($_POST['stars']))
			$stars = $_POST['stars'];
		if(isset($_POST['bwedit']))
			$bwedit = $_POST['bwedit'];
		if(isset($_POST['bweditdone']))
			$bweditdone = $_POST['bweditdone'];
		if(isset($_POST['bwsub']))
			$bwsub = $_POST['bwsub'];
		if(isset($_POST['remove']))
			$remove = $_POST['remove'];
		if(isset($_POST['id']))
			$id = $_POST['id'];
			
		if(isset($bwsub)) {
			if(isset($bwurl)) {
			$bwquery = "INSERT INTO wcddl_sites (name,url,email,rate) VALUES ('".mysql_real_escape_string($bwname)."','".mysql_real_escape_string($bwurl)."','".mysql_real_escape_string($bwemail)."','".$stars."')";
			@mysql_query($bwquery);
			
				echo '<h2 align="center">Site has beed added.</h2>';
				
			}
			if(isset($remove)) {
				$fishpan = 0;
				foreach($remove as $id) {
					mysql_query("DELETE FROM wcddl_sites WHERE id = '".mysql_real_escape_string($id)."'");
					$fishpan++;
				}
				echo '<h2 align="center">'.$fishpan.' Sites were Removed </h2>';
			}
		}
		elseif(isset($bwedit)) {
			foreach($remove as $id) {		
				$get = mysql_query("SELECT * FROM wcddl_sites where id='$id'");
				$get = mysql_fetch_assoc($get);				
				$sform .='<form action="" method="post"><table width="100%">
				<tr><td align="center">Whitelist an URL</td></tr>
				<tr><td align="center">DO NOT INCLUDE WWW., HTTP:// or a directory! ONLY THE HOST NAME</td></tr>
				<tr><td align="center">URL: <input type="text" name="bwurl" value="'.$get['url'].'" id="title"></td></tr>
				<tr><td align="center">Name: <input type="text" name="bwname" value="'.$get['name'].'" id="title"></td></tr>
				<tr><td align="center">Email: <input type="text" name="bwemail" value="'.$get['email'].'" id="title"></td></tr>
				<input type="hidden" name="id" value="'.$id.'" />
				<tr><td align="center">
							<select name="stars">
							<option value="'.$get['rate'].'">'.$get['rate'].' Stars</option>
							<option>Select Star</option>
							<option value="1">1 Star</option>
							<option value="2">2 Stars</option>
							<option value="3">3 Stars</option>
							<option value="4">4 Stars</option>
							<option value="5">5 Stars</option>
							</select>
					</td>
				</tr>
				<tr><td align="center" colspan="2"><input type="submit" value="Edit" name="bweditdone" id="title"></td></tr></table></form>';
			}
		}
		elseif(isset($bweditdone)) {
			mysql_query("update wcddl_sites set rate='$stars', url='$bwurl', name='$bwname', email='$bwemail'  where id='$id'");
			echo '<h2 align="center">Site has been edited.</h2>';
		}
		else {
		$sform = '<form action="" method="post"><table width="100%">
		<tr><td><h2 align="center">Name</h2></td><td><h2 align="center">URL</h2></td><td><h2 align="center">Email</h2></td><td><h2 align="center">Stars</h2></td><td class="td3"><h2 align="center">Action</h2></td></tr>';
		$max = mysql_query("SELECT id,name,url,email,rate FROM wcddl_sites");
		$max = mysql_num_rows($max);
		$max = ceil($max/$slimit);
        
		$get = mysql_query("SELECT id,name,url,email,rate FROM wcddl_sites LIMIT ".$pg.",".$slimit."");
		while($row = mysql_fetch_array($get)) {
			$sform .= '<tr><td align="center">'.$row['name'].'</td><td align="center"><a href="http://'.$row['url'].'" target="_blank">'.$row['url'].'</a></td><td align="center">'.$row['email'].'</td><td align="center" style="font-size:20px;">'.$row['rate'].'*</td><td align="center"><input type="checkbox" name="remove[]" value="'.$row['id'].'"></td></tr>';
		}
		$sform .= '<tr><td colspan="3" align="right"><input type="submit" value="Edit Selected" name="bwedit" id="title"><input type="submit" value="Remove Selected" name="bwsub" id="title"></td></tr>
		</table></form><br>
		<form action="" method="post"><table width="100%">
		<tr><td align="center">Add an site</td></tr>
		<tr><td align="center">DO NOT INCLUDE WWW., HTTP:// or a directory! ONLY THE HOST NAME</td></tr>
		<tr><td align="center">URL: <input type="text" name="bwurl" id="title"></td></tr>
		<tr><td align="center">Name: <input type="text" name="bwname" id="title"></td></tr>
		<tr><td align="center">Email: <input type="text" name="bwemail" id="title"></td></tr>
		<tr><td align="center">
					<select name="stars">
					<option>Select Star</option>
					<option value="1">1 Star</option>
					<option value="2">2 Stars</option>
					<option value="3">3 Stars</option>
					<option value="4">4 Stars</option>
					<option value="5">5 Stars</option>
					</select>
			</td>
		</tr>
		<tr><td align="center" colspan="2"><input type="submit" value="Add" name="bwsub" id="title"></td></tr></table></form>';
		$sform .= '<br /><center>'.$core->paginator("index.php?go=sitestars&page=#i#",$core->page,$max);
				echo ' of <strong>'.$max.' Pages</strong></center>';
		}
		echo $sform;
}
if($_GET['go']=='sitestars') $core->attachHook("adminFunctions","sitestars");
} //end of $modenabled
?>