<?
	class Page {

        var $pageID;
        var $title;
        var $name;
        var $filename;
		var $volume;
		var $issue;
		var $news_date;
		var $news_type;
        
		var $type_name;
        var $contents = array();


		function Page($var="") {
			if (is_numeric($var) && ($var)) {
                    require_once(CONNECTION);
				$sql = "SELECT * FROM pages WHERE pageID = $var";
                     $result = @$mysqli->query($sql);
				$row = @$result->fetch_array();
				$this->makeFromRow($row);
                
			} else {
				$this->makeFromRow($var);
			}
            
                $this->fill_content_array();
				
			if($this->news_type != 0) {
				$sql = "SELECT type FROM types WHERE typeID = $this->news_type";
				$result = @$mysqli->query($sql);
				$row = @$result->fetch_array();
				$this->type_name = $row['type'];
			}
		}

		function makeFromRow($row="") {
                $this->pageID =                     isset($row["pageID"])    ?    $row["pageID"]	    :    ($this->pageID  ?  $this->pageID : 0);
                $this->title =                 isset($row["title"])    ?    $row["title"]	    :    ($this->title  ?  $this->title : '');
                $this->name =                   isset($row["name"])    ?    $row["name"]	    :    ($this->name  ?  $this->name : '');
                $this->filename =                  isset($row["filename"])    ?    $row["filename"]	    :    ($this->filename  ?  $this->filename : '');
				$this->volume =                  isset($row["volume"])    ?    $row["volume"]	    :    ($this->volume  ?  $this->volume : '');
				$this->issue =                  isset($row["issue"])    ?    $row["issue"]	    :    ($this->issue  ?  $this->issue : 'no');
				$this->news_date =                  isset($row["news_date"])    ?    $row["news_date"]	    :    ($this->news_date  ?  $this->news_date : '');
				$this->news_type =                  isset($row["news_type"])    ?    $row["news_type"]	    :    ($this->news_type  ?  $this->news_type : 0);
            }

		function Save() {
                require_once(CONNECTION);
                foreach($this as $key => $value) {
                     if(is_string($value)) {
                        $this->$key = $this->clean_input($value);
                    }
                }

			if ($this->pageID) {

				$sql = "UPDATE pages SET"
					. " title = '$this->title',"
                    . " name = '$this->name',"
                    . " filename = '$this->filename', volume='$this->volume', issue='$this->issue', news_date='$this->news_date', news_type=$this->news_type"
                    . " WHERE pageID         = $this->pageID";

				$result = @$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);

			} else {

				$sql = "INSERT INTO pages"
					        . " (pageID,"            
                            . " title,"         
                            . " name,"          
                            . " filename, volume, issue,news_date, news_type)"  
					. " VALUES"
                        . " ($this->pageID,"            
                        . " '$this->title',"         
                        . " '$this->name',"          
                        . " '$this->filename', '$this->volume', '$this->issue', '$this->news_date', $this->news_type)";

				$result = @$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);

				$this->pageID = $mysqli->insert_id;

			}
		}

		function Delete() {
                require_once(CONNECTION);
			$sql = "DELETE FROM pages WHERE pageID = $this->pageID";
			$result = @$mysqli->query($sql);
		}
        
        function getField($field) {
            return $this->$field;
        }
        
        function setField($field, $value) {
            $this->$field = $value;
        }
		
		function fill_from_volume_issue($vv,$ii) {
			require_once(CONNECTION);
			$vv = $this->clean_input($vv);
			$ii =  $this->clean_input($ii);
			$sql = "SELECT * FROM pages WHERE volume = '$vv' AND issue = '$ii'";
			$result = @$mysqli->query($sql);
			$count=@$result->num_rows;
			if($count != 1) {
				return false;
			} else {
				$row = @$result->fetch_array();
				$this->makeFromRow($row);
			}
				
			
			
		}
        
     
        
        function update_from_post($post) {
            foreach($post as $key => $value) {
                if(array_key_exists($key, $this)) {
                    $this->setField($key, $this->clean_input($value));
                }
            }
        }
        
        function clean_input($input) {
            $input=strip_tags($input);
            //$input=str_replace("#","%23",$input);
            //$input=str_replace("'","`",$input);
            $input=trim($input);
             if (ini_get('magic_quotes_gpc')) { 
                $input = stripslashes($input); 
            } 
            $input=$mysqli->real_escape_string($input);
            return $input; 
        }
            
        function get_list() {
				$types = $this->get_types();
                $rere = array();
            	$sql = "SELECT * FROM pages ORDER BY news_type ASC, name ASC";
                $result = @$mysqli->query($sql);
                $count=@$result->num_rows;
                if($count < 1)
                    return false;
                    
                while($row = @$result->fetch_array()) {
                    //$rere[$row['pageID']] = array("name" => $row['name'], "type" => $types[$row['news_type']]);
					$rere[$row['pageID']] = $row['name'];
                }
                return $rere;
        }
		
		function get_better_list($type=false) {
			$rere = array();
			$sql = "SELECT * FROM pages";
			if($type)
				$sql .= " WHERE news_type=$type";
			$sql .= " ORDER BY news_date DESC, news_type ASC";
			$result = @$mysqli->query($sql)  or die('get_better failed: ' . $mysqli->error);
			$count=@$result->num_rows;
			if($count < 1)
				return false;
				
			while($row = @$result->fetch_array()) {
				$rere[$row['pageID']] = new Page($row['pageID']);
			}
			
			return $rere;
        }
		
		
	
		
		function print_issue() {
			$output = date('M d, Y', strtotime($this->news_date));
			
			return '<span>' . $this->title . ' ' . $output . '</span>' . ' <br/>   <i>Volume: ' . $this->volume . ', Issue: ' . $this->issue . '</i>';
		}
		
		function get_volume() {
			return $this->volume;
		}
		
		function get_issue() {
			return $this->issue;
		}
		
		function is_private() {
			return ($this->issue == 'yes') ? true : false;
		}
		
		function get_news_date_formatted() {
			return date('M d, Y', strtotime($this->news_date));
		}
		
		function get_a_type() {
			return $this->news_type;
		}
        
        function available_pages() {
            $rere = array();
            $sql = "SELECT pageID, filename FROM pages";
            $result = @$mysqli->query($sql)   or die('Query avail failed: ' . $mysqli->error);
            $count=@$result->num_rows;
             if($count < 1)
                return null;
                
            while($row = @$result->fetch_assoc()) {
                $rere[$row['pageID']] = $row['filename'];
            }
            
            return $rere;
        }
        
        
        function fill_content_array($with_active=false) {
                require_once(CLASS_DIR . '/content.class.php');
                $rere = array();
                $sql = "SELECT * FROM content WHERE pageID = $this->pageID";
                if($with_active)
                    $sql .= " AND is_active = 'yes'";
                    
                $sql .= " ORDER BY date_added DESC";
                $result = @$mysqli->query($sql);
                $count=@$result->num_rows;
                if($count < 1)
                    $this->contents = null;
                else    
                    $this->contents = array();
                    
                while($row = @$result->fetch_assoc()) {
                    $this->contents[] = new Content($row['contentID']);
                }
        }
		
		function display_search_results($search_term) {
			$out = '';
			
			$sql = "SELECT * FROM content WHERE content LIKE '%$search_term%'";
			$result = @$mysqli->query($sql);
            $count=@$result->num_rows;
            if($count < 1) {
				$terms = explode(" ", $search_term);
				foreach($terms as $term) {
					$sql .= " OR content LIKE '%$term%'";
				}
			} 
			$result = @$mysqli->query($sql);
            $count=@$result->num_rows;
			if($count < 1) {
				$out = '<p>Sorry, the search term <i>' . $search_term . '</i> did not return with any results.</p>';
			} else {
				$out .= '<h4>Your search term \'<i>' . $search_term . '</i>\' returned <b>' . $count . '</b> results.</h4>';
				while($row = @$result->fetch_assoc()) {
					$pp = new Page($row['pageID']);
					$out .= '
						<p>• <a href="' . URL . '/' . $pp->filename . '"><b>' . $pp->title . '</b></a></p>';
				}
			}
			
			echo $out;
		
		}
		
		function build_navigation($loggedin=false) {
			$out = '';
		
			$types = $this->get_types($loggedin);
			
			foreach($types as $id => $type) {
				$out .= '<a href="#" class="' . $this->format_type($type) .' sub_nav">' . $type . '</a> 
				<div class="sub_nav">
					';
				
				$sql = "SELECT * FROM pages WHERE news_type=$id AND volume>0 AND issue != 'yes' ORDER BY volume DESC";
				$result = @$mysqli->query($sql);
				while($row = @$result->fetch_array()) {
					$out .= '<a href="' . URL . '/' . $row['filename'] . '">' . $row['title'] . '</a>';
				}
				$out .= '</div>';
			
			}		
			
			return $out;
		}
		
		function build_side_navigation() {
			$out = '';
		
		$sql = "SELECT * FROM pages WHERE news_type=$this->news_type ORDER BY pageID";
		//ORDER BY title ASC";
				$result = @$mysqli->query($sql);
				while($row = @$result->fetch_array()) {
					$out .= '<a href="' . URL . '/' . $row['filename'] . '"';
					if($this->filename == $row['filename'])
						$out .= ' class="active"';
					$out .= '>' . $row['title'] . '</a>';
				}
		
			return $out;
		}
		
		function format_type($type) {
			return strtolower(str_replace('/','',str_replace(' ', '_',$type)));
		}
		
		
		function get_types($loggedin=false) {
			require_once(CONNECTION);
			$rere = array();
			$sql = "SELECT * FROM types";    
			if(!$loggedin)
				$sql .= " WHERE typeID != 10";
				
			$result = @$mysqli->query($sql);
			while($row = @$result->fetch_array()) {
				$rere[$row['typeID']] = $row['type'];
			}
			return $rere;
		}
		
		function printSubNavigation() {
			//coming one day.
		}
		
		function get_side_images() {
			
			$c = 1;
			$ret = '';
			if($this->issue != 'no') {
				$ret = '<div class="side_images">';
				$img_parts = explode(".", $this->issue);
				//$ret .= $img_parts[0];
				 while(file_exists(DIRR . '/images/top/' . $img_parts[0] . $c . '.jpg')) {
					$ret .= '<img src="' . URL . '/images/top/' . $img_parts[0] . $c . '.jpg" class="side_image" /><br/><br/>';
					$c++;
				} 
				$ret .= '</div>';
			} 
			
			return $ret;
			
		}
        
        function printPage() {
            $this->fill_content_array(true);
            
			$dd = date('M d, Y', strtotime($this->news_date));
			
			if(!file_exists(DIRR . '/images/top/' . $this->issue)) {
				$this->issue = 'building_holder.jpg';
			}
			
			echo '<div id="theRight">
			
				<img src="' . URL . '/images/top/' . $this->issue . '" class="body_header" />
						<div id="theWords">
				';


			if($this->title != '')	
				echo '<h2>' . $this->title . '</h2>';
			

            if(empty($this->contents)) {
                echo '<p><b>No page content yet!</b></p>';
            } else {
                    
                    foreach($this->contents as $content) {            
                        echo '
                            <div class="aContent">
                        ';
                
                        echo $content->getField('content');                
                
                        echo '                    
                            </div>
                        ';
                    }
            }
			
			echo '
				
						</div>
			</div>
			';
        
        }
        
        function print_form() {
                // indeed.
                require_once(FORM_MAKER);
				require_once(CONSTANTS);
                
				$types = $this->get_types();
				
				$weights = array('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25');
				
                echo '<div id="form_box">';
				if($this->pageID != 0)
					echo '<h2>Edit Page: <i>' . $this->name . '</i></h2>';
				else
					echo '<h2>Create New Page</h2>';
					
                
                echo '<form name="page_form" action="' . ADMIN_URL . '/index.php" method="post">';
                
                input_hidden_print('m', 'pages');
                input_hidden_print('page_save','yes');
                input_hidden_print('page', $this->pageID);
                input_hidden_print('pageID', $this->pageID);
                
				echo '<div class="fform">';
                input_text_print('Reference Name', 'name',$this->name);
				echo '</div>
					<div class="fform">';
                input_text_print('Filename', 'filename', $this->filename);
                echo '</div>
					<div class="fform">';
                input_text_print('Title', 'title', $this->title);
				echo '<br/>';
                echo '       <span class="sub_input">(This is the title that comes up at the top of the page)</span>';
				echo '</div>
					<div class="fform">';
				input_text_print('Date Added', 'news_date', $this->news_date, 'dater');
                
				echo '</div>
					<div class="fform">';
				input_text_print('Top Image', 'issue', $this->issue);
	//			one_check_box('Publish to Home Page?','issue','yes',(($this->issue=='yes') ? 1 : 0));
				//echo '       <span class="sub_input">(check if page will require signin)</span>';
				echo '</div>
					<div class="fform">';
				//one_check_box('Side Navigation?','volume','yes',(($this->volume=='yes') ? 1 : 0));
				//select_print('Side Navigation', 'volume', $weights, $this->volume);
				//echo '       <br/>(Select the weight of this page on the side navigation. Pick \'0\' to not display in the side navigation. The higher the number, the higher it will appear in the navigation)';
				//echo '</div>
//					<div class="fform">';
				select_print('Category', 'news_type', $types, $this->news_type);
				echo '</div>
					<div class="fform">';
				echo '<br/>';
			
                submit_print('submit_page', 'Save Page', 'nice_pos');                
                
                echo '       <span class="sub_input"><a href="' . ADMIN_URL . '?m=pages&page=' . $this->pageID . '&action=view">back to list</a></span>';
				echo '</div>';
                echo '</form>';
                echo '</div>';
        }
        
        function print_details() {
            echo '<h2>Page : ' . $this->title . '</h2>';
            echo '<p>
            <i>Reference Name</i> : <b>' . $this->name . '</b><br/>
            <i>Filename</i> : '. $this->filename . ' | <a href="' . ADMIN_URL . '/index.php?m=pages&page=' . $this->pageID . '&action=edit">edit page details</a> | <a href="' . ADMIN_URL . '/index.php?m=pages&page=' . $this->pageID . '&action=preview" target="_blank">preview</a> | <a href="' . ADMIN_URL . '/index.php?m=pages&page=' . $this->pageID . '&action=remove" class="remover">delete</a> | <a href="' . ADMIN_URL . '/index.php?m=content&action=new&page=' . $this->pageID . '">Add Content</a>';
			
			if($this->volume != 0)
				echo '<br/>-- <i>this page appears in the side navigation</i> --';
			
			if($this->issue == 'yes')
				echo '<br/>-- <b><i> this published to the homepage </i></b> --';
			
			echo '</p>
			';
            echo '
            <hr />
            <p>Contents : </p>
            ';
             echo '<div class="contents head">
                                <div class="link">Content</div>
                                <div class="check">Date Added</div>
                                <div class="check">Is Active?</div>
                        </div>
                ';
            
            if(empty($this->contents)) {
                echo '<p><b>No page content yet!</b> | <a href="' . ADMIN_URL . '/index.php?m=content&action=new&page=' . $this->pageID . '">Add  New Content</a></p>';
            } else {
               
                $alt = 0;
                foreach($this->contents as $content) {
                    echo '<div class="contents' . (($alt%2!=0) ? ' alt' : '') . '">';
                    echo '
                        <div class="link">';
                    echo '<a href="' . ADMIN_URL . '?m=content&content=' . $content->getField('contentID') . '&action=view">' . $content->getField('ref_name') . ' • ' . $content->getField('title') . ' </div>
                    <div class="check">
                        ' . date('d/m/Y', strtotime($content->getField('date_added'))) . '</a>
                        </div>';
                     echo '<div class="check">';
                    echo '<input type="checkbox" name="is_active[' . $content->getField('contentID') . ']"';
                    if($content->getField('is_active') == 'yes')
                        echo ' checked="checked"';
                    echo '>';
                    echo '</div>';
                    echo '<div class="smaller">
                                  <a href="' . ADMIN_URL . '?m=content&content=' . $content->getField('contentID') . '&action=edit">edit</a> 
                                </div>';
                    echo '</div>';
                   
                    
   
                    $alt++;
                }
            }
        }
                
      

	}

?>
