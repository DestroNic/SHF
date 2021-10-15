<?php
	class HsTemplate {

        var $tID;
        var $title;
        var $thumbnail;
		var $layout;
		
		var $custom_fields = false; 
        


		function HsTemplate($var="") {
			if (is_numeric($var) && ($var)) {
                    require_once(CONNECTION);
				$sql = "SELECT * FROM hs_template WHERE tID = $var";
                     $result = @$mysqli->query($sql);
				$row = @$result->fetch_array();
				$this->makeFromRow($row);
                
			} else {
				$this->makeFromRow($var);
				
				
			}
            if($this->tID==0 && isset($var['more_field'])) {
				$this->new_fields_fill($var['more_field']);
			} else {
				$this->fill_content_array();
				if(isset($var['more_field']))
					$this->new_fields_fill($var['more_field']);
			}
		}

		function makeFromRow($row="") {
                $this->tID =                     isset($row["tID"])    ?    $row["tID"]	    :    ($this->tID  ?  $this->tID : 0);
                $this->title =                 isset($row["title"])    ?    $row["title"]	    :    ($this->title  ?  $this->title : '');
                $this->thumbnail =                   isset($row["thumbnail"])    ?    $row["thumbnail"]	    :    ($this->thumbnail  ?  $this->thumbnail : '');
				$this->layout =                  isset($row["layout"])    ?    $row["layout"]	    :    ($this->layout  ?  $this->layout : '');
            }

		function Save() {
                require_once(CONNECTION);
                foreach($this as $key => $value) {
                     if(is_string($value)) {
						if($key != 'layout') {
							$this->$key = $this->clean_input($value);
						}
                    }
                }

			if ($this->tID) {

				$sql = "UPDATE hs_template SET"
					. " title = '$this->title',"
                    . " thumbnail = '$this->thumbnail',"
                    . "  layout='$this->layout'"
                    . " WHERE tID         = $this->tID";

				$result = @$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);

			} else {

				$sql = "INSERT INTO hs_template"
					        . " (tID,"            
                            . " title,"         
                            . " thumbnail,"          
                            . " layout)"  
					. " VALUES"
                        . " ($this->tID,"            
                        . " '$this->title',"         
                        . " '$this->thumbnail',"          
                        . " '$this->layout')";

				$result = @$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);

				$this->tID = $mysqli->insert_id;
				
			}
			
			if($this->custom_fields) {
				foreach($this->custom_fields as $a_field) {
					if($a_field[0] == 0) {
						$sql = "INSERT INTO hs_custom_fields (tID,field) VALUES ($this->tID, '" . $a_field[1] . "')";
					} else {
						$sql = "UPDATE hs_custom_fields SET field='" . $a_field[1] . "' WHERE cfID = " . $a_field[0];
					}
					$result = @$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);
				}
			}

		}

		function Delete() {
                require_once(CONNECTION);
			$sql = "DELETE FROM hs_template WHERE tID = $this->tID";
			$result = @$mysqli->query($sql);
		}
        
        function getField($field) {
            return $this->$field;
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
		
		function new_fields_fill($fields) {
			if(is_array($fields)) {
				foreach($fields as $field) {
					$this->custom_fields[] = array(0,$field,'text');
				}
			}
		}
		
		function fill_content_array() {
			$sql = "SELECT * FROM hs_custom_fields WHERE tID = $this->tID";
			$result = @$mysqli->query($sql) or die('fill_content_error:' . $mysqli->error);
            $count=@$result->num_rows;
			if($count < 1)
				$this->custom_fields = false;
			else {
				$this->custom_fields = array();
				while($row = @$result->fetch_assoc()) {
					$this->custom_fields[] = array($row['cfID'],$row['field'],$row['type']);
				}
			}
		}
            
        function get_list() {
                $rere = array();
            	$sql = "SELECT * FROM hs_template ORDER BY title ASC";
                $result = @$mysqli->query($sql);
                $count=@$result->num_rows;
                if($count < 1)
                    return false;
                    
                while($row = @$result->fetch_array()) {
                    $rere[$row['tID']] = $row['title'];
                }
                return $rere;
        }
		
		function get_better_list($type=false) {
			$rere = array();
			$sql = "SELECT * FROM hs_template";
			if($type)
				$sql .= " WHERE layout=$type";
			$sql .= " ORDER BY title";
			$result = @$mysqli->query($sql)  or die('get_better failed: ' . $mysqli->error);
			$count=@$result->num_rows;
			if($count < 1)
				return false;
				
			while($row = @$result->fetch_array()) {
				$rere[$row['tID']] = new HsTemplate($row['tID']);
			}
			
			return $rere;
        }
		
		
	
		
		function get_event_start_time() {
			return $this->event_start_time;
		}
		
		function get_event_end_time() {
			return $this->event_end_time;
		}
		
		function is_private() {
			return ($this->event_end_time == 'yes') ? true : false;
		}
		
		function get_rsvp_date_formatted() {
			return date('M d, Y', strtotime($this->rsvp_date));
		}
		
		function get_a_type() {
			return $this->layout;
		}
        
        
        function print_form() {
                // indeed.
                require_once(FORM_MAKER);
				require_once(CONSTANTS);
                
                echo '<div id="form_box">';
				if($this->tID != 0)
					echo '<h2>Edit Template: <i>' . $this->title . '</i></h2>';
				else
					echo '<h2>Create New Template</h2>';
					
                
                echo '<form thumbnail="page_form" action="' . ADMIN_URL . '/index.php" method="post">';
                
                input_hidden_print('m', 'template');
                input_hidden_print('template_save','yes');
                input_hidden_print('template', $this->tID);
                input_hidden_print('tID', $this->tID);
                
				echo '<div class="fform">';
						input_text_print('Title', 'title', $this->title);
				echo '</div>
					<div class="fform">';
						input_text_print('Thumbnail', 'thumbnail',$this->thumbnail);
				echo '</div>';
				
				if($this->custom_fields) {
					foreach($this->custom_fields as $field) {
						echo '
							<div class="fform">';
						input_text_print('Field:', 'field[' . $field[0] .']', $field[1]);
						echo '</div>';
					}
				}
				echo'
					<div id="more_fields1"></div>
					<div class="fform"><a href="plus" class="add_field">add another field</a></div>';
				
				echo '				
					<div class="fform">';
						textarea_print('Layout', 'layout', $this->layout, '12','35');
				
				
				
				echo'
					<div class="fform">';
				echo '<br/>';
			
                submit_print('submit_page', 'Save Template', 'nice_pos');                
                
				if($this->tID != 0)
					echo '       <span class="sub_input"><a href="' . ADMIN_URL . '?m=template&template=' . $this->tID . '&action=view">back to details</a></span>';
				else
					echo '       <span class="sub_input"><a href="' . ADMIN_URL . '?m=template">back to list</a></span>';
				echo '</div>';
                echo '</form>';
                echo '</div>';
        }
        
        function print_details() {
            echo '<h2>Template : ' . $this->title . '</h2>';
            echo '<p>
						<i><b>Thumbnail</b></i> : <b>' . $this->thumbnail . '</b><br/>
						<a href="' . ADMIN_URL . '/index.php?m=template&template=' . $this->tID . '&action=edit">edit template</a> | <a href="' . ADMIN_URL . '/index.php?m=template&template=' . $this->tID . '&action=remove" class="remover">delete</a><br/>
				
				<img src="' . URL . '/thumbnails/' . $this->thumbnail . '" /><br/><br/>
				<textarea disabled="disabled" cols="30" rows="20">' . $this->layout . '</textarea><br/><br/>
            </p>
			';
            echo '
            <hr />
            <h3>Custom Fields : </h3>
            <p>';
			if($this->custom_fields) {
				foreach($this->custom_fields as $field) {
					echo '• <i>' . $field[0] . '</i> - <b>' . $field[1] . '</b><br/>';
					
				}
			} else {
				echo '<i>No Custom Fields!</i>';
			}
			echo '</p>';
			
           
        }
		
		function print_side_nav() {
			// this will print out the side navigation.
			$list = $this->get_list();
			echo '<h4>Templates</h4>';
			if($list) {
				foreach($list as $i => $t) {
					echo '<a href="' . URL . '/index.php?m=template&action=view&template=' . $i . '">' . $t . '</a>';
				}
			}
			
			echo '<a href="' . URL . '/index.php?m=template&action=new" class="add_new"><b>Add New Template</b></a>';
		}

	}

?>
