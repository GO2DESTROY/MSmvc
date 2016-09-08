$(function () {
    $('#databaseConnectionSelector').on('change', function () {
        jQuery.ajax({
            url: 'generate/table/'+$('#databaseConnectionSelector option:selected').html(),
            success: function (data) {
                var tableSelector =  $('#tableDatabaseSelector');
                if(typeof data.tables !== 'undefined' && data.tables.length > 0)
                {
                    tableSelector.prop('disabled', false);
                    tableSelector.html('');
                    for(var i=0; i< data.tables.length; i++)
                    {
                        tableSelector.append('<option value="'+data.tables[i]['tables']+'">'+data.tables[i]['tables']+'</option>')
                    }
                }
                else
                {
                    tableSelector.prop('disabled', true);
                    tableSelector.html("<option disabled>The current database doesn't contain any tables</option>");
                }
            }
        });
    });

    $('.generatorRadio').on('click',function(e){
        $(this).tab('show');
    });
});

//var str = JSON.stringify(obj, undefined, 4);
