<?
	class Calendar {

        var $calID;
        var $title;
        var $date;
		var $start_time;
		var $end_time;
        var $description;
		var $type = 0;
        
        


		function Calendar($var="") {
			if (is_numeric($var) && ($var)) {
                    require_once(CONNECTION);
				$sql = "SELECT * FROM calendar WHERE calID = $var";
                     $result = @$mysqli->query($sql);
				$row = @$result->fetch_array();
				$this->makeFromRow($row);
                
			} else {
				$this->makeFromRow($var);
			}
            
		}

		function makeFromRow($row="") {
                $this->calID =                     isset($row["calID"])    ?    $row["calID"]	    :    ($this->calID  ?  $this->calID : 0);
                $this->title =                 isset($row["title"])    ?    $row["title"]	    :    ($this->title  ?  $this->title : '');
                $this->date =                   isset($row["date"])    ?    $row["date"]	    :    ($this->date  ?  $this->date : '');
				$this->start_time =                   isset($row["start_time"])    ?    $row["start_time"]	    :    ($this->start_time  ?  $this->start_time : '');
				$this->end_time =                   isset($row["end_time"])    ?    $row["end_time"]	    :    ($this->end_time  ?  $this->end_time : '');
                $this->description =                  isset($row["description"])    ?    $row["description"]	    :    ($this->description  ?  $this->description : '');
				$this->type =                     isset($row["type"])    ?    $row["type"]	    :    ($this->type  ?  $this->type : 0);
            }

		function Save() {
                require_once(CONNECTION);
                foreach($this as $key => $value) {
                     if(is_string($value)) {
                        $this->$key = $this->clean_input($value);
                    }
                }

			if ($this->calID) {

				$sql = "UPDATE calendar SET"
					. " title = '$this->title',"
                    . " date = '$this->date', start_time = '$this->start_time', end_time = '$this->end_time"
                    . " description = '$this->description', type=$this->type"
                    . " WHERE calID         = $this->calID";

				$result = @$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);

			} else {

				$sql = "INSERT INTO calendar"
					        . " (calID,"            
                            . " title,"         
                            . " date, start_time, end_time"          
                            . " description, type)"  
					. " VALUES"
                        . " ($this->calID,"            
                        . " '$this->title',"         
                        . " '$this->date', '$this->start_time', '$this->end_time',"          
                        . " '$this->description', $this->type)";

				$result = @$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);

				$this->calID = $mysqli->insert_id;

			}
		}

		function Delete() {
                require_once(CONNECTION);
			$sql = "DELETE FROM calendar WHERE calID = $this->calID";
			$result = @$mysqli->query($sql);
		}
  /*      
        function getField($field) {
            return $this->$field;
        }
        
        function setField($field, $value) {
            $this->$field = $value;
        }
        
     */
	 
        
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
		
		
		function make_from_date($date=false) {
			if(!$date)
				$date = date('Y-m-d');
			$sql = "SELECT * FROM calendar WHERE date = '" . $date . "'";
			$result = @$mysqli->query($sql)   or die('Query avail failed: ' . $mysqli->error);
            $count=@$result->num_rows;
			if($count > 0) {
				$row = @$result->fetch_assoc();
				$this->makeFromRow($row);
				return true;
			} else {
			}
			
			return false;
		}
		
		function get_list($opp=false) {
			$rere = array(); 
			$sql = "SELECT * FROM calendar ORDER BY date ";
			if($opp)
				$sql .= "DESC";
			else
				$sql .= "ASC";
			$result = @$mysqli->query($sql)   or die('Query p list failed: ' . $mysqli->error);
            while($row = @$result->fetch_assoc()) {
				$rere[$row['calID']] = date('n/j/y', strtotime($row['date'])) . ' : <span>' . $row['title'] . '</span>';
			}
			
			return $rere;
		
		}
		
		function print_list() {
			$sql = "SELECT * FROM calendar ORDER BY date DESC";
			$result = @$mysqli->query($sql)   or die('Query p list failed: ' . $mysqli->error);
			echo '<p>';
            while($row = @$result->fetch_assoc()) {
				$dd = date('M d, Y', strtotime($row['date']));
				echo '• <a href="' . ADMIN_URL . '/index.php?m=calendar&cal=' . $row['calID'] . '&action=edit">' . $row['title'] . ' - ' . $dd . '</a><br/>';
			}
			echo '</p>';
			echo '<p><a href="' . ADMIN_URL . '/index.php?m=calendar&action=new"><b>Add New Calendar Event!</b></a></p>';
		
		}
		
		function get_title() {
			return $this->title;
		}
		
		function get_date() {
			return $this->date;
		}
		
		function get_description() {
			return $this->description;
		}
		
		function get_types() {
			require_once(CONNECTION);
			$rere = array();
			$sql = "SELECT * FROM types";    
			$result = @$mysqli->query($sql);
			while($row = @$result->fetch_array()) {
				$rere[$row['typeID']] = $row['type'];
			}
			return $rere;
		}
		
		function get_formatted_types() {
			require_once(CONNECTION);
			$rere = array();
			$sql = "SELECT * FROM types";    
			$result = @$mysqli->query($sql);
			while($row = @$result->fetch_array()) {
				$rere[$row['typeID']] = strtolower(str_replace('/','',str_replace(' ', '_',$row['type'])));
			}
			return $rere;
		}
		
		 function print_form() {
                // indeed.
                require_once(FORM_MAKER);
				
				$types = $this->get_types();
				
                echo '<div id="form_box">';
				if($this->calID != 0)
					echo '<h2>Edit Calendar Event: <i>' . $this->title . '</i></h2>';
				else
					echo '<h2>Create New Calendar Event</h2>';
					
                
                echo '<form name="page_form" action="' . ADMIN_URL . '/index.php" method="post">';
                
                input_hidden_print('m', 'calendar');
                input_hidden_print('cal_save','yes');
                input_hidden_print('cal', $this->calID);
                input_hidden_print('calID', $this->calID);
                
                 echo '
					<div class="fform">';              
                input_text_print('Title', 'title', $this->title);
				echo '<br/>';
                echo '       <span class="sub_input">(This is the title that comes up at the top of the page)</span>';
				echo '</div>
					<div class="fform">';
				input_text_print('Event Date', 'date', $this->date,'dater');
				echo '</div>
					<div class="fform">';
				input_text_print('Start Time', 'start_time', $this->start_time);
				echo '</div>
					<div class="fform">';
				input_text_print('End Time', 'end_time', $this->end_time);
				echo '       <br/><span class="sub_input">Enter <b>Start</b> and <b>End</b> times as you would like them to be displayed.</span>';
				echo '</div>
					<div class="fform">';
				select_print('Event Type', 'type', $types, $this->type);
				echo '</div>
					<div class="fform">';
				textarea_print('Event Description','description',$this->description);
				echo '</div>
					<div class="fform">';
				
                submit_print('submit_page', 'Save Event', 'nice_pos');                
                
                echo '       <span class="sub_input"><a href="' . ADMIN_URL . '?m=calendar&cal=' . $this->calID . '&action=view">back to list</a></span>
					</div>
				';
                echo '</form>';
                echo '</div>';
        }
		
		function print_details() {
			$types = $this->get_types();
			echo '<h2>Calendar Event: ' . $this->title . '</h2>';
			echo '<p>
				<i>Title</i>: <b>' . $this->title . '</b><br/>
				<i>Date</i>: <b>' . $this->date . '</b><br/>
				<i>Event Type</i>: <b>' . $types[$this->type] . '</b><br/>
				<i>Description</i>: <b>' . $this->description  . '</b><br/><br/>
				<a href="' . ADMIN_URL . '/index.php?m=calendar&cal=' . $this->calID . '&action=edit">edit calendar details</a> | <a href="' . ADMIN_URL . '/index.php?m=calendar&cal=' . $this->calID . '&action=remove" class="remover">delete</a><br/>
				
			</p>';
		}
		
		function print_detailed_list() {
			$types = $this->get_formatted_types();
			
			$c = 1;
			$out = '';
			$curr_month = '';
			$today = date('Y-m-d', strtotime("-1 week"));
			$sql = "SELECT * FROM calendar WHERE date > '$today' ORDER BY date ASC";
			$result = @$mysqli->query($sql)   or die('Query detail list failed: ' . $mysqli->error);
            while($row = @$result->fetch_assoc()) {
				if($curr_month != date('F', strtotime($row['date']))) {
					$curr_month = date('F', strtotime($row['date']));
					$out .= '<h1>' . $curr_month . '</h1>';
				}
				$dd = date('M d, Y', strtotime($row['date']));
				$out .= '<h2 class="cal_head ' . $types[$row['type']] . '">' . $dd . '<span>' . $row['title'] . '</span></h2>';
				$out .= ' 
					<div class="cal_detail ' . $types[$row['type']] . '_outline">
						' . $row['description'] . '
					</div>
					';
				
			}
			
			echo $out;
		}
        
		
		
			
			
			
     
                
      
}
	

?>
