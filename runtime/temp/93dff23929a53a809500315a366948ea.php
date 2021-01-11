<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:97:"F:\2_install_path\wamp64\www\FastAdmin\public/../application/admin\view\exam\exam_user\index.html";i:1600139186;s:81:"F:\2_install_path\wamp64\www\FastAdmin\application\admin\view\layout\default.html";i:1588765312;s:78:"F:\2_install_path\wamp64\www\FastAdmin\application\admin\view\common\meta.html";i:1588765312;s:80:"F:\2_install_path\wamp64\www\FastAdmin\application\admin\view\common\script.html";i:1588765312;}*/ ?>
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
                                <style>
    .sp_result_area {
        width: 100px !important;
    }
</style>
<div class="panel panel-default panel-intro">
    <?php echo build_heading(); ?>

    <div class="panel-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="one">
                <div class="widget-body no-padding">
                    <div id="toolbar" class="toolbar">
                        <a href="javascript:;" class="btn btn-primary btn-refresh" title="<?php echo __('Refresh'); ?>"><i
                                class="fa fa-refresh"></i> </a>
                        <a href="javascript:;"
                            class="btn btn-success btn-add <?php echo $auth->check('exam/exam_user/add')?'':'hide'; ?>"
                            title="<?php echo __('Add'); ?>"><i class="fa fa-plus"></i> <?php echo __('Add'); ?></a>
                        <a href="javascript:;"
                            class="btn btn-success btn-edit btn-disabled disabled <?php echo $auth->check('exam/exam_user/edit')?'':'hide'; ?>"
                            title="<?php echo __('Edit'); ?>"><i class="fa fa-pencil"></i> <?php echo __('Edit'); ?></a>
                        <a href="javascript:;"
                            class="btn btn-danger btn-del btn-disabled disabled <?php echo $auth->check('exam/exam_user/del')?'':'hide'; ?>"
                            title="<?php echo __('Delete'); ?>"><i class="fa fa-trash"></i> <?php echo __('Delete'); ?></a>
                        <a href="javascript:;"
                            class="btn btn-danger btn-import <?php echo $auth->check('exam/exam_user/import')?'':'hide'; ?>"
                            title="<?php echo __('Import'); ?>" id="btn-import-file" data-url="ajax/upload"
                            data-mimetype="csv,xls,xlsx" data-multiple="false"><i class="fa fa-upload"></i>
                            <?php echo __('Import'); ?></a>
                        <!-- 回收站 -->
                        <a class="btn btn-success btn-recyclebin btn-dialog <?php echo $auth->check('exam/exam_user/recyclebin')?'':'hide'; ?>"
                            href="exam/exam_user/recyclebin" title="<?php echo __('Recycle bin'); ?>"><i class="fa fa-recycle"></i>
                            <?php echo __('Recycle bin'); ?></a>
                        <!-- 根据查询结果，关联考试 -->
                        <!-- <a class="btn btn-primary btn-dialog <?php echo $auth->check('exam/exam_user/linkExam')?'':'hide'; ?>"
                            href="exam/exam_user/linkExam" title="<?php echo __('Link_exam'); ?>"><i class="fa fa-link"></i>
                            <?php echo __('Link_exam'); ?></a> -->
                        <!-- 取消关联考试 -->
                        <!-- <a class="btn btn-warning btn-dialog <?php echo $auth->check('exam/exam_user/unlinkExam')?'':'hide'; ?>"
                            href="exam/exam_user/unlinkExam" title="<?php echo __('Unlink_exam'); ?>"><i class="fa fa-unlink"></i>
                            <?php echo __('Unlink_exam'); ?></a> -->
                    </div>
                    <table id="table" class="table table-striped table-bordered table-hover table-nowrap"
                        data-operate-edit="<?php echo $auth->check('exam/exam_user/edit'); ?>"
                        data-operate-del="<?php echo $auth->check('exam/exam_user/del'); ?>"
                        width="100%">
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script id="customformtpl" type="text/html">
    <!--form表单必须添加 form-commonsearch 这个类-->
<form class="form-commonsearch" name="searchForm" id="searchForm">
    <div style="border-radius:2px;margin-bottom:10px;background:#f5f5f5;padding:15px 20px;">
        <h4>自定义搜索表单</h4>
        <hr>
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div class="form-group">
                    <label class="control-label">按考试项目查询已关联某项考试</label>
                    <input type="hidden" class="operate" data-name="link.exam_id" value="=" />
                    <div>
                        <select id="c-exam_id" class="form-control" name="link.exam_id">
                            <option value="">查询已关联考试项目的考生</option>
                            <?php if(is_array($examProject) || $examProject instanceof \think\Collection || $examProject instanceof \think\Paginator): if( count($examProject)==0 ) : echo "" ;else: foreach($examProject as $key=>$vo): ?>
                            <option value="<?php echo $key; ?>"><?php echo $vo; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="control-label">院系/单位</label>
                    <div class="row" data-toggle="cxselect" data-selects="org_id,major,class_name">
                        <input type="hidden" class="operate" data-name="org_id" value="=" />
                        <div class="col-xs-4">
                            <select class="org_id form-control" name="org_id" data-first-title="选择院系"
                                data-url="exam/exam_user/linkSelect">
                            </select>
                        </div>

                        <input type="hidden" class="operate" data-name="major" value="=" />
                        <div class="col-xs-4">
                            <select class="major form-control" name="major" data-first-title="选择专业"
                                data-url="exam/exam_user/linkSelect" data-query-name="org_id">
                            </select>
                        </div>

                        <input type="hidden" class="operate" data-name="class_name" value="=" />
                        <div class="col-xs-4">
                            <select class="class_name form-control" name="class_name" data-first-title="选择班级"
                                data-url="exam/exam_user/linkSelect" data-query-name="major">
                            </select>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-1">
                <div class="form-group">
                    <label class="control-label">年级</label>
                    <input type="hidden" class="operate" data-name="grade" value="=" />
                    <div>
                        <input id="c-grade" data-source="exam/exam_user/getExamUserGrade" data-primary-key="grade"
                            data-field="grade" class="form-control selectpage" name="grade" type="text" value=""
                            style="display:block;">
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-1">
                <div class="form-group">
                    <label class="control-label">考生类型</label>
                    <input type="hidden" class="operate" data-name="type" value="=" />
                    <div>
                        <select class="form-control" name="type">
                            <option value="">请选择</option>
                            <?php if(is_array($typeList) || $typeList instanceof \think\Collection || $typeList instanceof \think\Paginator): if( count($typeList)==0 ) : echo "" ;else: foreach($typeList as $key=>$vo): ?>
                            <option value="<?php echo $key; ?>" <?php if(in_array(($key), explode(',',""))): ?>selected<?php endif; ?>><?php echo $vo; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-2">
                <div class="form-group">
                    <label class="control-label">在线时长</label>
                    <!--显式的operate操作符-->
                    <div class="input-group">
                        <div class="input-group-btn">
                            <select class="form-control operate c-online_time" data-name="onl.online_time" style="width:60px;">
                                <option value="=" selected>等于</option>
                                <option value=">">大于</option>
                                <option value="<">小于</option>
                            </select>
                        </div>
                        <input class="form-control" type="text" name="onl.online_time" placeholder="秒" value="" />
                    </div>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="form-group">
                    <label class="control-label"></label>
                    <div class="row">
                        <div class="col-xs-2">
                            <input type="submit" class="btn btn-success btn-block form-submit-btn" value="搜索" />
                        </div>
                        <div class="col-xs-2">
                            <input type="reset" class="btn btn-primary btn-block" value="重置" />
                        </div>
                        
                        <div class="col-xs-2">
                            <input type="submit" class="btn btn-success btn-block link-exam" value="<?php echo __('Link_exam'); ?>" rel="linkExam" />
                        </div>
                        <div class="col-xs-2">
                            <input type="submit" class="btn btn-primary btn-block unlink-exam" value="<?php echo __('Unlink_exam'); ?>"  rel="unlinkExam" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
</script>
<!-- 关联/取消关联考试 select 弹层 -->
<script id="link-exam-select-tpl" type="text/html">
    <form id="link-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">

        <div class="form-group">
            <label class="control-label">按考试项目查询已关联某项考试</label>
            <div>
                <select id="c-link_exam_id" data-rule="required" name="link_exam_id" class="form-control" >
                    <option value="">查询已关联考试项目的考生</option>
                    <?php if(is_array($examProject) || $examProject instanceof \think\Collection || $examProject instanceof \think\Paginator): if( count($examProject)==0 ) : echo "" ;else: foreach($examProject as $key=>$vo): ?>
                    <option value="<?php echo $key; ?>"><?php echo $vo; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
            <input type="hidden" name="search_exam_id" value="<%=searchField.link_exam_id%>" />
            <input type="hidden" name="org_id" value="<%=searchField.org_id%>" />
            <input type="hidden" name="major" value="<%=searchField.major%>" />
            <input type="hidden" name="grade" value="<%=searchField.grade_text%>" />
            <input type="hidden" name="class_name" value="<%=searchField.class_name%>" />
            <input type="hidden" name="student_type" value="<%=searchField.type%>" />
            <input type="hidden" name="online_time" value="<%=searchField.onl_online_time%>" />
        </div>
    </form>
    
</script>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/FastAdmin/public/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/FastAdmin/public/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo htmlentities($site['version']); ?>"></script>
    </body>
</html>