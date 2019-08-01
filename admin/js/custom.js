$(function(){
				
	// SUPERFISH FOR MENU ( DROP-DOWN )
	$("ul.nav").superfish({
		animation:{
			height: "show",
			width: "show"
		}, speed : 500
	});
					
	// TOOLTIPS
	$(".tooltip").easyTooltip();
		
	// CHECK ALL CHECKBOX WHEN HEADER ONE IS PRESSED
	$('.checkall').click(
		function(){
			$(this).parent().parent().parent().parent().find("input[type='checkbox']").attr('checked', $(this).is(':checked'));   
		}
	);
	
	// CLOSE NOTIFICATION MESSAGES WHEN CLICKED
	$(".close").click(
		function () {
			$(this).fadeTo(400, 0, function () { // Links with the class "close" will close parent
			$(this).slideUp(400);
		});
		return false;
		}
	);

				
	// SORTABLE, PORTLETS
	$(".column").sortable({
		connectWith: '.column'
	});
				
	$(".sort").sortable({
		connectWith: '.sort'
	});		

	$(".portlet").addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all")
			.find(".portlet-header")
			.addClass("ui-widget-header ui-corner-all")
			.prepend('<span class="ui-icon ui-icon-circle-arrow-s"></span>')
			.end()
			.find(".portlet-content");

	$(".portlet-header .ui-icon").click(function() {
		$(this).toggleClass("ui-icon-minusthick");
		$(this).parents(".portlet:first").find(".portlet-content").toggle();
	});

	$(".column").disableSelection();

	// ACCORDION
	$("#accordion").accordion({ header: "h3" });
	
	// TABS
	$('#tabs').tabs();
	
	// DIALOG			
	$('#dialog').dialog({
		autoOpen: false,
		width: 500,
		buttons: {
			"Ok": function() { 
				$(this).dialog("close"); 
			}, 
			"Cancel": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
				
	// DIALOG LINK
	$('#dialog_link').click(function(){
		$('#dialog').dialog('open');
		return false;
	});

	// DATEPICKER
	$('#datepicker').datepicker({
		dateFormat: 'dd/mm/yy',    
		dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],    
		dayNamesMin: ['D','S','T','Q','Q','S','S','D'],    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],    
		monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],   
		monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],    
		nextText: 'Próximo',    
		prevText: 'Anterior',
		inline: true
	});
				
	// SLIDER
	$('#slider').slider({
		range: true,
		values: [20, 70]
	});
				
	// PROGRESSBAR
	$("#progressbar").progressbar({
		value: 40 
	});
				
	// HOVER STATES ON STATIC WIDGETS
	$('#dialog_link, ul#icons li').hover(
		function() { $(this).addClass('ui-state-hover'); }, 
		function() { $(this).removeClass('ui-state-hover'); }
	);
	
    $("table.tablesorter").tablesorter(); 
    $("#trigger-link").click(function() { 
        // set sorting column and direction, this will sort on the first and third column the column index starts at zero 
        var sorting = [[0,0],[2,0]]; 
        // sort on the first column 
        $("table").trigger("sorton",[sorting]); 
        // return false to stop default link action 
        return false; 
    }); 
	
	

	// WYSIWYG EDITOR
	$('textarea.wysiwyg').wysiwyg({
  		controls: {
    		strikeThrough : { visible : true },
    		underline     : { visible : true },
      
   			separator00 : { visible : true },
      
     		justifyLeft   : { visible : true },
      		justifyCenter : { visible : true },
     		justifyRight  : { visible : true },
     		justifyFull   : { visible : true },
      
 			separator01 : { visible : true },
      
   			indent  : { visible : true },
			outdent : { visible : true },
      
     		separator02 : { visible : true },
      
    		subscript   : { visible : true },
    		superscript : { visible : true },
      
   			separator03 : { visible : true },
  	    
  			undo : { visible : true },
   			redo : { visible : true },
      
    		separator04 : { visible : true },
      
     		insertOrderedList    : { visible : true },
    		insertUnorderedList  : { visible : true },
    		insertHorizontalRule : { visible : true },
      
    		h4mozilla : { visible : true && $.browser.mozilla, className : 'h4', command : 'heading', arguments : ['h4'], tags : ['h4'], tooltip : "Header 4" },
     		h5mozilla : { visible : true && $.browser.mozilla, className : 'h5', command : 'heading', arguments : ['h5'], tags : ['h5'], tooltip : "Header 5" },
     		h6mozilla : { visible : true && $.browser.mozilla, className : 'h6', command : 'heading', arguments : ['h6'], tags : ['h6'], tooltip : "Header 6" },
      
     		h4 : { visible : true && !( $.browser.mozilla ), className : 'h4', command : 'formatBlock', arguments : ['<H4>'], tags : ['h4'], tooltip : "Header 4" },
     		h5 : { visible : true && !( $.browser.mozilla ), className : 'h5', command : 'formatBlock', arguments : ['<H5>'], tags : ['h5'], tooltip : "Header 5" },
   			h6 : { visible : true && !( $.browser.mozilla ), className : 'h6', command : 'formatBlock', arguments : ['<H6>'], tags : ['h6'], tooltip : "Header 6" },
      
   			separator07 : { visible : true },
      
     		cut   : { visible : true },
     		copy  : { visible : true },
     		paste : { visible : true }
   		}
  	});
	
	$('input.searchbox').searchbox();

});
