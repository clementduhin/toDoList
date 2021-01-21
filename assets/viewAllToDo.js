

if(jQuery) {
    alert('coucou');
}

$(document).ready(function(){   
    $("#btn").on("click", function(event){  
    $.ajax({  
        url:        '/',  
        type:       'POST',   
        dataType:   'json',  
        async:      true,  
        
        success: function(data, status) {  
            var e = $('<tr><td>Name</td><td>Description</td><td>Date limite</td><td>Fait/ou pas</td></tr>');  
            $('#allToDos').html('');  
            $('#allToDos').append(e);  
            
            for(i = 0; i < data.length; i++) {  
                toDo = data[i];  
                var e = $('<tr><td id = "name"></td><td id = "description"></td><td id = "limitedAt"></td><td id="statut"></td></tr>');
                
                $('#name', e).html(toDo['name']);  
                $('#description', e).html(toDo['description']);  
                $('#limitedAt', e).html(toDo['limitedAt']);  
                $('#statut', e).html(toDo['statut']);  
                $('#allToDos').append(e);  
            }  
        },  
        error : function(xhr, textStatus, errorThrown) {  
            alert('Ajax request failed.');  
        }  
    });  
    });  
});  