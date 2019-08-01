function resetForm(id) {
	$('#'+id).each(function(){
			this.reset();
	});
}	

function cleanit(thefield)
{
	if(thefield.defaultValue == thefield.value) {thefield.value="";};
}

function do_action(module, action, id)
{
	$.ajax(
	{
	   type: "POST",
	   url: '?' + module + '/' + action  + '/' + id,
	   data: {},
	   success: function(msg)
	   {
		document.location.href = '?' + module;
	   }
	});
}

function go_action(module, action, id)
{
	if(id != '' && id != undefined)
		url = '?' + module + '/' + action  + '/' + id;
	else
		url = '?' + module + '/' + action;
	document.location.href = url;
}


$(window).bind('resize', function(){
	resize();
});

function resize()
{
	
	var wh = $(window).height();
	var bh = $('#main').height();
	var fh = $('#footer').height();
	var hh = $('#header').height();
	
	var diff = wh - fh;

	console.log("%s: %o", 'window[wh] = ' + wh + ', main[bh] = ' + bh + ', diff = ' + diff, this);
	console.log("%s: %o", 'footer[fh] = ' + fh + ', header[hh] = ' + hh, this);

	if ((bh + hh) < diff)
	{
		console.log("Body + Header is smaller than space available ", this);

		$('#container').height(diff+20);
		console.log("Setting CONTAINER to " + (diff + 20), this);
		if(wh > (bh + hh)) 
		{
			console.log("Window is greater than Body + Header ", this);
			
			$('#content').height(diff - 165 + 20);
			console.log("Setting CONTENT to " + (diff - 165 + 20), this);
		}
		else
		{
			$('#content').height(bh + hh);
			console.log("Setting CONTENT to " + (bh + hh), this);

		}
	}
	else
	{
		console.log("Body + Header is greater than space available ", this);
		$('#container').height(bh + hh + 10);
		console.log("Setting CONTAINER to " + (bh + hh + 10), this);

		$('#content').height(bh);
		console.log("Setting CONTENT to " + (bh + hh), this);
		
	}

	$("#loading_screen").height($(window).height());
	
}

function display_loading_screen()
{
	$("#loading_screen").style.height = $(window).height();
	$("#loading_screen").style.display = 'block';
}

function hide_loading_screen()
{
	$("#loading_screen").style.display = 'none';
}			

$(document).ready(function()
{
	//$("body").css("display", "none");
	//$("body").fadeIn(2000);

	setTimeout(function(){resize()}, 100);
	
	if(typeof(CKEDITOR) != 'undefined')
	{
		CKEDITOR.on('instanceReady',
			  function( evt )
			  {
				 resize();
			  });	
	}
	
});
