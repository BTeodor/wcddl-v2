	Install:
    
    ALTER TABLE `wcddl_sites` ADD `rate` int( 1 ) NOT NULL 

    open funcs.php
        replace:
            $get = "SELECT id,sid,title,type,url FROM wcddl_queue ORDER BY id ASC";
            $get = $this->processDataHook('queueQuery',$get);
            $get = mysql_query($get);
        with:
            $querybig=mysql_query("SELECT q.id,q.sid,q.title,q.type,q.url FROM wcddl_queue q LEFT JOIN wcddl_sites s ON (s.id = q.sid) where s.rate in (1,2,3)") or die(mysql_error());
            if(mysql_num_rows($querybig)!=0) { 
                $get=mysql_query("SELECT q.id,q.sid,q.title,q.type,q.url FROM wcddl_queue q LEFT JOIN wcddl_sites s ON (s.id = q.sid) where s.rate in (1,2,3) ORDER BY s.rate ASC LIMIT ".$pg.",".$queueLimit."") or die(mysql_error());
                $max = mysql_query("SELECT q.id,q.sid,q.title,q.type,q.url FROM wcddl_queue q LEFT JOIN wcddl_sites s ON (s.id = q.sid) where s.rate in (1,2,3)");
            }
            else { 
                $get=mysql_query("SELECT q.id,q.sid,q.title,q.type,q.url FROM wcddl_queue q LEFT JOIN wcddl_sites s ON (s.id = q.sid) where s.rate in (4,5) ORDER BY rand() LIMIT ".$pg.",".$queueLimit."") or die(mysql_error());
                $max = mysql_query("SELECT q.id,q.sid,q.title,q.type,q.url FROM wcddl_queue q LEFT JOIN wcddl_sites s ON (s.id = q.sid) where s.rate in (4,5)");
            }
            $max = mysql_num_rows($max);
            $max = ceil($max/$queueLimit);
            
       find: echo '</table></form>';
       add after: echo '<br /><center>'.$this->paginator("index.php?go=queue&page=#i#",$this->page,$max);
                    echo ' of <strong>'.$max.' Pages</strong></center>';
        
        find: $downloadSite = mysql_query("SELECT name as sname, url as surl FROM wcddl_sites WHERE id = '".$row['sid']."'");
        replace with: $downloadSite = mysql_query("SELECT name as sname, url as surl, rate as srate FROM wcddl_sites WHERE id = '".$row['sid']."'");
        
        
        find: protected function admin_queue() {
        add after: $queueLimit = '50'; $pg = ($this->page-1)*$queueLimit;
    
    
    
    add <?php echo $download['srate'];?>* where you want to show the *
