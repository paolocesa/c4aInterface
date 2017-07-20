<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>City4Age</title>

    <!-- Bootstrap Core CSS -->
    <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../../dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="../../dist/css/style.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../../vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery -->
    <script src="../../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../../vendor/metisMenu/metisMenu.min.js"></script>


    <!-- Table -->
    <link rel="stylesheet" href="../../dist/css/bootstrap-table.css"/>
    <script type="text/javascript" src="../../dist/js/bootstrap-table.js"></script>
    <script type="text/javascript" src="../../dist/js/bootstrap-table-filter-control.js"></script>
    <script type="text/javascript" src="../../js/flatJSON.js"></script>
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <a class="navbar-brand" href="intervention_index.html">City4Age - Installation: Lecce</a>
            </div>
            <!-- /.navbar-header -->
            <!-- /.navbar-top-links -->
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="panel">
                <div id="toolbar">
                    <div class="row">
                        <div class="col-lg-12">
                        <button id="edit_btn" class="btn btn-info">
                            <i class="glyphicon fa fa-pencil"></i> Edit
                        </button>
                        </div>
                    </div>
                    <div class="panel"></div>
                    <div class="row">
                        <div class="col-lg-12">
                            <button id="remove" class="btn btn-danger" disabled>
                                <i class="glyphicon glyphicon-remove"></i> Delete
                            </button>
                            <button id="save" class="btn btn-success">
                                <i class="glyphicon glyphicon-save"></i> Save
                            </button>
                        </div>
                    </div>
                    <div class="panel"></div>
                </div>

                <table id="table" style="table-layout: fixed;"
                    data-toolbar="#toolbar"
                    data-search="false"
                    data-show-refresh="false"
                    data-show-toggle="false"
                    data-show-columns="true"
                    data-show-export="false"
                    data-detail-view="false"
                    data-detail-formatter="detailFormatter"
                    data-minimum-count-columns="2"
                    data-show-pagination-switch="false"
                    data-pagination="false"
                    data-id-field="ID"
                    data-page-list="[10, 25, 50, 100, ALL]"
                    data-show-footer="false"
                    data-side-pagination="server"
                    data-url="../json/miniplans.json"
                    data-response-handler="responseHandler"
                    >
                </table>
            </div>

            <script>
                var $table = $('#table'),
                    $remove = $('#remove'),
                    $save=$('#save'),
                    $edit_btn=$('#edit_btn'),
                    all_data=[],
                    selections = [];
                    is_editing=false;
                $remove.addClass('hidden');
                $save.addClass('hidden');

                var order_col_name="";
                var order_type="";

                function initTable() {
                    $table.bootstrapTable({
                        height: getHeight(),
                        columns: [
                            [
                                {
                                    field: 'state',
                                    radio: true,
                                    rowspan: 1,
                                    align: 'center',
                                    valign: 'middle'
                                }, {
                                    title: 'Message Text',
                                    field: 'Body',
                                    rowspan: 1,
                                    align: 'left',
                                    valign: 'middle',
                                    sortable: true,
                                    editable: {
                                        type:'textarea',
                                        rows:10,
                                        placement:'right'
                                    },
                                    noedit:function(value, row, index) {
                                        console.log(is_editing);
                                        if (is_editing) {
                                            return false;  // return false if you want the field editable.
                                        }else{
                                            return true;
                                        }
                                        $table.bootstrapTable('refresh');
                                    },
                                    width: '30%',
                                    formatter:text_formatter,
                                    footerFormatter: totalTextFormatter
                                }, {
                                    title: 'URL',
                                    field: 'Url',
                                    rowspan: 1,
                                    align: 'center',
                                    valign: 'middle',
                                    sortable: false,
                                    formatter: my_url_formatter,
                                    width: '10%',
                                    footerFormatter: totalTextFormatter
                                },
                                {
                                    title: 'Attached media',
                                    field: 'Images',
                                    rowspan: 1,
                                    align: 'center',
                                    valign: 'middle',
                                    sortable: false,
                                    formatter: my_url_formatter,
                                    width: '10%',
                                    footerFormatter: totalTextFormatter
                                },
                                {
                                    title: 'Attached Audio/Video',
                                    field: 'Others',
                                    rowspan: 1,
                                    align: 'center',
                                    valign: 'middle',
                                    sortable: false,
                                    formatter: my_url_formatter,
                                    width: '10%',
                                    footerFormatter: totalTextFormatter
                                },
                                {
                                    title: 'Channel ',
                                    field: 'Channel',
                                    rowspan: 1,
                                    align: 'center',
                                    valign: 'middle',
                                    sortable: true,
                                    editable:{
                                        type: 'select',
                                        source:[
                                            {value: 'SMS', text: 'SMS'},
                                            {value: 'Messenger', text: 'Messenger'},
                                            {value: 'FB page', text: 'FB page'},
                                        ]
                                    },
                                    footerFormatter: totalTextFormatter
                                },
                                {
                                    title: 'Date',
                                    field: 'Date',
                                    rowspan: 1,
                                    align: 'center',
                                    valign: 'middle',
                                    sortable: true,
                                    editable:true,
                                    footerFormatter: totalTextFormatter
                                },
                                {
                                    title: 'Time',
                                    field: 'Time',
                                    rowspan: 1,
                                    align: 'center',
                                    valign: 'middle',
                                    sortable: true,
                                    editable:true,
                                    footerFormatter: totalTextFormatter
                                }
                            ]
                        ]
                    });
                    // sometimes footer render error.
                    setTimeout(function () {
                        $table.bootstrapTable('resetView');
                    }, 200);
                   
                    $table.on('check.bs.table uncheck.bs.table ' +
                            'check-all.bs.table uncheck-all.bs.table', function () {
                        $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);

                        // save your data, here just save the current page
                        selections = getIdSelections();
                        // push or splice the selections if you want to save all data selections
                    });
                    $table.on('expand-row.bs.table', function (e, index, row, $detail) {
                        if (index % 2 == 1) {
                            $detail.html('Loading from ajax request...');
                            $.get('LICENSE', function (res) {
                                $detail.html(res.replace(/\n/g, '<br>'));
                            });
                        }
                    });
                    $table.on('sort.bs.table', function (event,name,order) {
                        console.log(event,name,order);
                        order_col_name=name;
                        order_type=order;
                    });
                    $table.on('all.bs.table', function (e, name, args) {
                        console.log(name, args);
                    });
                    $edit_btn.click(function () {
                        $edit_btn.addClass('disabled');
                        $remove.removeClass('hidden');
                        $save.removeClass('hidden');
                        $table.bootstrapTable('showColumn', 'state');
                        is_editing=true;

                    });
                    $remove.click(function () {
                        var ids = getIdSelections();
                        console.log(ids);
                        $table.bootstrapTable('remove', {
                            field: 'ID',
                            values: ids
                        });
                        $remove.prop('disabled', true);
                    });
                    $save.click(function(){
                        console.log($table.bootstrapTable('getData'));
                        $.each(all_data,function(key,value){
                            var trovato=false;
                            $.each($table.bootstrapTable('getData'),function(i,row){
                                if(value.ID==row.ID){
                                    all_data[key]=row;
                                }
                            });
                        });
                        console.log(all_data);
                        $remove.addClass('hidden');
                        $save.addClass('hidden');
                        $edit_btn.removeClass('disabled');

                        $table.bootstrapTable('hideColumn', 'state');
                        //window.close();
                    });
                    $(window).resize(function () {
                        $table.bootstrapTable('resetView', {
                            height: getHeight()
                        });
                    });
                }

                function getIdSelections() {
                    return $.map($table.bootstrapTable('getSelections'), function (row) {
                        return row.ID
                    });
                }

                function responseHandler(res) {
                    console.log(res);
                    var newRes=[];
                    if($table.bootstrapTable('getData').length>0){
                        newRes=$table.bootstrapTable('getData');
                    }else{
                        $.each(res.rows, function (i, row) {
                            row.state = $.inArray(row.ID, selections) !== -1;
                            var resID=<?php echo $_POST['resID']?>;
                            var tempID=<?php echo $_POST['tempID']?>;
                            if(row.Resource_ID==resID && row.Template_ID==tempID)
                            {
                                newRes.push(row);
                            }
                        });
                    }
                    res.rows=newRes;
                    if(order_col_name.length>0 && order_type.length>0){
                        if(order_type=="asc"){
                             res.rows.sort(function (a,b) { return (a[order_col_name] > b[order_col_name]) ? 1 : ((b[order_col_name] > a[order_col_name]) ? -1 : 0)});
                         }
                         if(order_type=="desc"){
                             res.rows.sort(function (a,b) { return (a[order_col_name] > b[order_col_name]) ? -1 : ((b[order_col_name] > a[order_col_name]) ? 1 : 0)});
                         }
                    }
                    console.log(res);
                    $table.bootstrapTable('hideColumn', 'state');
                    //$table.bootstrapTable('editable',false);

                    
                    
                    // for (var i = 0; i < res.data.length; i++) {
                    //     var index_th= $('#table th[data-field=name]').index();
                    //     var index_td= $('#table a[data-name=name]').parent().index();
                    //     if(index_th==index_td){
                    //     $('#table a[data-name=name].editable').editable('toggleDisabled');
                    //     }   
                    // }

                    return res;
                }

                JSON.flatten = function(data) {
                    var result = {};
                    function recurse (cur, prop) {
                        if (Object(cur) !== cur) {
                            result[prop] = cur;
                        } else if (Array.isArray(cur)) {
                            for(var i=0, l=cur.length; i<l; i++)
                                recurse(cur[i], prop + "[" + i + "]");
                            if (l == 0)
                                result[prop] = [];
                        } else {
                            var isEmpty = true;
                            for (var p in cur) {
                                isEmpty = false;
                                recurse(cur[p], prop ? prop+"."+p : p);
                            }
                            if (isEmpty && prop)
                                result[prop] = {};
                        }
                    }
                    recurse(data, "");
                    return result;
                }

                function my_url_formatter(value, row, index) {
                    if(value!="none"){
                        return "<a href='" + value + "' target='_blank' style='word-wrap:break-word'>" + value + "</a>";
                    }
                    return value;
                }

                function text_formatter(value, row, index) {
                    //return "<span style='text-decoration:none'>" + value + "</span>";
                    return value;
                }

                function detailFormatter(index, row) {
                    var html = [];
                    $.each(row, function (key, value) {
                        html.push('<p><b>' + key + ':</b> ' + value + '</p>');
                    });
                    return html.join('');
                }

                function operateFormatter(value, row, index) {
                    return [
                        '<a class="like" href="javascript:void(0)" title="Like">',
                        '<i class="glyphicon glyphicon-heart"></i>',
                        '</a>  ',
                        '<a class="remove" href="javascript:void(0)" title="Remove">',
                        '<i class="glyphicon glyphicon-remove"></i>',
                        '</a>'
                    ].join('');
                }

                window.operateEvents = {
                    'click .like': function (e, value, row, index) {
                        alert('You click like action, row: ' + JSON.stringify(row));
                    },
                    'click .remove': function (e, value, row, index) {
                        $table.bootstrapTable('remove', {
                            field: 'id',
                            values: [row.id]
                        });
                    }
                };

                function totalTextFormatter(data) {
                    return 'Total';
                }

                function totalNameFormatter(data) {
                    return data.length;
                }

                function totalPriceFormatter(data) {
                    var total = 0;
                    $.each(data, function (i, row) {
                        total += +(row.price.substring(1));
                    });
                    return '$' + total;
                }

                function getHeight() {
                    return $(window).height() - $('h1').outerHeight(true);
                }

                $(function () {
                    var scripts = [
                            location.search.substring(1) || '../../vendor/bootstrap-table/src/bootstrap-table.js',
                            '../../vendor/bootstrap-table/src/extensions/export/bootstrap-table-export.js',
                            'http://rawgit.com/hhurz/tableExport.jquery.plugin/master/tableExport.js',
                            '../../vendor/bootstrap-table/src/extensions/editable/bootstrap-table-editable.js',
                            'http://rawgit.com/vitalets/x-editable/master/dist/bootstrap3-editable/js/bootstrap-editable.js'
                        ],
                        eachSeries = function (arr, iterator, callback) {
                            callback = callback || function () {};
                            if (!arr.length) {
                                return callback();
                            }
                            var completed = 0;
                            var iterate = function () {
                                iterator(arr[completed], function (err) {
                                    if (err) {
                                        callback(err);
                                        callback = function () {};
                                    }
                                    else {
                                        completed += 1;
                                        if (completed >= arr.length) {
                                            callback(null);
                                        }
                                        else {
                                            iterate();
                                        }
                                    }
                                });
                            };
                            iterate();
                        };

                    eachSeries(scripts, getScript, initTable);
                });

                function getScript(url, callback) {
                    var head = document.getElementsByTagName('head')[0];
                    var script = document.createElement('script');
                    script.src = url;

                    var done = false;
                    // Attach handlers for all browsers
                    script.onload = script.onreadystatechange = function() {
                        if (!done && (!this.readyState ||
                                this.readyState == 'loaded' || this.readyState == 'complete')) {
                            done = true;
                            if (callback)
                                callback();

                            // Handle memory leak in IE
                            script.onload = script.onreadystatechange = null;
                        }
                    };

                    head.appendChild(script);

                    // We handle everything using the script element injection
                    return undefined;
                }

                
            </script>
            </div>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    

    <!-- Custom Theme JavaScript -->
    <script src="../../dist/js/sb-admin-2.js"></script>
    <script src="../../dist/js/plans.js"></script>

</body>

</html>


