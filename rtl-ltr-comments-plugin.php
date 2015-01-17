<?php
	/*
		Plugin Name: RTL/LTR Comments
		Description: WordPress plugin to enable switching between RTL/LTR comments on Hebrew/Arabic WordPress blogs
		Author: Jacob Ward
		Version: 0.1.0
		Author URI: http://www.jacobward.co.uk
		Plugin URI: http://www.jacobward.co.uk
	*/



	// Adding ltr comment field
	function add_ltr_comment_field( $comment_id ){
		add_comment_meta( $comment_id, 'ltr_toggle_button', $_POST['ltr_toggle_button'] );
	}
	add_action( 'comment_post', 'add_ltr_comment_field' );

	// Filtering output based on custom ltr meta
	add_filter( 'comment_text', 'ltr_filter' );

	function ltr_filter( $mytext ) {

		global $comment;

		$comment_id = get_comment_ID();	// Getting the comment ID

		$ltr = get_comment_meta( $comment_id, 'ltr_toggle_button', true );	// Getting the comment meta



		if ( $ltr ) {
			$mytext = '<p style="direction: ltr;">' . get_comment_text( $comment ) . '</p>';
			return $mytext;
		}
		else {
			return $mytext = get_comment_text( $comment );
		}
	}

	// Inserting the ltr button!
	function insert_ltr_button() {

		$ltr_button_up = plugins_url( 'img/ltr_button_up.png', __FILE__ );
		$ltr_button_down = plugins_url( 'img/ltr_button_down.png', __FILE__ );


		// Insert all the styles and scripts

		echo '

			<style type="text/css">
				#ltr_toggle_label {
					display: block;
					position: relative;
					width: 26px;
					height: 25px;
					background-image: url(' . $ltr_button_up . ');
					border: 0;
					background-repeat: no-repeat;
					margin-right: 4px;
					margin-left: 4px;
					margin-bottom: -30px;
					margin-top: 5px;
					overflow: hidden;
				}

				#ltr_toggle_label.down {
					background-image: url(' . $ltr_button_down . ');
				}

				#ltr_toggle_label.up {
					background-image: url(' . $ltr_button_up . ');
				}

				#comment.down {
					direction: ltr;
				}

				#comment.up {
				    direction: rtl;
				}
			</style>

		';

		echo '


		<input type="checkbox" id="ltr_toggle_button" style="display: none;" name="ltr_toggle_button" />

		<script type="text/javascript">

		(function($) {

			$(document).ready(function(){
    			$("#ltr_toggle_label").toggle(function() {
      			        $("#comment").removeClass("up");
        			    $("#comment").addClass("down");
        			    $("#ltr_toggle_label").removeClass("up");
        			    $("#ltr_toggle_label").addClass("down");
        			 }, function() {
        			    $("#comment").removeClass("down");
        			    $("#comment").addClass("up");
        			    $("#ltr_toggle_label").removeClass("down");
        			    $("#ltr_toggle_label").addClass("up");

    			});
			});


			function preload(arrayOfImages) {
    			$(arrayOfImages).each(function(){
        			$("<img/>")[0].src = this;
        		});
			}

			preload([
    			"' . $ltr_button_up . '",
    			"' . $ltr_button_down . '"
    		]);


		})(jQuery);
		</script>

		';



		echo '<p class="comment-form-comment"><label for="ltr_toggle_button" id="ltr_toggle_label" title="כיווניות משמאל לימין"></label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" style="padding-top: 32px;"></textarea></p>';

	}

	add_filter( 'comment_form_field_comment', 'insert_ltr_button' );

	// jQuery stuff
	add_action( 'init', 'my_plugin_jquery' );
    function my_plugin_jquery() {
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js' );
		wp_enqueue_script( 'jquery' );
    }


?>
