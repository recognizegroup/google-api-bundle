<?php
use Recognize\GoogleApiBundle\DependencyInjection\RecognizeGoogleApiExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RecognizeGoogleApiExtensionTest extends \PHPUnit_Framework_TestCase{

    /**
     * @var RecognizeGoogleApiExtension
     */
    private $extension;

    /**
     * Root name of the configuration
     *
     * @var string
     */
    private $root;

    public function setUp() {
        parent::setUp();

        $this->extension = new RecognizeGoogleApiExtension();
        $this->root = "recognize_google_api";
    }

    public function testGetConfigWithDefaultValues() {
        $this->extension->load(array(), $container = $this->getContainer());


        $this->assertTrue($container->hasParameter($this->root . ".config"));

        $config = $container->getParameter($this->root . ".config");
        $this->assertEquals(null, $config["api_key"]);
        $this->assertEquals("en", $config["default_locale"]);
    }

    public function testGetConfigWithOverrideValues() {
        $configs = array(
            "api_key" => "asdfa",
            "default_locale" => "nl"
        );
        $this->extension->load(array($configs), $container = $this->getContainer());

        $this->assertTrue($container->hasParameter($this->root . ".config"));
        $config = $container->getParameter($this->root . ".config");
        $this->assertEquals("asdfa", $config["api_key"]);
        $this->assertEquals("nl", $config["default_locale"]);
    }


    public function getContainer(){
        $container = new ContainerBuilder();
        $container->setParameter('recognize_google_api.config', array());
        return $container;
    }
}