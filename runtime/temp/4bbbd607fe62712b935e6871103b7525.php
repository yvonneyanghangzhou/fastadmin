<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:94:"F:\2_install_path\wamp64\www\FastAdmin\public/../application/admin\view\exam\project\edit.html";i:1594950181;s:81:"F:\2_install_path\wamp64\www\FastAdmin\application\admin\view\layout\default.html";i:1588765312;s:78:"F:\2_install_path\wamp64\www\FastAdmin\application\admin\view\common\meta.html";i:1588765312;s:80:"F:\2_install_path\wamp64\www\FastAdmin\application\admin\view\common\script.html";i:1588765312;}*/ ?>
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
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Name'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-name" class="form-control" name="row[name]" type="text" value="<?php echo htmlentities($row['name']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Duration'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-duration" class="form-control" name="row[duration]" type="text"
                value="<?php echo htmlentities($row['duration']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Description'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <textarea id="c-description" class="form-control " rows="5" name="row[description]"
                cols="50"><?php echo htmlentities($row['description']); ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Total_times'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-total_times" class="form-control" name="row[total_times]" type="number"
                value="<?php echo htmlentities($row['total_times']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Start_date'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-start_date" class="form-control datetimepicker" data-date-format="YYYY-MM-DD HH:mm:ss"
                data-use-current="true" name="row[start_date]" type="text" value="<?php echo $row['start_date']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Close_date'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-close_date" class="form-control datetimepicker" data-date-format="YYYY-MM-DD HH:mm:ss"
                data-use-current="true" name="row[close_date]" type="text" value="<?php echo $row['close_date']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Allow_mock'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select id="c-allow_mock" class="form-control selectpicker" name="row[allow_mock]">
                <?php if(is_array($allowOrNotList) || $allowOrNotList instanceof \think\Collection || $allowOrNotList instanceof \think\Paginator): if( count($allowOrNotList)==0 ) : echo "" ;else: foreach($allowOrNotList as $key=>$vo): ?>
                <option value="<?php echo $key; ?>" <?php if(in_array(($key), is_array($row['allow_mock'])?$row['allow_mock']:explode(',',$row['allow_mock']))): ?>selected<?php endif; ?>><?php echo $vo; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Mock_start_date'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-mock_start_date" class="form-control datetimepicker" data-date-format="YYYY-MM-DD HH:mm:ss"
                data-use-current="true" name="row[mock_start_date]" type="text" value="<?php echo $row['mock_start_date']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Mock_close_date'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-mock_close_date" class="form-control datetimepicker" data-date-format="YYYY-MM-DD HH:mm:ss"
                data-use-current="true" name="row[mock_close_date]" type="text" value="<?php echo $row['mock_close_date']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Pass_line'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-pass_line" class="form-control" name="row[pass_line]" type="number"
                value="<?php echo htmlentities($row['pass_line']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Question_num'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-question_num" class="form-control" name="row[question_num]" type="number"
                value="<?php echo htmlentities($row['question_num']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Enable_commitment'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select id="c-allow_print_commitment" class="form-control selectpicker" name="row[enable_commitment]">
                <?php if(is_array($allowOrNotList) || $allowOrNotList instanceof \think\Collection || $allowOrNotList instanceof \think\Paginator): if( count($allowOrNotList)==0 ) : echo "" ;else: foreach($allowOrNotList as $key=>$vo): ?>
                <option value="<?php echo $key; ?>" <?php if(in_array(($key), is_array($row['enable_commitment'])?$row['enable_commitment']:explode(',',$row['enable_commitment']))): ?>selected<?php endif; ?>><?php echo $vo; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Commitment_content'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <textarea id="c-commitment_content" class="form-control editor" rows="5" name="row[commitment_content]" cols="50"><?php echo htmlentities($row['commitment_content']); ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Allow_print_commitment'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select id="c-allow_print_commitment" class="form-control selectpicker" name="row[allow_print_commitment]">
                <?php if(is_array($allowOrNotList) || $allowOrNotList instanceof \think\Collection || $allowOrNotList instanceof \think\Paginator): if( count($allowOrNotList)==0 ) : echo "" ;else: foreach($allowOrNotList as $key=>$vo): ?>
                <option value="<?php echo $key; ?>" <?php if(in_array(($key), is_array($row['allow_print_commitment'])?$row['allow_print_commitment']:explode(',',$row['allow_print_commitment']))): ?>selected<?php endif; ?>><?php echo $vo; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Allow_print_certificate'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select id="c-allow_print_certificate" class="form-control selectpicker" name="row[allow_print_certificate]">
                <?php if(is_array($allowOrNotList) || $allowOrNotList instanceof \think\Collection || $allowOrNotList instanceof \think\Paginator): if( count($allowOrNotList)==0 ) : echo "" ;else: foreach($allowOrNotList as $key=>$vo): ?>
                <option value="<?php echo $key; ?>" <?php if(in_array(($key), is_array($row['allow_print_certificate'])?$row['allow_print_certificate']:explode(',',$row['allow_print_certificate']))): ?>selected<?php endif; ?>><?php echo $vo; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
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