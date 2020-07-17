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
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'username', title: __('Username')},
                        {field: 'name', title: __('Name')},
                        {field: 'org_name', title: __('院系单位')},
                        {field: 'class_name', title: __('Class_name')},
                        {field: 'major', title: __('Major')},
                        {field: 'grade', title: __('Grade')},
                        {field: 'type', title: __('Type') },
                        { field: 'online_time', title: __('在线时长') },
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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