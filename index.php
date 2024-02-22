<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/CalorieMacroCalculator/">
    <title>Calorie and Macro Calculator</title>
    <link rel="stylesheet" href="res/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>

<body>
    <br>
    <br>
    <div id="app">
        <h1>Calorie And Macro Calculator</h1>
        <br>
        <form id="calculatorForm" class="container" method="POST">
            <div class="row">
                <div class="col">
                    <h3>Method</h3>
                    <label for="action">Select Calculation Method:</label>
                    <select id="action" class="form-control">
                        <option value="1">Default</option>
                        <option value="2">LBS Assignment Method</option>
                    </select>
                </div>
                <div class="col">
                    <h3>Units</h3>
                    <label for="units">Select Units:</label>
                    <select id="units" class="form-control">
                        <option value="1">Metric (kg, cm)</option>
                        <option value="2">Imperial (lb, in)</option>
                    </select>
                </div>
            </div>
            <br>
            <!-- Stats Input -->
            <div class="row">
                <div class="col-md-4">
                    <h3>Stats</h3>

                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="number" id="age" name="age" class="form-control" value="19">
                    </div>

                    <div class="form-group">
                        <label for="weight">Weight:</label>

                        <div class="input-group">
                            <input type="number" id="weight" name="weight" class="form-control" value="65">
                            <div class="input-group-append">
                                <div class="input-group-text">kg</div>
                                <div class="input-group-text" style="display: none;">lb</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="height">Height:</label>
                        <div class="input-group">
                            <input type="number" id="height" name="height" class="form-control" value="172">
                            <div class="input-group-append">
                                <div class="input-group-text">cm</div>
                                <div class="input-group-text" style="display: none;">in</div>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label>Gender:</label>
                        <br>
                        <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                            <label class="btn btn-info active">
                                <input type="radio" name="gender" id="male" value="male" autocomplete="off" checked>
                                Male
                            </label>
                            <label class="btn btn-info">
                                <input type="radio" name="gender" id="female" value="female" autocomplete="off"> Female
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <h3>Modifiers</h3>
                    <div class="form-group">
                        <label for="alevel">Activity Level:</label>
                        <select id="alevel" class="form-control">
                            <option value="1.2">Sedentary (1.2)</option>
                            <option value="1.375">Lightly Active (1.375)</option>
                            <option value="1.55">Moderately Active (1.55)</option>
                            <option value="1.725">Very Active (1.725)</option>
                            <option value="1.9">Extremely Active (1.9)</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="goal-d">Goal:</label>
                        <select id="goal-d" class="form-control">
                            <option value="-20">Lose Weight (–20%)</option>
                            <option value="-10">Slowly Lose Weight (–10%)</option>
                            <option value="0" selected>Maintain Weight (0%)</option>
                            <option value="10">Slowly Gain Weight (+10%)</option>
                            <option value="20">Gain Weight (+20%)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="pro">How Much Protein?</label>
                        <select id="pro" class="form-control">
                            <option value="2.2,1">
                                1g per pound (standard)
                            </option>
                            <option value="1.8,0.82">
                                0.82g per pound (acceptable)
                            </option>
                            <option value="	3.3,1.5">
                                1.5g per pound (RIP hunger)
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="fatP">Fat/Carb Calorie Split:</label> <br>

                        <div class="range-container">
                            <input type="range" class="form-range w-100" id="range" min="0" max="100">
                            <div id="track" class="track"></div>

                        </div>

                        <div class="input-group mt-3">
                            <input type="number" id="fatP" class="form-control" min="0" max="100">
                            <span class="input-group-text">% fat</span>
                        </div>
                        </input>
                    </div>

                </div>


                <div class="col-md-4" id="resultColumn">
                    <h3>Results</h3>
                    <br>
                    <h4 class="app-h4">
                        Your
                        <?php
                        $tooltipText = "The Basal Metabolic Rate is a person's energy usage rate while at rest in a "
                            . "temperate environment when the digestive system is inactive. In other words, it is the "
                            . "minimum energy needed to maintain a person's vital organs only.";
                        ?>

                        <a href="#" data-toggle="tooltip" title="<?php echo $tooltipText; ?>">BMR </a>
                        is <b><span id="bmrPlaceholder">0</span></b> kcal
                    </h4>

                    <h4 class="app-h4">
                        Your
                        <a href="#" data-toggle="tooltip"
                            title="The Total Daily Energy Expenditure is the total energy that a person uses in a day.">
                            TDEE </a>
                        is <b><span id="tdeePlaceholder">0</span></b> kcal
                    </h4>
                    <br>
                    <h4 class="app-h4">Daily Calories and Macros</h4>
                    <table id="cal-table" class="table">
                        <thead>
                            <tr id="tbl-r">
                                <th scope="col" id="caltab" style="width: 28%;">Calories</th>
                                <th scope="col" id="protab" style="width: 24%;">Protein</th>
                                <th scope="col" id="fattab" style="width: 24%;">Fat</th>
                                <th scope="col" id="cartab" style="width: 24%;">Carbs</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="tbl-r2">
                                <td id="caltab-r">0</td>
                                <td id="protab-r">0</td>
                                <td id="fattab-r">0</td>
                                <td id="cartab-r">0</td>
                            </tr>
                        </tbody>
                    </table>

                    <h5 id="estimatedWeightLossText">Estimated weight loss per week</h5>
                    <div class="star-effect">
                        <h3 id="estimatedWeightLossValue">0.00 kg</h3>
                        <img src="res/img/confetti.png" alt="Confetti Graphic">
                    </div>
                </div>
            </div>

        </form>
    </div>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="customActivityModal" tabindex="-1" role="dialog"
        aria-labelledby="customActivityModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customActivityModalLabel">Enter Custom Activity Level
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="customActivityForm">
                        <div class="form-group">
                            <label for="sittingHours" title="Watching television, reading, sleeping, etc.">Sitting or
                                lying
                                down:</label>
                            <input type="number" class="form-control" id="sittingHours" min="0" max="24">
                        </div>
                        <div class="form-group">
                            <label for="littleMovementHours" title="Office work, driving, playing chess, etc.">Sitting
                                with little
                                movement:</label>
                            <input type="number" class="form-control" id="littleMovementHours" min="0" max="24">
                        </div>
                        <div class="form-group">
                            <label for="someMovementHours" title="Housework, shopping, cooking, etc.">Sitting with some
                                movement:</label>
                            <input type="number" class="form-control" id="someMovementHours" min="0" max="24">
                        </div>
                        <div class="form-group">
                            <label for="standingHours" title="Walking, gardening, cleaning, etc.">Walking or
                                standing:</label>
                            <input type="number" class="form-control" id="standingHours" min="0" max="24">
                        </div>
                        <div class="form-group">
                            <label for="moreMovementHours" title="Hiking, dancing, golfing, etc.">Walking or standing
                                with more
                                movement:</label>
                            <input type="number" class="form-control" id="moreMovementHours" min="0" max="24">
                        </div>
                        <div class="form-group">
                            <label for="strenuousHours" title="Jogging, swimming, cycling, weightlifting">Physically
                                strenuous:</label>
                            <input type="number" class="form-control" id="strenuousHours" min="0" max="24">
                        </div>
                        <div class="form-group">
                            <label for="veryStrenuousHours"
                                title="Football, skiing, intense cycling, cardio training">Physically
                                very strenuous:</label>
                            <input type="number" class="form-control" id="veryStrenuousHours" min="0" max="24">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveActivity">Save
                        changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Include Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>

    <script src="res/js/page.js"></script>

</body>


</html>