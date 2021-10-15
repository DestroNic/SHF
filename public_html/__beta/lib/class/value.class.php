<?
	class CValues {

        var $cvID;
        var $cfID;
        var $blastID;
        var $value;
        var $field;


		function CValues($var="") {
			if (is_numeric($var) && ($var)) {
                    require_once(CONNECTION);
				$sql = "SELECT * FROM hs_values WHERE cvID = $var";
                     $result = @$mysqli->query($sql);
				$row = @$result->fetch_array();
				$this->makeFromRow($row);
			} else {
				$this->makeFromRow($var);
			}
		}

		function makeFromRow($row="") {
                $this->cvID =                     isset($row["cvID"])    ?    $row["cvID"]	    :    ($this->cvID  ?  $this->cvID : 0);
                $this->cfID =                 isset($row["cfID"])    ?    $row["cfID"]	    :    ($this->cfID  ?  $this->cfID : 0);
                $this->blastID =                   isset($row["blastID"])    ?    $row["blastID"]	    :    ($this->blastID  ?  $this->blastID : 0);
                $this->value =                  isset($row["value"])    ?    $row["value"]	    :    ($this->value  ?  $this->value : '');
				
				if(isset($row['field'])) {
					$this->field =                  isset($row["field"])    ?    $row["field"]	    :    ($this->field  ?  $this->field : '');
				} else {
					$sql2 = "SELECT field FROM hs_custom_fields WHERE cfID = $this->cfID";
					$result = @$mysqli->query($sql);
					$row2 = @$result->fetch_array();
					$this->field = $row2['field'];
				}
                
            }

		function Save() {
                require_once(CONNECTION);
                foreach($this as $key => $value) {
                    if(is_string($value)) {
                        $this->$key = $this->clean_input($value);
                    }
                }

			if ($this->cvID) {

				$sql = "UPDATE hs_values SET"
					. " cfID = '$this->cfID',"
                    . " blastID = '$this->blastID',"
                    . " value = '$this->value', hs_values='$this->hs_values', date_added='$this->date_added', is_active='$this->is_active'"
                    . " WHERE cvID         = $this->cvID";

				$result = @$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);

			} else {

				$sql = "INSERT INTO hs_values"
					        . " (cvID,"            
                            . " cfID,"         
                            . " blastID,"          
                            . " value, hs_values, date_added, is_active)"  
					. " VALUES"
                        . " ($this->cvID,"            
                        . " '$this->cfID',"         
                        . " '$this->blastID',"          
                        . " '$this->value', '$this->hs_values', '$this->date_added', '$this->is_active')";

				$result = @$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);

				$this->cvID = $mysqli->insert_id;

			}
		}

		function Delete() {
                require_once(CONNECTION);
			$sql = "DELETE FROM hs_values WHERE cvID = $this->cvID";
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
            $pager = new Page($this->cfID);
            
            
            echo '<h2>Page : <u>' . $this->blastID . '</u></h2>';
            echo '<p>This is for the hs_values on the: <i>' . $pager->getField('value') . '</i>.<br/><br/>
                            <a href="' . ADMIN_URL . '?m=hs_values&hs_values=' . $this->getField('cvID') . '&action=edit">edit this hs_values</a> | <a href="' . ADMIN_URL . '/index.php?m=hs_values&hs_values=' . $this->cvID. '&action=remove" class="remover">delete</a> | <a href="' . ADMIN_URL . '?m=pages&page=' . $this->cfID . '&action=view">back</a><br/>
                            <b>Reference Name</b>: ' . $this->blastID . '<br/>
                            <b>CValues value</b>: ' . $this->value . '<br/>
                            <b>Date Added</b>: ' . date('m/d/Y', strtotime($this->date_added)) . '<br/>
                            <b>Is Active</b>? ' . $this->is_active . '
            </p>';
            
         /*   echo '<div id="the_hs_values_preview_box">
                            ' . $this->hs_values .'            
            </div>'; */
        
        }
        
        function print_form($page_id=0) {
            require_once(FORM_MAKER);
            require_once(ADMIN_LIB . '/fckeditor/fckeditor.php');
            
            $ed = new FCKeditor('FCKeditor1');
            //$ed->BasePath = '/jbnj_admin/lib/fckeditor/';
			$ed->BasePath = FCK_BASEPATH;
            $ed->Height = '650';
            //$ed->EditorAreaCSS = '/rrb.css';
            $ed->Value = $this->hs_values;
            
            echo '<div id="form_box">';
            echo '<form name="hs_values_form" action="' . ADMIN_URL . '/index.php" method="post">';
            
            input_hidden_print('m','hs_values');
            input_hidden_print('hs_values_save','yes');
            input_hidden_print('hs_values',$this->cvID);
            input_hidden_print('cvID',$this->cvID);
            
            if($this->cfID != 0)
                input_hidden_print('cfID', $this->cfID);
            else 
                input_hidden_print('cfID', $page_id);
            
            input_text_print('Ref. Name', 'blastID', $this->blastID);
            input_text_print('value', 'value', $this->value);
            $dd = ($this->date_added != '0000-00-00') ? date('Y-m-d', strtotime($this->date_added)) : date('Y-m-d');
            input_text_print('Date Added','date_added', $dd);
            echo '
                <span class="sub_input">(yyyy-mm-dd)</span>
            ';
            one_check_box('Is Active?','is_active','yes',(($this->is_active=='yes') ? 1 : 0));
            
            $ed->Create();
            
            submit_print('submit_page', 'Save CValues', 'nice_pos');
            
            echo '       <span class="sub_input"><a href="' . ADMIN_URL . '?m=pages&page=';
            if($this->cfID != 0)
                echo $this->cfID;
            else    
                echo $page_id;
                 
            echo '&action=view">back to page info</a></span>';
            
            
            echo '</form>';
            echo '</div>';
            
            
            
            
        
        }
        
        
            
        
      

	}

?>
