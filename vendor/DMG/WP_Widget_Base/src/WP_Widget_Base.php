<?php
Namespace DMG\WP_Widget_Base;
/*
	Base widget class.

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



class WP_Widget_Base extends \WP_Widget
{
	public function deleteCacheOptions()
	{
		$alloptions = wp_cache_get( 'alloptions', 'options' );

		if ( isset($alloptions['widget_recent_entries']) )
		{
			delete_option('widget_recent_entries');
		}
	}




	public function addWidgetClass( $before_widget, $class = '' )
	{
		$classes = $this->sanitizeCSSClasses( $class );

		if( !empty( $classes ) )
		{
			// No 'class' attribute so add one
			if( strpos($before_widget, 'class') === false )
			{
				$before_widget = str_replace('>', 'class="'. $classes . '"', $before_widget);
			}
			// There is 'class' attribute so append
			else
			{
				$before_widget = str_replace('class="', 'class="'. $classes . ' ', $before_widget);
			}
		}

		return $before_widget;
	}



	public function getTitle( $args, $instance, $filter_name = '' )
	{
		if( isset($instance['show_title']) and $instance['show_title'] == false )
		{
			return '';
		}

		$title = '';
		$url_start = '';
		$url_end = '';

		if( !empty( $instance['title'] ) )
		{
			$title =  $instance['title'];
		}

		if( !empty( $instance['title_url'] ) )
		{
			$url_start	= '<a href="' . $instance['title_url'] . '">';
			$url_end 	= '</a>';
		}

		if( !empty( $filter_name ) )
		{
			$title = apply_filters( $filter_name, $title, $instance, $this->id_base );
		}
		
		return $args['before_title'] . $url_start . $title . $url_end . $args['after_title'];
	}



	public function openAdvancedSection()
	{
		echo '<p class="dmg-widget-advanced-toggle hide-if-no-js"><strong><a class="dmg-widget-show-advanced alignright" href="javascript:void(0);">Show Advanced</a><a class="dmg-widget-hide-advanced hide-if-js alignright" href="javascript:void(0);">Hide Advanced</a></strong><br class="clear"></p><div class="dmg-widget-advanced-options hide-if-js">';
	}



	public function closeAdvancedSection()
	{
		echo <<<HEREDOC
	</div>


	<script type="text/javascript">
		jQuery(function()
		{
			jQuery('.dmg-widget-show-advanced').on( 'click', function()
			{
				jQuery( this ).hide();
				jQuery( this ).next('.dmg-widget-hide-advanced').show();
				jQuery( this ).parents('.dmg-widget-advanced-toggle').next('.dmg-widget-advanced-options').slideDown();
			});

			jQuery('.dmg-widget-hide-advanced').on( 'click', function()
			{
				jQuery( this ).hide();
				jQuery( this ).prev('.dmg-widget-show-advanced').show();
				jQuery( this ).parents('.dmg-widget-advanced-toggle').next('.dmg-widget-advanced-options').slideUp();
			});
		});
	</script>
HEREDOC;
	}





	//////////////////////////////////
	// Form control methods
	//////////////////////////////////



	public function checkboxControl( $name, $label, $value )
	{
		?>
		<p><input id="<?php echo $this->get_field_id( $name ); ?>" name="<?php echo $this->get_field_name( $name ); ?>" type="checkbox" <?php checked(isset( $value ) ? $value : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id( $name ); ?>"><?php _e( $label ); ?></label></p>
		<?php
	}



	public function booleanControl( $name, $label, $value )
	{
		$checked = '';

		if( $value == true )
		{
			$checked = ' checked="checked"';
		}

		?>
		<p>
			<input name="<?php echo $this->get_field_name( $name ); ?>" type="hidden" value="0" />
			<input id="<?php echo $this->get_field_id( $name ); ?>" name="<?php echo $this->get_field_name( $name ); ?>" type="checkbox" value="1" <?php echo $checked; ?> />
			&nbsp;<label for="<?php echo $this->get_field_id( $name ); ?>"><?php _e( $label ); ?></label>
		</p>
		<?php
	}



	public function textControl( $name, $label, $value )
	{
		?>
		<p><label for="<?php echo $this->get_field_id( $name ); ?>"><?php _e( $label ); ?></label><input class="widefat" id="<?php echo $this->get_field_id( $name ); ?>" name="<?php echo $this->get_field_name( $name ); ?>" type="text" value="<?php echo $value; ?>" /></p>
		<?php
	}



	public function textareaControl( $name, $label, $value )
	{
		echo '<p>';

		if( !empty($label) )
		{
			echo '<label for="' . $this->get_field_id( $name ) . '">' . _e( $label ) . '</label>';
		}

		echo '<textarea class="widefat" rows="16" cols="20" id="' . $this->get_field_id('text') . '" name="' . $this->get_field_name('text') . '">' . $value . '</textarea></p>';
	}



	public function numberControl( $name, $label, $value, $min = 0, $max = null, $step = null )
	{
		$min_attr = '';
		$max_attr = '';
		$step_attr = '';

		if( !is_null( $min ) and is_int( $min ) )
		{
			$min_attr = " min=\"{$min}\"";
		}

		if( !is_null( $max ) and is_int( $max ) )
		{
			$max_attr = " max=\"{$max}\"";
		}

		if( !is_null( $step ) and is_int( $step ) )
		{
			$step_attr = " step=\"{$step}\"";
		}

		?>
		<p><label for="<?php echo $this->get_field_id( $name ); ?>"><?php _e( $label ); ?></label><input id="<?php echo $this->get_field_id( $name ); ?>" name="<?php echo $this->get_field_name( $name ); ?>" type="number" value="<?php echo $value; ?>" <?php echo $min_attr . $max_attr . $step_attr;?>/></p>
		<?php
	}



	public function selectControl( $name, $label, $current, $options, $first = '&mdash; Select &mdash;' )
	{
		$html = '';

		if( !empty( $first ) )
		{
			$html .= '<option value="">' . __( '&mdash; Select &mdash;' ) . '</option>';
		}

		foreach( $options as $option => $value )
		{
			if( is_int($option) )
			{
				$option = $value;
			}

			$selected = '';
			if( $value == $current )
			{
				$selected = ' selected="selected"';
			}

			$html .= "<option value=\"$value\"{$selected}>{$option}</option>";
		}

		if( !empty($html) )
		{
			?>
			<p><label for="<?php echo $this->get_field_id( $name ); ?>"><?php _e( $label ); ?></label>
				<select id="<?php echo $this->get_field_id( $name ); ?>" name="<?php echo $this->get_field_name( $name ); ?>">
					<?php echo $html; ?>
				</select>
			</p>
			<?php
		}
	}



	public function postTypeSelectControl( $name, $label, $value, $args = [], $operator = 'and' )
	{
		$this->selectControl( $name, $label, $value, get_post_types( $args, 'names', $operator ));
	}



	public function taxonomySelectControl( $name, $label, $value )
	{
		$taxonomies = get_taxonomies( ['public' => true] );

		if( !empty( $taxonomies ) )
		{
			$this->selectControl( $name, $label, $value, $taxonomies );
		}
	}



	//////////////////////////////////
	// Sanitization methods
	//////////////////////////////////



	public function sanitizeTaxonomy( $taxonomy = '' )
	{
		$taxonomies = get_taxonomies( ['public' => true] );

		if( in_array( $taxonomy, $taxonomies ) )
		{
			return $taxonomy;
		}

		return $taxonomies[0];
	}



	public function sanitizeTitle( $title = '' )
	{
		return sanitize_text_field( $title );
	}



	public function sanitizeBoolean( $bool = null )
	{
		if( $bool === 0 or $bool === false or empty($bool) )
		{
			return false;
		}

		return true;
	}





	/**
	 * Sanitize a space seperated, comma seperated or array of CSS classes using WP sanitize_html_class.
	 * https://codex.wordpress.org/Function_Reference/sanitize_html_class
	 * @param mixed $classes
	 */
	public function sanitizeCSSClasses( $classes = '' )
	{
		if( empty( $classes ) )
		{
			return '';
		}

		if( is_string($classes) )
		{
			$classes = str_replace([' ',','], ',', $classes);
			$classes = explode(',', $classes );
		}

		if( is_array($classes) )
		{
			$classes = array_map( 'trim', $classes );
			$classes = array_map( 'sanitize_html_class', $classes );

			return implode(' ', $classes);
		}

		return '';	
	}



	public function sanitizeInteger( $integer = '', $min = 0, $max = null, $default = null )
	{
		$integer = 0 + $integer;

		if( !is_int( $integer ) )
		{
			return $default;
		}

		if( is_int($min) and $integer < $min )
		{
			$integer = $min;
		}

		if( is_int($max) and $integer > $max )
		{
			$integer = $max;
		}

		return $integer;
	}



	public function sanitizeHTML( $html )
	{
		return stripslashes( wp_filter_post_kses( addslashes( $html )));
	}

}
