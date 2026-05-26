<?php
// includes/header-plain.php
if (!isset($pageTitle)) {
    $pageTitle = 'Pajaken';
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $pageTitle; ?> - Pajaken</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="resources/css/global.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6200ff',
                        'primary-light': '#f0ebff',
                        'primary-dark': '#4a00cc',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 font-sans antialiased">