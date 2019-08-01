<link type="text/css" href="css/layout.css" rel="stylesheet" />	
<script language="Javascript">

parent.document.insert_podcast.reset();

</script>
<style>
body
{
	background:none !important;
	font-size: 11px !important;
}
</style>

<form id="multiple_op" method="post" action="?<? echo($module); ?>/delete_podcasts">
<input type="hidden" value="<? echo $id; ?>" name="<? echo($config['id_column']); ?>"/>
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="fullwidth">
    <thead>
        <tr>
            <td width="20"><input type="checkbox" class="checkall" /></td>
            <td>Data</td>
            <td>Título</td>
            <td>Arquivo</td>
        </tr>
    </thead>
    <tbody>
<?
$x=0;

foreach($podcasts as $podcast)
{

?>
    <tr class="<? echo ($x % 2 == 0) ? 'odd' : '' ?>">
        <td><input type="checkbox" name="id_s[]" id="id_s" value="<? echo($podcast['pod_id']); ?>"/></td>
        <td><? echo(Date::format($podcast['pod_date'])); ?></td>
        <td><? echo($podcast['title']); ?></td>
        <td><? echo($podcast['podcast_file']); ?></td>
    </tr>

 <?
	$x++;
}
?>
<?  if ( $x == 0 ) { ?>
    <tr>
        <td align="center" colspan="6"><? if ($x == 0) echo("Sem Podcasts cadastrados! Use o box acima para inseri-las.");?>&nbsp;</td>
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
