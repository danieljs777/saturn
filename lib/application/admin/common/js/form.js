function form_submit(action)
{
	els = document.forms[0].elements;

	for(a=0;a<els.length;a++)
	{
		if (els[a].type == 'text')
		{
			if(els[a].value=='')
			{
				window.alert('Você não preencheu todos os campos.\nVerifique.');
				els[a].className='redalert';
				els[a].focus();
				return false;
			}
		}
	}

	document.forms[0].action=action;
	document.forms[0].submit();
}

function list_returnSelected(object)
{
	var elem = document.getElementById(object);
	return (elem[elem.selectedIndex].value);
}

function list_clear(object)
{
	var elem = document.getElementById(object);
	for(i = elem.length - 1; i>=0; i--)
	{
		elem.remove(i);	
	}
	
}
function lockscreen()
{
	objElems = document.all;
	for(i=0;i<objElems.length;i++)
	{
		if(objElems[i].type == "button" || objElems[i].type == "text" || objElems[i].type == "password" || objElems[i].type == "select-one" || objElems[i].type == "textarea")
			objElems[i].disabled = true;
	}
	window.defaultStatus = 'Aguarde... Carregando...';
  
}

function unlockscreen()
{
	objElems = document.all;
	for(i=0;i<objElems.length;i++)
	{
		if(objElems[i].type == "button" || objElems[i].type == "text" || objElems[i].type == "password" || objElems[i].type == "select-one" || objElems[i].type == "textarea" )
			objElems[i].disabled = false;
	}
	window.defaultStatus = 'Operação Processada!';
  
}

function SelectAllCheckBoxes(action)

{

   var myform=document.forms['aspnetForm'];

   var len = myform.elements.length;

   for( var i=0 ; i < len ; i++) 

   {

   if (myform.elements[i].type == 'checkbox') 

      myform.elements[i].checked = action;

   }

}




