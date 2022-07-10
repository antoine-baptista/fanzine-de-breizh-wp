<?php
/**
 * Header file in case of the elementor way
 *
 * @package header-footer-elementor
 * @since 1.2.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>


<!-- rajout code AB -->

<?php
	// wordpress possède des variables globales dont la variable $post
	// du coup, j'indique ce-dessous que le code s'adresse à tous les posts
	global $post;

	// on affecte une valeur à la variable qui va stocker l'url de l'image background
	$imageUrl = "";
	// on créé un tableau pour la banque d'images
	$imagesIDs = [4172, 4104, 3741, 3692, 3691, 3690, 3689, 3687, 3686, 368, 3684, 3141, 3190, 3681, 3683, 2050, 1071];
	// et on prend au hasard une image en prenant bien soin de commencer l'aléatoire du tableau à 0
	// un tableau commence à 0 mais un comptage à 1
	$imageRandom = get_permalink($imagesIDs[rand(0, count($imagesIDs)-1)]);


	// on regarde si la variable $post est à null 
	// si elle ne l'est pas on continue à exécuter le code qui suit
	// sinon on se rend à else tout en bas
	if(isset($post)){
		// on créé une variable $postId inutilisée à laquelle on affecte par défaut
		// la valeur de $post à laquelle on a donné la valeur du ID du post si c'en est un
		$postId = $post->ID;
		// ensuite on switche 
		switch($postId){
			// page d'accueil
			// stèle protohistorique
			case 29:
				$imageUrl = get_permalink(71);
				break;
			// page d'exemple
			// cap coz
			case 2:
				$imageUrl = get_permalink(1154);
				break;
			// page rechercher
			// maison sur le sable
			case 136:
				$imageUrl = get_permalink(320);
				break;
			// page à propos
			// vue aérienne anse du saint laurent
			case 140:
				$imageUrl = get_permalink(323);
				break;
			// page about me
			// plage de kerleven
			case 142:
				$imageUrl = get_permalink(326);
				break;
			// page balades
			// porte bleue
			case 144:
				$imageUrl = get_permalink(3701);
				break;
			// page contact
			// voilier
			case 146:
				$imageUrl = get_permalink(332);
				break;
			// page login
			// maison bretonne
			case 148:
				$imageUrl = get_permalink(335);
				break;
			// page politique de confidentialité
			// saint laurent
			case 138:
				$imageUrl = get_permalink(1070);
				break;
			// page logout
			// barque avec l'hermine
			case 2341:
				$imageUrl = get_permalink(2348);
				break;
			// page proposer un article
			// main avec un plume
			case 2227:
				$imageUrl = get_permalink(2448);
				break;
			// page sitemap
			// bateau échoué
			case 2726:
				$imageUrl = get_permalink(2998);
				break;
			// page s'inscrire
			// oiseaux volant sur l'océan
			case 2275:
				$imageUrl = get_permalink(3011);
				break;
			// page exercice bdd
			// datacenter
			case 4260:
				$imageUrl = get_permalink(4308);
				break;
			// au cas où je n'aurais pas indiqué l'image souhaitée
			// pour une page nouvellement créée
			// on prendra une image au hasard
			default:
				$imageUrl = $imageRandom;
				break;
		}
	}
	else{
		// et enfin, pour toutes les pages qui ne sont pas des 
		// 'posts' dans WordPress, on prend également une page au
		// hasard
		$imageUrl = $imageRandom;
	}
?>

<!-- dans cette dernière ligne on affecte un style au body en lui donnant une background-image
     je n'affecte que l'image de fond afin de pouvoir fonctionner avec les id des medias 
	 seules quelques pages qui ne sont pas des posts ont une image spécifique --
	 dans ce cas là, je suis obligé de mettre l'url absolu  
	 background-position:bottom left; background-size: cover; background-repeat: no-repeat; background-attachment: fixed -->
<body <?php body_class(); ?> style='background-image:url(<?= $imageUrl ?>)'>
<!-- fin rajout code AB -->
<?php do_action( 'wp_body_open' ); ?>
<div id="page" class="hfeed site">

<?php do_action( 'hfe_header' ); ?>
