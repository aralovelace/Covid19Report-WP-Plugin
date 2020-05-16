<?php declare(strict_types=1);
namespace Inspsyde_April\Inc;

class ClassActivator
{
    public static function activate()
    {
        $minPhp = '7.0';
        // Check PHP Version and deactivate & die if it doesn't meet minimum requirements.
        if (version_compare(PHP_VERSION, $minPhp, '<')) // phpcs:disable Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis , Inpsyde.CodeQuality.LineLength.TooLong
        {
            deactivate_plugins(plugin_basename(__FILE__));
            wp_die(esc_attr_e('This plugin requires a minimum PHP Version of {$minPhp}')); // phpcs:disable Inpsyde.CodeQuality.VariablesName.SnakeCaseVar ,  Inpsyde.CodeQuality.LineLength.TooLong
        }
    }
}
