define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'exam/project/index' + location.search,
                    add_url: 'exam/project/add',
                    edit_url: 'exam/project/edit',
                    del_url: 'exam/project/del',
                    multi_url: 'exam/project/multi',
                    table: 'exam_project',
                }
            });
            $.fn.bootstrapTable.locales[Table.defaults.locale]['formatSearch'] = function () { return "考试名称搜索"; };
            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name')},
                        {field: 'baseorg.name', title: __('org_id')},
                        {field: 'duration', title: __('Duration')},
                        {field: 'start_date', title: __('Start_date'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'close_date', title: __('Close_date'), operate:'RANGE', addclass:'datetimerange'},
                        { field: 'status', title: __('Status'), searchList: { "1": __('Status 1'), "2": __('Status 2'), "3": __('Status 3') }, formatter: Table.api.formatter.status},
                        {field: 'allow_mock', title: __('Allow_mock'), searchList: {"1":__('Allow_mock 1'),"2":__('Allow_mock 2')}, formatter: Table.api.formatter.normal},
                        {field: 'total_times', title: __('Total_times')},
                        {field: 'pass_line', title: __('Pass_line')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

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
                url: 'exam/project/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name'), align: 'left'},
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
                                    url: 'exam/project/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'exam/project/destroy',
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