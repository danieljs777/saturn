<!--//

function openCenterWindow(pURL, pName, pWidth, pHeight, pParams, pSetFocus) {
	var maxW = screen.availWidth;
	var maxH = screen.availHeight;
	var topPos = (maxH - pHeight)/2;
	var leftPos = (maxW - pWidth)/2;
	
	var newWin = window.open(pURL, pName, 'top='+topPos+', left='+leftPos+', width='+pWidth+', height='+pHeight+' '+pParams);
	if (pSetFocus) 
		newWin.focus();
}

/*
##############################################################################################################
*/

function openPopUpImage(pImage, pTitle)
  {

  w = window.open('',pTitle,'width=10,height=10');
  w.document.write( "<html><head><title>"+pTitle+"</title>\n" ); 
  w.document.write( "<script language='JavaScript'>\n"); 
  w.document.write( "IE5=NN4=NN6=false;\n"); 
  w.document.write( "if(document.all)IE5=true;\n"); 
  w.document.write( "else if(document.getElementById)NN6=true;\n"); 
  w.document.write( "else if(document.layers)NN4=true;\n"); 
  w.document.write( "function autoSize() {\n"); 
  w.document.write( "if(IE5) self.resizeTo(document.images[0].width+10,document.images[0].height+31)\n"); 
  w.document.write( "else if(NN6) self.sizeToContent();\n");
  w.document.write( "else window.resizeTo(document.images[0].width,document.images[0].height+20)\n"); 
  w.document.write( "self.focus();\n"); 
  w.document.write( "}\n</scri");
  w.document.write( "pt>\n"); 
  w.document.write( "</head><body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 onLoad='javascript:autoSize();'>" );
  w.document.write( "<a href='javascript:window.close();'><img src='"+pImage+"' border=0 alt='"+pTitle+"'></a>" ); 
  w.document.write( "</body></html>" );
  w.document.close(); 
 
  w.moveTo(0,0);
  }
  
function chSelection(y, z)
{

	var frm = document.s;
	if (frm.sel.value != '')
	{
		var elm = document.getElementById(frm.sel.value.toString());
		elm.className = 'grid';
	}

	y.className    = 'clicked';
	frm.lsel.value = frm.sel.value;
	frm.sel.value  = z;

}	  
//  onMouseOver="if (document.s.sel.value != 273) {this.className='sel'};" onMouseOut="if (document.s.sel.value != 273) {this.className='grid'};"
//  onClick="javascript:chSelection(this,273); 
  //-->
function toogle_div(id)
{
   el = document.getElementById(id);
   el.style.display = (el.style.display == 'none') ? 'block' : 'none';
}

function open_closediv(id, state)
{
   el = document.getElementById(id);
   el.style.display = state;
}


function tgStatusObject(fAction)
{
    if (fAction=='delete') 
        verb='deletar ';
    else
        verb='alterar status d';
    
    if (window.confirm('Voce realmente deseja '+verb+'os itens selecionados?'))
	{
        document.form1.faction.value=fAction;
        document.form1.submit();
    }
}

function doSearchCustom()
{
	document.form1.action = 'default.php';
	document.form1.submit();
}

function resizeOuterTo(w,h)
{
	if (parseInt(navigator.appVersion)>3)
	{
		if (navigator.appName=="Netscape")
		{
			top.outerWidth=w;
			top.outerHeight=h;
			top.moveTo(0,0);
		}
		else top.resizeTo(w,h);
	}
}

//resizeOuterTo(screen.width,screen.height-30);

