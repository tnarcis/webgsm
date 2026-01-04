/**
 * Tests for WebGSM Checkout JavaScript
 *
 * @package WebGSM\Tests
 */

describe('WebGSM Checkout - Phone Normalization', () => {

  /**
   * Phone normalization function (extracted from checkout.js)
   */
  function normalizePhone(phone) {
    return phone.replace(/[\s\-\.\(\)]/g, '');
  }

  test('should remove spaces from phone number', () => {
    expect(normalizePhone('0722 123 456')).toBe('0722123456');
  });

  test('should remove dashes from phone number', () => {
    expect(normalizePhone('0722-123-456')).toBe('0722123456');
  });

  test('should remove dots from phone number', () => {
    expect(normalizePhone('0722.123.456')).toBe('0722123456');
  });

  test('should remove parentheses from phone number', () => {
    expect(normalizePhone('(0722) 123456')).toBe('0722123456');
  });

  test('should handle mixed separators', () => {
    expect(normalizePhone('(0722)-123.456')).toBe('0722123456');
  });

  test('should handle already normalized phone', () => {
    expect(normalizePhone('0722123456')).toBe('0722123456');
  });

  test('should handle international format', () => {
    expect(normalizePhone('+40 722 123 456')).toBe('+40722123456');
  });
});

describe('WebGSM Checkout - CUI Validation', () => {

  /**
   * CUI validation function
   */
  function validateCUI(cui) {
    // Remove RO prefix
    let cuiNumeric = cui.replace(/^RO/i, '');
    // Remove non-numeric characters
    cuiNumeric = cuiNumeric.replace(/[^0-9]/g, '');
    // Check length (2-10 digits)
    return cuiNumeric.length >= 2 && cuiNumeric.length <= 10;
  }

  test('should validate correct CUI with RO prefix', () => {
    expect(validateCUI('RO31902941')).toBe(true);
  });

  test('should validate correct CUI without RO prefix', () => {
    expect(validateCUI('31902941')).toBe(true);
  });

  test('should reject too short CUI', () => {
    expect(validateCUI('1')).toBe(false);
  });

  test('should reject too long CUI', () => {
    expect(validateCUI('12345678901')).toBe(false);
  });

  test('should accept minimum length CUI', () => {
    expect(validateCUI('12')).toBe(true);
  });

  test('should accept maximum length CUI', () => {
    expect(validateCUI('1234567890')).toBe(true);
  });

  test('should handle CUI with spaces', () => {
    expect(validateCUI('RO 31902941')).toBe(true);
  });
});

describe('WebGSM Checkout - CNP Validation', () => {

  /**
   * CNP validation function
   */
  function validateCNP(cnp) {
    const cnpClean = cnp.replace(/[^0-9]/g, '');
    return cnpClean.length === 13;
  }

  test('should validate correct 13-digit CNP', () => {
    expect(validateCNP('1234567890123')).toBe(true);
  });

  test('should reject 12-digit CNP', () => {
    expect(validateCNP('123456789012')).toBe(false);
  });

  test('should reject 14-digit CNP', () => {
    expect(validateCNP('12345678901234')).toBe(false);
  });

  test('should handle CNP with spaces', () => {
    expect(validateCNP('1 2 3 4 5 6 7 8 9 0 1 2 3')).toBe(true);
  });

  test('should handle CNP with dashes', () => {
    expect(validateCNP('123456-789012-3')).toBe(true);
  });
});

describe('WebGSM Checkout - Email Validation', () => {

  /**
   * Email validation function (basic)
   */
  function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  test('should validate correct email', () => {
    expect(validateEmail('test@example.com')).toBe(true);
  });

  test('should validate email with subdomain', () => {
    expect(validateEmail('user@mail.example.com')).toBe(true);
  });

  test('should validate email with plus sign', () => {
    expect(validateEmail('user+tag@example.com')).toBe(true);
  });

  test('should reject email without @', () => {
    expect(validateEmail('invalid.email.com')).toBe(false);
  });

  test('should reject email without domain', () => {
    expect(validateEmail('test@')).toBe(false);
  });

  test('should reject email without user', () => {
    expect(validateEmail('@example.com')).toBe(false);
  });

  test('should reject email with spaces', () => {
    expect(validateEmail('test @example.com')).toBe(false);
  });
});
