<?php
/**
 * Copyright © MageEnv. All rights reserved.
 * Licensed under the Open Software License 3.0 (OSL-3.0). See LICENSE.txt.
 */
declare(strict_types=1);

namespace MageEnv\EInvoiceHyva\Test\Unit;

use PHPUnit\Framework\TestCase;

/**
 * The add-on stays presentation only, loads only on Hyva pages and keeps its CSS scoped.
 */
class AddOnContractTest extends TestCase
{
    private function root(): string
    {
        return dirname(__DIR__, 2);
    }

    public function testOnlyRegistrationPhp(): void
    {
        $php = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->root(), \FilesystemIterator::SKIP_DOTS)
        );
        foreach ($iterator as $file) {
            $path = substr((string)$file, strlen($this->root()) + 1);
            $skip = str_starts_with($path, 'Test/') || str_starts_with($path, 'vendor/');
            if (str_ends_with($path, '.php') && !$skip) {
                $php[] = $path;
            }
        }
        $this->assertSame(['registration.php'], $php);
    }

    public function testLayoutsOnlyUseHyvaHandles(): void
    {
        foreach (glob($this->root() . '/view/frontend/layout/*.xml') as $layout) {
            $this->assertStringStartsWith('hyva_', basename($layout), 'Layout would also load on Luma');
        }
    }

    public function testLayoutsTargetCoreBlocks(): void
    {
        $expected = [
            'hyva_customer_account_edit.xml' => 'mageenv.einvoice.account.fields',
            'hyva_sales_order_invoice.xml' => 'mageenv.einvoice.downloads',
            'hyva_sales_order_creditmemo.xml' => 'mageenv.einvoice.downloads',
        ];
        foreach ($expected as $file => $block) {
            $xml = (string)file_get_contents($this->root() . '/view/frontend/layout/' . $file);
            $this->assertStringContainsString('name="' . $block . '"', $xml, $file);
        }
    }

    public function testCssIsScoped(): void
    {
        $css = (string)file_get_contents($this->root() . '/view/frontend/web/css/mageenv-einvoice.css');
        $css = (string)preg_replace('#/\*.*?\*/#s', '', $css);
        foreach (explode('}', $css) as $rule) {
            $selector = trim((string)strstr($rule, '{', true));
            if ($selector === '') {
                continue;
            }
            foreach (explode(',', $selector) as $part) {
                $this->assertStringContainsString('.mageenv-ei--hyva', $part, 'Unscoped selector: ' . $part);
            }
        }
    }

    public function testTemplatesUseCoreBlockApiOnly(): void
    {
        foreach (glob($this->root() . '/view/frontend/templates/*/*.phtml') as $template) {
            $content = (string)file_get_contents($template);
            $this->assertStringContainsString('mageenv-ei--hyva', $content, basename($template));
            $this->assertStringNotContainsString('ObjectManager', $content, basename($template));
        }
    }
}
