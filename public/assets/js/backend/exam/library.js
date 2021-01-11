define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'exam/library/index' + location.search,
                    add_url: 'exam/library/add',
                    edit_url: 'exam/library/edit',
                    del_url: 'exam/library/del',
                    multi_url: 'exam/library/multi',
                    table: 'exam_library',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                columns: [
                    [
                        { checkbox: true },
                        { field: 'id', title: __('Id') },
                        { field: 'cover_image', title: __('Cover_image'), events: Table.api.events.image, formatter: Table.api.formatter.image },
                        { field: 'name', title: __('Library_name') },
                        { field: 'question_count', title: __('Question_count') },
                        {
                            field: 'library_type',
                            title: __('Library_type'),
                            searchList: { "1": __('Library_type 1'), "2": __('Library_type 2'), "3": __('Library_type 3') },
                            custom: { 1: 'success', 2: 'warning', 3: 'primary' },
                            formatter: Table.api.formatter.normal
                        },
                        { field: 'baseorg.name', title: __('Baseorg.name') },
                        // {field: 'org_id', title: __('Org_id')},
                        { field: 'username', title: __('Username') },
                        {
                            field: 'status',
                            title: __('Status'),
                            custom: { yes: 1, no: 2 },
                            formatter: Table.api.formatter.toggle
                        },
                        {
                            field: 'front_show',
                            title: __('Front_show'),
                            custom: { yes: 1, no: 2 },
                            formatter: Table.api.formatter.toggle
                        },
                        { field: 'description', title: __('Description') },
                        { field: 'weigh', title: __('Weigh') },
                        { field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate }
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
                url: 'exam/library/recyclebin' + location.search,
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
                                    url: 'exam/library/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'exam/library/destroy',
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