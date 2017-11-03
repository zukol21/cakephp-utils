/**
 * DataTables initialiser Logic.
 */
function DataTablesInit(options)
{
    this.options = options;

    var table = this.dataTable();

    if (this.options.batch) {
        this.batchToggle(table);
        this.batchSelect(table);
        this.batchClick(table);
    }

    return table;
}

DataTablesInit.prototype = {

    dataTable: function () {
        var that = this;

        var settings = {
            searching: false,
            language: {
                processing: '<i class="fa fa-refresh fa-spin fa-fw"></i> Processing...'
            },
            columnDefs: [
                {targets: [-1], orderable: false}
            ],
        };

        settings.order = [ this.options.order ? this.options.order : [0, 'asc'] ];

        // ajax specific options
        if (this.options.ajax) {
            settings.processing = true;
            settings.serverSide = true;
            settings.deferRender = true;
            settings.ajax = {
                url: this.options.ajax.url,
                headers: {
                    'Authorization': 'Bearer ' + this.options.ajax.token
                },
                data: function (d) {
                    // batch specific options
                    if (that.options.batch) {
                        d.primary_key = 1;
                        d.order[0].column -= 1;
                    }

                    if (that.options.ajax.extras) {
                        d = $.extend({}, d, that.options.ajax.extras);
                    }

                    d.limit = d.length;
                    d.page = 1 + d.start / d.length;

                    return d;
                },
                dataFilter: function (d) {
                    d = jQuery.parseJSON(d);
                    d.recordsTotal = d.pagination.count;
                    d.recordsFiltered = d.pagination.count;

                    return JSON.stringify(d);
                }
            };
        }

        // batch specific options
        if (this.options.batch) {
            settings.createdRow = function ( row, data, index ) {
                $(row).attr('data-id', data[0]);
                $('td', row).eq(0).text('');
            };
            settings.select = {
                style: 'multi',
                selector: 'td:first-child'
            };

            settings.columnDefs[0].targets.push(0);
            settings.columnDefs.push({targets: [0], className: 'select-checkbox'});
        }

        // state specific options
        if (this.options.state) {
            settings.stateSave = true;
            settings.stateDuration = this.options.state.duration;
        }

        var table = $(this.options.table_id).DataTable(settings);

        return table;
    },

    batchToggle: function (table) {
        var that = this;

        table.on('select', function () {
            $(that.options.batch.id).attr('disabled', false);
        });

        table.on('deselect', function (e, dt, type, indexes) {
            if (0 === table.rows('.selected').count()) {
                $(that.options.batch.id).attr('disabled', true);
            }
        });
    },

    batchClick: function (table) {
        $('*[data-batch="1"]').click(function (e) {
            e.preventDefault();

            var confirmed = true;
            // show confirmation message, if required
            if ($(this).data('batch-confirm')) {
                confirmed = confirm($(this).data('batch-confirm'));
            }

            if (!confirmed) {
                return;
            }

            var $form = $(
                '<form method="post" action="' + $(this).data('batch-url') + '"></form>'
            );
            $('#' + table.table().node().id + ' tr.selected').each(function () {
                $form.append('<input type="text" name="batch[ids][]" value="' + $(this).attr('data-id') + '">');
            });

            $form.appendTo('body').submit();
        });
    },

    batchSelect: function (table) {
        // select/deselect all table rows
        // @link https://stackoverflow.com/questions/42570465/datatables-select-all-checkbox?answertab=active#tab-top
        table.on('click', 'th.select-checkbox', function () {
            if ($('th.select-checkbox').hasClass('selected')) {
                table.rows().deselect();
                $('th.select-checkbox').removeClass('selected');
            } else {
                table.rows().select();
                $('th.select-checkbox').addClass('selected');
            }
        });

        // check/uncheck select-all checkbox based on rows select/deselect triggering
        // @link https://stackoverflow.com/questions/42570465/datatables-select-all-checkbox?answertab=active#tab-top
        table.on('select deselect', function () {
            if (table.rows({
                selected: true
            }).count() !== table.rows().count()) {
                $('th.select-checkbox').removeClass('selected');
            } else {
                $('th.select-checkbox').addClass('selected');
            }
        });
    }
};
