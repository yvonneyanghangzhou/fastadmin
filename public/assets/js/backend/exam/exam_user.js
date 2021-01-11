define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'exam/exam_user/index' + location.search,
                    add_url: 'exam/exam_user/add',
                    edit_url: 'exam/exam_user/edit',
                    del_url: 'exam/exam_user/del',
                    multi_url: 'exam/exam_user/multi',
                    table: 'exam_user',
                }
            });
            //input默认搜索
            $.fn.bootstrapTable.locales[Table.defaults.locale]['formatSearch'] = function () { return "用户名/姓名搜索"; };

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                searchFormVisible: true,
                searchFormTemplate: 'customformtpl',
                columns: [
                    [
                        { checkbox: true },
                        { field: 'id', title: __('Id') },
                        { field: 'avatar', title: __('Avatar'), events: Table.api.events.image, formatter: Table.api.formatter.image },
                        { field: 'username', title: __('Username') },
                        { field: 'name', title: __('Name') },
                        { field: 'org_name', title: __('Org_name') },
                        { field: 'major', title: __('Major') },
                        { field: 'grade', title: __('Grade') },
                        { field: 'class_name', title: __('Class_name') },
                        { field: 'type_text', title: __('Type') },
                        {
                            field: 'online_time',
                            title: __('Online_time'),
                            formatter: function (value, row, index, field) {
                                if (row.online_time) {
                                    return row.change_online_time
                                } else {
                                    return '';
                                }
                            }
                        },
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);//当内容渲染完成后,只能在此之后才能拿到$的值

            //查询提交按钮需要清空关联事件的值
            $(".form-submit-btn").click(function () {
                $("#topaction").val(0);
                $("select[name=link_exam_id]").val(0);
                return true;
            });

            //事件按钮操作
            $("#link-operate").on("click", ".link-btn input", function () {
                $("#topaction").val($(this).attr("rel"));
                return true;
            });

            //关联考试
            $(document).on("click", "input.link-exam", function () {
                var form = $(this).closest("form[name=searchForm]");
                Fast.api.ajax({
                    url: 'exam/exam_user/linkExamSelect',
                    type:'post',
                    data: form.serialize(),
                }, function (data) {
                    //关联select模板
                    Layer.open({
                        type:0,//可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                        content: Template("link-exam-select-tpl", {searchField: data.searchField}),
                        area: ["580px", "300px"],
                        title: __('link_exam_projects'),
                        btn: ['确定', __('Cancel')],
                        yes: function (index,layero) {
                            Fast.api.ajax({
                                url: 'exam/exam_user/linkExam',
                                dataType: 'json',
                                data: {
                                    formdata:$("form#link-form",layero).serialize(),
                                    online_time_op:$('.c-online_time').find("option:selected",layero).val(),
                                    _method:'GET'
                                }
                            }, function (data, ret) {
                                Layer.closeAll();
                                Layer.alert(ret.msg);
                            }, function (data, ret) {
                                Layer.closeAll();
                                Layer.alert(ret.msg);
                            });
                        }
                    });
                    return false;
                });
                return false;

            });

            //取消关联考试
            $(document).on("click", "input.unlink-exam", function () {
                var form = $(this).closest("form[name=searchForm]");
                Fast.api.ajax({
                    url: 'exam/exam_user/linkExamSelect',
                    type:'post',
                    data: form.serialize(),
                }, function (data) {
                    //关联select模板
                    Layer.open({
                        type:0,//可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                        content: Template("link-exam-select-tpl", {searchField: data.searchField}),
                        area: ["580px", "300px"],
                        title: __('link_exam_projects'),
                        btn: ['确定', __('Cancel')],
                        yes: function (index,layero) {
                            Fast.api.ajax({
                                url: 'exam/exam_user/unlinkExam',
                                dataType: 'json',
                                data: {
                                    formdata:$("form#link-form",layero).serialize(),
                                    _method:'GET'
                                }
                            }, function (ret, data) {
                                Layer.closeAll();
                                Layer.alert(data.msg);
                            }, function (ret, data) {
                                Layer.closeAll();
                                Layer.alert(data.msg);
                            });
                        }
                    });
                    return false;
                });
                return false;

            });

        },
        recyclebin: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'dragsort_url': ''
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: 'exam/exam_user/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        { checkbox: true },
                        { field: 'id', title: __('Id') },
                        { field: 'name', title: __('Name'), align: 'left' },
                        {
                            field: 'deletetime',
                            title: __('Deletetime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            width: '130px',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'Restore',
                                    text: __('Restore'),
                                    classname: 'btn btn-xs btn-info btn-ajax btn-restoreit',
                                    icon: 'fa fa-rotate-left',
                                    url: 'exam/exam_user/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'exam/exam_user/destroy',
                                    refresh: true
                                }
                            ],
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});