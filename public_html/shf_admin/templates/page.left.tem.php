<?php 
    // left side 
?>

<div id="theSideNavigation">
    <?php if(!$page_list) { ?>
            <p><i>No pages yet!</i></p>
    <?php } else { ?>
            <h2>Pages</h2>
    <?php
                    
                        foreach($page_list as $pid => $name) { 
                            
        ?>
                    <a href="<?=ADMIN_URL?>/index.php?m=pages&page=<?=$pid?>&action=view"<?php echo ($pid == $page_id) ? ACTIVE : ''; ?>><?=$name?></a>
                    
    <?php       } ?>
    <?php } ?>
    
    <a href="<?=ADMIN_URL?>/index.php?m=pages&action=new" class="bottom">Add New Page</a>
                    

</div>