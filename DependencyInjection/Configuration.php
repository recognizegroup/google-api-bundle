<?php
namespace Recognize\GoogleApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder,
	Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Yaml\Parser;

/**
 * Class Configuration
 * @package Recognize\GoogleApiBundle\DependencyInjection
 * @author Nick Obermeijer <n.obermeijer@recognize.nl>
 */
class Configuration implements ConfigurationInterface {

	/**
	 * {@inheritDoc}
	 */
	public function getConfigTreeBuilder() {
		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root('recognize_google_api');

		// Load the default values
		$yaml = new Parser();
		$defaultconfig = $yaml->parse( file_get_contents(__DIR__.'/../Resources/config/config.yml') );


		$rootNode
			->children()
				->scalarNode('default_locale')
				->defaultValue( $defaultconfig['recognize_google_api']['default_locale'] )->end()
				->scalarNode('api_key')
				->defaultValue( $defaultconfig['recognize_google_api']['api_key'] )->end()
			->end()
		;

		return $treeBuilder;
	}

}