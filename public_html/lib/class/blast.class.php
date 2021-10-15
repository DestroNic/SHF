<?php
	class HsBlast {
		

        var $blastID;
		var $tID;
        var $title;
        var $event_date;
		var $event_start_time;
		var $event_end_time;
		var $event_address1;
		var $event_address2;
		var $event_city;
		var $event_state;
		var $event_zip;
		var $rsvp_date;
		var $speaker_id;
		var $created_by;
		var $is_approved;
		
		
		var $custom_values = false; 
        


		function HsBlast($var="") {
			if (is_numeric($var) && ($var)) {
                    require_once(CONNECTION);
				$sql = "SELECT * FROM hs_blasts WHERE blastID = $var";
                     $result = @$mysqli->query($sql);
				$row = @$result->fetch_array();
				$this->makeFromRow($row);
                
			} else {
				$this->makeFromRow($var);
				
			}
            
		}

		function makeFromRow($row="") {
			require_once(CLASS_DIR . '/value.class.php');
                $this->blastID =                     isset($row["blastID"])    ?    $row["blastID"]	    :    ($this->blastID  ?  $this->blastID : 0);
                $this->tID =                  isset($row["tID"])    ?    $row["tID"]	    :    ($this->tID  ?  $this->tID : 0);
				$this->title =                 isset($row["title"])    ?    $row["title"]	    :    ($this->title  ?  $this->title : '');
                $this->event_date =                   isset($row["event_date"])    ?    $row["event_date"]	    :    ($this->event_date  ?  $this->event_date : '');
				$this->event_start_time =                  isset($row["event_start_time"])    ?    $row["event_start_time"]	    :    ($this->event_start_time  ?  $this->event_start_time : '');
				$this->event_end_time =                  isset($row["event_end_time"])    ?    $row["event_end_time"]	    :    ($this->event_end_time  ?  $this->event_end_time : '');
				$this->event_address1 =                  isset($row["event_address1"])    ?    $row["event_address1"]	    :    ($this->event_address1  ?  $this->event_address1 : '');
				$this->event_address2 =                  isset($row["event_address2"])    ?    $row["event_address2"]	    :    ($this->event_address2  ?  $this->event_address2 : '');
				$this->event_city =                  isset($row["event_city"])    ?    $row["event_city"]	    :    ($this->event_city  ?  $this->event_city : '');
				$this->event_state =                  isset($row["event_state"])    ?    $row["event_state"]	    :    ($this->event_state  ?  $this->event_state : '');
				$this->event_zip =                  isset($row["event_zip"])    ?    $row["event_zip"]	    :    ($this->event_zip  ?  $this->event_zip : '');
				$this->rsvp_date =                  isset($row["rsvp_date"])    ?    $row["rsvp_date"]	    :    ($this->rsvp_date  ?  $this->rsvp_date : '');
				$this->speaker_id =                  isset($row["speaker_id"])    ?    $row["speaker_id"]	    :    ($this->speaker_id  ?  $this->speaker_id : 0);
				$this->created_by =                  isset($row["created_by"])    ?    $row["created_by"]	    :    ($this->created_by  ?  $this->created_by : 0);
				$this->is_approved =                  isset($row["is_approved"])    ?    $row["is_approved"]	    :    ($this->is_approved  ?  $this->is_approved : '');
				
				if($this->blastID != 0) {
					$this->fill_custom_values_from_blast_id();
				} else if($this->tID != 0) {
					$this->fill_custom_values_from_t_id();
				} else {
					$this->custom_values = false;
				}
				
				
            }

		function Save() {
                require_once(CONNECTION);
                foreach($this as $key => $value) {
                     if(is_string($value)) {
						if($key != 'event_start_time') {
							$this->$key = $this->clean_input($value);
						}
                    }
                }

			if ($this->blastID) {

				$sql = "UPDATE hs_blasts SET"
					. " title = '$this->title',"
                    . " event_date = '$this->event_date',"
                    . "  event_start_time='$this->event_start_time'"
                    . " WHERE blastID         = $this->blastID";

				$result = @$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);

			} else {

				$sql = "INSERT INTO hs_blasts"
					        . " (blastID,"            
                            . " title,"         
                            . " event_date,"          
                            . " event_start_time)"  
					. " VALUES"
                        . " ($this->blastID,"            
                        . " '$this->title',"         
                        . " '$this->event_date',"          
                        . " '$this->event_start_time')";

				$result = @$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);

				$this->blastID = $mysqli->insert_id;
				
			}
			
			if($this->custom_values) {
				foreach($this->custom_values as $a_field) {
					if($a_field->cvID == 0) {
						$sql = "INSERT INTO hs_values (cfID, blastID, value) VALUES ($a_field->cfID, $this->blastID, '" . $a_field->value . "')";
					} else {
						$sql = "UPDATE hs_values SET cfID = " . $a_field->cfID . ", blastID=" . $this->blastID . ", value='" . $a_field->value . "' WHERE cvID = " . $a_field->cvID;
					}
					$result = @$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);
				}
			}

		}

		function Delete() {
                require_once(CONNECTION);
			$sql = "DELETE FROM hs_blasts WHERE blastID = $this->blastID";
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
					$this->custom_values[] = array(0,$field,'text');
				}
			}
		}
		
		function fill_custom_values_from_blast_id() {
			// fill with values
			require_once(CLASS_DIR . '/value.class.php');
			
			$sql = "SELECT * FROM hs_values WHERE blastID = $this->blastID";
			$result = @$mysqli->query($sql) or die('fill_content_error:' . $mysqli->error);
            $count=@$result->num_rows;
			if($count < 1)
				$this->custom_values = false;
			else {
				$this->custom_values = array();
				while($row = @$result->fetch_assoc()) {
					$this->custom_values[$row['cvID']] = new CValues($row);
				}
			}			
		}
		
		function fill_custom_values_from_t_id() {
			// fill from template no value
			require_once(CLASS_DIR . '/value.class.php');
			
			$sql = "SELECT * FROM hs_custom_fields WHERE tID = $this->tID";
			$result = @$mysqli->query($sql) or die('fill_content_error:' . $mysqli->error);
            $count=@$result->num_rows;
			if($count < 1)
				$this->custom_values = false;
			else {
				$this->custom_values = array();
				while($row = @$result->fetch_assoc()) {
					$vv = array('field' => $row['field']);
					$this->custom_values[] = new CValues($vv);
				}
			}			
		}
		
		function fill_content_array() {
			$sql = "SELECT * FROM hs_values WHERE blastID = $this->blastID";
			$result = @$mysqli->query($sql) or die('fill_content_error:' . $mysqli->error);
            $count=@$result->num_rows;
			if($count < 1)
				$this->custom_values = false;
			else {
				$this->custom_values = array();
				while($row = @$result->fetch_assoc()) {
					$this->custom_values[] = array($row['cfID'],$row['field'],$row['type']);
				}
			}
		}
            
        function get_list() {
                $rere = array();
            	$sql = "SELECT * FROM hs_blasts ORDER BY title ASC";
                $result = @$mysqli->query($sql);
                $count=@$result->num_rows;
                if($count < 1)
                    return false;
                    
                while($row = @$result->fetch_array()) {
                    $rere[$row['blastID']] = $row['title'];
                }
                return $rere;
        }
		
		function get_better_list() {
			$rere = array();
			$sql = "SELECT * FROM hs_blasts WHERE created_by = '" . $_COOKIE['jbnj_i'] . "' ORDER BY rsvp_date";
			$result = @$mysqli->query($sql)  or die('get_better failed: ' . $mysqli->error);
			$count=@$result->num_rows;
			if($count < 1)
				return false;
				
			while($row = @$result->fetch_array()) {
				$rere[$row['blastID']] = new HsBlast($row['blastID']);
			}
			
			return $rere;
        }
		
		function print_list() {
			$alt=1;
			$bbs= $this->get_better_list();
			echo '<div id="list">';
			
			if($bbs) {
				foreach($bbs as $i => $b) {
					echo '<div class="row';
					if($alt%2 == 0)
						echo ' off';
					echo '"><div class="title">' . $b->title . '</div>
						<div class="links"><a href="' . URL . '/index.php?m=blast&blast=' . $b->blastID . '&action=remove" class="remover">delete</a> | <a href="' . URL . '/index.php?m=blast&blast=' . $b->blastID . '&action=view">view</a> | <a href="' . URL . '/index.php?m=blast&blast=' . $b->blastID . '&action=edit">edit</a></div>
						</div>
						';
						
					$alt++;
				}
			} else {
				echo '<p class="alert">You do not have any blasts yet.</p>';
			}		
			
			
			
			echo '</div>';
			
			
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
			return $this->event_start_time;
		}
		
		function print_step($step) {
			echo '<p>';
			if(1 == $step)
				echo '<b>';
				
			echo '1. Select your template';
				
			if(1 == $step) {
				echo '</b><br/>';
				echo 'Please select a template from the right to continue';
			}			
			echo '</p>
				<p>';
			
			if(2 == $step) 
				echo '<b>';
			
			echo '2. Fill out required information';
			
			if(2 == $step) {
				echo '</b><br/>
					Fill out all fields. All fields are required to proceed.';
			}
			echo '</p>
				<p>';
				
			if(3 == $step) 
				echo '<b>';
			
			echo '3. Confirm';
			
			if(3 == $step) {
				echo '</b>
					';
			}
			echo '</p>
				<p>';
				
			if(4 == $step) 
				echo '<b>';
			
			echo '4. Complete!';
			
			if(4 == $step) {
				echo '</b>';
			}
			echo '</p>';
		}
		
		function set_template($template_id=false) {
			if($template_id) {
				$this->tID = $template_id;
				$this->fill_custom_values_from_t_id();
			} else {
				echo '<p class="error">i do not know why this would even happen.</p>';
			}
		}
		
		function print_confirmation() {
			if($this->tID  == 0) {
				echo '<h2>You must pick a template first</h2>';
				$this->print_template_selection();
				return;
			} 
			
			require_once(CONSTANTS);
			require_once(CLASS_DIR . '/template.class.php');
			$tt = new HsTemplate($this->tID);
			$outt = '<div class="fform"><h3>Selected Template: <span>' . $tt->title . '</span></h3></div>
					 <div class="fform"><b>Title:</b> ' . $this->title . '</div>
					 <div class="fform"><b>Event Date:</b> ' . date('M d, y', strtotime($this->event_date)) . '</div>
					 <div class="fform"><b>Event Start Time:</b> ' . $this->event_start_time . '</div>
					 <div class="fform"><b>Event End Time:</b> ' . $this->event_end_time . '</div>
					 <div class="fform"><b>Event Street Address:</b> ' . $this->event_address1 . '</div>
					 <div class="fform"><b>Event Address 2:</b> ' . $this->event_address2. '</div>
					 <div class="fform"><b>Event City:</b> ' . $this->event_city . '</div>
					 <div class="fform"><b>Event State:</b> ' . $this->event_state . '</div>
					 <div class="fform"><b>Event Zip:</b> ' . $this->event_zip . '</div>
					 <div class="fform"><b>RSVP Date:</b> ' . date('M d, y', strtotime($this->rsvp_date)) . '</div>
					 <div class="fform"><h3>Custom Fields for <span>' . $tt->title . '</span></h3></div>
				';
				if($this->custom_values) {
					foreach($this->custom_values as $value) {
						$outt .= '
							<div class="fform"><b>' . ucfirst(str_replace('_',' ',$value->field)) . ':</b> ' . $value->value . '</div>';
					}
				}
				
				$outt .= '<div class="fform"><a href="' . URL . '/index.php?m=blast&blast=' . $this->blastID . '&action=new&step=2" class="nice_butt cancel"><span><span>< Go Back & Edit</span></span></a> <a href="' . URL . '/index.php?m=blast&blast=' . $this->blastID . '&action=new&step=4" class="nice_butt continue"><span><span>Continue ></span></span></a></div>';
				echo $outt;
		}
        
        function print_form() {
                // indeed.
				if($this->tID  == 0) {
					echo '<h2>You must pick a template first</h2>';
					$this->print_template_selection();
					return;
				} 
				
                require_once(FORM_MAKER);
				require_once(CONSTANTS);
				require_once(CLASS_DIR . '/template.class.php');
				
				$tt = new HsTemplate($this->tID);
                
                echo '<div id="form_box">';
				if($this->blastID != 0)
					echo '<h2>Edit Blast: <i>' . $this->title . '</i></h2>';
				else
					echo '<h2>Create New Blast</h2>';
					
                
                echo '<form event_date="page_form" action="' . ADMIN_URL . '/index.php" method="post">';
                
                input_hidden_print('m', 'blast');
				if($this->blastID == 0) {
					input_hidden_print('action', 'new');
					input_hidden_print('step', '3');
				}
                input_hidden_print('blast_save','yes');
                input_hidden_print('blast', $this->blastID);
                input_hidden_print('blastID', $this->blastID);
				input_hidden_print('created_by', $_COOKIE['jbnj_i']);
				input_hidden_print('tID', $this->tID);
				
				echo '<div class="fform"><h3>Selected Template: <span>' . $tt->title . '</span></h3></div>';
				
                
				echo '<div class="fform">';
						input_text_print('Title', 'title', $this->title);
				echo '       <br/><span class="sub_input">This is for reference <b>only</b></span>
					</div>
					<div class="fform">';
						input_text_print('Event Date: ', 'event_date',$this->event_date,'dater');
				echo '</div>
					<div class="fform">
				';
					input_text_print('Event Start Time', 'event_start_time', $this->event_start_time);
					
				echo '</div>
					<div class="fform">
				';
					input_text_print('Event End Time', 'event_end_time', $this->event_end_time);
					
				echo '
							<br/><span class="sub_input">Enter times the same way you want them displayed<br/> (i.e. "7:00pm" or "6:30" or even "Five Forty-Five")</span>
						</div>
					<div class="fform">
				';
					input_text_print('Event Street', 'event_address1', $this->event_address1);
					
				echo '</div>
					<div class="fform">
				';
					input_text_print('Event Address 2', 'event_address2', $this->event_address2);
					
				echo '</div>
					<div class="fform">
				';
					input_text_print('Event City', 'event_city', $this->event_city);
					
				echo '</div>
					<div class="fform">
				';
					input_text_print('Event State', 'event_state', $this->event_state);
					
				echo '</div>
					<div class="fform">
				';
					input_text_print('Event Zip', 'event_zip', $this->event_zip);
					
				echo '</div>
					<div class="fform">
				';
					input_text_print('RSVP Date', 'rsvp_date', $this->rsvp_date,'dater');
					
				
				echo '</div>
				
				<div class="fform">
					<h3>Template Specific Fields:</h3>
				</div>
				
				';
				
				if($this->custom_values) {
					foreach($this->custom_values as $value) {
						echo '
							<div class="fform">';
						//input_text_print('Field:', 'field[' . $field[0] .']', $field[1]);
						input_text_print(ucfirst(str_replace('_',' ',$value->field)), 'value[' . $value->cfID .']', $value->value);
						echo '</div>';
					}
				}

				
				// put speaker in here if necessary
				
				
				
				echo'
					<div class="fform">';
				echo '<br/>';
			
                submit_print('submit_page', 'Save Template', 'nice_pos');                
                
				if($this->blastID != 0)
					echo '       <span class="sub_input"><a href="' . ADMIN_URL . 'index.php?m=blast&blast=' . $this->blastID . '&action=view">back to details</a></span>';
				else
					echo '       <span class="sub_input"><a href="' . ADMIN_URL . 'index.php?m=blast&action=new">select a different template</a></span>';
				echo '</div>';
                echo '</form>';
                echo '</div>';
        }
        
        function print_details() {
            echo '<h2>Template : ' . $this->title . '</h2>';
            echo '<p>
						<i><b>event_date</b></i> : <b>' . $this->event_date . '</b><br/>
						<a href="' . ADMIN_URL . '/index.php?m=template&template=' . $this->blastID . '&action=edit">edit template</a> | <a href="' . ADMIN_URL . '/index.php?m=template&template=' . $this->blastID . '&action=remove" class="remover">delete</a><br/>
				
				<img src="' . URL . '/event_dates/' . $this->event_date . '" /><br/><br/>
				<textarea disabled="disabled" cols="30" rows="20">' . $this->event_start_time . '</textarea><br/><br/>
            </p>
			';
            echo '
            <hr />
            <h3>Custom Fields : </h3>
            <p>';
			if($this->custom_values) {
				foreach($this->custom_values as $field) {
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
		
		function print_template_selection() {
			require_once(CLASS_DIR . '/template.class.php');
			$tt = new HsTemplate();
			
			$t_list = $tt->get_better_list();
			
			if($t_list) {
				foreach($t_list as $i => $t) {
					echo '<div class="template_box">
						<a href="' . URL . '/index.php?m=blast&action=new&step=2&template=' . $t->tID . '">
							<img src="' . URL . '/thumbnails/' . $t->thumbnail . '" width="150px" height="182px" /><br/>
							' . $t->title . '
						</a>							
					</div>
					';
				}
			} else {
				echo '<p>Please contact the administrator to add some templates</p>';
			}
		}

	}

?>
