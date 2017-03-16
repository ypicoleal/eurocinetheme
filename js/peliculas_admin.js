$ = jQuery;

$(document).ready(function (){
	change_ciudad();
	$("#ciudad_sel").change(function(){
		change_ciudad();
	});

	$("a.add").click(function(){
		add_horario();
		return false;
	});
	set_buttons();
});

function set_buttons(){
	$("a.hor_del").click(function(){
		var index = $(this).attr('index');
		delete_horario(index);
	});
}

function change_ciudad() {
	var curr = $("#ciudad_sel").val();
	$("#teatro_sel option").hide();
	$("#teatro_sel option[ciudad='" + curr + "']").show();
	$("#teatro_sel").val("");
}

function add_horario(){
	var count = $("#num_hor").val();
	++count;
	var monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Deciembre"];
	var ciudad = $("#ciudad_sel").val();
	var teatro = $("#teatro_sel").val();
	var fecha = new Date($("#hor_fecha").val());
	var hora = $("#hor_time").val();
	var html = '<p class="widefat horario" index="'+count+'">'+ ciudad +', '+ monthNames[fecha.getMonth()] + " " + (fecha.getDate()+1) +', '+ teatro +', '+ hora +' <a onclick="del_horario(this)" index="'+count+'" class="hor_del"><img src="'+ template_url +'/img/delete.svg" /></a></p>';
	$("#hor_cont").append(html);
	$("#hor_cont").append('<input type="hidden" index="'+count+'" name="_ciudad_'+count+'" value="'+ciudad+'"/>');
	$("#hor_cont").append('<input type="hidden" index="'+count+'" name="_teatro_'+count+'" value="'+teatro+'"/>');
	$("#hor_cont").append('<input type="hidden" index="'+count+'" name="_fecha_'+count+'" value="'+$("#hor_fecha").val()+'"/>');
	$("#hor_cont").append('<input type="hidden" index="'+count+'" name="_hora_'+count+'" value="'+hora+'"/>');
	$("#num_hor").val(count);
}

function del_horario(elem){
	var index = $(elem).attr('index');
	delete_horario(index);
}

function delete_horario(index){
	console.log($('p[index="'+ index +'"]'));
	$('p[index="'+ index +'"]').remove();
	$('input[index="'+ index +'"]').remove();
	var count = $("#num_hor").val();

	for (var i = (parseInt(index) + 1); i <= count; i++) {
		$('p[index="'+ i +'"]').attr('index', i-1);
		$('a[index="'+ i +'"]').attr('index', i-1);
		$('input[index="'+ i +'"]').each(function(indx, value){
			var name = $(value).attr('name');
			var name = name.replace(""+i, ""+(i-1));
			$(value).attr('name', name);
			$(value).attr('index', i-1);
		});
	}

	$("#num_hor").val(--count);
}