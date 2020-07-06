<?php
/**
 * Displays a table in the WC Settings page
 *
 * @link        https://relato.com.br
 * @since       1.0.0
 *
 * @package     Rltves_Wc_Chamilo
 * @subpackage  Rltves_Wc_Chamilo/admin
 *
 */

$GLOBALS['hide_save_button'] = true;

$rltvesdb = $GLOBALS["rltvesdb"];

$content = "<h1>5 últimos pedidos: </h1>";

if (isset($rltvesdb)) {

    $rows = $rltvesdb->get_results("select created_at, info, id from tc_notes order by created_at desc limit 5");
    
    $content .= "
    <table class=\"tblrel\" border=\"0\" width=\"100%\">
        <tr>
            <th width=\"150px\">Data</th>
            <th>Info</th>
            <th>Id</th>
        </tr>
        ";
        foreach ($rows as $row):
            $content .= "<tr><td>".$row->created_at."</td>";
            $content .= "<td>".$row->info."</td>";
            $content .= "<td>".$row->id."</td></tr>";
        endforeach;
        $content .= "</table>";

    $rows2 = $rltvesdb->get_results("SELECT session.id as id, session.name as nome, session.duration as dur, course.title as curso, course.code as ref FROM session LEFT OUTER JOIN session_rel_course ON session.id = session_rel_course.session_id LEFT OUTER JOIN course ON session_rel_course.c_id = course.id order by id desc");

    $content2 = "<h1>Sessões do EAD:</h1>";
    $content2 .= "
    <table class=\"tblrel\" border=\"0\" width=\"100%\">
        <tr>
            <th>Id</th>
            <th>Nome</th>
            <th>Duração</th>
            <th>Curso</th>
            <th>REF</th>
        </tr>
        ";
        foreach ($rows2 as $row):
            $content2 .= "<tr><td>".$row->id."</td>";
            $content2 .= "<td>".$row->nome."</td>";
            $content2 .= "<td>".$row->dur."</td>";
            $content2 .= "<td>".$row->curso."</td>";
            $content2 .= "<td>".$row->ref."</td></tr>";
        endforeach;
        $content2 .= "</table>";
}      
?>

<?php echo $content2; ?>
<?php echo $content; ?>

