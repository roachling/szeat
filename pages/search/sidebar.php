<div class="col-sm-3 cuisine-types">
    <div class="myfonts">
        <h2><?php echo ($xml->$lang->cuisintypes == "" ? $xml->en->cuisintypes : $xml->$lang->cuisintypes); ?></h2>
    </div>
    <script>
    $(document).ready(function(){
            <?php if (checkFeild($cuisines)) { ?>
                // $("#<?php echo $cuisines; ?>").trigger('click');
            <?php } ?>
        });
    </script>
    <ul style="margin:0;padding:0;margin-top:20px;">
    	<li><a id="all" class="types-selected">All <strong><?php echo countCuisines('all'); ?></strong></a></li>
        <?php $cuisines = $db->query("SELECT * FROM cuisines WHERE $status=1 ORDER BY title"); ?>
        <?php while ($cr=$db->fetch_array($cuisines)) { ?>
        	<li><a id="<?php echo $cr['id']; ?>"><span><?php echo urlText($pr['id']); ?><?php echo ($lang=='cn'?($cr['title_cn']==""?$cr['title']:$cr['title_cn']):$cr['title']); ?></span> <strong><?php echo countCuisines($cr['id']); ?></strong></a></li>
        <?php } // while $cuisines loop ?>
     </ul>
</div>