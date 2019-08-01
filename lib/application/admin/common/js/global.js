// JavaScript Document
jQuery.fn.log = function (msg)
{
	if(typeof console !== 'undefined')
		console.log("%s: %o", msg, this);

	return this;
};	

jQuery.fn.reset = function ()
{
   $(this).each (function()
   {
	  this.reset();
   });
};

function serialize(_obj)
{
    // Let Gecko browsers do this the easy way
    if (typeof _obj.toSource !== 'undefined' && typeof _obj.callee === 'undefined')
    {
       return _obj.toSource();
    }


   // Other browsers must do it the hard way
    switch (typeof _obj)
    {
       // numbers, booleans, and functions are trivial:
       // just return the object itself since its default .toString()
       // gives us exactly what we want
       case 'number':
       case 'boolean':
       case 'function':
          return _obj;
          break;

      // for JSON format, strings need to be wrapped in quotes
       case 'string':
          return '\'' + _obj + '\'';
          break;

      case 'object':
          var str;
          if (_obj.constructor === Array || typeof _obj.callee !== 'undefined')
          {
             str = '[';
             var i, len = _obj.length;
             for (i = 0; i < len-1; i++) { str += serialize(_obj[i]) + ','; }
             str += serialize(_obj[i]) + ']';
          }
          else
          {
             str = '{';
             var key;
             for (key in _obj) { str += key + ':' + serialize(_obj[key]) + ','; }
             str = str.replace(/\,$/, '') + '}';
          }
          return str;
          break;

      default:
          return 'UNKNOWN';
          break;
    }
}


jQuery.fn.serializeObject = function()
{
   var o = {};
   var a = this.serializeArray();
   $.each(a, function() {
	   if (o[this.name]) {
		   if (!o[this.name].push) {
			   o[this.name] = [o[this.name]];
		   }
		   o[this.name].push(this.value || '');
	   } else {
		   o[this.name] = this.value || '';
	   }
   });
   return o;
};

function reset_message(div_name)
{
	div_name = (div_name == undefined) ? "#div_ajax_message" : "#" + div_name;

	$(div_name).html('');
	$(div_name).attr('style', 'display:none;');
}

function show_message(div_name, error_class, message)
{
	div_name = (div_name == undefined) ? "#div_ajax_message" : "#" + div_name;
	
	$(div_name).html(message);
	$(div_name).removeClass().addClass(error_class);					
	$(div_name).attr('style', 'display:block;');
}

function hide_message(div_name)
{
	div_name = (div_name == undefined) ? "#div_ajax_message" : "#" + div_name;

	$(div_name).attr('style', 'display:none;');
}

