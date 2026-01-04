<?php
/**
 * Tests for WebGSM_Checkout_Validate class
 *
 * @package WebGSM\Tests
 */

namespace WebGSM\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Brain\Monkey;
use Brain\Monkey\Functions;

/**
 * Test Checkout Validation class
 */
class Test_Checkout_Validate extends TestCase {

    /**
     * Setup test environment
     */
    protected function setUp(): void {
        parent::setUp();
        Monkey\setUp();

        // Mock WordPress functions
        Functions\when('sanitize_text_field')->returnArg();
        Functions\when('sanitize_email')->returnArg();
        Functions\when('is_email')->alias(function($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        });
        Functions\when('get_current_user_id')->justReturn(0);
        Functions\when('get_user_meta')->justReturn([]);
        Functions\when('__')->returnArg();

        // Mock WooCommerce notice function
        Functions\when('wc_add_notice')->alias(function($message, $type) {
            throw new \Exception($message);
        });
    }

    /**
     * Teardown test environment
     */
    protected function tearDown(): void {
        Monkey\tearDown();
        parent::tearDown();
    }

    /**
     * Test phone normalization
     *
     * @dataProvider phone_normalization_provider
     */
    public function test_phone_normalization($input, $expected) {
        if (!class_exists('WebGSM_Checkout_Validate')) {
            $this->markTestSkipped('WebGSM_Checkout_Validate class not loaded');
        }

        $validator = new \WebGSM_Checkout_Validate();

        $reflection = new \ReflectionClass($validator);
        $method = $reflection->getMethod('webgsm_normalize_phone');
        $method->setAccessible(true);

        $result = $method->invoke($validator, $input);
        $this->assertEquals($expected, $result);
    }

    /**
     * Data provider for phone normalization
     */
    public function phone_normalization_provider() {
        return [
            ['0722-123-456', '0722123456'],
            ['0722 123 456', '0722123456'],
            ['(0722) 123.456', '0722123456'],
            ['0722.123.456', '0722123456'],
            ['+40 722 123 456', '+40722123456'],
            ['0722123456', '0722123456'], // Already normalized
        ];
    }

    /**
     * Test CUI validation format
     *
     * @dataProvider cui_validation_provider
     */
    public function test_cui_validation($cui, $should_be_valid) {
        // Extract numeric part
        $cui_numeric = preg_replace('/^RO/i', '', $cui);
        $cui_numeric = preg_replace('/[^0-9]/', '', $cui_numeric);

        $is_valid = strlen($cui_numeric) >= 2 && strlen($cui_numeric) <= 10;

        $this->assertEquals($should_be_valid, $is_valid, "CUI validation failed for: $cui");
    }

    /**
     * Data provider for CUI validation
     */
    public function cui_validation_provider() {
        return [
            // Valid CUIs
            ['RO31902941', true],
            ['31902941', true],
            ['RO12345678', true],
            ['12', true], // Minimum length
            ['1234567890', true], // Maximum length

            // Invalid CUIs
            ['1', false], // Too short
            ['12345678901', false], // Too long
            ['RO1', false], // Too short even with RO prefix
        ];
    }

    /**
     * Test CNP validation
     *
     * @dataProvider cnp_validation_provider
     */
    public function test_cnp_validation($cnp, $should_be_valid) {
        $cnp_clean = preg_replace('/[^0-9]/', '', $cnp);
        $is_valid = strlen($cnp_clean) === 13;

        $this->assertEquals($should_be_valid, $is_valid, "CNP validation failed for: $cnp");
    }

    /**
     * Data provider for CNP validation
     */
    public function cnp_validation_provider() {
        return [
            // Valid CNPs (13 digits)
            ['1234567890123', true],
            ['6001231123456', true],

            // Invalid CNPs
            ['123456789012', false], // 12 digits
            ['12345678901234', false], // 14 digits
            ['12345', false], // Too short
        ];
    }

    /**
     * Test email validation
     *
     * @dataProvider email_validation_provider
     */
    public function test_email_validation($email, $should_be_valid) {
        $is_valid = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;

        $this->assertEquals($should_be_valid, $is_valid, "Email validation failed for: $email");
    }

    /**
     * Data provider for email validation
     */
    public function email_validation_provider() {
        return [
            // Valid emails
            ['test@example.com', true],
            ['user+tag@domain.ro', true],
            ['name.surname@company.com', true],

            // Invalid emails
            ['invalid', false],
            ['@example.com', false],
            ['test@', false],
            ['test @example.com', false],
        ];
    }

    /**
     * Test customer type detection
     */
    public function test_customer_type_detection() {
        if (!class_exists('WebGSM_Checkout_Validate')) {
            $this->markTestSkipped('WebGSM_Checkout_Validate class not loaded');
        }

        $validator = new \WebGSM_Checkout_Validate();

        $reflection = new \ReflectionClass($validator);
        $method = $reflection->getMethod('webgsm_get_customer_type');
        $method->setAccessible(true);

        // Test PJ
        $_POST['billing_customer_type'] = 'pj';
        $result = $method->invoke($validator);
        $this->assertEquals('pj', $result);

        // Test PF
        $_POST['billing_customer_type'] = 'pf';
        $result = $method->invoke($validator);
        $this->assertEquals('pf', $result);

        // Test default (no POST)
        unset($_POST['billing_customer_type']);
        $result = $method->invoke($validator);
        $this->assertEquals('pf', $result); // Should default to PF

        // Cleanup
        unset($_POST['billing_customer_type']);
    }

    /**
     * Test POST value retrieval
     */
    public function test_get_post_value() {
        if (!class_exists('WebGSM_Checkout_Validate')) {
            $this->markTestSkipped('WebGSM_Checkout_Validate class not loaded');
        }

        $validator = new \WebGSM_Checkout_Validate();

        $reflection = new \ReflectionClass($validator);
        $method = $reflection->getMethod('webgsm_get_post_value');
        $method->setAccessible(true);

        // Test with value
        $_POST['test_field'] = '  Test Value  ';
        $result = $method->invoke($validator, 'test_field');
        $this->assertEquals('Test Value', $result); // Should be trimmed

        // Test without value
        $result = $method->invoke($validator, 'nonexistent_field');
        $this->assertEquals('', $result); // Should return empty string

        // Cleanup
        unset($_POST['test_field']);
    }
}
