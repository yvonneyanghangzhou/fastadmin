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

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name')},
                        {field: 'duration', title: __('Duration')},
                        {field: 'allow_mock', title: __('Allow_mock'), searchList: {"1":1,"2":2}, formatter: Table.api.formatter.normal},
                        {field: 'mock_start_date', title: __('Mock_start_date')},
                        {field: 'mock_close_date', title: __('Mock_close_date')},
                        {field: 'total_times', title: __('Total_times')},
                        {field: 'start_date', title: __('Start_date')},
                        {field: 'close_date', title: __('Close_date')},
                        {field: 'pass_line', title: __('Pass_line')},
                        {field: 'question_num', title: __('Question_num')},
                        {field: 'enable_commitment', title: __('Enable_commitment'), searchList: {"1":__('Enable_commitment 1'),"2":__('Enable_commitment 2')}, formatter: Table.api.formatter.normal},
                        {field: 'commitment_content', title: __('Commitment_content')},
                        {field: 'allow_print_commitment', title: __('Allow_print_commitment'), searchList: {"2":__('Allow_print_commitment 2'),"1":__('Allow_print_commitment 1')}, formatter: Table.api.formatter.normal},
                        {field: 'allow_print_certificate', title: __('Allow_print_certificate'), searchList: {"2":__('Allow_print_certificate 2'),"1":__('Allow_print_certificate 1')}, formatter: Table.api.formatter.normal},
                        {field: 'college_id', title: __('College_id')},
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