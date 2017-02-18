<?php
/**
 * Holds utility classes and constants to facilitate common operations of PSR-7; the primary purpose is to provide constants for referring to request methods, response status codes and messages, and potentially common headers.
 *
 * @author php-fig <info@php-fig.org>
 * @see https://github.com/php-fig/http-message-util
 */

namespace Core\Fig\Http\Message;
/**
 * Defines constants for common HTTP request methods.
 *
 * Usage:
 *
 * <code>
 * class RequestFactory implements RequestMethodInterface
 * {
 *     public static function factory(
 *         $uri = '/',
 *         $method = self::METHOD_GET,
 *         $data = []
 *     ) {
 *     }
 * }
 * </code>
 *
 * @package Core\Fig\Http\Message
 */
interface RequestMethodInterface
{
    const METHOD_HEAD    = 'HEAD';
    const METHOD_GET     = 'GET';
    const METHOD_POST    = 'POST';
    const METHOD_PUT     = 'PUT';
    const METHOD_PATCH   = 'PATCH';
    const METHOD_DELETE  = 'DELETE';
    const METHOD_PURGE   = 'PURGE';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_TRACE   = 'TRACE';
    const METHOD_CONNECT = 'CONNECT';
}