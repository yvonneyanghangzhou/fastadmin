<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:96:"F:\2_install_path\wamp64\www\FastAdmin\public/../application/admin\view\exam\exam_user\edit.html";i:1594884297;s:81:"F:\2_install_path\wamp64\www\FastAdmin\application\admin\view\layout\default.html";i:1588765312;s:78:"F:\2_install_path\wamp64\www\FastAdmin\application\admin\view\common\meta.html";i:1588765312;s:80:"F:\2_install_path\wamp64\www\FastAdmin\application\admin\view\common\script.html";i:1588765312;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="/FastAdmin/public/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/FastAdmin/public/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/FastAdmin/public/assets/js/html5shiv.js"></script>
  <script src="/FastAdmin/public/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>
    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG && !\think\Config::get('fastadmin.multiplenav')): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo $vo['url']; ?>"><?php echo $vo['title']; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <form id="edit-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Username'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-username" data-rule="required" class="form-control" name="row[username]" type="text"
                value="<?php echo htmlentities($row['username']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Name'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-name" data-rule="required" class="form-control" name="row[name]" type="text"
                value="<?php echo htmlentities($row['name']); ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Org_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-org_id" data-rule="required" data-source="base_org/index" class="form-control selectpage"
                name="row[org_id]" type="text" value="<?php echo htmlentities($row['org_id']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Password'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-password" data-rule="required" class="form-control" name="row[password]" type="text"
                value="<?php echo htmlentities($row['password']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Major'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-major" class="form-control" name="row[major]" type="text" value="<?php echo htmlentities($row['major']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Class_name'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-class_name" class="form-control" name="row[class_name]" type="text"
                value="<?php echo htmlentities($row['class_name']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Grade'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-grade" class="form-control" name="row[grade]" type="text" value="<?php echo htmlentities($row['grade']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Type'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-type" class="form-control" name="row[type]" type="text" value="<?php echo htmlentities($row['type']); ?>">
        </div>
    </div>

    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
        </div>
    </div>
</form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/FastAdmin/public/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/FastAdmin/public/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo htmlentities($site['version']); ?>"></script>
    </body>
</html>