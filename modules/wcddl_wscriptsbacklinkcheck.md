-> phpmyadmin
ALTER TABLE `wcddl_sites` ADD `backlink` INT( 1 ) NOT NULL DEFAULT '0'


-> open funcs.php
	all edits are made in the protected function admin_queue()
	
	- find
		 $hack = '<span style="color:green;">CLEAN</span>';
	-- add after 
		if(!isset($bkk)) {
				if($got['bk']=='0') $bkk='<span style="color:orange">Not checked</span>';
				elseif($got['bk']=='1') $bkk='<span style="color:red">No link</span>';	
				elseif($got['bk']=='2') $bkk='<span style="color:green">Link found</span>';	
		}
		
	- find 
		$site = mysql_query("SELECT name as sname, url as surl FROM wcddl_sites WHERE id = '".mysql_real_escape_string($got['sid'])."' LIMIT 1");
	-- replace with 
		$site = mysql_query("SELECT name as sname, url as surl, backlink as bk FROM wcddl_sites WHERE id = '".mysql_real_escape_string($got['sid'])."' LIMIT 1");
		
	- find
		<td><a href="http://'.$got['surl'].'" target="_blank">'.$got['sname'].'</a></td>
	-- add after
		<td>'.$bkk.'</td>
		
	- find
		<tr><td>Type</td><td>Title</td><td>Site</td><td>Select</td><td>Cleanliness</td></tr>
	--replace with
		<tr><td>Type</td><td>Title</td><td>Site</td><td>Backlink</td><td>Select</td><td>Cleanliness</td></tr>