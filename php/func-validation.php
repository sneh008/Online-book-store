<?php  

# Form validation function
function is_empty($var, $text, $location, $ms = 'error') {
    if (empty(trim($var))) {
        $_SESSION[$ms] = "The $text is required";
        header("Location: $location");
        exit;
    }
    return false;
}
