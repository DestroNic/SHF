<?php 
    // left side 
?>
<div id="theLeft">
		<div id="theNavigation">
								<!-- <div id="content_left"> -->
			<h2>Pages</h2>
			<b><a href="<?=ADMIN_URL?>/index.php?m=pages&action=new"><b>Add New Page</b></a></b>
    <?php if(!$page_list) { ?>
            <p><i>No pages yet!</i></p>
    <?php } else { 
						$first = true;
						$prev_type = '';
		
                        foreach($page_list as $pid => $name) { 
								if($prev_type != $name['type']) { 
									if(!$first)
										echo '</div>';
									echo '<h3>' . $name['type'] . '</h3>';
									echo '<div class="sub_nav">';
									$prev_type = $name['type'];
									$first = false;
								}
                            
        ?>
                    <a href="<?=ADMIN_URL?>/index.php?m=pages&page=<?=$pid?>&action=view"<?php echo ($pid == $page_id) ? ACTIVE : ''; ?>><?=$name['name']?></a>
                    
    <?php       } ?>
					</div>
    <?php } ?>
    
    
                    

</div></div>