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
//todo: inplement on click model explorer 
//http://msmvc.local.nl/generate/model/gebruikersModel
function syntaxHighlight(json) {
    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
        var cls = 'number';
        if (/^"/.test(match)) {
            if (/:$/.test(match)) {
                cls = 'key';
            } else {
                cls = 'string';
            }
        } else if (/true|false/.test(match)) {
            cls = 'boolean';
        } else if (/null/.test(match)) {
            cls = 'null';
        }
        return '<span class="' + cls + '">' + match + '</span>';
    });
}

//var str = JSON.stringify(obj, undefined, 4);
