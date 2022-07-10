<?php
/*
Template Name: pages
*/
// Version 2022-07-03
get_header(); // On affiche l'en-tête du thème WordPress
?>

<?php
global $wpdb; // On se connecte à la base de données du site
$pages = $wpdb->get_results("
SELECT DISTINCT post_title, user_nicename, post_content, guid FROM `wp_posts`
JOIN wp_users ON wp_posts.post_author = wp_users.ID
WHERE post_type = 'page' AND post_status NOT IN ('trash', 'auto-draft', 'draft');
");

?>

<div class="container-sample-db">

        <h1>Liste des pages</h1>
        <h3>Pas de classement</h3>
        <br>

        <?php
            echo "<table>";
            echo "<tr>";
            echo "<th>Titre</th>";
            echo "<th>Auteur</th>";
            echo "<th>Contenu</th>";
            echo "<th>Lien</th>";
            echo "</tr>";
            foreach($pages as $page) {
            echo '<tr>';
            echo "<td style=text-align:center>".$page->post_title."</td>";
            echo "<td style=text-align:center>".$page->user_nicename."</td>";
            echo "<td style=text-align:center>".$page->post_content."</td>";
            echo "<td style=text-align:center>".$page->guid."</td>";
            echo '</tr>';
            }
            echo '</table>';
        ?>

</div>

<?php get_footer(); // On affiche de pied de page du thème
?>
