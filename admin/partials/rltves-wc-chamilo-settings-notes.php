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

$content = "<h1>Last 5 notes: </h1>";

if (isset($rltvesdb)) {

    $rows = $rltvesdb->get_results("select created_at, info, id from tc_notes order by created_at desc limit 5");
    
    $content .= "
    <table border=\"1\" width=\"100%\">
        <tr>
            <th>Date</th>
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
}      
?>

<?php echo $content; ?>

