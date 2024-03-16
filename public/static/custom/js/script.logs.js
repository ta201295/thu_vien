function loadResults(){
    var url =  "/issue-log";

    var table = $('#issue-logs-table');
    
    var default_tpl = _.template($('#all_logs_display').html());

    $.ajax({
        url : url,
        success : function(data){
            console.log(data);
            if($.isEmptyObject(data)){
                table.html('<tr><td colspan="99">No Logs</td></tr>');
            } else {
                table.html('');
                // console.log(JSON.stringify(data));
                
                for(var log in data){
                    table.append(default_tpl(data[log]));
                }
            }
        },
        beforeSend : function(){
            table.css({'opacity' : 0.4});
        },
        complete : function() {
            table.css({'opacity' : 1.0});
        }
    });
}

$(document).ready(function(){    
    loadResults();
});

