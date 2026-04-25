<?php
include 'includes/db.php';

$sql = "CREATE TABLE IF NOT EXISTS faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    sort_order INT DEFAULT 0,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'faqs' created successfully or already exists.\n";
    
    // Seed some initial FAQs if empty
    $check = $conn->query("SELECT id FROM faqs LIMIT 1");
    if ($check->num_rows == 0) {
        $seeds = [
            ["How do I book a tour?", "You can book any tour or hotel directly through our website by selecting your destination and following the checkout process."],
            ["What is the cancellation policy?", "Cancellation policies vary by service. Generally, cancellations made 48 hours before the trip are eligible for a full refund."],
            ["How can I track my booking?", "Once a booking is confirmed, you can see all your active and past bookings in the 'My Bookings' section of your user dashboard."],
            ["Are there any hidden charges?", "No, we believe in transparent pricing. The total price shown at checkout includes all applicable taxes and fees."]
        ];
        
        foreach ($seeds as $seed) {
            $q = $conn->real_escape_string($seed[0]);
            $a = $conn->real_escape_string($seed[1]);
            $conn->query("INSERT INTO faqs (question, answer) VALUES ('$q', '$a')");
        }
        echo "Initial FAQs seeded successfully.\n";
    }
} else {
    echo "Error creating table: " . $conn->error . "\n";
}
?>
