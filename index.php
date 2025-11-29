<?php
// Determine the protocol (http or https)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";

// Get the server host (e.g., localhost, 192.168.x.x, domain.com)
$host = $_SERVER['HTTP_HOST'];

// Get the current directory path
// dirname($_SERVER['PHP_SELF']) returns the path to the directory containing the current script
// We replace backslashes with forward slashes for Windows compatibility
$path = str_replace('\\', '/', dirname($_SERVER['PHP_SELF']));

// Remove trailing slashes to ensure clean concatenation
$path = rtrim($path, '/');

// Construct the target URL
$targetUrl = $protocol . "://" . $host . $path . "/public/";

// Redirect
header("Location: " . $targetUrl);
exit;
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="refresh" content="0;url=<?php echo htmlspecialchars($targetUrl); ?>">
<title>Redirecting...</title>
</head>
<body>
    <p>If you are not redirected automatically, follow this <a href="<?php echo htmlspecialchars($targetUrl); ?>">link to the application</a>.</p>
</body>
</html>
