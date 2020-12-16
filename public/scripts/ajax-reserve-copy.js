$(document).ready(function() {
    $("#ajax_form").on('click', '.turn_btn',
        function(){
            var turn = $(this).attr('value');
            console.log(turn);
            $("#turn").attr('value', turn);
    
            sendAjaxForm('ajax_form');
			return false; 
		}
    );
});

function sendAjaxForm(ajax_form) {
    $.ajax({
        url:     'http://litic-tac-toe.com/play/turn',
        type:     "POST",
        dataType: "html",
        data: $("#"+ajax_form).serialize()
 	});
}

var strGET = window.location.search.replace( '?', ''); 

var url = "http://litic-tac-toe.com/play/game?" + strGET;

var arg = url + " #game";


// console.log(arg);

function refresh(){
    $('#game').load(arg);
}

refresh();
var interval = 1000;
setInterval('refresh()', interval);