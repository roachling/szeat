<?php require_once("../class/config.inc.php"); ?>
<?php require_once("../class/FileUpload.class.php"); ?>
<?php require_once("../class/Pagination.class.php"); ?>
<?php include("include/functions.php"); ?>
<?php is_session(); ?>
<?php 
	$pData = array(
				array(
					'link'	=> 'dashboard.php',
					'title'	=> 'Dashboard'
				),
				array(
					'link'	=> 'buildings.php',
					'title'	=> 'Buildings'
				)
			);
?>
<?php
if (isset($_GET['id']) && checkFeild($_GET['id'])) {
	$id = addslashes($_GET['id']);

	if (isset($_GET['delete'])) {
		if($db->query("DELETE FROM buildings WHERE id='$id' LIMIT 1")) {
			$_SESSION['msg'] = "Building successfully deleted.";
			$_SESSION['error'] = false;
			header("Location: buildings.php");
			exit();
		} else {
			$_SESSION['msg'] = "Building can't be deleted.";
			$_SESSION['error'] = true;
			header("Location: buildings.php");
			exit();
		}
	} elseif (isset($_GET['status'])) {
		
		$data['status'] = $_GET['status'];
		
		if ($db->query_update("buildings", $data, "id='$id'")) {
			$_SESSION['msg'] = "Building status successfully changed.";
			$_SESSION['error'] = false;
			header("Location: buildings.php");
			exit();
		} else {
			$_SESSION['msg'] = "Building status can't be changed.";
			$_SESSION['error'] = true;
			header("Location: buildings.php");
			exit();
		}
	}elseif (isset($_GET['status_cn'])) {
        
        $data['status_cn'] = $_GET['status_cn'];
        
        if ($db->query_update("buildings", $data, "id='$id'")) {
            $_SESSION['msg'] = "Building status successfully changed.";
            $_SESSION['error'] = false;
            header("Location: buildings.php");
            exit();
        } else {
            $_SESSION['msg'] = "Building status can't be changed.";
            $_SESSION['error'] = true;
            header("Location: buildings.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Buildings</title>
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link id="bootstrap-style" href="css/bootstrap.css" rel="stylesheet">
	<link href="css/bootstrap-responsive.css" rel="stylesheet">
	<link id="base-style" href="css/style.css" rel="stylesheet">
	<link id="base-style-responsive" href="css/style-responsive.css" rel="stylesheet">
	<!--[if lt IE 7 ]>
		<link id="ie-style" href="css/style-ie.css" rel="stylesheet">
	<![endif]-->
	<!--[if IE 8 ]>
		<link id="ie-style" href="css/style-ie.css" rel="stylesheet">
	<![endif]-->
	<!--[if lt IE 9]>
	  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="shortcut icon" href="img/favicon.ico">
</head>
<body>
	<?php include("include/header.php"); ?>
		<div class="container-fluid">
            <div class="row-fluid">
                <?php include("include/sidebar.php"); ?>
                <?php include("include/no-javascript-error.php"); ?>
                <div id="content" class="span10">
                <!-- start: Content -->
                <?php include("include/breadcrumbs.php"); ?>
                    
                    <div class="row-fluid sortable">		
                        <div class="box span12">
                            <div class="box-header" data-original-title>
                                <h2><i class="icon-map-marker"></i><span class="break"></span>Buildings</h2>
                                <div class="box-icon">
                                    <a href="buildings-add.php" class="btn-link noUnderline"><i class="fa-icon-plus"> Add New Building </i></a>
                                </div>
                            </div>
                            
                            <?php if(isset($_SESSION['msg'])) { ?>
                            <div class="box-content alerts">
                                <div class="alert alert-<?php echo $_SESSION['error']==true ? 'error':'success'; ?>">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <?php echo $_SESSION['msg']; ?>
                                </div>
                            </div><!--alerts-->
                            <?php } // if isset($_SESSION['msg']) ?>
                            
                            <div class="box-content">
                                <table class="table table-striped table-bordered bootstrap-datatable datatable">
                                  <thead>
                                      <tr>
                                          <th>English Building Name</th>
                                          <th>Chinese Building Name</th>
                                          <th>Area</th>
                                          <th>Users</th>
                                          <th>Created</th>
                                          <th>English Status</th>
                                          <th>Chinese Status</th>                                          
                                          <th>Actions</th>
                                      </tr>
                                  </thead>   
                                  <tbody>
                                    <?php $query = $db->query("SELECT * FROM buildings ORDER BY id"); ?>
                                    <?php if ($db->affected_rows > 0) { ?>
										<?php while($r=$db->fetch_array($query)) { ?>
                                            <tr>
                                                <td><?php echo $r['title']; ?></td>
                                                <td><?php echo $r['title_cn']; ?></td>
                                                <td><?php echo getData('areas','title',$r['area_id']); ?></td>
                                                <td><?php echo recordsCounter('users','building_id',$r['id']); ?></td>
                                                <td class="center"><?php echo date("Y/m/d",strtotime($r['date'])); ?></td>
                                                <td class="center">
                                                    <span class="label <?php echo $r['status']==1 ? 'label-success':NULL; ?>">
                                                    	<?php echo $r['status']==1 ? 'Active':'Inactive'; ?>
                                                    </span>
                                                </td>
                                                <td class="center">
                                                    <span class="label <?php echo $r['status_cn']==1 ? 'label-success':NULL; ?>">
                                                        <?php echo $r['status_cn']==1 ? 'Active':'Inactive'; ?>
                                                    </span>
                                                </td>                                                
                                                <td class="center">
                                                    <a class="btn btn-info" href="buildings-edit.php?id=<?php echo $r['id']; ?>" title="Edit">
                                                        <i class="icon-edit icon-white"></i>  
                                                    </a>
                                                    <a class="btn btn-inverse" href="?id=<?php echo $r["id"]; ?>&status=<?php echo $r['status']==1 ? 0:1; ?>" title="Change Status">
                                                        <i class="icon-eye-<?php echo $r['status']==1 ? 'open':'close'; ?> icon-white"></i> 
                                                    </a>

                                                    <a class="btn btn-warning" href="?id=<?php echo $r["id"]; ?>&status_cn=<?php echo $r['status_cn']==1 ? 0:1; ?>" title="Change Status">
                                                        <i class="icon-eye-<?php echo $r['status_cn']==1 ? 'open':'close'; ?> icon-white"></i> 
                                                    </a>                                                    
                                                    
                                                    <a class="btn btn-danger" data-toggle="modal" data-target="#deleteModal<?php echo $r['id']; ?>" title="Delete">
                                                        <i class="icon-trash icon-white"></i>
                                                    </a>

                                                    <div class="modal fade" id="deleteModal<?php echo $r['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-body">
                                                                    <div class="col">

                                                                        <p>Are you sure?</p>
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                                        <a class="btn btn-danger" href="?id=<?php echo $r['id']; ?>&delete" title="Delete">
                                                                            <i class="icon-trash icon-white"></i>
                                                                        </a>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                        <?php } // endwhile $query loop ?>
                                    <?php } // $db->affected_rows ?>
                                  </tbody>
                              </table>            
                            </div>
                        </div><!--/span-->
                    
                    </div><!--/row-->
                    
                    
                </div><!--/#content.span10-->
            </div><!--/fluid-row-->
		<div class="clearfix">&nbsp;</div>
		<?php include("include/footer.php"); ?>		
	</div><!--/.fluid-container-->
<?php include("include/footer-inc.php"); ?>
<?php unset($_SESSION['msg'], $_SESSION['error']); ?>
</body>
</html>
