$(function () {
    $('#databaseConnectionSelector').on('change', function () {
        jQuery.ajax({
            url: 'generate/'+$('#databaseConnectionSelector option:selected').html(),
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

    $('.generateCheckbox').on('click',function(){
       if($(this).attr('name') =='database')
       {
           var databaseSelectionHolder = $('#databaseSelectionHolder');
           var normalSelectionHolder = $('#normalSelectionHolder');
           if($(this).is(':checked'))
           {
               databaseSelectionHolder.show();
               normalSelectionHolder.hide();
           }
           else
           {
               databaseSelectionHolder.hide();
               normalSelectionHolder.show();
           }
       }
    });
});