<? 
//form maker is a bunch of functions where you can pass what you need to make form elements

function options_print($options = array(), $default = null) {
    $selected = ' selected="selected"';
    foreach($options as $option => $value) {
        echo "<option value=\"$option\"";
        if($option == $default)
            echo $selected;
        echo ">$value</option>";
    }
}

function select_print($text, $name, $options = array(), $default=null, $class=null) {
    echo "<label for=\"$name\"";
    if($class != null)
        echo " class=\"$class\"";
    echo ">$text</label>";
    echo "<select name=\"$name\" id=\"$name\"";
    if($class != null)
        echo " class=\"$class\"";
    echo ">";
    options_print($options, $default);
    echo "</select>";
}

function input_text_print($text, $name, $value='', $class='') {
    echo "<label for=\"$name\">$text</label>";
    echo "<input type=\"text\" name=\"$name\" id=\"$name\" value=\"$value\"";
    if($class != '')
        echo " class=\"$class\"";
    echo " />";
}

function input_text_sized_print($text, $name, $value='', $size='10') {
    echo "<label for=\"$name\">$text</label>";
    echo "<input type=\"text\" name=\"$name\" id=\"$name\" size=\"$size\" value=\"$value\" />";
}

function input_disabled_text_print($text, $name, $value='') {
    echo "<label for=\"$name\">$text</label>";
    echo "<input type=\"text\" name=\"$name\" id=\"$name\" value=\"$value\" disabled=\"disabled\" class=\"disabled\" />";
}

function input_hidden_print($name, $value='') {
    echo "<input type=\"hidden\" name=\"$name\" id=\"$name\" value=\"$value\" />";
}

function input_password_print($text, $name, $value='', $class='') {
    echo "<label for=\"$name\">$text</label>";
    echo "<input type=\"password\" name=\"$name\" id=\"$name\" value=\"$value\"";
    if($class != '')
        echo " class=\"$class\"";
    echo " />";
}

function input_file_print($text, $name, $value='', $class='') {
    if($text != '') {
        echo "<label for=\"$name\">$text</label>";
    }
    echo "<input type=\"file\" name=\"$name\" id=\"$name\" value=\"$value\"";
    if($class != '')
        echo " class=\"$class\"";
    echo " />";
}

function textarea_print($text, $name, $value='', $rows='7', $cols='30',$class='') {
    if($text != null)
        echo "<label for=\"$name\"";
	if($class != '')
        echo " class=\"$class\"";
	echo ">$text</label>";
    echo "<textarea rows=\"$rows\" cols=\"$cols\" name=\"$name\" id=\"$name\"";
    if($class != '')
        echo " class=\"$class\"";
    echo ">$value</textarea>";
}

function button_print($name,$value,$class='') {
    echo "<input type=\"button\" name=\"$name\" id=\"$name\" value=\"$value\"";
    if($class != '')
        echo "class=\"$class\"";
    echo " />";
}

function submit_print($name,$value,$class='') {
    echo "<input type=\"submit\" name=\"$name\" id=\"$name\" value=\"$value\"";
    if($class != '')
        echo "class=\"$class\"";
    echo " />";
}

function one_check_box($text,$name,$value,$default = 0,$class='') {
    echo "<label for=\"$name\">$text</label>";
    echo "<input type=\"checkbox\" name=\"$name\" value=\"$value\"";
    if($default)
        echo " checked=\"checked\"";
    if($class != '')
        echo " class=\"$class\"";
    echo " />";
}


function image_upload_box($name, $id='') {
    echo "<div class=\"image_upload_box\" id=\"box_$name\"><div class=\"inside\">";
    echo "<p class=\"cancel\"><a href=\"#\" class=\"remove_image\" name=\"$name\">remove image</a> | <a href=\"#\" class=\"cancel_image\" name=\"$name\">cancel</a></p>";
    echo "<h1>Upload New Image</h1>";
    echo "<p>This will overwrite your current image if you have one already</p>";
    input_file_print('', "new_$name",'');
    button_print("butt_$name",'Upload','new_image_butt');
    
    echo "</div></div>";
}
