<?php
// database connection
$server     = "localhost";
$dbuser     = "root";
$dbpassword = "";
$database   = "task";

try {
    $conn = new PDO('mysql:host=' . $server . ';dbname=' . $database, $dbuser, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed:" . $e->getMessage();
}

// Set the content type to JSON
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw JSON data from the request body
    $input_data = file_get_contents('php://input');

    // Decode the JSON data
    $data = json_decode($input_data, true);

    // Validate the required fields
    if (
        isset($data['product_id']) &&
        isset($data['user_id']) &&
        isset($data['review_text'])
    ) {
        // additional validation
        if (gettype($data['product_id']) !== "integer") {
            echo json_encode([
                'status' => 400,
                'message' => 'Product Id must be a number!'
            ]);
            die();
        }
        if (gettype($data['user_id']) !== "integer") {
            echo json_encode([
                'status' => 400,
                'message' => 'User Id must be a number!'
            ]);
            die();
        }

        // Save the review data to the database
        $product_id = htmlspecialchars(strip_tags($data['product_id']));
        $user_id = htmlspecialchars(strip_tags($data['user_id']));
        $review_text = htmlspecialchars(strip_tags($data['review_text']));

        $query = "INSERT INTO reviews (product_id, user_id, review_text) VALUES (:product_id, :user_id, :review_text)";
        $query_run = $conn->prepare($query);

        $insert_data = [
            ':product_id' => $product_id,
            ':user_id' => $user_id,
            ':review_text' => $review_text
        ];
        $query_execute = $query_run->execute($insert_data);

        // Return a response message
        if ($query_execute) {
            $response = [
                'status' => 'success',
                'message' => 'Review submitted successfully.',
            ];
        } else {
            $response = [
                'status' => 'Failed',
                'message' => 'Something went wrong.',
            ];
        }
    } else {
        // Return an error response for missing or invalid fields
        $response = [
            'status' => 'error',
            'message' => 'Invalid or missing data. Please provide product_id, user_id, and review_text.',
        ];
    }
} else {
    // Return an error response for unsupported request methods
    $response = [
        'status' => 'error',
        'message' => 'Unsupported request method. Only POST requests are allowed.',
    ];
}

// Output the JSON response
echo json_encode($response);
