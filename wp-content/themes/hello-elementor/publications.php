<?php

/*
Template Name: publications
*/

// Version 2022-07-03
get_header(); // On affiche l'en-tête du thème WordPress
?>

<?php
global $wpdb; // On se connecte à la base de données du site
$publications = $wpdb->get_results("
SELECT p.ID, p.post_date, u.user_nicename, p.post_title, ts.name, p.post_type, p.post_content FROM `wp_posts` p
JOIN wp_users u ON p.post_author = u.ID
JOIN wp_term_relationships tr ON tr.object_id = p.ID
JOIN wp_term_taxonomy tax ON tax.term_taxonomy_id = tr.term_taxonomy_id
JOIN wp_terms ts ON tax.term_id = ts.term_id
WHERE tax.taxonomy = 'category'
AND p.post_type in ('page', 'post')
AND p.post_status = 'publish'
ORDER BY ts.name ASC, p.post_date DESC, p.post_title ASC;
");

?>

<div class="container-sample-db">

        <h1>Liste des publications</h1>
        <h3>Classement par catégories, par dates et par titres de publication</h3>
        <br>

        <?php
            echo "<table>";
            echo "<tr>";
            echo "<th>Date de publication</th>";
            echo "<th>Auteur</th>";
            echo "<th>Titre</th>";
            echo "<th>Catégorie</th>";
            echo "<th>Type de publication</th>";
            echo "<th>Contenu</th>";
            echo "</tr>";
            foreach($publications as $publication) {
            echo '<tr>';
            echo "<td style=text-align:center>".$publication->post_date."</td>";
            echo "<td style=text-align:center>".$publication->user_nicename."</td>";
            echo "<td style=text-align:center>".$publication->post_title."</td>";
            echo "<td style=text-align:center>".$publication->name."</td>";
            echo "<td style=text-align:center>".$publication->post_type."</td>";
            echo "<td style=text-align:center>".$publication->post_content."</td>";
            echo '</tr>';
            }
            echo '</table>';
        ?>

</div>

<?php get_footer(); // On affiche de pied de page du thème
?>
