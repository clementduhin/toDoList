const inputName = document.querySelector('.newName');
const inputDescription = document.querySelector('.newDescription');
const inputStatut = document.querySelector('.newStatut');
const inputDate = document.querySelector('.newLimitedAt');


$(".imgAvatar").hover(function(){
    $(".btnDeco").addClass('visible');
    $(this).removeClass('visible');
})

$(".btnDeco").mouseleave(function(){
    $(".imgAvatar").addClass('visible');
    $(this).removeClass('visible');
})

function addToDo(){

    var maRequeteAjout = new XMLHttpRequest();

    maRequeteAjout.open('POST','/');

    maRequeteAjout.setRequestHeader('Content-type','application/x-www-form-urlencoded');
    
    let newName = inputName.value;
    let newDescription = inputDescription.value;
    let newStatut = inputStatut.value;
    let newLimitedAt = new Intl.DateTimeFormat('fr-FR').format(new Date());
    let date = new Date();
    let mesParametres = 'name=' + newName + '&description=' + newDescription + '&statut=' + newStatut + '&limitedAt=' + newLimitedAt;

    maRequeteAjout.send(mesParametres);

    return  $.ajax({  
        url:        '/viewToDo',  
        type:       'POST',   
        dataType:   'json',  
        async:      true,  
        
        success: function(data, status) {  
            
            var e = '';  
            $('#allToDos').html('');  
            $('#allToDos').append(e);  
            
            for(i = 0; i < data.length; i++) {  
                toDo = data[i];  
                var e = $('<tr><td id = "name"></td><td id = "description"></td><td id = "limitedAt"></td><td id="statut"></td></tr>');
                $('#name', e).html(toDo['name']);  
                $('#description', e).html(toDo['description']);
                let date = new Date(toDo['limitedAt']['date']);
                console.log(date);
                $('#limitedAt', e).html(date.getDate()+'/'+(date.getMonth()+1)+'/'+date.getFullYear());  
                if(toDo['statut']){
                $('#statut', e).html('Fait !');  
                } else {
                   $('#statut', e).html('Pas encore fait !'); 
                }
                $('#allToDos').append(e);  
            }  
        },  
        error : function(xhr, textStatus, errorThrown) {  
            alert('Ajax request failed.');  
        }  
    });  
}

$(".addToDo").on("click", function(event){  
    event.preventDefault();
    addToDo();
}); 




 