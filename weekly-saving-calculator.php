<?php
/**
 * Plugin Name:       Weekly Saving Calculator
 * Plugin URI:        https://github.com/lumumbapl/Weekly-Saving-Calculator
 * Description:       The Weekly Saving Calculator is a handy WordPress plugin that helps users plan and track their weekly savings over a 52-week period. 
 * Version:           1.0.0
 * Author:            Patrick Lumumba
 * Author URI:        https://wpcorner.co
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       Weekly Saving Calculator
 * Domain Path:       /languages
 */


// Define the shortcode for the calculator
function weekly_saving_calculator_shortcode($atts) {
    ob_start();
    weekly_saving_calculator_page();
    return ob_get_clean();
}

add_shortcode('weekly_saving_calculator', 'weekly_saving_calculator_shortcode');

// Define the plugin page content
function weekly_saving_calculator_page() {
    ?>
    <div class="wrap">
        <h1>Weekly Saving Calculator</h1>

        <!-- Your HTML form for user input -->
        <form method="post">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required>

            <label for="start_amount">Start Amount:</label>
            <input type="number" id="start_amount" name="start_amount" required>

            <input type="submit" name="calculate" value="Calculate">
        </form>

        <?php
        // Handle form submission
        if (isset($_POST['calculate'])) {
            $start_date = sanitize_text_field($_POST['start_date']);
            $start_amount = floatval($_POST['start_amount']);
            
            // Perform calculations and display results
            display_savings_table($start_date, $start_amount);
        }
        ?>
    </div>
    <?php
}

// Function to calculate and display savings table
function display_savings_table($start_date, $start_amount) {
    ?>
    <h2>Savings Table</h2>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Week</th>
                <th>Weekly Deposit Amount</th>
                <th>Total Deposit</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $current_date = new DateTime($start_date);
            $total_deposit = 0;

            for ($week = 1; $week <= 52; $week++) {
                $weekly_deposit = $start_amount * $week;
                $total_deposit += $weekly_deposit;

                echo '<tr>';
                echo '<td>' . $current_date->format('Y-m-d') . '</td>';
                echo '<td>' . $week . '</td>';
                echo '<td>$' . number_format($weekly_deposit, 2) . '</td>';
                echo '<td>$' . number_format($total_deposit, 2) . '</td>';
                echo '</tr>';

                $current_date->modify('+1 week');
            }
            ?>
        </tbody>
    </table>
    <h3>Summary</h3>
    <p>Incremental Amount: $<?php echo number_format($start_amount, 2); ?></p>
    <p>Total Savings in 52 Weeks: $<?php echo number_format($total_deposit, 2); ?></p>
    <?php
}
