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
                        { checkbox: true },
                        { field: 'id', title: __('Id') },
                        { field: 'name', title: __('Name') },
                        { field: 'duration', title: __('Duration') },
                        {
                            field: 'start_date',
                            title: __('Start_date'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'close_date',
                            title: __('Close_date'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'exam_status_text',
                            title: __('状态'),
                            operate: false,
                            searchList: { "1": __('未开始'), "2": __('进行中'), "3": __('已结束') }, 
                            formatter: Table.api.formatter.normal
                        },
                        { field: 'allow_mock', title: __('Allow_mock'), searchList: { "1": '是', "2": '否' }, operate: false,formatter: Table.api.formatter.normal },
                        { field: 'total_times', title: __('Total_times') },
                        { field: 'pass_line', title: __('Pass_line') },
                        { field: 'question_num', title: __('Question_num') },
                        { field: 'college_id', title: __('College_id') },
                        { field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate }
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