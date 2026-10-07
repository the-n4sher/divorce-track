<?php

if (!function_exists('contains_attack_strings')) {

    /**
     * Scan POST data for common XSS / SQL injection patterns.
     * Normal user input will NOT trigger this.
     */
    function contains_attack_strings(array $data): bool
    {
        $patterns = [

            // ---- XSS DETECTION ----
            // <script> tags
            '/<script\b[^>]*>(.*?)<\/script>/is',
            '/<\s*script/iu',

            // Inline JS events: onclick="...", onload='...'
            '/on\w+\s*=\s*["\'].*?["\']/iu',

            // iframe tags (can load external content)
            '/<\s*iframe\b[^>]*>/iu',
            '/<\/\s*iframe\s*>/iu',

            // img tags with suspicious attributes (onerror, onload)
            '/<\s*img\b[^>]*(onerror|onload)\s*=/iu',
            '/<\s*img\b[^>]*src\s*=\s*["\']\s*javascript:/iu',

            // object/embed tags (can execute code)
            '/<\s*object\b[^>]*>/iu',
            '/<\s*embed\b[^>]*>/iu',
            '/<\s*applet\b[^>]*>/iu',

            // style tags (CSS injection)
            '/<\s*style\b[^>]*>(.*?)<\/style>/is',

            // link tags (can load external resources)
            '/<\s*link\b[^>]*>/iu',

            // meta tags with http-equiv (can redirect)
            '/<\s*meta\b[^>]*http-equiv/iu',

            // base tag (can change base URL)
            '/<\s*base\b[^>]*>/iu',

            // form tags (form hijacking)
            '/<\s*form\b[^>]*>/iu',

            // input tags with suspicious types
            '/<\s*input\b[^>]*(type\s*=\s*["\']?button|onclick)/iu',

            // svg tags with script (SVG XSS)
            '/<\s*svg\b[^>]*>.*?<\s*script/ius',

            // javascript: protocol in various contexts
            '/javascript\s*:/iu',

            // data: protocol (can embed scripts)
            '/data\s*:\s*text\/html/iu',
            '/data\s*:\s*image\/svg\+xml/iu',

            // ---- ENCODED XSS ATTEMPTS (bypass attempts) ----
            // HTML entities: &lt;script&gt;, &#60;script&#62;, &#x3c;script&#x3e;
            '/&(lt|#60|#x3c|#X3C);\s*script/iu',
            '/&(lt|#60|#x3c|#X3C);\s*iframe/iu',
            '/&(lt|#60|#x3c|#X3C);\s*img\b/iu',
            '/&(lt|#60|#x3c|#X3C);\s*style/iu',

            // URL encoding: %3Cscript%3E, %3cscript%3e
            '/%3[cC]\s*script\s*%3[eE]/iu',
            '/%3[cC]\s*iframe\s*%3[eE]/iu',
            '/%3[cC]\s*img\b/iu',
            '/%3[cC]\s*style/iu',
            '/javascript\s*%3[aA]/iu',

            // Unicode encoding: \u003cscript\u003e, \U003cscript\U003e
            '/\\\u003[cC]\s*script/iu',
            '/\\\U003[cC]\s*script/iu',

            // Hex encoding: \x3cscript\x3e, \X3Cscript\X3E
            '/\\\x3[cC]\s*script/iu',
            '/\\\X3[cC]\s*script/iu',

            // Double encoding attempts
            '/%253[cC]\s*script/iu', // %25 = encoded %
            '/&amp;(lt|#60);\s*script/iu', // &amp; = encoded &

            // Base64 encoded script (common patterns)
            '/PHNjcmlwdA==/iu', // <script base64
            '/PGlmcmFtZQ==/iu', // <iframe base64

            // ---- SQL INJECTION DETECTION ----
            // (Highly unlikely to appear in normal form input)

            '/\bUNION\b\s+\bSELECT\b/iu',
            '/\bDROP\b\s+\bTABLE\b/iu',
            '/\bINSERT\b\s+\bINTO\b/iu',
            '/\bDELETE\b\s+\bFROM\b/iu',

            // SQL comment
            '/--/u',

            // Classic "' OR 1=1"
            '/\bOR\b\s+1\s*=\s*1/iu',

            // AND 1=1 bypass
            '/\bAND\b\s+1\s*=\s*1/iu',
        ];

        foreach ($data as $key => $value) {

            if (!is_string($value)) {
                continue;
            }

            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $value)) {
                    // Log the exact offending field/value
                    log_message('warning', "SecurityScan: attack-like payload in '{$key}': {$value}");
                    return true;
                }
            }
        }

        return false;
    }
}
