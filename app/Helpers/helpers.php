<?php

if (!function_exists('clean_html')) {
    /**
     * Basic HTML sanitization.
     */
    function clean_html($html)
    {
        if (empty($html)) return "";
        
        // Allowed tags for rich text
        $allowed = '<p><a><b><i><u><strong><em><ul><ol><li><br><h1><h2><h3><h4><h5><h6><blockquote><pre><code>';
        return strip_tags($html, $allowed);
    }
}
