<?php

declare(strict_types=1);

namespace Setono\SyliusMeilisearchPlugin\Tests\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Setono\SyliusMeilisearchPlugin\DependencyInjection\Configuration;

/**
 * See examples of tests and configuration options here: https://github.com/SymfonyTest/SymfonyConfigTest
 */
final class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }

    /**
     * @test
     */
    public function processed_value_contains_required_value(): void
    {
        $this->assertProcessedConfigurationEquals([
            [
                'credentials' => ['app_id' => 'first_app_id', 'search_only_api_key' => 'first_search_only_api_key', 'admin_api_key' => 'first_admin_api_key'],
            ],
            [
                'credentials' => ['app_id' => 'last_app_id', 'search_only_api_key' => 'last_search_only_api_key', 'admin_api_key' => 'last_admin_api_key'],
            ],
        ], [
            'indexes' => [],
            'credentials' => [
                'master_key' => 'aSampleMasterKey',
            ],
            'routes' => [
                'product_index' => 'taxons/{slug}',
            ],
            'search' => [
                'enabled' => false,
                'indexes' => [],
            ],
        ]);
    }
}
