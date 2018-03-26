<?php

/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*                                   ----------- CORE FUNCTIONS -----------
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/

function load($url,$options=array()) {
//This code is from Paul Brighton, with some changes on it. https://github.com/berighton

    $default_options = array(
        'method'        => 'get',
        'post_data'        => true,
        'return_info'    => false,
        'return_body'    => true,
        'cache'            => false,
        'referer'        => '',
        'headers'        => array(),
        'session'        => false,
        'session_close'    => false,
    );

    // Sets the default options.
    foreach($default_options as $opt=>$value) {
        if(!isset($options[$opt])) $options[$opt] = $value;
    }

    $url_parts = parse_url($url);
    $ch = false;
    $info = array(//Currently only supported by curl.
        'http_code'    => 200
    );
    $response = '';
    
    $send_header = array(
        'Accept' => 'text/*',
        'User-Agent' => 'BinGet/1.00.A (http://www.bin-co.com/php/scripts/load/)'
    ) + $options['headers']; // Add custom headers provided by the user.
    
    if($options['cache']) {
        $cache_folder = joinPath(sys_get_temp_dir(), 'php-load-function');
        if(isset($options['cache_folder'])) $cache_folder = $options['cache_folder'];
        if(!file_exists($cache_folder)) {
            $old_umask = umask(0); // Or the folder will not get write permission for everybody.
            mkdir($cache_folder, 0777);
            umask($old_umask);
        }
        
        $cache_file_name = md5($url) . '.cache';
        $cache_file = joinPath($cache_folder, $cache_file_name); //Don't change the variable name - used at the end of the function.
        
        if(file_exists($cache_file)) { // Cached file exists - return that.
            $response = file_get_contents($cache_file);
            
            //Seperate header and content
            $separator_position = strpos($response,"\r\n\r\n");
            $header_text = substr($response,0,$separator_position);
            $body = substr($response,$separator_position+4);
            
            foreach(explode("\n",$header_text) as $line) {
                $parts = explode(": ",$line);
                if(count($parts) == 2) $headers[$parts[0]] = chop($parts[1]);
            }
            $headers['cached'] = true;
            
            if(!$options['return_info']) return $body;
            else return array('headers' => $headers, 'body' => $body, 'info' => array('cached'=>true));
        }
    }

    if(isset($options['post_data'])) { //There is an option to specify some data to be posted.
        $options['method'] = 'post';
        
        if(is_array($options['post_data'])) { //The data is in array format.
            $post_data = array();
            foreach($options['post_data'] as $key=>$value) {
                $post_data[] = "$key=" . urlencode($value);
            }
            $url_parts['query'] = implode('&', $post_data);
        } else { //Its a string
            $url_parts['query'] = $options['post_data'];
        }
    } elseif(isset($options['multipart_data'])) { //There is an option to specify some data to be posted.
        $options['method'] = 'post';
        $url_parts['query'] = $options['multipart_data'];
        /*
            This array consists of a name-indexed set of options.
            For example,
            'name' => array('option' => value)
            Available options are:
            filename: the name to report when uploading a file.
            type: the mime type of the file being uploaded (not used with curl).
            binary: a flag to tell the other end that the file is being uploaded in binary mode (not used with curl).
            contents: the file contents. More efficient for fsockopen if you already have the file contents.
            fromfile: the file to upload. More efficient for curl if you don't have the file contents.

            Note the name of the file specified with fromfile overrides filename when using curl.
         */
    }

    ///////////////////////////// Curl /////////////////////////////////////
    //If curl is available, use curl to get the data.
   //Don't use curl if it is specifically stated to use fsocketopen in the options
        
        if(isset($options['post_data'])) { //There is an option to specify some data to be posted.
            $page = $url;
            $options['method'] = 'post';
            
            if(is_array($options['post_data'])) { //The data is in array format.
                $post_data = array();
                foreach($options['post_data'] as $key=>$value) {
                    $post_data[] = "$key=" . urlencode($value);
                }
                $url_parts['query'] = implode('&', $post_data);
            
            } else { //Its a string
                $url_parts['query'] = '';
            }
        } else {
            if(isset($options['method']) and $options['method'] == 'post') {
                $page = $url_parts['scheme'] . '://' . 'localhost' . $url_parts['path'];
            } else {
                $page = $url;
            }
        }

        if($options['session'] and isset($GLOBALS['_binget_curl_session'])) $ch = $GLOBALS['_binget_curl_session']; //Session is stored in a global variable
        else $ch = curl_init('localhost');
        
        curl_setopt($ch, CURLOPT_URL, $page) or die("Invalid cURL Handle Resouce");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //Just return the data - not print the whole thing.
      
        curl_setopt($ch, CURLOPT_NOBODY, !($options['return_body'])); //The content - if true, will not download the contents. There is a ! operation - don't remove it.
        $tmpdir = NULL; //This acts as a flag for us to clean up temp files
        if(isset($options['method']) and $options['method'] == 'post' and isset($url_parts['query'])) {
            curl_setopt($ch, CURLOPT_POST, true);
            if(is_array($url_parts['query'])) {
                //multipart form data (eg. file upload)
                $postdata = array();
                foreach ($url_parts['query'] as $name => $data) {
                    if (isset($data['contents']) && isset($data['filename'])) {
                        if (!isset($tmpdir)) { //If the temporary folder is not specifed - and we want to upload a file, create a temp folder.
                            //  :TODO:
                            $dir = sys_get_temp_dir();
                            $prefix = 'load';
                            
                            if (substr($dir, -1) != '/') $dir .= '/';
                            do {
                                $path = $dir . $prefix . mt_rand(0, 9999999);
                            } while (!mkdir($path, $mode));
                        
                            $tmpdir = $path;
                        }
                        $tmpfile = $tmpdir.'/'.$data['filename'];
                        file_put_contents($tmpfile, $data['contents']);
                        $data['fromfile'] = $tmpfile;
                    }
                    if (isset($data['fromfile'])) {
                        // Not sure how to pass mime type and/or the 'use binary' flag
                        $postdata[$name] = '@'.$data['fromfile'];
                    } elseif (isset($data['contents'])) {
                        $postdata[$name] = $data['contents'];
                    } else {
                        $postdata[$name] = '';
                    }
                }
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $url_parts['query']);
            }
        }
      
        curl_setopt($ch, CURLOPT_COOKIEJAR, "/tmp/binget-cookie.txt"); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       	
        global $response;
        $response = curl_exec($ch);

        if(isset($tmpdir)) {
            //rmdirr($tmpdir); //Cleanup any temporary files :TODO:
        }

        $info = curl_getinfo($ch); //Some information on the fetch
        
        if($options['session'] and !$options['session_close']) $GLOBALS['_binget_curl_session'] = $ch; //Dont close the curl session. We may need it later - save it to a global variable
        else curl_close($ch);  //If the session option is not set, close the session.
   
} //endfunction


/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/

$array_counter 	= 0;
$get_to_replace	= array();
$get_result 	= array();

function contruct_page($page, $archive){

	global $response;
    global $id;
    global $get_to_replace;
    global $get_result;
    global $items2content;

    $site   = explode('/', $_SERVER['PHP_SELF']);
    $path   = 'http://'.$_SERVER['HTTP_HOST'].DS.$site[1].DS.PAGES_DIR.$page.DS;   

    if($archive == 'ver.php'){
        $id = '&id='.$id;
    }  else {
        $id = '';
    }

	$file 	= $path.$archive.'?page='.$page.$id;
	load($file, '');  
    $source = $response;

	if(!empty(preg_match_all("'<loop>(.*?)</loop>'si", $source, $match))){

		preg_match_all("'<loop>(.*?)</loop>'si", $source, $match); 
        $content = $match[0];

		$match_count = preg_match_all("'<loop_sql>(.*?)</loop_sql>'si", $source, $match); 
        $sql_options = $match[1];

        $x = 0;

        foreach ($content as $cont) {

            parse_str(strtr($sql_options[$x], "=;", "=&"), $value);

            $table      = $value['table'];
            $where      = $value['where'];
            $extras     = $value['extras'];
            $orderby    = $value['orderby'];
            $order      = $value['order'];
            $limit      = $value['limit'];

            loop_page( 
                $table,
                $cont,
                $where,
                $extras, 
                $orderby, 
                $order,
                $limit
            );

            $x++;

            $get_to_replace[] = $cont;

        }

		$final = str_replace($get_to_replace, $get_result, $source);

		$show_source = show_source($_SERVER['DOCUMENT_ROOT'].'\blackholeframe\app\config\directories.php', 'false');
		$show_source = str_replace(array('define</span><span style="color: #007700">(</span><span style="color: #DD0000">', '</span><span style="color: #007700">'), array("<start>", "</start>"), $show_source);
		$show_source = str_replace("'", "", $show_source);
		preg_match_all("'<start>(.*?)</start>'si", $show_source, $match); $dirs = $match[1];

		$dirs_value_array = array();
		foreach ($dirs as $dirs_value) {
			if(strpos($final, $dirs_value) == true){
				$final = str_replace($dirs_value, constant($dirs_value), $final);
			}
		}

        echo $final;
           
		} else {
			include (PAGES_DIR . $page . DS . 'index.php');
		}

} //endfunction


/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/

function loop_page($table, $content, $where, $extras, $orderby, $order, $limit){

	global $get_to_replace;
	global $get_result;

	$content = str_replace('.DS.', DS, $content);

	preg_match_all('/{+(.*?)}/', $content, $matches);
	$columns = str_replace(array('{', '}'), array('', ''), implode(',',$matches[0]));


	/*------------------ CLEANING FUNCTIONS ------------------*/
	$columns_functions_clean_exploded = explode(",",$columns);
	$items_functions_clean = array();
	foreach ($columns_functions_clean_exploded as $value_functions_clean) {
		if(strpos($value_functions_clean, "function->") === false){
			$items_functions_clean[] = $value_functions_clean;
		} else {
			$functions_clean_exploded 	= explode("->",$value_functions_clean);
			$items_functions_clean[] 	= $functions_clean_exploded[2];
		}
	}
	$items_functions_clean 		= implode(",", $items_functions_clean);
	$items_functions_clean 		= rtrim($items_functions_clean, ",");
	$items_functions_clean 		= str_replace(",,", ",", $items_functions_clean);	
	$columns_functions_clean 	= $items_functions_clean;

	
	/*--------------------------------------------------------*/

	$conn = db();

	if($where != ' '){ $where = ' WHERE '.$where; }
	if($orderby != ' '){ $orderby = ' ORDER BY '.$orderby; }
	if($order != ' '){ $order = ' '.$order.' '; }
    if($limit != ' '){ $limit = ' LIMIT '.$limit; }

    //echo $where; die();

	if ($result = $conn->query("SELECT $columns_functions_clean FROM $table $where $extras $orderby $order $limit")) {

	   $columns_exploded = explode(",",$columns);
	   $items = array();

	   foreach ($columns_exploded as $value) {
		    $items[] = "{".$value."}";
		}

		$items2content = '';

	    while ($obj = $result->fetch(PDO::FETCH_OBJ)) {

	    	$items2 = array();

	    	foreach ($columns_exploded as $value) {

	    		if(strpos($value, "function->") === false){
					 $items2[] = $obj->$value;
				} 
				else {
					$functions_clean_exploded = explode("->",$value);
					$functions_cleaning = $functions_clean_exploded[1]($obj->$functions_clean_exploded[2]);
					$items2[] = $functions_cleaning;
				}
			  
			} 

			$items2content .= str_replace($items, $items2, $content);

	    }

	    $get_result[] = $items2content;
	}	

	$conn = NULL;
} //endfunction


/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/

function loop($table, $content, $where, $extras, $order, $asc_desc, $limit){

	$content = str_replace('.DS.', DS, $content);

	preg_match_all('/{+(.*?)}/', $content, $matches);
	$columns = str_replace(array('{', '}'), array('', ''), implode(',',$matches[0]));

	/*------------------ CLEANING FUNCTIONS ------------------*/
	$columns_functions_clean_exploded = explode(",",$columns);
	$items_functions_clean = array();
	foreach ($columns_functions_clean_exploded as $value_functions_clean) {
		if(strpos($value_functions_clean, "function->") === false){
			$items_functions_clean[] = $value_functions_clean;
		} else {
			$functions_clean_exploded 	= explode("->",$value_functions_clean);
			$items_functions_clean[] 	= $functions_clean_exploded[2];
		}
	}
	$items_functions_clean 		= implode(",", $items_functions_clean);
	$items_functions_clean 		= rtrim($items_functions_clean, ",");
	$items_functions_clean 		= str_replace(",,", ",", $items_functions_clean);	
	$columns_functions_clean 	= $items_functions_clean;
	/*--------------------------------------------------------*/

	$conn = db();

	if(!empty($where)){ $where = ' WHERE '.$where; }
	if(!empty($order)){ $order = ' ORDER BY '.$order; }
	if(!empty($limit)){ $limit = ' LIMIT '.$limit; }

	if ($result = $conn->query("SELECT $columns_functions_clean FROM $table $where $extras $order $asc_desc $limit")) {

	   $columns_exploded = explode(",",$columns);
	   $items = array();

	   foreach ($columns_exploded as $value) {
		    $items[] = "{".$value."}";
		}

	    while ($obj = $result->fetch(PDO::FETCH_OBJ)) {
	    	$items2 = array();
	    	foreach ($columns_exploded as $value) {
	    		if(strpos($value, "function->") === false){
					 $items2[] = $obj->$value;
				} else {
					$functions_clean_exploded = explode("->",$value);
					$functions_cleaning = $functions_clean_exploded[1]($obj->$functions_clean_exploded[2]);
					$items2[] = $functions_cleaning;
				}
			   
			}
	        echo str_replace($items, $items2, $content);
	    }
	}	

	$conn = NULL;
} //endfunction




/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/

function loop_nested($table, $content, $where, $extras, $order, $asc_desc, $limit, $table_nest, $content_nest, $where_nest, $extras_nest, $order_nest, $asc_desc_nest, $limit_nest){

	$content = str_replace('.DS.', DS, $content);
	$content_nest = str_replace('.DS.', DS, $content_nest);

	preg_match_all('/{+(.*?)}/', $content, $matches);
	$columns = str_replace(array('{', '}'), array('', ''), implode(',',$matches[0]));

	preg_match_all('/{+(.*?)}/', $content_nest, $matches_nest);
	$columns_nest = str_replace(array('{', '}'), array('', ''), implode(',',$matches_nest[0]));

	/*------------------ CLEANING FUNCTIONS ------------------*/
	$columns_functions_clean_exploded = explode(",",$columns);
	$items_functions_clean = array();
	foreach ($columns_functions_clean_exploded as $value_functions_clean) {
		if(strpos($value_functions_clean, "function->") === false){
			$items_functions_clean[] = $value_functions_clean;
		} else {
			$functions_clean_exploded 	= explode("->",$value_functions_clean);
			$items_functions_clean[] 	= $functions_clean_exploded[2];
		}
	}
	$items_functions_clean 		= implode(",", $items_functions_clean);
	$items_functions_clean 		= rtrim($items_functions_clean, ",");
	$items_functions_clean 		= str_replace(",,", ",", $items_functions_clean);	
	$columns_functions_clean 	= $items_functions_clean;


	$columns_functions_clean_exploded_nest = explode(",",$columns_nest);
	$items_functions_clean_nest = array();
	foreach ($columns_functions_clean_exploded_nest as $value_functions_clean_nest) {
		if(strpos($value_functions_clean_nest, "function->") === false){
			$items_functions_clean_nest[] = $value_functions_clean_nest;
		} else {
			$functions_clean_exploded_nest 	= explode("->",$value_functions_clean_nest);
			$items_functions_clean_nest[] 	= $functions_clean_exploded_nest[2];
		}
	}
	$items_functions_clean_nest = implode(",", $items_functions_clean_nest);
	$items_functions_clean_nest = rtrim($items_functions_clean_nest, ",");
	$items_functions_clean_nest = str_replace(",,", ",", $items_functions_clean_nest);	
	$columns_functions_clean_nest = $items_functions_clean_nest;

	/*-----------------------------------------------*/

	$conn = db();

	if(!empty($where)){ $where = ' WHERE '.$where; }
	if(!empty($order)){ $order = ' ORDER BY '.$order; }
	if(!empty($limit)){ $limit = ' LIMIT '.$limit; }

	if ($result = $conn->query("SELECT $columns_functions_clean FROM $table $extras $order $asc_desc $limit")) {

	   $columns_exploded = explode(",",$columns);
	   $items = array();

	   foreach ($columns_exploded as $value) {
		    $items[] = "{".$value."}";
		}

	    while ($obj = $result->fetch(PDO::FETCH_OBJ)) {
	    	$items2 = array();
	    	foreach ($columns_exploded as $value) {
			    if(strpos($value, "function->") === false){
					 $items2[] = $obj->$value;
				} else {
					$functions_clean_exploded = explode("->",$value);
					$functions_cleaning = $functions_clean_exploded[1]($obj->$functions_clean_exploded[2]);
					$items2[] = $functions_cleaning;
				}
			}

	        echo str_replace($items, $items2, $content);

	        preg_match_all('/{+(.*?)}/', $where_nest, $matches_where_nest);
			$columns_where_nest = str_replace(array('{', '}'), array('', ''), implode(',',$matches_where_nest[0]));

	        if(!empty($where_nest)){ $where_nest2 = ' WHERE '.str_replace( '{'.$columns_where_nest.'}', " ".$obj->$columns_where_nest." ", $where_nest); }
			if(!empty($order_nest)){ $order_nest = ' ORDER BY '.$order_nest; }
			if(!empty($limit_nest)){ $limit_nest = ' LIMIT '.$limit_nest; }

			$sql = "SELECT $columns_functions_clean_nest FROM $table_nest $where_nest2 $extras_nest $order_nest $asc_desc_nest $limit_nest";

			if ($result_nest = $conn->query($sql)) {

			   $columns_exploded_nest = explode(",",$columns_nest);
			   		$items_nest = array();

			   	foreach ($columns_exploded_nest as $value_nest) {
				    $items_nest[] = "{".$value_nest."}";
				}

			    while ($obj_nest = $result_nest->fetch(PDO::FETCH_OBJ)) {
			    	$items2_nest = array();
			    	foreach ($columns_exploded_nest as $value_nest) {
					     if(strpos($value_nest, "functions->") === false){
							 $items2_nest[] = $obj_nest->$value_nest;
						} else {
							$functions_clean_exploded_nest = explode("->",$value_nest);
							$functions_cleaning_nest = $functions_clean_exploded_nest[1]($obj_nest->$functions_clean_exploded_nest[2]);
							$items2_nest[] = $functions_cleaning_nest;
						}
					}

					echo str_replace($items_nest, $items2_nest, $content_nest);
				}
			}

			

	    }
	}	

	$conn = NULL;
} //endfunction


/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*                                ----------- SMALL FUNCTIONS -----------
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/

function slug($str){
	$slug = array( ' '=>'-', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b' );
	$slug = strtolower(strtr( $str, $slug ));
	return $slug;
} //endfunction


function date_formating($str){
	$date_formating = date("d/m/Y", strtotime($str));
	return $date_formating;
} //endfunction

function date_formating_sem_ano($str){
	$date_formating = date("d/m", strtotime($str));
	return $date_formating;
} //endfunction

function remove_underlines($str){
	$remove_underlines = array( '_'=>' ');
	$remove_underlines = strtolower(strtr( $str, $remove_underlines ));
	return $remove_underlines;
} //endfunction

function limit_chars($str){
  $length = 550;
  if(strlen($str)<=$length){
    echo $str;
  }
  else{
    $str=substr($str,0,$length) . '...';
    return $str;
  }
} //endfunction

?><?php

/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*                                   ----------- CORE FUNCTIONS -----------
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/




/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/

$array_counter 	= 0;
$get_to_replace	= array();
$get_result 	= array();




/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/




/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/






/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/




/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*                                ----------- SMALL FUNCTIONS -----------
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/












?><?php

/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*                                   ----------- CORE FUNCTIONS -----------
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/




/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/

$array_counter 	= 0;
$get_to_replace	= array();
$get_result 	= array();




/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/




/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/






/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/




/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*                                ----------- SMALL FUNCTIONS -----------
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/












?><?php

/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*                                   ----------- CORE FUNCTIONS -----------
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/




/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/

$array_counter 	= 0;
$get_to_replace	= array();
$get_result 	= array();




/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/




/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/






/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/




/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*                                ----------- SMALL FUNCTIONS -----------
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/












?>