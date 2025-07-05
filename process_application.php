<?php
// process_application.php
// IMPORTANT: This is a conceptual script. Security and robust error handling are CRITICAL
// for any production use. Ensure file permissions, input sanitization, and server configurations
// are correctly and securely implemented.

$message = "";
$error_message = "";
$applicationData = ""; // Initialize to prevent error if not set later

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- Collect and Sanitize Data (Basic Example) ---
    // For production, use more robust sanitization and validation methods.
    $fullName = isset($_POST['fullName']) ? htmlspecialchars(trim($_POST['fullName'])) : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $pronouns = isset($_POST['pronouns']) ? htmlspecialchars(trim($_POST['pronouns'])) : '';
    $ageConfirm = isset($_POST['ageConfirm']) ? $_POST['ageConfirm'] : '';
    $liabilityWaiver = isset($_POST['liabilityWaiver']) ? $_POST['liabilityWaiver'] : '';
    $aboutYourself = isset($_POST['aboutYourself']) ? htmlspecialchars(trim($_POST['aboutYourself'])) : '';
    $projectInterests = isset($_POST['projectInterests']) ? htmlspecialchars(trim($_POST['projectInterests'])) : '';
    $memberReferences = isset($_POST['memberReferences']) ? htmlspecialchars(trim($_POST['memberReferences'])) : '';
    $tierInterest = isset($_POST['tierInterest']) ? htmlspecialchars(trim($_POST['tierInterest'])) : '';

    // --- Basic Validation ---
    if (empty($fullName) || empty($email) || empty($ageConfirm) || empty($liabilityWaiver) || empty($aboutYourself) || empty($projectInterests)) {
        $error_message = "Error: Please fill in all required fields.";
    }
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) { // Check !empty($email) before validation
        $error_message .= "<br>Error: Invalid email format.";
    }
    if ($ageConfirm !== '18plus') {
        $error_message .= "<br>Error: You must confirm you are 18 or older.";
    }
    if ($liabilityWaiver !== 'agreed') {
        $error_message .= "<br>Error: You must agree to the Acknowledgement of Risks & Waiver of Liability.";
    }

    if (empty($error_message)) {
        // --- Format Data for Storage/Email ---
        $applicationData = "New Membership Application:\n";
        $applicationData .= "Date: " . date("Y-m-d H:i:s") . "\n";
        $applicationData .= "Full Name: " . $fullName . "\n";
        $applicationData .= "Email: " . $email . "\n";
        $applicationData .= "Pronouns: " . $pronouns . "\n";
        $applicationData .= "Age Confirmed (18+): " . $ageConfirm . "\n";
        $applicationData .= "Liability Waiver Agreed: " . $liabilityWaiver . "\n";
        $applicationData .= "About Yourself: " . $aboutYourself . "\n";
        $applicationData .= "Project Interests: " . $projectInterests . "\n";
        $applicationData .= "Member References: " . $memberReferences . "\n";
        $applicationData .= "Interest in Tiered Membership: " . $tierInterest . "\n";
        $applicationData .= "-------------------------------------------------\n\n";

        // --- Conceptual: Store Data in a Local File ---
        // WARNING: This is a very basic example. For production:
        // 1. Ensure the directory is NOT web-accessible.
        // 2. Ensure proper file permissions (e.g., PHP script can write, but file not world-readable/writable).
        // 3. Implement file locking (flock) to prevent race conditions if multiple people apply at once.
        // 4. Consider more robust storage like a database.
        // 5. Add error handling for file operations.
        $filePath = __DIR__ . '/_private_data/submitted_applications/applications.txt';

        // --- Store Data in a Local File ---
        $applicationsDir = dirname($filePath);
        if (!file_exists($applicationsDir)) {
            // Attempt to create the directory if it doesn't exist.
            // Warning: mkdir() permissions (0750) might need adjustment based on your server setup.
            // The web server user needs to be able to create this directory or it must exist and be writable.
            if (!mkdir($applicationsDir, 0750, true)) { // 0750: owner rwx, group rx, others ---
                $error_message .= "<br>Critical Error: Failed to create the application storage directory ('" . htmlspecialchars($applicationsDir) . "'). Please check server permissions and ensure the parent directory is writable by the web server.";
            }
        } elseif (!is_writable($applicationsDir)) {
             $error_message .= "<br>Critical Error: The application storage directory ('" . htmlspecialchars($applicationsDir) . "') is not writable by the web server. Please check permissions.";
        }

        // Proceed only if there are no errors so far (including directory errors)
        if (empty($error_message)) {
            if (file_put_contents($filePath, $applicationData, FILE_APPEND | LOCK_EX)) {
                $message = "Application submitted successfully. We will review it and get back to you.";
            } else {
                $error_message .= "<br>Error: Could not save application data to file ('" . htmlspecialchars($filePath) . "'). Please contact us directly. This might be due to file permissions or other server issues.";
                // Log this error for admin review.
            }
        }
        // $message = "Application data prepared (File writing is conceptual and commented out in this template)."; // This line is now removed


        // --- Conceptual: Send Email Notification ---
        // WARNING: Requires a configured mail server (SMTP) on your web server.
        // The 'mail()' function can be unreliable; consider using a library like PHPMailer.
        // $to = "club@robotclub.to";
        // $subject = "New Membership Application from " . $fullName;
        // $headers = "From: webserver@yourdomain.com"; // Replace with a valid sender email
        // if (mail($to, $subject, $applicationData, $headers)) {
        //    // Email sent successfully
        // } else {
        //    // Email sending failed - might still save to file, or log error
        //    $error_message .= "<br>Notice: Could not send email notification, but application may have been saved.";
        // }
        $message .= "<br>Email notification is conceptual and commented out in this template.";


        // Clear form fields after successful submission (optional, for display purposes)
        // $_POST = array(); // Or redirect to a thank you page
    }
} else {
    // Not a POST request, or direct access to script
    $message = "Please fill out the application form.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status - Robot Club TO</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your existing stylesheet -->
    <style>
        /* Additional styles for this page if needed */
        main { padding-bottom: 80px; /* Ensure space for footer */ } /* Added to help with footer overlap potentially */
        .status-message { padding: 20px; margin: 20px auto; border-radius: 5px; max-width: 800px; } /* Centered status messages */
        .success { background-color: #e7f4e7; color: #006400; }
        .error { background-color: #f8d7da; color: #721c24; }
        pre {
            background-color: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            white-space: pre-wrap; /* Handles long lines */
            word-wrap: break-word; /* Breaks long words if necessary */
            max-width: 800px;
            margin: 20px auto; /* Centered pre block */
        }
        nav ul li a { /* Copied from main style.css for consistency if nav is different */
            color: #ffffff;
            text-decoration: none;
            font-size: 1.1em;
            padding: 0.5em 1em;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        nav ul li a:hover,
        nav ul li a:focus {
            background-color: #0059b3;
            color: #e6e6e6;
        }
    </style>
</head>
<body>
    <header>
        <!-- Assuming logo might be here too, ensure path is correct from this file's location -->
        <img src="logo.png" alt="Robot Club TO Logo" id="club-logo" style="display: none;"> <!-- JS will try to show it -->
        <h1>Robot Club TO - Application Status</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="application.html">Back to Application</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h2>Application Submission Status</h2>
            <?php if (!empty($message) && empty($error_message)): // Show success only if no errors ?>
                <p class="status-message success"><?php echo $message; ?></p>
            <?php endif; ?>
            <?php if (!empty($error_message)): ?>
                <p class="status-message error"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <?php if (empty($message) && empty($error_message) && $_SERVER["REQUEST_METHOD"] != "POST"): // Initial state before POST ?>
                <p class="status-message"><?php echo "Please fill out the application form."; ?></p>
            <?php endif; ?>

            <?php if (!empty($error_message) || ($_SERVER["REQUEST_METHOD"] != "POST" && empty($message) && empty($error_message)) ): ?>
                <p style="text-align: center;">If you encountered an error or haven't submitted, please <a href="application.html">go back to the form</a> and try again, ensuring all required fields are completed.</p>
            <?php endif; ?>

            <?php if (empty($error_message) && !empty($applicationData) && $_SERVER["REQUEST_METHOD"] == "POST"): ?>
                <h3>Submitted Data (For Your Review):</h3>
                <pre><?php echo htmlspecialchars($applicationData); ?></pre>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Robot Club TO. All rights reserved.</p>
    </footer>
    <!-- script.js linked for logo loading or other general scripts -->
    <script src="script.js"></script>
</body>
</html>
