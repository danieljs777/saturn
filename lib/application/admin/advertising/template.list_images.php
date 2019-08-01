<link type="text/css" href="css/layout.css" rel="stylesheet" />	
<script language="Javascript">
parent.document.insert_file.reset();
</script>
<style>
body
{
	background:none !important;
	font-size: 11px !important;
}
</style>

  <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="5">
    <tr>
      <td>
        <form id="insert_file" name="insert_file" action="?<? echo($_module); ?>/upload" method="post" target="gallery" enctype="multipart/form-data">
        <table width="100%"  border="0" align="center" cellpadding="0" class="fullwidth">
            <tr>
                <td>
                  Inserir imagem : &nbsp;
                  <input name="<? echo $config['id_column']; ?>" type="hidden" value="<? echo $id; ?>" class="field">
                  <input name="image" type="file" class="field">
                  <input name="Submit" type="submit" class="button" value="Inserir Imagem">  
              </td>
            </tr>
           </table>
         </form>
         </td>
         <tr>
  </table>

<form id="multiple_op" method="post" action="?<? echo($module); ?>/delete_images">
<input type="hidden" value="<? echo $id; ?>" name="<? echo($config['id_column']); ?>"/>
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="fullwidth">
    <thead>
        <tr>
            <td width="20"><input type="checkbox" class="checkall" /></td>
            <td>Imagem</td>
            <td>Tamanho</td>
            <td>Tipo</td>
            <td>Dimensões</td>
        </tr>
    </thead>
    <tbody>
<?
$x=0;

foreach($images as $image)
{

?>
    <tr class="<? echo ($x % 2 == 0) ? 'odd' : '' ?>">
        <td><input type="checkbox" name="id_s[]" id="id_s" value="<? echo($image[$config['file_id_column']]); ?>"/></td>
        <td><a href="<? echo($image[$config['file_path_column']]); ?>" target="_blank"><img src="<? echo($image[$config['file_path_column']]); ?>" width="150" border=0/></a></td>
        <td><? echo($image['_size_']); ?> Kb</td>
        <td><? echo($image['_dimensions_']['mime']); ?></td>
        <td><? echo($image['_dimensions_']['0'] ." x " . $image['_dimensions_']['1']); ?></td>
    </tr>

 <?
	$x++;
}
?>
<?  if ( $x == 0 ) { ?>
    <tr>
        <td align="center" colspan="6"><? if ($x == 0) echo("Sem imagens cadastradas! Use o box acima para inseri-las.");?>&nbsp;</td>
    </tr>
<? } ?>

    </tbody>
    <tr>
        <td colspan="10">
        <? if ($_SESSION["admin_luid"] < 2) { ?>
          <input type="submit" value="Deletar Seleção" class="button">
        <? } ?>
        </td>
    </tr>
</table>
</form>
