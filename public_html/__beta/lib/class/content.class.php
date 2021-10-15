<?
	class Content {

        var $contentID;
        var $pageID;
        var $ref_name;
        var $title;
        var $content;
        var $date_added;
        var $is_active;


		function Content($var="") {
			if (is_numeric($var) && ($var)) {
                    require_once(CONNECTION);
				$sql = "SELECT * FROM content WHERE contentID = $var";
                     $result = @$mysqli->query($sql);
				$row = @$result->fetch_array();
				$this->makeFromRow($row);
			} else {
				$this->makeFromRow($var);
			}
		}

		function makeFromRow($row="") {
                $this->contentID =                     isset($row["contentID"])    ?    $row["contentID"]	    :    ($this->contentID  ?  $this->contentID : 0);
                $this->pageID =                 isset($row["pageID"])    ?    $row["pageID"]	    :    ($this->pageID  ?  $this->pageID : '');
                $this->ref_name =                   isset($row["ref_name"])    ?    $row["ref_name"]	    :    ($this->ref_name  ?  $this->ref_name : '');
                $this->title =                  isset($row["title"])    ?    $row["title"]	    :    ($this->title  ?  $this->title : '');
                $this->content =                  isset($row["content"])    ?    $row["content"]	    :    ($this->content  ?  $this->content : '');
                $this->date_added =                  isset($row["date_added"])    ?    $row["date_added"]	    :    ($this->date_added  ?  $this->date_added : '0000-00-00');
                $this->is_active =                  isset($row["is_active"])    ?    $row["is_active"]	    :    ($this->is_active  ?  $this->is_active : 'no');
            }

		function Save() {
                require_once(CONNECTION);
                foreach($this as $key => $value) {
                    if(is_string($value)) {
                        $this->$key = $this->clean_input($value);
                    }
                }

			if ($this->contentID) {

				$sql = "UPDATE content SET"
					. " pageID = '$this->pageID',"
                    . " ref_name = '$this->ref_name',"
                    . " title = '$this->title', content='$this->content', date_added='$this->date_added', is_active='$this->is_active'"
                    . " WHERE contentID         = $this->contentID";

				$result = @$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);

			} else {

				$sql = "INSERT INTO content"
					        . " (contentID,"            
                            . " pageID,"         
                            . " ref_name,"          
                            . " title, content, date_added, is_active)"  
					. " VALUES"
                        . " ($this->contentID,"            
                        . " '$this->pageID',"         
                        . " '$this->ref_name',"          
                        . " '$this->title', '$this->content', '$this->date_added', '$this->is_active')";

				$result = @$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);

				$this->contentID = $mysqli->insert_id;

			}
		}

		function Delete() {
                require_once(CONNECTION);
			$sql = "DELETE FROM content WHERE contentID = $this->contentID";
			$result = @$mysqli->query($sql);
		}
        
        function getField($field) {
            return stripslashes($this->$field);
        }
        
        function setField($field, $value) {
            $this->$field = $value;
        }
        
     
        
        function update_from_post($post) {
            foreach($post as $key => $value) {
                if(array_key_exists($key, $this)) {
                    $this->setField($key, $this->clean_input($value));
                }
            }
        }
        
        function clean_input($input) {
            //$input=strip_tags($input);
            //$input=str_replace("#","%23",$input);
            //$input=str_replace("'","`",$input);
            $input=trim($input);
             if (ini_get('magic_quotes_gpc')) { 
                $input = stripslashes($input); 
            } 
            $input=$mysqli->real_escape_string($input);
            return $input; 
        }
        
        function print_details() {
            require_once(CLASS_DIR . '/page.class.php');
            $pager = new Page($this->pageID);
            
            
            echo '<h2>Page : <u>' . $this->ref_name . '</u></h2>';
            echo '<p>This is for the content on the: <i>' . $pager->getField('title') . '</i>.<br/><br/>
                            <a href="' . ADMIN_URL . '?m=content&content=' . $this->getField('contentID') . '&action=edit">edit this content</a> | <a href="' . ADMIN_URL . '/index.php?m=content&content=' . $this->contentID. '&action=remove" class="remover">delete</a> | <a href="' . ADMIN_URL . '?m=pages&page=' . $this->pageID . '&action=view">back</a><br/>
                            <b>Reference Name</b>: ' . $this->ref_name . '<br/>
                            <b>Content Title</b>: ' . $this->title . '<br/>
                            <b>Date Added</b>: ' . date('m/d/Y', strtotime($this->date_added)) . '<br/>
                            <b>Is Active</b>? ' . $this->is_active . '
            </p>';
            
         /*   echo '<div id="the_content_preview_box">
                            ' . $this->content .'            
            </div>'; */
        
        }
        
        function print_form($page_id=0) {
            require_once(FORM_MAKER);
    /*
		require_once(ADMIN_LIB . '/fckeditor/fckeditor.php');
            
            $ed = new FCKeditor('FCKeditor1');
            //$ed->BasePath = '/jbnj_admin/lib/fckeditor/';
			$ed->BasePath = FCK_BASEPATH;
            $ed->Height = '650';
            //$ed->EditorAreaCSS = '/rrb.css';
			//$ed->Config['EditorAreaCSS'] = STYLE_URL . '/style.css'; 
            $ed->Value = $this->content;
			
	*/
            
            echo '<div id="form_box">';
            echo '<form name="content_form" action="' . ADMIN_URL . '/index.php" method="post">';
            
            input_hidden_print('m','content');
            input_hidden_print('content_save','yes');
            input_hidden_print('content',$this->contentID);
            input_hidden_print('contentID',$this->contentID);
            
            if($this->pageID != 0)
                input_hidden_print('pageID', $this->pageID);
            else 
                input_hidden_print('pageID', $page_id);
            
            input_text_print('Ref. Name', 'ref_name', $this->ref_name);
            input_text_print('Title', 'title', $this->title);
            $dd = ($this->date_added != '0000-00-00') ? date('Y-m-d', strtotime($this->date_added)) : date('Y-m-d');
            input_text_print('Date Added','date_added', $dd,'dater');
            echo '
                
            ';
            one_check_box('Is Active?','is_active','yes',(($this->is_active=='yes') ? 1 : 0));
            
            //$ed->Create();
			
			echo '<div class="fform_yes">
				<textarea name="FCKeditor1" id="the_editor">';
			
			echo $this->content;
				
			echo '</textarea>
			
			</div> ';
            
            submit_print('submit_page', 'Save Content', 'nice_pos');
            
            echo '       <span class="sub_input"><a href="' . ADMIN_URL . '?m=pages&page=';
            if($this->pageID != 0)
                echo $this->pageID;
            else    
                echo $page_id;
                 
            echo '&action=view">back to page info</a></span>';
            
            
            echo '</form>';
            echo '</div>';
            
            
            
            
        
        }
        
        
            
        
      

	}

?>
