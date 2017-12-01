<?php
	header('Content-Type: application/json');
    session_start();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }

    $headers = apache_request_headers();
    if (isset($headers['CsrfToken'])) {
        if ($headers['CsrfToken'] !== $_SESSION['csrf_token']) {
            exit(json_encode(['error' => 'Wrong CSRF token.']));
        }
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            $address = 'http://' . $_SERVER['SERVER_NAME'];
            if (strpos($address, $_SERVER['HTTP_ORIGIN']) !== 0 || strpos($address, "http://localhost")) {
                exit(json_encode([
                    'error' => 'Invalid Origin header: ' . $_SERVER['HTTP_ORIGIN']
                ]));
            }
            if(isset($_SESSION["rows"])){
                $json = $_SESSION["rows"];
                echo json_encode($json);
            }
        } else {
            exit(json_encode(['error' => 'No Origin header']));
        }

    } else {
        exit(json_encode(['error' => 'No CSRF token.']));
    }

?>
