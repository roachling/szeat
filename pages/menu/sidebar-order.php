<div class="col-sm-3">
    <div id="sidebar">
        <?php if (isset($_SESSION['error']) && $_SESSION['error']==true) { ?>
        	<div class="error"><?php echo $_SESSION['msg']; ?></div>
        <?php } ?>
        
        <div class="block-page">

            <h2><?php echo ($xml->$lang->urorder==""?$xml->en->urorder:$xml->$lang->urorder); ?></h2>
            <ul id="sidebarorder">
                <?php 

                if (isset($_SESSION['user']['items'])) { 

                    if ($res['delivery_fee']>0){
                        $totalItemPrice = $res['delivery_fee'];
                        $_SESSION['user']['delivery_fee'] = $res['delivery_fee'];
                    } else {
                        $totalItemPrice = 0;
                        unset($_SESSION['user']['delivery_fee']);
                    }
                	
    				foreach ($_SESSION['user']['items'] as $key=>$item) { ?>
                    	<?php $ir = $db->query_first("SELECT * FROM menu_items WHERE id={$item['id']} AND $status=1"); ?>
                        <?php if ($db->affected_rows > 0) { ?>
    						<?php 
    							if ($item['size'] > 0) { 
    								$itemSize  = $db->query_first("SELECT * FROM menu_item_sizes WHERE id={$item['size']}");
    								// $itemValue = $itemSize['value'];
                                    $itemValue = ($lang == 'cn' ? ($itemSize['value_cn']==""?$itemSize['value']:$itemSize['value_cn']) : $itemSize['value']);
    								$itemPrice = $itemSize['price'];
    							} else {
    								$itemValue = $ir['value'];
    								$itemPrice = $ir['price'];
    							}
    						?>
                            <li>
                                <p><?php echo ($lang=='cn'?($ir['name_cn']==""?$ir['name']:$ir['name_cn']):$ir['name']); ?> <?php echo $itemValue; ?></p>
                                <div class="col-xs-4"><?php echo $item['quantity']; ?>x</div>
                                <div class="col-xs-4"><?php echo _priceSymbol; ?></div>
                                <?php $itemPriceCalculated = number_format($itemPrice*$item['quantity'],2); ?>
                                <?php $totalItemPrice += $itemPriceCalculated; ?>
                                <div class="col-xs-4" style="padding:0;"><i><?php echo $itemPriceCalculated; ?><a href="index.php?page=menu&restaurant=<?php echo urlText($res['name']); ?>&id=<?php echo $res['id']; ?>&remove_item=<?php echo $item['id']; ?>&size=<?php echo $item['size']; ?>"></i><img src="img/order-delete.png" alt=""  /></a></div>
                                <hr>
                            </li>
                        <?php } // $db->affected_rows > 0 ?>
                    <?php } // endforeach 

                    

                    if ($res['delivery_fee'] >0) {
                        echo '

                        <li>
                            <p>Delivery fee</p>
                            <div class="col-xs-4">1x</div>
                            <div class="col-xs-4">RMB</div>
                            <div class="col-xs-4" style="padding:0;"><i>'.number_format($res['delivery_fee'],2).'</i></div>
                        </li>

                        ';
                    }
              
                    ?>
                    <li>
                        <p><?php echo ($xml->$lang->subtot==""?$xml->en->subtot:$xml->$lang->subtot); ?>:</p>
                        <div class="col-xs-4"><?php echo _priceSymbol; ?></div>
                        <i style="font-weight:normal;float:right;"><?php echo number_format($totalItemPrice,2); ?></i>
                    </li>
    			<?php } // end isset ?>
            </ul>

            <?php
                // stop submit if less than resto's min order
                $spendEnough = false;
                if (($totalItemPrice-$res['delivery_fee'])>=$res['minimum_order']) {
                    $spendEnough=true;
                } else {
                    echo '<p class="redwarning">'.($xml->$lang->minorderreach==""?$xml->en->minorderreach:$xml->$lang->minorderreach).'</p>';
                }
            ?>

            <div class="order-button">
                <div class="view-menu"><a class="btn btn-yellow" href="<?php echo (($deliveryAvailable==false)||($spendEnough!=true)) ? "" : "index.php?page=order-details"; ?>"><?php echo ($xml->$lang->ordernow==""?$xml->en->ordernow:$xml->$lang->ordernow); ?></a></div>
            </div>
        </div>
    </div>
</div>