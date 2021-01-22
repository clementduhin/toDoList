
$('.deleteToDo').on('click', function(){
    console.log($(this).attr('id'));
})

function deleteToDo(idToDelete){
    $.ajax({  
        url:        '/deleteToDo/'+idToDelete,  
        type:       'POST',   
        dataType:   'json',  
        async:      true,  
        
        success: function(data, status) {  
            
            var e = '';  
            $('#allToDos').html('');  
            $('#allToDos').append(e);  
            
            for(i = 0; i < data.length; i++) {  
                toDo = data[i];  
                var e = $('<tr><td id = "name"></td><td id = "description"></td><td id = "limitedAt"></td><td id="statut"></td><td id="deleteToDo"></td></tr>');
                $('#name', e).html(toDo['name']);  
                $('#description', e).html(toDo['description']);
                let date = new Date(toDo['limitedAt']['date']);
                $('#limitedAt', e).html(date.getDate()+'/'+(date.getMonth()+1)+'/'+date.getFullYear());  
                if(toDo['statut']){
                $('#statut', e).html('Fait !');  
                } else {
                $('#statut', e).html('Pas encore fait !'); 
                }
                $('#deleteToDo', e).html('<button class="btn btn-danger deleteToDo" href="deleteToDo/'+data[i]['id']+'">X</button>')
                $('#allToDos').append(e);  
                console.log(data[i]['id']);
            }  
        },  
        error : function(xhr, textStatus, errorThrown) {  
            alert('Ajax request failed.');  
        }  
    });  
}

$('.deleteToDo').on('click', function(event){
    let idToDelete = $(this).attr('id');
    deleteToDo(idToDelete);
})