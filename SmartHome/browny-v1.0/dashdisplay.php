<!DOCTYPE html>
<html lang="en">
<?php
$pageTitle = "Dashboard";
include 'head.php';
?>

<body>
    

    <?php include 'navbar.php';
    include 'TraumaDump.php' ?>

    <div class="clearfix"></div>

    <section>
        <div class="page">
            <!-- Left section -->
            <div class="left-section">
                <div class="data-container">
                    <?php
                    $data = ['Luminosity' => 'luminosity', 'Temperature' => 'temperature', 'Presence' => 'presence'];
                    foreach ($data as $label => $id) {
                        echo "<div class='data-square'>
                                <h3 class='data-label'>$label</h3>
                                <p id='$id' class='data-value'></p>
                              </div>";
                    }
                    ?>
                </div>

                <div class="chartContainer">
                    <canvas id="realTimeChart"></canvas>
                </div>

                <div class="chartContainer">
                    <label>Select an Option:</label>
                    <select id="menu-range" name="menu" onchange="fetchHistoryTemp()">
                        <option value="Week">7 Days</option>
                        <option value="Month">30 Days</option>
                    </select>
                    <canvas id="visualTemp"></canvas>
                </div>

                <div class="chartContainer">
                    <label>Select an Option:</label>
                    <select id="menu-range-fan" name="menu" onchange="fetchFanWorkingCount()">
                        <option value="Week">7 Days</option>
                        <option value="Month">30 Days</option>
                    </select>
                    <canvas id="fanWorkingChart"></canvas>
                </div>

            </div>

            <!-- Right section -->
            <div class="right-section">
                <div class="box">
                    <h3>LED Control</h3>
                    <div class="slider-container">
                        <input type="range" class="slider" id="powerSlider1" min="0" max="100" step="5" value="<?= $latest_value1 ?>">
                        <div class="value-display">LED Power: <span id="sliderValue1"><?= $latest_value1 ?></span>%</div>
                    </div>
                </div>

                <div class="box">
                    <h3>Fan Control</h3>
                    <div class="slider-container">
                        <input type="range" class="slider" id="powerSlider" min="0" max="100" step="5" value="<?= $latest_value ?>">
                        <div class="value-display">Fan Power: <span id="sliderValue"><?= $latest_value ?></span>%</div>
                    </div>
                </div>

                <div class="box">
                    <h3>Automation</h3>
                    <div class="toggle-row">
                        <span>Automatic:</span>
                        <label class="switch">
                            <input type="checkbox" id="automaticToggle">
                            <span class="slider1 round"></span>
                        </label>
                        <button id="resetButton">Reset</button>
                    </div>

                    <div id="inputBoxContainer" class="hidden">
                        <h4>Input Thresholds</h4>
                        <?php
                        $inputs = [
                            'lightThreshold' => 'Light Threshold',
                            'fanThreshold' => 'Temperature Threshold',

                        ];
                        foreach ($inputs as $id => $label) {
                            echo "<div class='input-group'>
                                    <label for='$id'>$label:</label>
                                    <input type='number' id='$id' placeholder='Enter $label'>
                                  </div>";
                        }
                        ?>
                        <div class="button-row">
                            <button id="confirmButton">Confirm</button>
                        </div>
                    </div>

                    <div id="inputDisplay"></div>
                </div>
            </div>
        </div>
    </section>

    <script src="dashdisplay.js"></script>

</body>

</html>