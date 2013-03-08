	add <?=$core->templateVar("sresults")?> where you want the result
 
	add <?=$core->templateVar("search")?> for a search box if you don't have one 

	add this to htaccess 
		RewriteRule ^(.*)/search/(.*)/page/([0-9]{1,3})$ search.php?type=$1&q=$2&page=$3 
		RewriteRule ^search/(.*)/page/([0-9]{1,3})$ search.php?q=$1&page=$2 

	and in wcfg.php change the pageURLs for search and search_type with 
		"search" => "".$this->siteurl."search/[Q]/[PAGE]", 
		"search_type" => "".$this->siteurl."search/[TYPE]/[Q]/[PAGE]", 

