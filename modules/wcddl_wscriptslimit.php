<?php
/*BEGIN_INFO
Limit by WScripts.net
END_INFO*/
if(!defined("WCDDL_GUTS"))
	exit;
$limit=40;
$modEnabled = true; //Change to false if don't use
if($modEnabled) {  //start of $modenabled

function check($array) {
	global $core,$limit;
$dats=array();
$total=0;
	$sid=$array[1];
	$titles=$array[0]['downloads']['titles'];
	$urls=$array[0]['downloads']['urls'];
	$types=$array[0]['downloads']['types'];
	$data=time();
	//$data=date('d-m-Y',time());				
					$count=count($titles);
					for($i=0;$i<$count;$i++) {
						if(!empty($titles[$i]) && !empty($urls[$i]) && !empty($types[$i]) && in_array($types[$i],$core->allowed_types)) 
							{
								$ntitles[]=$titles[$i];
								$nurls[]=$urls[$i];
								$ntypes[]=$types[$i];
							}
						}
					$count=count($ntitles);
				if($count!=0)  {
					$queryCheckQueue=mysql_query("select count(*) as downloads from wcddl_queue where sid='$sid' AND $data-dat<86400");
					//$queryCheckQueue=mysql_query("select count(*) as downloads from wcddl_queue where sid='$sid' AND from_unixtime(dat,'%d-%m-%Y')='$data'");
					$resultCheckQueue=mysql_result($queryCheckQueue,0);
					$queryCheckDown=mysql_query("select count(*) as downloads from wcddl_downloads where sid='$sid' AND $data-datt<86400");
					//$queryCheckDown=mysql_query("select count(*) as downloads from wcddl_downloads where sid='$sid' AND from_unixtime(dat,'%d-%m-%Y')='$data'");
					$resultCheckDown=mysql_result($queryCheckDown,0);
					$result=$resultCheckQueue+$resultCheckDown;

					if($result+$count<=$limit) $total=$count; else $total=$limit-$result;
					
					unset($titles);unset($urls);unset($types);
					for($i=0;$i<$total;$i++) {
								$titles[]=$ntitles[$i];
								$urls[]=$nurls[$i];
								$types[]=$ntypes[$i];
					}
				}
					if($result+$total<=$limit) { $totald=$total; $available=$limit-$result-$totald; } else { $totald=$limit-$result; $available=0;}

					
					if($result==$limit) { 
					/*
						$queryTime=mysql_query("SELECT greatest(max(wd.datt),max(wq.dat)) FROM `wcddl_downloads` as wd,wcddl_queue as wq");
						$resultTime=mysql_result($queryTime,0);
						$queryTimeDownloads=mysql_query("SELECT count(*) from wcddl_downloads as wd, wcddl_queue as wq where wq.dat='$resultTime' and wd.datt='$resultTime'");
						$resultTimeDownloads=mysql_result($queryTimeDownloads,0);
					
					$queryTime=mysql_query("SELECT least(min(wd.datt),min(wq.dat)) FROM `wcddl_downloads` as wd,wcddl_queue as wq");
					$resultTime=mysql_result($queryTime,0);
					$queryCheckQueue=mysql_query("select dat from wcddl_queue where sid='$sid' AND $data-dat<86400 group by dat");
					while($resultCheckQueue=mysql_fetch_array($queryCheckQueue))  if($dats==NULL) $dats=$dats.$resultCheckQueue['dat']; else $dats=$dats.",".$resultCheckQueue['dat'];
					$queryCheckQueue=mysql_query("select datt from wcddl_downloads where sid='$sid' AND $data-dat<86400 and datt not in (".$dats.") group by datt");
					while($resultCheckQueue=mysql_fetch_array($queryCheckQueue))  if($dats==NULL) $dats=$dats.$resultCheckQueue['datt']; else $dats=$dats.",".$resultCheckQueue['datt'];
						$queryTimeDownloads=mysql_query("SELECT count(*) from wcddl_downloads as wd where wd.datt in (".$dats.")");
						$resultTimeDownloads1=mysql_result($queryTimeDownloads,0);
						$queryTimeDownloads=mysql_query("SELECT count(*) from wcddl_queue as wq where wq.dat in (".$dats.")");
						$resultTimeDownloads2=mysql_result($queryTimeDownloads,0);
						$resultTimeDownloads=$resultTimeDownloads1+$resultTimeDownloads2;
						*/
						$queryCheckQueue=mysql_query("select dat from wcddl_queue where sid='$sid' AND $data-dat<86400 group by dat");
						while($resultCheckQueue=mysql_fetch_array($queryCheckQueue))  if(!in_array($resultCheckQueue['dat'],$dats)) $dats[]=$resultCheckQueue['dat'];
						$queryCheckQueue=mysql_query("select datt from wcddl_downloads where sid='$sid' AND $data-dat<86400 group by datt");
						while($resultCheckQueue=mysql_fetch_array($queryCheckQueue))  if(!in_array($resultCheckQueue['datt'],$dats)) $dats[]=$resultCheckQueue['datt'];
						$i=0;
						foreach($dats as $dat) {
	
							$queryTimeDownloads=mysql_query("SELECT count(*) from wcddl_queue as wq where wq.dat='$dat'");
							$resultTimeDownloads1=mysql_result($queryTimeDownloads,0);
							$queryTimeDownloads=mysql_query("SELECT count(*) from wcddl_downloads as wd where wd.datt='$dat'");
							$resultTimeDownloads2=mysql_result($queryTimeDownloads,0);
							$datts[$i]['dl']=$resultTimeDownloads1+$resultTimeDownloads2;
							$datts[$i]['dat']=$dat;
							$i++;
						}
	
						$mess='You reached your maxim number of downloads for today.<br /> You can submit:<br />';
						foreach($datts as $datt) {
							 $in=sectohours(86400-($data-$datt['dat']));
							 $mess=$mess.$datt['dl'].' downloads in '.$in.'<br />';
						}
					}
					elseif($available==0) $mess='Only '.$total.' downloads accepted. You reached your maxim number of downloads for today.';
					else $mess=$total." downloads submitted successfully!<br />Today you may submit ".$available." downloads!<br />";
	return array($titles,$urls,$types,$mess,$data);
}
 function sectohours($secs) 
    { 
        $vals = array('w' => (int) ($secs / 86400 / 7), 
                      'd' => $secs / 86400 % 7, 
                      'h' => $secs / 3600 % 24, 
                      'm' => $secs / 60 % 60, 
                      's' => $secs % 60); 
 
        $ret = array(); 
 
        $added = false; 
        foreach ($vals as $k => $v) { 
            if ($v > 0 || $added) { 
                $added = true; 
                $ret[] = $v . $k; 
            } 
        } 
 
        return join(' ', $ret); 
    } 
$core->attachDataHook("modLimit","check");	
} //end of $modenabled
?>