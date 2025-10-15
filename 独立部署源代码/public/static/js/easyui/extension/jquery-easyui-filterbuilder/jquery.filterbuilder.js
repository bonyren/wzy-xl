/**
 * Filter Builder for jQuery EasyUI
 * version: 1.0.1
 */
(function ($) {
    $(function () {
        if (!$('#filterbuilder-style').length) {
            $('head').append(
                '<style id="filterbuilder-style">' +
                '.fb-tree .tree-node{height:40px}' +
                '.fb-tree .tree-expanded,.fb-tree .tree-node-hover,.fb-tree .tree-node-selected{background:none}' +
                '.fb-tree .tree-icon,.fb-tree .tree-hit{background:none}' +
                '.fb-group-add,.fb-add,.fb-del{margin-left:5px}' +
                '.fb-op{margin:0 5px}' +
                '.fb-group-op{width: 60px;color: #fff;border-color: #b52b27;background: #d84f4b;filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#d84f4b,endColorstr=#c9302c,GradientType=0);}' +
                '.fb-group-op:hover{color: #fff;background: #c9302c;filter: none;}' +
                '.fb-op{width: 30px;color: #fff;border-color: #3c8b3c;background: #4cae4c;filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#4cae4c,endColorstr=#449d44,GradientType=0);}' +
                '.fb-op:hover{color: #fff;background: #449d44;filter: none;}' + 
                '.fb-field{width: 90px;color: #fff;border-color: #1f637b;background: #2984a4;filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#2984a4,endColorstr=#24748f,GradientType=0);}' +
                '.fb-field:hover{color: #fff;background: #24748f;filter: none;}' + 
                '.fb-tree .f-row .tree-hit{display:none;}' +
                '</style>'
            );
        }
    });

    function buildMenu(target, name, items) {
        var state = $.data(target, 'filterbuilder');
        var opts = state.options;
        if (!state[name]) {
            state[name] = $('<div></div>').appendTo(target).menu({
                height: 'auto',
                onShow: function () {
                    $(this).css('overflow', 'hidden');
                },
                onClick: function (item) {
                    var opts = $(this).menu('options');
                    $(opts.alignTo).menubutton({ text: item.text });
                    $(opts.alignTo).menubutton('options').onMenuClick(item);
                }
            });
            for (var i = 0; i < items.length; i++) {
                state[name].menu('appendItem', items[i]);
            }
        }
        return state[name];
    }

    function updateMenu(target, name, items) {
        var state = $.data(target, 'filterbuilder');
        if (!state['name']) {
            buildMenu(target, name, items);
        }
        state[name].empty();
        for (var i = 0; i < items.length; i++) {
            state[name].menu('appendItem', items[i]);
        }
    }

    function destroyEditor(target) {
        var roots = $(target).tree('getRoots');
        $.easyui.forEach(roots, true, function (node) {
            if (node.editortype) {
                $('#' + node.domId).find('.fb-editor')[node.editortype]('destroy');
                node.editortype = null;
            }
        });
    }

    function parseRows(target) {
        var state = $.data(target, 'filterbuilder');
        var opts = state.options;
        var groupAddMenu = buildMenu(target, 'groupAddMenu', opts.groupMenus)
        var groupOpMenu = buildMenu(target, 'groupOpMenu', $.map(opts.groupOperators, function (item) {
            return { name: item.op, text: item.text };
        }));
        var fieldMenu = buildMenu(target, 'fieldMenu', $.map(opts.fields, function (item) {
            return { name: item.field, text: item.title || item.text }
        }));
        var opMenu = buildMenu(target, 'opMenu', $.map(opts.operators, function (item) {
            return { name: item.op, text: item.text }
        }));

        destroyEditor(target);
        $(target).find('.tree-node').addClass('f-row');
        $(target).find('.tree-title').addClass('f-row f-full');
        $(target).find('.fb-group-row').each(function () {
            var nodeEl = $(this).closest('.tree-node');
            var domId = nodeEl.attr('id');
            var node = $(target).tree('findBy', { field: 'domId', value: domId });

            $(this).empty();
            var cc = $('<div class="f-full"></div>').appendTo(this);
            var mb = $('<a href="javascript:;" class="fb-group-op"></a>').appendTo(cc).menubutton({
                text: $.easyui.getArrayItem(opts.groupOperators, 'op', node.op).text,
                menu: groupOpMenu,
                hasDownArrow: false,
                showEvent: 'click',
                onMenuClick: function (item) {
                    node.op = item.name;
                }
            });
            var mb = $('<a href="javascript:;" class="fb-group-add"></a>').appendTo(this).menubutton({
                iconCls: 'icon-add',
                menu: groupAddMenu,
                hasDownArrow: false,
                showEvent: 'click',
                onMenuClick: function (item) {
                    if (item.name == 'group') {
                        $(target).tree('append', {
                            parent: nodeEl,
                            data: [{
                                op: opts.groupOperators[0].op,
                                value: '',
                                children: []
                            }]
                        });
                    } else {
                        $(target).tree('append', {
                            parent: nodeEl,
                            data: [{
                                field: opts.fields[0].field,
                                op: opts.operators[0].op,
                                value: ''
                            }]
                        });
                    }
                    parseRows(target);
                }
            });
            if ($(target).tree('getRoot').domId != domId) {
                var del = $('<a href="javascript:;" class="fb-del"></a>').appendTo(this).linkbutton({
                    plain: true,
                    iconCls: 'icon-remove',
                    onClick: function () {
                        $(target).tree('remove', nodeEl);
                        parseRows(target);
                    }
                })
            }
        });

        function getOperatorItem(node) {
            var fieldOpts = $.easyui.getArrayItem(opts.fields, 'field', node.field);
            var operators = fieldOpts.operators || opts.operators;
            var opItem = $.easyui.getArrayItem(operators, 'op', node.op);
            if (!opItem) {
                opItem = operators[0];
            }
            return opItem;
        }

        function buildEditor(cc, node) {
            var opItem = getOperatorItem(node);
            var editor = opItem.editor || { type: 'textbox', options: {required:true,validType:['number','length[1,10]']}};
            var type = editor.type;
            var opts = $.extend({}, editor.options, {
                value: node.value,
                onChange: function (value) {
                    node.value = value;
                }
            });
            opts.width = opts.width || 100;

            if (node.editortype != type) {
                var obj = cc.find('.fb-editor');
                if (obj.length) {
                    obj[node.editortype]('destroy');
                }
                obj = $('<input class="fb-editor">').appendTo(cc);
                obj[type](opts);
                node.editortype = type;
            }
        }

        $(target).find('.fb-row').each(function () {
            var nodeEl = $(this).closest('.tree-node');
            var domId = nodeEl.attr('id');
            var node = $(target).tree('findBy', { field: 'domId', value: domId });
            var opItem = getOperatorItem(node);
            node.op = opItem.op;

            $(this).empty();
            var cc = $('<div class="f-full"></div>').appendTo(this);
            var mb = $('<a href="javascript:;" class="fb-field"></a>').appendTo(cc).menubutton({
                text: $.easyui.getArrayItem(opts.fields, 'field', node.field).title,
                menu: fieldMenu,
                hasDownArrow: false,
                showEvent: 'click',
                onMenuClick: function (item) {
                    node.field = item.name;
                    var opItem = getOperatorItem(node);
                    node.op = opItem.op;
                    op.menubutton({ text: opItem.text });
                    buildEditor(cc, node);
                }
            });
            var op = $('<a href="javascript:;" class="fb-op"></a>').appendTo(cc).menubutton({
                text: opItem.text,
                menu: opMenu,
                hasDownArrow: false,
                showEvent: 'click',
                onMenuClick: function (item) {
                    node.op = item.name;
                    buildEditor(cc, node);
                },
                onClick: function () {
                    var fieldOpts = $.easyui.getArrayItem(opts.fields, 'field', node.field);
                    var operators = fieldOpts.operators || opts.operators;
                    updateMenu(target, 'opMenu', $.map(operators, function (item) {
                        return { name: item.op, text: item.text }
                    }));
                }
            });
            buildEditor(cc, node);
            var del = $('<a href="javascript:;" class="fb-del"></a>').appendTo(this).linkbutton({
                plain: true,
                iconCls: 'icon-remove',
                onClick: function () {
                    $(target).tree('remove', nodeEl);
                    parseRows(target);
                }
            })
        });
    }

    function build(target) {
        var state = $.data(target, 'filterbuilder');
        var opts = state.options;
        var data = opts.rules || [];
        if (!data.length) {
            data = [{
                op: 'and',
                children: []
            }];
        }

        $(target).addClass('fb-tree').tree({
            data: data,
            animate: false,
            formatter: function (node) {
                if (node.children) {
                    return '<div class="fb-group-row f-row f-full"></div>';
                } else {
                    return '<div class="fb-row f-row f-full"></div>';
                }
            },
            onCollapse: function (node) {
                $(this).tree('expand', node.target);
            }
        });
        parseRows(target);
    }

    $.fn.filterbuilder = function (options, param) {
        if (typeof options == 'string') {
            var method = $.fn.filterbuilder.methods[options];
            if (method) {
                return method(this, param);
            } else {
                return this.tree(options, param);
            }
        }
        options = options || {};
        return this.each(function () {
            var state = $.data(this, 'filterbuilder');
            if (state) {
                $.extend(state.options, options);
            } else {
                state = $.data(this, 'filterbuilder', {
                    options: $.extend({}, $.fn.filterbuilder.defaults, $.fn.filterbuilder.parseOptions(this), options)
                });
                build(this);
            }
        });
    };

    $.fn.filterbuilder.methods = {
        options: function (jq) {
            return $.data(jq[0], 'filterbuilder').options;
        },
        getRules: function (jq) {
            var rules = $.extend(true, [], jq.tree('getRoots'));
            $.easyui.forEach(rules, true, function (node) {
                delete node.target;
                delete node.domId;
                delete node.state;
                delete node.checkState;
                delete node.checked;
                delete node.editortype;
            });
            return rules[0];
        }
    };

    $.fn.filterbuilder.parseOptions = function (target) {
        var t = $(target);
        return $.extend({}, $.parser.parseOptions(target, [
        ]));
    };

    $.fn.filterbuilder.defaults = {
        rules: [],
        fields: [],
        groupMenus: [
            { name: 'condition', text: 'Add Condition' },
            { name: 'group', text: 'Add Group' }
        ],
        groupOperators: [
            { op: 'and', text: 'And' },
            { op: 'or', text: 'Or' }
        ],
        operators: [
            { op: 'contains', text: 'Contains' },
            { op: 'equal', text: 'Equal' },
            { op: 'notequal', text: 'Not Equal' },
            { op: 'beginwith', text: 'Begin With' },
            { op: 'endwith', text: 'End With' },
            { op: 'less', text: 'Less' },
            { op: 'lessorequal', text: 'Less Or Equal' },
            { op: 'greater', text: 'Greater' },
            { op: 'greaterorequal', text: 'Greater Or Equal' }
        ]
    };

    $.parser.plugins.push('filterbuilder');
})(jQuery);
