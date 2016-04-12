<?php

/**
 * Register new Ninja Forms field
 */
function ninja_forms_register_field_maps()
{
	$args = array(
		'name'               =>  __( 'Address autocomplete', 'ninja-forms' ),
		'sidebar'            =>  'template_fields',
		'edit_function'      =>  'ninja_forms_field_maps_edit',
		'display_function'   =>  'ninja_forms_field_maps_display',
		'save_function'      =>  '',
		'group'              =>  'standard_fields',
		'edit_label'         =>  true,
		'edit_label_pos'     =>  true,
		'edit_req'           =>  true,
		'edit_custom_class'  =>  true,
		'edit_help'          =>  true,
		'edit_desc'          =>  true,
		'edit_meta'          =>  false,
		'edit_conditional'   =>  true,
		'conditional'        =>  array(
			                        'value' => array(
				                       'type' => 'text',
			                        ),
		                         ),
		'pre_process'        =>  'ninja_forms_field_maps_pre_process',
	);

	ninja_forms_register_field( '_maps', $args );
}

add_action( 'init', 'ninja_forms_register_field_maps' );


/**
 * Edit field in admin
 */
function ninja_forms_field_maps_edit( $field_id, $data )
{
	$plugin_settings = nf_get_settings();
	
	
	$custom = '';
	
	// Default Value
	if( isset( $data['default_value'] ) ) {
		$default_value = $data['default_value'];
	} else {
		$default_value = '';
	}
	if( $default_value == 'none' ) {
		$default_value = '';
	}

	?>
	<div class="description description-thin">
		<span class="field-option">
		<label for="">
			<?php _e( 'Default Value' , 'ninja-forms'); ?>
		</label><br />
			<select id="default_value_<?php echo $field_id;?>" name="" class="widefat ninja-forms-_text-default-value">
				<option value="" <?php if( $default_value == '') { echo 'selected'; $custom = 'no'; } ?>><?php _e( 'None', 'ninja-forms' ); ?></option>
				<option value="_custom" <?php if($custom != 'no') { echo 'selected'; } ?>><?php _e( 'Custom', 'ninja-forms' ); ?> -></option>
			</select>
		</span>
	</div>
	<div class="description description-thin">

		<label for="" id="default_value_label_<?php echo $field_id;?>" style="<?php if( $custom == 'no' ) { echo 'display:none;'; } ?>">
			<span class="field-option">
			<?php _e( 'Default Value' , 'ninja-forms' ); ?><br />
			<input type="text" class="widefat code" name="ninja_forms_field_<?php echo $field_id; ?>[default_value]" id="ninja_forms_field_<?php echo $field_id; ?>_default_value" value="<?php echo $default_value; ?>" />
			</span>
		</label>

	</div>

	<?php
}


/**
 * Display field on front-end
 */
function ninja_forms_field_maps_display( $field_id, $data )
{
	global $current_user;
	$field_class = ninja_forms_get_field_class( $field_id );

	if( isset( $data['default_value'] ) ) {
		$default_value = $data['default_value'];
	} else {
		$default_value = '';
	}

	if( isset( $data['label_pos'] ) ) {
		$label_pos = $data['label_pos'];
	} else {
		$label_pos = "left";
	}

	if( isset( $data['label'] ) ) {
		$label = $data['label'];
	} else {
		$label = '';
	}

	
	?>
	<input id="ninja_forms_field_<?php echo $field_id; ?>" data-mask="<?php echo $mask; ?>"   name="ninja_forms_field_<?php echo $field_id; ?>" type="text" class="<?php echo $field_class; ?>" value="<?php echo $default_value; ?>" rel="<?php echo $field_id; ?>" />

	
	<script>
      var placeSearch, autocomplete;
      var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
      };

      function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('ninja_forms_field_<?php echo $field_id; ?>')),
            {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
      }

      function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        for (var component in componentForm) {
          document.getElementById(component).value = '';
          document.getElementById(component).disabled = false;
        }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
          }
        }
      }

      // Bias the autocomplete object to the user's geographical location,
      // as supplied by the browser's 'navigator.geolocation' object.
      function geolocate() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
          });
        }
      }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY; ?>&libraries=places&callback=initAutocomplete"
        async defer></script>
	<?php
}


/**
 * Pre process field value
 *
 * @param int $field_id  Field ID
 * @param string $user_value  Field value
 */
function ninja_forms_field_maps_pre_process( $field_id, $user_value )
{
	global $ninja_forms_processing;
		
	$ninja_forms_processing->update_field_value( $field_id, $user_value );

}