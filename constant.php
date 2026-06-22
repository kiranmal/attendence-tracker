<?php
define('ACCESS_TOKEN','EAAQpSzbGwIYBRLwGYfSDEZAUbzK89Aw1ZAL6TxZAR8bKZBAGex46N2ZCcFCWZBadQGPzq57GhjiDJQauvNOVx2mjBUEfBuS3D9BrOgakaZAWCQ6B5bCfYFPohF4pGNd6ieIiFK76ZBYOc6illxJ6D7ZAlqX3XIvTKzMHUfrRdp8Hl6SFhBguFFM4t6dM31UpJuhzD8wZDZD');
define('USER_OTP', rand(100000,999999));
define('PHONE_NUMBER_ID', '968565872999312');
define('SENDER_TO', '919818596699');
define('VERSION', 'v24.0');
define('OTP_CODE', rand(100000,999999));
define('PURPOSE' , 'login');
define('VALID_FOR','10 minutes');
define('HELP_PHONE','+91 92174 96699');
define('BUTTON_VALUE', OTP_CODE);
define('WP_URL', 'https://graph.facebook.com/' . VERSION . '/' . PHONE_NUMBER_ID . '/messages');

// PCL Services URL
define('PCL_SERVICE_URL', 'https://procorporateleague.com/pcl_rest/api/v9/auth/');
