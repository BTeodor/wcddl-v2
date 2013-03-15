	/* INSTALL
	ALTER TABLE `wcddl_queue` ADD `dat` VARCHAR( 15 ) NOT NULL 
	ALTER TABLE `wcddl_downloads` ADD `datt` VARCHAR( 15 ) NOT NULL 
	
	-find in funcs.php (all edits are done in doSubmit() function)
							$inserted['details'] = array(
								"sname" => $sname,
								"surl" => $surl,
								"email" => $email,
								"sid" => $sid
							);
							
	-add after
							list($titles,$urls,$types,$mess,$time) = $this->processDataHook("modLimit",array($checksPass,$sid));
	
	-find:
		mysql_query("INSERT INTO wcddl_queue (sid,title,type,url) VALUES ('".mysql_real_escape_string($sid)."','".mysql_real_escape_string($titles[$i])."','".mysql_real_escape_string($types[$i])."','".mysql_real_escape_string($urls[$i])."')");
	-replace with:
		mysql_query("INSERT INTO wcddl_queue (sid,title,type,url,dat) VALUES ('".mysql_real_escape_string($sid)."','".mysql_real_escape_string($titles[$i])."','".mysql_real_escape_string($types[$i])."','".mysql_real_escape_string($urls[$i])."',".$time.")");
		
		
	-find:
		$subSuccess = 'Downloads submitted successfully!';
	-replace with:
		$subSuccess = $mess;
	
	
	-find: (edits are done in admin_queue() function)
		$fetch = mysql_query("SELECT sid,title,type,url FROM wcddl_queue WHERE id = '".mysql_real_escape_string($addVal)."'");
	-replace with:
		$fetch = mysql_query("SELECT sid,title,type,url,dat FROM wcddl_queue WHERE id = '".mysql_real_escape_string($addVal)."'");
	
	-find:
		mysql_query("INSERT INTO wcddl_downloads (sid,title,type,url,dat) VALUES ('".$fetch['sid']."','".$fetch['title']."','".$fetch['type']."','".$fetch['url']."','".time()."')");
	-replace with: 
		mysql_query("INSERT INTO wcddl_downloads (sid,title,type,url,dat,datt) VALUES ('".$fetch['sid']."','".$fetch['title']."','".$fetch['type']."','".$fetch['url']."','".time()."','".$fetch['dat']."',)");
				
				
	Info:
	-open wcddl_wscriptslimit.php and change $limit to how many downloads/day.
