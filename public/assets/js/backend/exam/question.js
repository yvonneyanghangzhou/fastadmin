define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'exam/question/index' + location.search,
                    add_url: 'exam/question/add',
                    edit_url: 'exam/question/edit',
                    del_url: 'exam/question/del',
                    multi_url: 'exam/question/multi',
                    import_url: 'exam/question/import',  //只添加这行，这里说明文档不知道为何找不到
                    table: 'exam_question',
                }
            });
            //input默认搜索
            $.fn.bootstrapTable.locales[Table.defaults.locale]['formatSearch'] = function () { return "按题干检索"; };

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                // 通用搜索指表格上方的搜索，通用搜索的表单默认是隐藏的，如果需要默认显示
                searchFormVisible: true,
                // 浏览模式可以切换卡片视图和表格视图两种模式
                showToggle: false,
                // 显示隐藏列可以快速切换字段列的显示和隐藏，如果不需要此功能
                showColumns: false,
                columns: [
                    [
                        { checkbox: true },
                        { field: 'id', title: __('Id'), operate: false },
                        {
                            field: 'title_content',
                            title: __('Title_content'),
                            operate: 'LIKE',
                            cellStyle: {
                                css: { 'text-align': 'left!important' }
                            },
                            formatter: function (value, row) {
                                return row.title_text;
                            }
                        },
                        // {
                        //     field: 'type',
                        //     title: __('Type'),
                        //     searchList: { "1": __('Type 1'), "2": __('Type 2'), "3": __('Type 3'), "4": __('Type 4'), "5": __('Type 5') },
                        //     formatter: Table.api.formatter.normal
                        // },
                        {
                            field: 'type',
                            title: __('Type'),
                            searchList: Config.typeList,
                            custom: { 1: 'info', 2: 'warning', 3: 'success' },
                            formatter: Table.api.formatter.label
                        },
                        {
                            field: 'option_text',
                            operate: false,//通用搜索中不显示
                            title: __('Option_content'),

                        },
                        {
                            field: 'answer_text',
                            operate: false,
                            title: __('Answer_content')
                        },
                        // {field: 'blanks_num', title: __('Blanks_num')},
                        {
                            field: 'examlibrary.id',//用于自定义搜索的提交字段
                            title: __('Examlibrary.library_name'),
                            searchList: $.getJSON("exam/library/searchList"),//用于自定义搜索select下拉list
                            formatter: function (value, row, index) {   //用于显示table列中的中文
                                return row.examlibrary.name;
                            }
                        },
                        {
                            field: 'examquestiontag.id',
                            title: __('Examquestiontag.tag_name'),
                            searchList: $.getJSON("exam/question_tag/searchList"),
                            formatter: function (value, row, index) {
                                return row.examquestiontag.name;
                            }
                        },
                        {
                            field: 'level',
                            title: __('Level'),
                            searchList: {
                                "1": __('Level 1'), "2": __('Level 2'), "3": __('Level 3')
                            },
                            formatter: Table.api.formatter.status
                        },
                        {
                            field: 'createtime',
                            title: __('Createtime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        // {field: 'creator', title: __('Creator')},
                        { field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate }
                    ]
                ]
            });
            //**************自定义导入excel,先弹出模板下载页面***************//
            $(document).on("click", ".btn-import-custom", function () {
                Layer.open({
                    type: 1,//可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                    content: Template("question-import-tpl",{}),
                    area: ["580px", "300px"],
                    title: __('question_import_tpl'),

                });
                return false;
            });
            //**************自定义导入excel,导入***************//
            $(document).on("click", ".btn-import-question", function () {
                // 导入
                require(['upload'], function(Upload) {
                    //绑定事件
                    Upload.api.plupload($('#btn-import-file-question'), function(data, ret) {
                        Fast.api.ajax({
                            //导入接口
                            url:'exam/question/import',
                            data: {
                                file: data.url
                            },
                        }, function(data, ret) {
                            //成功
                            table.bootstrapTable('refresh');
                        });
                    });
                });
                
            });

            //*************************** 自定义导出export开始*******************************//
            //表单提交方法
            var submitForm = function (ids, layero) {
                //表格对象的ID
                var options = table.bootstrapTable('getOptions');
                console.log(options);
                var columns = [];
                //遍历表格中每一列对象，包括了所有的样式等field是当前列设置的名称，去掉第一个复选框和最后一个操作按钮栏
                $.each(options.columns[0], function (i, j) {
                    if (j.field && !j.checkbox && j.visible && j.field != 'operate') {
                        columns.push(j.field);
                    }
                });
                console.log(columns);
                //自定义搜索中filter，op搜索参数值
                var search = options.queryParams({});
                console.log(search);
                //自定义搜索
                $("input[name=filter]", layero).val(search.filter);
                $("input[name=op]", layero).val(search.op);
                //快捷搜索，此处为题干
                $("input[name=search]", layero).val(options.searchText);
                //复选框
                $("input[name=ids]", layero).val(ids);
                console.log(columns.join(','));
                $("input[name=columns]", layero).val(columns.join(','));
                // return false;
                $("form", layero).submit();
            };

            //点击导出按钮
            $(document).on("click", ".btn-export", function () {
                var ids = Table.api.selectedids(table);
                var page = table.bootstrapTable('getData');
                var all = table.bootstrapTable('getOptions').totalRows;
                console.log(ids, page, all);
                Layer.confirm("请选择导出的选项<form action='" + Fast.api.fixurl("exam/question/export") + "' method='post' target='_blank'><input type='hidden' name='ids' value='' /><input type='hidden' name='filter' ><input type='hidden' name='op'><input type='hidden' name='search'><input type='hidden' name='columns'></form>", {
                    title: '导出数据',
                    //btn: ["选中项(" + ids.length + "条)", "本页(" + page.length + "条)", "全部(" + all + "条)"],
                    btn: ["选中项(" + ids.length + "条)"],
                    success: function (layero, index) {
                        $(".layui-layer-btn a", layero).addClass("layui-layer-btn0");
                    }
                    , yes: function (index, layero) {
                        submitForm(ids.join(","), layero);
                        return false;
                    }
                    ,
                    btn2: function (index, layero) {
                        var ids = [];
                        $.each(page, function (i, j) {
                            ids.push(j.id);
                        });
                        submitForm(ids.join(","), layero);
                        return false;
                    }
                    ,
                    btn3: function (index, layero) {
                        submitForm("all", layero);
                        return false;
                    }
                })
            });
            //*************************** 自定义export结束**************************//
            // 为表格绑定事件
            Table.api.bindevent(table);
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
                url: 'exam/question/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        { checkbox: true },
                        { field: 'id', title: __('Id') },
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
                                    url: 'exam/question/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'exam/question/destroy',
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

            //选择判断题，填空题，简单题隐藏选项栏，选择题显示选项栏
            $(document).on('change', "select#c-type", function () {
                var that = this;
                var option_val = $(that).find('option:selected').val();
                switchPage(option_val)
            });

            function switchPage(type_val) {
                switch (type_val) {
                    //判断题
                    case '1':
                        //选择题 选项
                        $('#c-option_content').closest('.form-group').removeClass('show');
                        $('#c-option_content').closest('.form-group').addClass('hidden');
                        //显示正确错误项
                        $('input[name="row[true_false_answer]"]').closest('.form-group').removeClass('hidden');
                        $('input[name="row[true_false_answer]"]').closest('.form-group').addClass('show');
                        //隐藏标准答案的文本框
                        $('#c-input_answer').closest('.form-group').removeClass('show');
                        $('#c-input_answer').closest('.form-group').addClass('hidden');
                        //加上必填
                        $('input[name="row[true_false_answer]"]').data('rule', 'required');
                        //删除标准答案文本框 的必填标志  data-rule="required"
                        $('#c-input_answer').removeData('rule');
                        break;
                    case '2':
                    case '3':
                        //选择题 选项
                        $('#c-option_content').closest('.form-group').removeClass('hidden');
                        $('#c-option_content').closest('.form-group').addClass('show');
                        //隐藏判断题 正确错误项目
                        $('input[name="row[true_false_answer]"]').closest('.form-group').removeClass('show');
                        $('input[name="row[true_false_answer]"]').closest('.form-group').addClass('hidden');
                        //显示常规答案项
                        $('#c-input_answer').closest('.form-group').removeClass('hidden');
                        $('#c-input_answer').closest('.form-group').addClass('show');
                        //加上必填
                        $('input[name="row[true_false_answer]"]').removeData('rule');
                        //删除标准答案文本框 的必填标志  data-rule="required"
                        $('#c-input_answer').data('rule', 'required');
                        break;
                    case '4'://填空题
                    case '5'://简答题
                        $('#c-option_content').closest('.form-group').removeClass('show');
                        $('#c-option_content').closest('.form-group').addClass('hidden');
                        //隐藏判断题 正确错误项目
                        $('input[name="row[true_false_answer]"]').closest('.form-group').removeClass('show');
                        $('input[name="row[true_false_answer]"]').closest('.form-group').addClass('hidden');
                        //显示常规答案项
                        $('#c-input_answer').closest('.form-group').removeClass('hidden');
                        $('#c-input_answer').closest('.form-group').addClass('show');
                        //加上必填
                        $('input[name="row[true_false_answer]"]').removeData('rule');
                        //删除标准答案文本框 的必填标志  data-rule="required"
                        $('#c-input_answer').data('rule', 'required');
                        break;
                    default:
                        break;
                }
            }
            Controller.api.bindevent();
        },
        edit: function () {
            //选择判断题，填空题，简单题隐藏选项栏，选择题显示选项栏
            //当前试题类型
            var type_val = $('select#c-type').find('option:selected').val();
            //页面初始化时
            switchPage(type_val);
            //修改操作
            $(document).on('change', "select#c-type", function () {
                var that = this;
                var option_val = $(that).find('option:selected').val();
                switchPage(option_val);
            });

            function switchPage(type_val) {
                switch (type_val) {
                    //判断题
                    case '1':
                        //选择题 选项
                        $('#c-option_content').closest('.form-group').removeClass('show');
                        $('#c-option_content').closest('.form-group').addClass('hidden');
                        //显示正确错误项
                        $('input[name="row[true_false_answer]"]').closest('.form-group').removeClass('hidden');
                        $('input[name="row[true_false_answer]"]').closest('.form-group').addClass('show');
                        //隐藏标准答案的文本框
                        $('#c-input_answer').closest('.form-group').removeClass('show');
                        $('#c-input_answer').closest('.form-group').addClass('hidden');
                        //加上必填
                        $('input[name="row[true_false_answer]"]').data('rule', 'required');
                        //删除标准答案文本框 的必填标志  data-rule="required"
                        $('#c-input_answer').removeData('rule');
                        break;
                    case '2':
                    case '3':
                        //选择题 选项
                        $('#c-option_content').closest('.form-group').removeClass('hidden');
                        $('#c-option_content').closest('.form-group').addClass('show');
                        //隐藏判断题 正确错误项目
                        $('input[name="row[true_false_answer]"]').closest('.form-group').removeClass('show');
                        $('input[name="row[true_false_answer]"]').closest('.form-group').addClass('hidden');
                        //显示常规答案项
                        $('#c-input_answer').closest('.form-group').removeClass('hidden');
                        $('#c-input_answer').closest('.form-group').addClass('show');
                        //加上必填
                        $('input[name="row[true_false_answer]"]').removeData('rule');
                        //删除标准答案文本框 的必填标志  data-rule="required"
                        $('#c-input_answer').data('rule', 'required');
                        break;
                    case '4'://填空题
                    case '5'://简答题
                        $('#c-option_content').closest('.form-group').removeClass('show');
                        $('#c-option_content').closest('.form-group').addClass('hidden');
                        //隐藏判断题 正确错误项目
                        $('input[name="row[true_false_answer]"]').closest('.form-group').removeClass('show');
                        $('input[name="row[true_false_answer]"]').closest('.form-group').addClass('hidden');
                        //显示常规答案项
                        $('#c-input_answer').closest('.form-group').removeClass('hidden');
                        $('#c-input_answer').closest('.form-group').addClass('show');
                        //加上必填
                        $('input[name="row[true_false_answer]"]').removeData('rule');
                        //删除标准答案文本框 的必填标志  data-rule="required"
                        $('#c-input_answer').data('rule', 'required');
                        break;
                    default:
                        break;
                }
            }
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