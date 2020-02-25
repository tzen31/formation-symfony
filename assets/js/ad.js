$('#add-image').click(function(){
	//Je récupère le numéro des futurs champs que je vais créer
	//const index = $('#ad_images div.form-group').length;
	const index = +$('#widgets-counter').val();
	//console.log(index);
	
	//Je récupère le prototype des entrées
	const tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);

	//J'injecte ce code au sein de la div
	$('#ad_images').append(tmpl);
	//console.log(tmpl);

	$('#widgets-counter').val(index + 1);

	//Je gère le bouton supprimer
	handleDeleteButtons();
});

//Gérer les boutons de suppressions
function handleDeleteButtons() 
{
	$('button[data-action="delete"]').click(function() {
		const target =this.dataset.target;
		//console.log(target);
		$(target).remove();
	});
}

function updateCounter() {
	const count = +$('#ad_images div.for.group').length;
	$('#widgets-counter').val(count);
}

updateCounter();
handleDeleteButtons();
