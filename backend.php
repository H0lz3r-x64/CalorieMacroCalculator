<?php

const HTTP_METHOD_NOT_ALLOWED = 405;
const HTTP_BAD_REQUEST = 400;
const HTTP_OK = 200;

$postParams = [
    'action' => FILTER_SANITIZE_NUMBER_INT,
    'age' => FILTER_SANITIZE_NUMBER_INT,
    'weight' => FILTER_SANITIZE_NUMBER_FLOAT,
    'height' => FILTER_SANITIZE_NUMBER_FLOAT,
    'gender' => FILTER_SANITIZE_SPECIAL_CHARS,
    'pal' => FILTER_SANITIZE_NUMBER_FLOAT,
    'goal' => FILTER_SANITIZE_NUMBER_FLOAT,
    'proteinPerKg' => FILTER_SANITIZE_NUMBER_FLOAT,
    'fatPercentage' => FILTER_SANITIZE_NUMBER_FLOAT,
    'units' => FILTER_SANITIZE_SPECIAL_CHARS
];

$data = [];

foreach ($postParams as $param => $filter) {
    $sanitized = isset($_POST[$param]) ? filter_var($_POST[$param], $filter, FILTER_FLAG_ALLOW_FRACTION) : null;
    if ($filter === FILTER_SANITIZE_NUMBER_INT) {
        $sanitized = (int) $sanitized;
    }
    if ($filter === FILTER_SANITIZE_NUMBER_FLOAT) {
        $sanitized = (float) $sanitized;
    }
    if ($filter === FILTER_SANITIZE_STRING) {
        $sanitized = (string) $sanitized;
    }
    $data[$param] = $sanitized;
}

if (in_array(null, $data, true)) {
    returnJsonHttpResponse(HTTP_BAD_REQUEST, 'Bad Request');
}

if ($data['units'] === '2') {
    $data['weight'] *= 0.453592;
    $data['height'] *= 2.54;
}

switch ($data['action']) {
    case 1:
        calculateNutrition($data);
        break;
    case 2:
        calculateNutritionLbs($data);
        break;
    default:
        echo 'Invalid action';
        exit;
}
exit;

function calculateBMR($age, $weight, $height, $gender, $multiplier)
{
    return $gender === 'male'
        ? 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age)
        : 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);
}

function calculateBMRLBS($age, $weight, $height, $gender, $multiplier)
{
    return $gender === 'male'
        ? 66.47 + (13.7 * $weight) + (5 * $height) - (6.8 * $age)
        : 655.1 + (9.6 * $weight) + (1.8 * $height) - (4.7 * $age);
}

function calculateNutrition($data)
{
    $bmr = calculateBMR($data['age'], $data['weight'], $data['height'], $data['gender'], $data['pal']);
    calculateMacros($bmr, $data);
}

function calculateNutritionLbs($data)
{
    $bmr = calculateBMRLBS($data['age'], $data['weight'], $data['height'], $data['gender'], $data['pal']);
    calculateMacros($bmr, $data);
}

function calculateMacros($bmr, $data)
{
    // Calculate TDEE
    $tdee = $bmr * $data['pal'];

    // Calculate goal calories
    $goalCalories = $tdee + ($tdee * $data['goal']);

    // Calculate macros
    $protein = $data['proteinPerKg'] * $data['weight'];
    $proteinCalories = $protein * 4;

    // Calculate remaining calories for fat and carbs
    $remainingCalories = $goalCalories - $proteinCalories;

    // Calculate fat and carbs based on the fat-carb split
    $fatCalories = $remainingCalories * $data['fatPercentage'];
    $fat = $fatCalories / 9;
    $carbCalories = $remainingCalories - $fatCalories;
    $carbs = $carbCalories / 4;

    // Calculate estimated kg per week
    $calorieDeficitSurplus = ($goalCalories - $tdee) * 7;
    $estimatedKgPerWeek = $calorieDeficitSurplus / 7700;

    returnJsonHttpResponse(
        HTTP_OK,
        array(
            'bmr' => $bmr,
            'tdee' => $tdee,
            'goalCalories' => $goalCalories,
            'protein' => $protein,
            'fat' => $fat,
            'carbs' => $carbs,
            'estimatedKgPerWeek' => $estimatedKgPerWeek
        )
    );
}

function returnJsonHttpResponse($httpCode, $data)
{
    ob_start();
    ob_clean();
    header_remove();
    header("Content-type: application/json; charset=utf-8");
    http_response_code($httpCode);
    echo json_encode($data);
    exit();
}