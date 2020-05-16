<?php declare(strict_types=1);

class Covid19APIReportTest extends WP_UnitTestCase // phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
{
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testLoadDependencies()
    {
        $pluginDirectory = plugin_dir_path(dirname(__FILE__));
        $this->assertFileExists($pluginDirectory . 'public/js/main.js');
        $this->assertFileExists($pluginDirectory . 'public/js/jquery-3.5.1.js');
        $this->assertFileExists($pluginDirectory . 'public/js/jquery.dataTables.min.js');
        $this->assertFileExists($pluginDirectory . 'public/css/jquery.dataTables.min.css');
        $this->assertFileExists($pluginDirectory . 'inc/ClassActivator.php');
        $this->assertFileExists($pluginDirectory . 'inc/ClassDeactivator.php');
    }

    public function testAPIConnectionSummary()
    {
        $url = 'https://api.covid19api.com/summary';
        $args = [ 'method' => 'GET'];
        $response = wp_remote_get($url, $args);
        $this->assertTrue(!is_wp_error($response));
    }

    public function testAPIConnectionCountry()
    {
        $url = 'https://api.covid19api.com/total/country/united-kingdom';
        $args = [ 'method' => 'GET'];
        $response = wp_remote_get($url, $args);
        $this->assertTrue(!is_wp_error($response));
    }
}
