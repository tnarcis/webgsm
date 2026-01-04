<?php
/**
 * Tests for WebGSM_Checkout_ANAF class
 *
 * @package WebGSM\Tests
 */

namespace WebGSM\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Brain\Monkey;
use Brain\Monkey\Functions;

/**
 * Test ANAF integration class
 */
class Test_Checkout_ANAF extends TestCase {

    /**
     * Setup test environment
     */
    protected function setUp(): void {
        parent::setUp();
        Monkey\setUp();

        // Mock WordPress functions
        Functions\when('wp_verify_nonce')->justReturn(true);
        Functions\when('sanitize_text_field')->returnArg();
        Functions\when('wp_send_json_error')->alias(function($message) {
            throw new \Exception($message);
        });
        Functions\when('wp_send_json_success')->alias(function($data) {
            return $data;
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
     * Test remove_diacritics with Romanian characters
     *
     * @covers WebGSM_Checkout_ANAF::remove_diacritics
     */
    public function test_remove_diacritics_romanian_chars() {
        if (!class_exists('WebGSM_Checkout_ANAF')) {
            $this->markTestSkipped('WebGSM_Checkout_ANAF class not loaded');
        }

        $anaf = new \WebGSM_Checkout_ANAF();

        // Use reflection to access private method
        $reflection = new \ReflectionClass($anaf);
        $method = $reflection->getMethod('remove_diacritics');
        $method->setAccessible(true);

        // Test lowercase diacritics
        $this->assertEquals('aaistu', $method->invoke($anaf, 'ăâîșțu'));

        // Test uppercase diacritics
        $this->assertEquals('AAISTU', $method->invoke($anaf, 'ĂÂÎȘȚU'));

        // Test mixed case
        $this->assertEquals('Bucuresti', $method->invoke($anaf, 'București'));
        $this->assertEquals('Timis', $method->invoke($anaf, 'Timiș'));
    }

    /**
     * Test get_state_code with all Romanian counties
     *
     * @covers WebGSM_Checkout_ANAF::get_state_code
     * @dataProvider county_codes_provider
     */
    public function test_get_state_code_all_counties($county_name, $expected_code) {
        if (!class_exists('WebGSM_Checkout_ANAF')) {
            $this->markTestSkipped('WebGSM_Checkout_ANAF class not loaded');
        }

        $anaf = new \WebGSM_Checkout_ANAF();

        // Use reflection to access private method
        $reflection = new \ReflectionClass($anaf);
        $method = $reflection->getMethod('get_state_code');
        $method->setAccessible(true);

        $result = $method->invoke($anaf, $county_name);
        $this->assertEquals($expected_code, $result, "Failed for county: $county_name");
    }

    /**
     * Data provider for county codes
     */
    public function county_codes_provider() {
        return [
            ['ALBA', 'AB'],
            ['ARAD', 'AR'],
            ['ARGES', 'AG'],
            ['BACAU', 'BC'],
            ['BIHOR', 'BH'],
            ['BISTRITA-NASAUD', 'BN'],
            ['BOTOSANI', 'BT'],
            ['BRASOV', 'BV'],
            ['BRAILA', 'BR'],
            ['BUCURESTI', 'B'],
            ['BUZAU', 'BZ'],
            ['CARAS-SEVERIN', 'CS'],
            ['CALARASI', 'CL'],
            ['CLUJ', 'CJ'],
            ['CONSTANTA', 'CT'],
            ['COVASNA', 'CV'],
            ['DAMBOVITA', 'DB'],
            ['DOLJ', 'DJ'],
            ['GALATI', 'GL'],
            ['GIURGIU', 'GR'],
            ['GORJ', 'GJ'],
            ['HARGHITA', 'HR'],
            ['HUNEDOARA', 'HD'],
            ['IALOMITA', 'IL'],
            ['IASI', 'IS'],
            ['ILFOV', 'IF'],
            ['MARAMURES', 'MM'],
            ['MEHEDINTI', 'MH'],
            ['MURES', 'MS'],
            ['NEAMT', 'NT'],
            ['OLT', 'OT'],
            ['PRAHOVA', 'PH'],
            ['SATU MARE', 'SM'],
            ['SALAJ', 'SJ'],
            ['SIBIU', 'SB'],
            ['SUCEAVA', 'SV'],
            ['TELEORMAN', 'TR'],
            ['TIMIS', 'TM'],
            ['TULCEA', 'TL'],
            ['VASLUI', 'VS'],
            ['VALCEA', 'VL'],
            ['VRANCEA', 'VN'],
        ];
    }

    /**
     * Test get_state_code with diacritics
     *
     * @covers WebGSM_Checkout_ANAF::get_state_code
     */
    public function test_get_state_code_with_diacritics() {
        if (!class_exists('WebGSM_Checkout_ANAF')) {
            $this->markTestSkipped('WebGSM_Checkout_ANAF class not loaded');
        }

        $anaf = new \WebGSM_Checkout_ANAF();

        $reflection = new \ReflectionClass($anaf);
        $method = $reflection->getMethod('get_state_code');
        $method->setAccessible(true);

        // Test with Romanian diacritics
        $this->assertEquals('BN', $method->invoke($anaf, 'Bistrița-Năsăud'));
        $this->assertEquals('MS', $method->invoke($anaf, 'Mureș'));
        $this->assertEquals('TM', $method->invoke($anaf, 'Timiș'));
    }

    /**
     * Test parse_anaf_result structure
     *
     * @covers WebGSM_Checkout_ANAF::parse_anaf_result
     */
    public function test_parse_anaf_result_structure() {
        if (!class_exists('WebGSM_Checkout_ANAF')) {
            $this->markTestSkipped('WebGSM_Checkout_ANAF class not loaded');
        }

        $anaf = new \WebGSM_Checkout_ANAF();

        $reflection = new \ReflectionClass($anaf);
        $method = $reflection->getMethod('parse_anaf_result');
        $method->setAccessible(true);

        // Mock ANAF response data
        $anaf_data = [
            'date_generale' => [
                'denumire' => 'SC TEST SRL',
                'nrRegCom' => 'J40/1234/2020',
            ],
            'adresa_sediu_social' => [
                'sdenumire_Strada' => 'Calea Victoriei',
                'snumar_Strada' => '1',
                'sdetalii_Adresa' => 'Bl. A, Sc. 1',
                'sdenumire_Localitate' => 'Municipiul București',
                'sdenumire_Judet' => 'BUCURESTI',
            ],
            'inregistrare_scop_Tva' => [
                'scpTVA' => 1,
            ],
        ];

        $result = $method->invoke($anaf, $anaf_data, '12345678');

        // Verify structure
        $this->assertIsArray($result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('cui', $result);
        $this->assertArrayHasKey('j', $result);
        $this->assertArrayHasKey('address', $result);
        $this->assertArrayHasKey('city', $result);
        $this->assertArrayHasKey('county', $result);
        $this->assertArrayHasKey('state_code', $result);
        $this->assertArrayHasKey('is_tva', $result);

        // Verify values
        $this->assertEquals('SC TEST SRL', $result['name']);
        $this->assertEquals('RO12345678', $result['cui']); // Should have RO prefix for TVA
        $this->assertEquals('J40/1234/2020', $result['j']);
        $this->assertEquals('București', $result['city']); // Should remove "Municipiul" prefix
        $this->assertEquals('B', $result['state_code']);
        $this->assertTrue($result['is_tva']);
    }

    /**
     * Test parse_anaf_result without TVA
     *
     * @covers WebGSM_Checkout_ANAF::parse_anaf_result
     */
    public function test_parse_anaf_result_non_tva() {
        if (!class_exists('WebGSM_Checkout_ANAF')) {
            $this->markTestSkipped('WebGSM_Checkout_ANAF class not loaded');
        }

        $anaf = new \WebGSM_Checkout_ANAF();

        $reflection = new \ReflectionClass($anaf);
        $method = $reflection->getMethod('parse_anaf_result');
        $method->setAccessible(true);

        $anaf_data = [
            'date_generale' => [
                'denumire' => 'PFA ION POPESCU',
                'nrRegCom' => 'F40/567/2020',
            ],
            'adresa_sediu_social' => [
                'sdenumire_Strada' => 'Strada Mihai Eminescu',
                'snumar_Strada' => '10',
                'sdenumire_Localitate' => 'Cluj-Napoca',
                'sdenumire_Judet' => 'CLUJ',
            ],
            'inregistrare_scop_Tva' => [
                'scpTVA' => 0, // Not registered for VAT
            ],
        ];

        $result = $method->invoke($anaf, $anaf_data, '1234567');

        // Should NOT have RO prefix for non-TVA
        $this->assertEquals('1234567', $result['cui']);
        $this->assertFalse($result['is_tva']);
    }
}
