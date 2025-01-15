<?php
/*
Plugin Name: ACF Block Variation location
Plugin URI: https://github.com/rynokins/acf-block-variation-location
Description: WordPress / ACF plugin to allow a Field Group Location for every ACF block variation registered
License: MIT
Authors: David Lapointe Gilbert, Ryan Edwards
Version: 0.2
Author URI: https://github.com/rynokins
Text Domain: acfbvl
Domain Path: /languages
*/

/**
 * Add acf_block_variation rule type to the location rule options
 * @param $choices
 * @return array
 */
add_filter('acf/location/rule_types', function ($choices) {
    $block_variations_cache = get_transient('acf_block_variations');
    if(!$block_variations_cache) {
        return $choices;
    }
    $choices['Block Variations'] = ['acf_block_variation' => 'ACF Block Variation'];

    return $choices;
});

/**
 * Add acf_block_variation rule values to the location rule options
 * Builds the list of available acf block variations
 * @param $choices
 * @return array
 */
add_filter('acf/location/rule_values/acf_block_variation', function ($choices) {
    $block_variations_cache = get_transient('acf_block_variations');
    if(!$block_variations_cache) {
        return $choices;
    }
    foreach ($block_variations_cache as $blockname => $variation) {
      $choices[$blockname] = $variation;
    }

    return $choices;
});

/**
 * Add acf_block_variation to the location rule match
 * Checks if the rule value matches the current block variation
 * @param $match
 * @param $rule
 * @param $screen
 * @param $field_group
 * @return bool
 */
add_filter('acf/location/rule_match/acf_block_variation', function ($match, $rule, $screen, $field_group) {
    if (!empty($screen['block']) && isset($_REQUEST['block'])){
        $block = $_REQUEST['block'];
        $block = wp_unslash( $block );
        $block = json_decode( $block, true );
        $className = isset($block['className']) ? $block['className'] : 'is-style-default';

        $match = $rule['value'] == $className;

        if($rule['operator'] == '!=') {
          $match = !$match;
        }

    }
      return $match;
}, 10, 4);

/**
 * Add acf_block_variation to the field group match
 * Checks if the field group location rule matches the current block variation
 * @param $field_groups
 * @return array
 */
add_filter('acf/load_field_groups', function($field_groups = []) {
    if ( isset($_REQUEST['block'])) {
        $block = $_REQUEST['block'];
        $block = wp_unslash( $block );
        $block = json_decode( $block, true );
        $className = isset($block['className']) ? $block['className'] : 'is-style-default';

        foreach ( $field_groups as $groupkey => $group ) {
          foreach ( $group['location'] as $locs ) {
            foreach ( $locs as $loc) {
              if ( $loc['param'] == 'acf_block_variation' ) {
                $result = $loc['value'] == $className;

                if ( $loc['operator'] == '!=' ) {
                    $result = !$result;
                }
                if ( !$result ) {
                    unset( $field_groups[$groupkey]);
                }
              }
            }
          }
        }
      }
    return $field_groups;
}, 10, 1);

/**
 * Builds a cache of available block variations
 * (used to populate the location rule options)
 * Caches the result in a transient
 * @return void
*/
add_action('admin_enqueue_scripts', function(){
    $block_types = acf_get_block_types();
    $acf_block_variations = [];
    foreach ($block_types as $block_name => $block_data) {
      // Check if it's an ACF block and has variations
      if (strpos($block_name, 'acf/') === 0 && array_key_exists('variations', $block_data)) {
        foreach ($block_data['variations'] as $variation) {
          $acf_block_variations[$variation['attributes']['className']] = $variation['title'];
        }
      }
    }
    set_transient('acf_block_variations', $acf_block_variations);
});

