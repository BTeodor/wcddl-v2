<?php
/*BEGIN_INFO
Search by WScripts.net
END_INFO*/
if(!defined("WCDDL_GUTS"))
   exit;
$modEnabled = true; //Change to false if don't use
if($modEnabled) {  //start of $modenabled
$q = strtolower(mysql_real_escape_string($_POST['q']));
if($q) $t = mysql_real_escape_string($_POST['type']);
$p = $_REQUEST['page'];
if($p==NULL) $p=1;
if($t==NULL) $t='All';
$qclean = preg_replace("[^ 0-9a-zA-Z]", " ", $q);
while (strstr($qclean, "  ")) {
   $qclean = str_replace("  ", " ", $qclean);
}
$qclean = str_replace(" ", "+", $qclean);


if ($q != '') {
	if($t != 'All') header( 'Location: '.$core->siteurl.$t.'/search/'.$qclean .'/page/'.$p);
	else header( 'Location: '.$core->siteurl.'search/'.$qclean .'/page/'.$p);
}

function counts() {
global $core;
		if(isset($_REQUEST['q'])) {
			$q = $_REQUEST['q'];
		}
		$sqlWhere = "";
		if(isset($q)) {
			$q=str_replace(" ","+",$q);
			$s['q']=$q;
			if($core->search_type == "narrow") {
				$sqlWhere = " WHERE title LIKE '%".mysql_real_escape_string(str_replace("+","%",$q))."%'";
			} elseif($core->search_type == "wide") {
				$qExp = explode("+",$q);
				$sqlWhere = array();
				foreach($qExp as $fq)
					$sqlWhere[] = "title LIKE '%".mysql_real_escape_string($fq)."%'";
				$sqlWhere = implode(" OR ",$sqlWhere);
				$sqlWhere = " WHERE (".$sqlWhere.")";
			}
		}
	
	$sql = mysql_query("SELECT * FROM wcddl_downloads".$sqlWhere."");
	while($row=mysql_fetch_array($sql)) {
		$s['all']++;
		foreach($core->allowed_types as $type) {
			if($row['type']==$type) $s[$type]++;	
		}
	}
	return $s;
	
}

$searchtml = '<form id="search" action="'.$core->siteurl.'index.php" method="post" onsubmit="return checksearch(this)">
<input type="text" onclick="clickclear(this, \'Search...\')" onblur="clickrecall(this,\'Search...\')" name="q" id="q" value="Search..." size="20" /><br />
<input type="hidden" name="page" value="1" />
<center><SELECT name="type">
<OPTION selected>All</option>';
foreach($core->allowed_types as $type)
{
$searchtml.= '<OPTION class="'.$type.'" value='.$type.'> '.ucfirst($type).'</option>';
}
$searchtml.= '</select></center>
<center><input type="submit" value="GO" id="title" /></center>
</form>';
$core->setTemplateVar("search",$searchtml);

$cc=counts();
$sresult='<table width="100%">';
$sresult .='<tr><td><a href="'.$core->siteurl.'search/'.$cc['q'].'/page/1">ALL</a></td><td align="right">'.$cc['all'].'</td></tr>';
foreach($core->allowed_types as $allowed_type) {
	if(isset($cc[$allowed_type])) $sresult .='<tr><td><a href="'.$core->siteurl.$allowed_type.'/search/'.$cc['q'].'/page/1">'.$allowed_type.'</a></td><td align="right">'.$cc[$allowed_type].'</td></tr>';
}
$sresult .='</table>';
if($_GET['q']) $core->setTemplateVar("sresults",$sresult);
} //end of $modenabled
?>