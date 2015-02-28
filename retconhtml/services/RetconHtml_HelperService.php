<?php namespace Craft;

/**
 * Got WYSIWYG? Retcon offers convenient Twig filters for easy HTML rewriting.
 *
 * @author      Mats Mikkel Rummelhoff <http://mmikkel.no>
 * @package     Retcon HTML
 * @since       Craft 2.3
 * @copyright   Copyright (c) 2015, Mats Mikkel Rummelhoff
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @link        https://github.com/mmikkel/RetconHtml-Craft
 */

class RetconHtml_HelperService extends BaseApplicationComponent
{

	protected 	$_environmentVariables = null,
				$_settings = null;


	public function getSetting( $setting )
	{

		// Get settings
		if ( $this->_settings === null ) {

			$plugin = craft()->plugins->getPlugin( 'retconHtml' );
			$pluginSettings = $plugin->getSettings();
			$settings = array();

			$settings[ 'baseTransformPath' ] = trim( rtrim( $pluginSettings->baseTransformPath, '/' ) ?: rtrim( $_SERVER[ 'DOCUMENT_ROOT' ], '/' ) );
			$settings[ 'baseTransformUrl' ] = trim( rtrim( $pluginSettings->baseTransformUrl, '/' ) ?: rtrim( CRAFT_SITE_URL, '/' ) );
			$settings[ 'encoding' ] = trim( $pluginSettings->encoding ) ?: 'UTF-8';

			if ( strpos( $settings[ 'baseTransformPath' ], '{' ) > -1 || strpos( $settings[ 'baseTransformUrl' ], '{' ) > -1 ) {

				// Get environment variables
				if ( $this->_environmentVariables === null ) {
					$this->_environmentVariables = craft()->config->get( 'environmentVariables' );
				}

				// Replace environment variables
				if ( is_array( $this->_environmentVariables ) && ! empty( $this->_environmentVariables ) ) {
					foreach ( $this->_environmentVariables as $key => $value ) {
						$settings[ 'baseTransformPath' ] = preg_replace( '#/+#','/', str_replace( '{' . $key . '}', $value, $settings[ 'baseTransformPath' ] ) );
						$settings[ 'baseTransformUrl' ] = preg_replace( '#/+#','/', str_replace( '{' . $key . '}', $value, $settings[ 'baseTransformUrl' ] ) );
						$settings[ 'baseTransformUrl' ] = str_replace( ':/', '://', $settings[ 'baseTransformUrl' ] );
					}
				}

			}

			$this->_settings = $settings;

		}

		return $this->_settings[ $setting ] ?: false;

	}

	/*
	* Parse selector string and return components
	*
	*/
	public function getSelector( $selector )
	{

		$delimiters = array( 'id' => '#', 'class' => '.' );

		$selectorString = preg_replace( '/\s+/', '', $selector );

		$selector = array(
			'tag' => $selector,
			'attribute' => false,
			'attributeValue' => false,
		);

		// Check for class or ID
		foreach ( $delimiters as $attribute => $indicator ) {

			if ( strpos( $selectorString, $indicator ) > -1 ) {

				$temp = explode( $indicator, $selectorString );

				$selector[ 'tag' ] = $temp[ 0 ] !== '' ? $temp[ 0 ] : '*';

				if ( ( $attributeValue = $temp[ count( $temp ) - 1 ] ) !== '' ) {
					$selector[ 'attribute' ] = $attribute;
					$selector[ 'attributeValue' ] = $attributeValue;
				}

				break;

			}

		}

		return (object) $selector;

	}

	public function getEncoding()
	{
		$encoding = $this->getSetting( 'encoding' );
		return $encoding ? trim( $encoding ) : false;
	}

	public function getHtml( $html )
	{
		return TemplateHelper::getRaw( preg_replace( '~<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>\s*~i', '', $html ) ) ?: false;
	}

}