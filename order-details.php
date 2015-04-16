<?php $orderPage = true; ?>
<?php require_once('class/config.inc.php'); ?>
<?php require_once("class/Pagination.class.php"); ?>
<?php require_once('includes/functions.php'); ?>
<?php 
if (!isset($_SESSION['user']['items']) || sizeof($_SESSION['user']['items']) < 1) { 
	$_SESSION['error'] = true;
	$_SESSION['msg'] = "Please add items to your order.";
	header("Location: {$_SERVER['HTTP_REFERER']}");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo _title; ?><?php echo checkFeild(_tagline) ? ' - '._tagline:NULL; ?></title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
    <link rel="stylesheet/less" type="text/css" href="css/style.less"/>
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="js/less-1.3.0.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/custom-form-elements.js"></script>
    <script type="text/javascript" charset="utf-8">
	$(function(){
	  $("select#area").change(function(){
		$.getJSON("ajaxSelect.php",{area: $(this).val(), ajax: 'true'}, function(j){
		  var options = '';
		  for (var i = 0; i < j.length; i++) {
			options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
		  }
		  $("select#building").html(options);
		})
	  })
	})
	</script>
</head>

<body>
    <?php require_once('includes/header.php'); ?>
    <div class="container main">
    	<div class="row">
        	<div class="span12">
            	<div class="page-header">
                	<a href="index.php">Home</a>
                    <img src="img/title-icon.png" alt="" />
                    <a href="#">Search results</a>
                    <img src="img/title-icon.png" alt="" />
                    <?php if (isset($_SESSION['user']['restaurant']['name'])) { ?>
                        <a href="restaurant.php?restaurant=<?php echo urlText($_SESSION['user']['restaurant']['name']); ?>&id=<?php echo $_SESSION['user']['restaurant']['id']; ?>"><?php __($_SESSION['user']['restaurant']['name']); ?></a>
                        <img src="img/title-icon.png" alt="" />
                        <a href="menu.php?restaurant=<?php echo urlText($_SESSION['user']['restaurant']['name']); ?>&id=<?php echo $_SESSION['user']['restaurant']['id']; ?>">Menu</a>
	                    <img src="img/title-icon.png" alt="" />
                    <?php } else { ?>
                        <a href="#">Restaurant</a>
                        <img src="img/title-icon.png" alt="" />
                        <a href="#">Menu</a>
	                    <img src="img/title-icon.png" alt="" />
                    <?php } ?>
                    <a href="#">Delivery address</a>
                </div>
            </div>
        </div>
        
    	<!-- main content -->
        <div class="row">
        	<!-- your-order -->
        	<div class="span6">
            	<div class="your-order gray-bg-container">
                	<h2>Your order</h2>
                    <!-- full-order-price-container -->
                    <div class="full-order-price-container">
                    	<?php $total_price = 0; ?>
                    	<?php if (isset($_SESSION['user']['items'])) { ?>
							<?php foreach ($_SESSION['user']['items'] as $key=>$item) { ?>
                                <?php $ir = $db->query_first("SELECT * FROM menu_items WHERE id={$item['id']} AND status=1"); ?>
                                <?php if ($db->affected_rows > 0) { ?>
                                	<?php 
										if ($item['size'] > 0) { 
											$itemSize  = $db->query_first("SELECT * FROM menu_item_sizes WHERE id={$item['size']}");
											$itemValue = $itemSize['value'];
											$itemPrice = $itemSize['price'];
										} else {
											$itemValue = $ir['value'];
											$itemPrice = $ir['price'];
										}
									?>
                                    <div class="full-order-price-row">
                                        <span class="first-element"><?php echo $item['quantity']; ?> x no. <?php __($ir['item_number']); ?> <?php __($ir['name']); ?> <?php echo $itemValue; ?></span>
                                        <span class="second-element"><?php echo _priceSymbol; ?></span>
                                        <span class="third-element"><?php echo number_format($itemPrice*$item['quantity'],2); ?></span>
                                        <a href="?remove_item=<?php echo $item['id']; ?>&size=<?php echo $item['size']; ?>" class="delete-button"></a>
                                    </div>
                                    <?php $total_price += number_format($itemPrice*$item['quantity'],2); ?>
								<?php } // $db->affected_rows > 0 ?>
                            <?php } // endforeach ?>
                        <?php } // end isset ?>
                        
                        <div class="full-order-price-row final">
                        	<span class="first-element">Total</span>
                        	<span class="second-element"><?php echo _priceSymbol; ?></span>
                        	<span class="third-element"><?php echo $total_price; ?></span>
                        </div>
                	</div>
                    <!-- end of full-order-price-container -->
                    
                    <a href="menu.php?restaurant=<?php echo urlText($_SESSION['user']['restaurant']['name']); ?>&id=<?php echo$_SESSION['user']['restaurant']['id']; ?>" class="gray-button">Add additional items to your order</a>
                    
                    <div class="leave-comment">
                    	<h3>Leave a note for the restaurant</h3>
                        <textarea name="notesDummy" id="notesDummy"><?php echo isset($_SESSION['user']['notes']) ? $_SESSION['user']['notes']:NULL; ?></textarea>
                        <script type="text/javascript">
							$(document).ready(function(){
								$("#notesDummy").keyup(function(){
									$(".notesbox").val(this.value);
								});
							});
						</script>
                    </div>
                    
                </div>
            </div>
        	<!--end of your-order -->
            
        	<!-- your-info -->
        	<div class="span6">
            	<?php if (isset($_SESSION['user']['id'])) { ?>
                	<?php $uData = $db->query_first("SELECT * FROM users WHERE id={$_SESSION['user']['id']}"); ?>
                	<div class="your-info gray-bg-container">
                        <h2>Name and Address</h2>
                        <div class="your-info-registration">
                            <form action="order-summary.php" method="post">
                                <strong>Please check that your delivery information is correct.</strong>
                                <div class="registration-row">
                                    <label>Name:</label>
                                    <input type="text" name="name" value="<?php echo $uData['name']; ?>"  />
                                </div>
                                <div class="registration-row">
                                    <label>Phone:</label>
                                    <input type="text" name="phone" value="<?php echo $uData['phone']; ?>" />
                                </div>
                                <div class="registration-row">
                                    <label>Your Area:</label>
                                    <select name="area" id="area">
                                        <option hidden="">Select</option>
                                        <?php $areas = $db->query("SELECT * FROM areas WHERE status=1 ORDER BY title ASC"); ?>
                                        <?php while ($r=$db->fetch_array($areas)) { ?>
                                            <option value="<?php echo $r['id']; ?>" <?php echo $r['id']==$uData['area_id'] ? 'selected':NULL; ?>>
												<?php __($r['title']); ?>
                                            </option>
                                        <?php } // while $areas loop ?>
                                    </select>
                                </div>
                                <div class="registration-row">
                                    <label>Your Building:</label>
                                    <select name="building" id="building">
                                        <option hidden="hidden">Select</option>
                                        <?php $cuisines = $db->query("SELECT * FROM buildings WHERE status=1 ORDER BY title ASC"); ?>
                                        <?php while ($r=$db->fetch_array($cuisines)) { ?>
                                            <option value="<?php echo $r['id']; ?>" <?php echo $r['id']==$uData['building_id'] ? 'selected':NULL; ?>>
												<?php __($r['title']); ?>
                                            </option>
                                        <?php } // while $areas loop ?>
                                    </select>
                                </div>
                                <div class="registration-row">
                                    <label>Your Block/ Apt #:</label>
                                    <input type="text" name="apartment"  value="<?php echo $uData['apartment']; ?>"/>
                                </div>
                                
                                <textarea class="notesbox" name="notes" id="notes" style="display:none;"><?php echo isset($_SESSION['user']['notes']) ? $_SESSION['user']['notes']:NULL; ?></textarea>
                                <input type="hidden" name="price" id="price" value="<?php echo $total_price; ?>" />
                                
                                <button type="submit" class="yellow-button">Confirm</button>
                                
                                <?php if (isset($_SESSION['msg'])) { ?>
                                    <div class="clearfix"></div>
                                    <div class="smallFont <?php echo $_SESSION['error']==true ? 'text-error':'text-success'; ?>" style="text-align:center;">
                                        <br /><?php echo $_SESSION['msg']; ?>
                                    </div>
                                <?php } // isset $_SESSION['msg'] ?>
                        	</form>
                        </div>
                        <!-- end of your-info-registration -->
                    </div>
                <?php } else { // if (!isset($_SESSION['user']['id']) ?>
                    <div class="your-info gray-bg-container">
                        <h2>Name and Address</h2>
                        <!-- your-info-registration -->
                        <div class="your-info-registration">
                            <form action="login.php?redirect=<?php echo urldecode('order-details.php'); ?>" method="post">
                                <h3>Existing Member</h3>
                                <div class="registration-row">
                                    <label>E-mail Address:</label>
                                    <input type="email" name="email" />
                                </div>
                                <div class="registration-row">
                                    <label>Password:</label>
                                    <input type="password" name="password" autocomplete="off" />
                                </div>
                                <a href="forgot-password.php" class="forgot-password">Forgotten your password?</a>
                                <textarea class="notesbox" name="notes" id="notes" style="display:none;"><?php echo isset($_SESSION['user']['notes']) ? $_SESSION['user']['notes']:NULL; ?></textarea>
                                <button type="submit" class="yellow-button">Login</button>
                            </form>
                        </div>
                        <!-- end of your-info-registration -->
                        <div class="your-info-registration">
                            <form action="login.php?signup=true&redirect=<?php echo urldecode('order-details.php'); ?>" method="post">
                                <h3>New Member</h3>
                                <div class="registration-row">
                                    <label>Name:</label>
                                    <input type="text" name="name" />
                                </div>
                                <div class="registration-row">
                                    <label>Phone:</label>
                                    <input type="text" name="phone" />
                                </div>
                                <div class="registration-row">
                                    <label>Your Area:</label>
                                    <select name="area" id="area">
                                        <option hidden="hidden">Select</option>
                                        <?php $areas = $db->query("SELECT * FROM areas WHERE status=1 ORDER BY title ASC"); ?>
                                        <?php while ($r=$db->fetch_array($areas)) { ?>
                                            <option value="<?php echo $r['id']; ?>"><?php __($r['title']); ?></option>
                                        <?php } // while $areas loop ?>
                                    </select>
                                </div>
                                <div class="registration-row">
                                    <label>Your Building:</label>
                                    <select name="building" id="building">
                                        <option hidden="hidden">Select</option>
                                        <?php $cuisines = $db->query("SELECT * FROM buildings WHERE status=1 ORDER BY title ASC"); ?>
                                        <?php while ($r=$db->fetch_array($cuisines)) { ?>
                                            <option value="<?php echo $r['id']; ?>"><?php __($r['title']); ?></option>
                                        <?php } // while $areas loop ?>
                                    </select>
                                </div>
                                <div class="registration-row">
                                    <label>Your Block/ Apt #:</label>
                                    <input type="text" name="apartment" />
                                </div>
                                <div class="registration-row">
                                    <label>E-mail Address:</label>
                                    <input type="email" name="email1" />
                                </div>
                                <div class="registration-row">
                                    <label>Re-enter Email Address:</label>
                                    <input type="email" name="email2" />
                                </div>
                                <div class="registration-row">
                                    <label>Password:</label>
                                    <input type="password" name="password1" />
                                </div>
                                <div class="registration-row">
                                    <label>Re-enter Password:</label>
                                    <input type="password" name="password2" />
                                </div>
                                <div class="registration-row checkbox-row">
                                    <input type="checkbox" name="newsletter" checked="checked" value="1" />
                                    <p>I would like to sign up to the SHENZHEN EAT newsletter (via email and mobile) for the chance to win free takeaway for a year.</p>
                                </div> 
                                <div class="registration-row checkbox-row">
                                    <input type="checkbox" name="terms" value="1" />
                                    <p>I accept the <a href="#">terms and conditions</a>, <a href="#">privacy policy</a> &amp; <a href="#">cookies policy</a></p>
                                </div> 
                                <textarea class="notesbox" name="notes" id="notes" style="display:none;"><?php echo isset($_SESSION['user']['notes']) ? $_SESSION['user']['notes']:NULL; ?></textarea>
                                <button type="submit" class="yellow-button">Next</button>
                            </form>
                        </div>
                        <!-- end of your-info-registration -->
                    </div>
                <?php } // if (!isset($_SESSION['user']['id']) ?>
            </div>
        	<!-- end of your-info -->
            
        </div>
    	<!-- end of main content -->
    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>