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

function returnBook(bookID, selectedForm){
    var url =  '/issue-log/' + bookID + '/edit';
    $.ajax({
        type : 'GET',
        
        url : url,
        success: function(data) {
            selectedForm.prepend(templates.alert_box( {type: 'success', message: data} ));
            ClearReturn();
            $('#return_book_id').focus();

        },
        error: function(xhr, status, error){
            var err = jQuery.parseJSON(xhr.responseText).error;
            selectedForm.prepend(templates.alert_box( {type: 'danger', message: err.message} ));
        },
        beforeSend: function() {
            selectedForm.css({'opacity' : '0.4'});
        },
        complete: function() {
            selectedForm.css({'opacity' : '1.0'});
        }
    });
}

function ClearReturn(){
    $('#return_book_id').val('') // if you want the value to be empty you will make the like this
}

function ClearIssueBook(){
    $('#issue_book_id').val('') // if you want the value to be empty you will make the like this
    $('#issue_student_id').val('') // if you want the value to be empty you will make the like this
}


$(document).ready(function(){
    $(document).on("click","#issuebook",function(){
        var selectedForm = $(this).parents('form'),
            bookStudentId = selectedForm.find("#book_student").val();
        
        if(bookStudentId == ""){
            selectedForm.prepend(templates.alert_box( {type: 'danger', message: "Invalid Data"} ));
        } else {
            issueBook(bookStudentId, selectedForm);
        }
    });

    $(document).on("click","#returnbook",function(){
        var selectedForm = $(this).parents('form'),
            bookID = selectedForm.find("input[data-form-field~=book-issue-id]").val();
        
        if(bookID == ""){
            selectedForm.prepend(templates.alert_box( {type: 'danger', message: "Invalid Data"} ));
        } else {
            returnBook(bookID, selectedForm);
        }
    });
    
    loadResults();

});

