<?php 
    // left side 
?>
<div id="theSideNavigation">
			<h2><?=$page_titles?></h2>
			<b><a href="<?=ADMIN_URL?>/index.php?m=calendar&action=new"><b>Add New Event</b></a></b>
    <?php if(!$page_list) { ?>
            <p><i>No <?=$page_titles?> yet!</i></p>
    <?php } else { 
		
                        foreach($page_list as $pid => $name) { 
																
                            
        ?>
                    <a href="<?=ADMIN_URL?>/index.php?m=calendar&cal=<?=$pid?>&action=view"<?php echo ($pid == $page_id) ? ACTIVE : ''; ?>><?=$name?></a>
                    
    <?php       } ?>
    <?php } ?>
    
    
                    

</div>