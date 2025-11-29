<?php
echo "intl extension loaded: " . (extension_loaded('intl') ? 'YES' : 'NO') . "\n";
echo "Locale class exists: " . (class_exists('Locale') ? 'YES' : 'NO') . "\n";
if (extension_loaded('intl')) {
    echo "intl version: " . phpversion('intl') . "\n";
}
