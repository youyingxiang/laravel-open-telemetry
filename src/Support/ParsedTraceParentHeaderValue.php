<?php

namespace Spatie\OpenTelemetry\Support;

use OpenTelemetry\API\Trace\SpanContext;

class ParsedTraceParentHeaderValue
{
    public static function make(string $headerValue): ?self
    {
        if (substr_count($headerValue, '-') !== 3) {
            return null;
        }

        [$version, $traceId, $spanId, $flags] = explode('-', $headerValue);
        if ($version !== '00') {
            return null;
        }

        if (! SpanContext::isValidTraceId($traceId)) {
            return null;
        }

        if (! SpanContext::isValidSpanId($spanId)) {
            return null;
        }

        if (! SpanContext::isValidTraceFlag($flags)) {
            return null;
        }

        return new self($version, $traceId, $spanId, $flags);
    }

    public function __construct(
        public string $version,
        public string $traceId,
        public string $spanId,
        public string $flags,
    ) {
    }
}
