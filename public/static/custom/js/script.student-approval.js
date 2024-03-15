function loadResults(){
    var url =  "/book-student/pending?branch=" + $('#branch_select').val();

    if($('#category_select').val() != 0){
        url += "&category=" + $('#category_select').val();
    }

    var table = $('#approval-table');
    
    var default_tpl = _.template($('#approvalstudents_show').html());

    $.ajax({
        url : url,
        success : function(data){
            if($.isEmptyObject(data)){
                table.html('<tr><td colspan="99">No Students for approval for these filters</td></tr>');
            } else {
                table.html('');
                for (var student in data) {
                    table.append(default_tpl(data[student]));
                }
            }
        }
    });
}

function approveStudent(bookStudentID, flag, btn) {
    var module_body = btn.parents('.module-body'),
        table = $('#approval-table');

    $.ajax({
        type : 'PUT',
        data : { 
            _method : "PUT", 
            status : flag,
            _token:_token
        },
        url : '/book-student/' + bookStudentID,
        success: function(data) {
            module_body.prepend(templates.alert_box( {type: 'success', message: data} ));
            loadResults();
        },
        error: function(xhr, msg){
            module_body.prepend(templates.alert_box( {type: 'danger', message: msg} ));     
        },
        beforeSend: function() {
            table.css({'opacity' : '0.4'});
        },
        complete: function() {
            table.css({'opacity' : '1.0'});
        }
    });
}

$(document).ready(function(){
    $("#category_select").change(function(){
        loadResults();
    });

    $(document).on("click",".student-status",function(){
        var selectedRow = $(this).parents('tr'),
            bookStudentID = selectedRow.data('book-student-id')
            flag = $(this).data('status');
        
        approveStudent(bookStudentID, flag, $(this));
    });
    
    loadResults();
});
