<!DOCTYPE html>
<html lang="en">
<head>
  <title></title>
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
  <script src="/lib/components/jcrop/js/jquery.min.js"></script>
  <script src="/lib/components/jcrop/js/jquery.Jcrop.js"></script>
  <link rel="stylesheet" href="/lib/components/jcrop/demos/demo_files/main.css" type="text/css" />
  <link rel="stylesheet" href="/lib/components/jcrop/demos/demo_files/demos.css" type="text/css" />
  <link rel="stylesheet" href="/lib/components/jcrop/css/jquery.Jcrop.css" type="text/css" />
<script type="text/javascript">

	$(function(){
	
		$('#cropbox').Jcrop({
			aspectRatio: <? echo ($data['final_width'] / $data['final_height']); ?>,
			onSelect: updateCoords,
//			setSelect: [ 0, 0, <? echo ($image_dim[0]); ?>, <? echo ($image_dim[1]); ?> ],
//			minSize: [ <? echo ($data['final_width']); ?>, <? echo ($data['final_height']); ?> ],
		});
		
	});
		
	function updateCoords(c)
	{
		$('#x').val(c.x);
		$('#y').val(c.y);
		$('#w').val(c.w);
		$('#h').val(c.h);
	};
	
	function checkCoords()
	{
		if (parseInt($('#w').val())) return true;
		alert('Selecione uma região para recortar!');
		return false;
	};

</script>
<style type="text/css">
  #target {
    background-color: #ccc;
    width: 500px;
    height: 330px;
    font-size: 24px;
    display: block;
  }


</style>
</head>
<body top="10" left="10">

    <p>
        <b>Selecione a área a ser recortada. 
        Tamanho da imagem : <? echo ($image_dim[0]); ?> x <? echo ($image_dim[1]); ?>
        Tamanho final da área recortada : <? echo $data['final_width']; ?> x <? echo $data['final_height']; ?>
        </b>
    </p>

    <img src="<? echo $image_path; ?>" id="cropbox" />

    <form action="?<? echo $module; ?>/crop_image" method="post" onsubmit="return checkCoords();">
        <input type="hidden" id="x" name="x" />
        <input type="hidden" id="y" name="y" />
        <input type="hidden" id="w" name="w" />
        <input type="hidden" id="h" name="h" />
        
        <input type="hidden" id="f_w" name="f_w" value="<? echo $data['final_width']; ?>"/>
        <input type="hidden" id="f_h" name="f_h" value="<? echo $data['final_height']; ?>"/>

        <input type="hidden" id="image_path" name="image" value="<? echo $image; ?>" />
        <input type="hidden" id="thumb_path" name="image" value="<? echo $image; ?>" />
        
        <input type="submit" value="Recortar" class="btn btn-large btn-inverse" />
        <a href="?<? echo $module; ?>/list_images/<? echo $object_id; ?>"><input type="button" value="Usar original" class="btn btn-large btn-inverse" /></a>
    </form>


</body>

</html>
