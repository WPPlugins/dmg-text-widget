<?php
Namespace DMG\WP_Text_Widget;

/*
	Text widget with advanced options class.

    Copyright (C) 2016  Dan Gifford

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


Use DMG\WP_Widget_Base\WP_Widget_Base;


class WP_Text_Widget extends WP_Widget_Base
{
	public function __construct()
	{
		parent::__construct(
			'dmg_text_widget', 
			__('DMG Text Widget'), 
			['classname' => 'dmg_text_widget', 'description' => __('Display text or HTML with advanced options to apply a CSS class, add paragraphs, apply shortcodes and hide the title.')], 
			['width' => 400, 'height' => 350]
		);
	}



	public function widget( $args, $instance )
	{
		// Use class
		$args['before_widget'] = $this->addWidgetClass( $args['before_widget'], $instance['class'] );



		/**
		 * Filter the content of the Text widget.
		 */
		if( empty( $instance['text'] ) )
		{
			$text = '';
		}
		else
		{
			$text = apply_filters( $this->id_base . '_text', $instance['text'], $instance, $this->id_base );
		}



		/**
		 * Apply shortcodes to the text if required
		 */
		if( $instance['apply_shortcodes'] === true )
		{
			$text = do_shortcode($text);
		}



		/**
		 * Apply paragraphs to the text if required
		 */
		if( $instance['add_paragraphs'] === true  )
		{
			$text = wpautop( $text );
		}



		/**
		 * Output
		 */
		echo $args['before_widget'];
		echo $this->getTitle( $args, $instance, $this->id_base . '_title' );
		echo '<div class="textwidget">' . $text . '</div>';
		echo $args['after_widget'];
	}



	public function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;

		$instance['title'] 				= $this->sanitizeTitle($new_instance['title']);
		$instance['title_url'] 			= esc_url($new_instance['title_url']);
		$instance['class'] 				= $this->sanitizeCSSClasses($new_instance['class']);
		$instance['add_paragraphs'] 	= $this->sanitizeBoolean($new_instance['add_paragraphs']);
		$instance['apply_shortcodes'] 	= $this->sanitizeBoolean($new_instance['apply_shortcodes']);
		$instance['show_title'] 		= $this->sanitizeBoolean($new_instance['show_title']);

		if( current_user_can('unfiltered_html') )
		{
			$instance['text'] =  $new_instance['text'];
		}
		else
		{
			$instance['text'] = $this->sanitizeHTML($new_instance['text']); 
		}

		$this->deleteCacheOptions();

		return $instance;
	}



	public function form( $instance )
	{
		$instance = wp_parse_args( (array) $instance, ['title' => '', 'text' => '', 'class' => '', 'show_title' => 1, 'title_url' => '', 'add_paragraphs' => 1] );

		$this->textControl( 'title', 'Title:', $this->sanitizeTitle($instance['title']) );

		$this->textareaControl( 'text', '', esc_textarea($instance['text']) );

		$this->openAdvancedSection();

			$this->textControl( 'class', 'CSS class(es) applied to widget wrapper:', $this->sanitizeCSSClasses( $instance['class'] ) );

			$this->textControl( 'title_url', 'URL for the title (make the title a link):', esc_url( $instance['title_url'] ) );

			$this->booleanControl( 'add_paragraphs', 'Automatically add paragraphs', $this->sanitizeBoolean( $instance['add_paragraphs'] ));

			$this->booleanControl( 'apply_shortcodes', 'Apply shortcodes', $this->sanitizeBoolean( $instance['apply_shortcodes'] ));

			$this->booleanControl( 'show_title', 'Show the Title', $this->sanitizeBoolean( $instance['show_title'] ));

		$this->closeAdvancedSection();
	}
}