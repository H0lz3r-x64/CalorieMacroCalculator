// Function to fetch data from the backend
const fetchData = (data) => {
    return $.ajax({
        type: "POST",
        url: "backend.php",
        data: data,
    });
};

// Function to calculate estimated weight loss/gain
const calculateEstimatedWeight = (estimatedKgPerWeek, units) => {
    const estimatedWeightPerWeek = Math.abs(
        estimatedKgPerWeek * (units === "2" ? 2.20462 : 1)
    ).toFixed(2);
    const weightUnit = units === "2" ? "lbs" : "kg";
    const weightChangeType = estimatedKgPerWeek > 0 ? "gain" : "loss";

    return {
        text: `Estimated weight ${weightChangeType} per week`,
        value: `${estimatedWeightPerWeek} ${weightUnit}`,
    };
};

// Improved Function to update dynamic values
const updateDynamicValues = () => {
    const data = {
        action: $("#action").val(),
        age: $("#age").val(),
        weight: $("#weight").val(),
        height: $("#height").val(),
        gender: $("input[name=gender]:checked").val(),
        pal: $("#alevel").val(),
        goal: $("#goal-d").val() / 100, // convert percentage to decimal
        proteinPerKg: $("#pro").val(),
        fatPercentage: $("#fatP").val() / 100, // convert percentage to decimal
        units: $("#units").val(), // units parameter
    };

    fetchData(data)
        .done((response) => {
            try {
                const {
                    bmr,
                    tdee,
                    goalCalories,
                    protein,
                    fat,
                    carbs,
                    estimatedKgPerWeek,
                } = response;

                $("#bmrPlaceholder").text(Math.round(bmr));
                $("#tdeePlaceholder").text(Math.round(tdee));
                $("#caltab-r").text(Math.round(goalCalories));
                $("#protab-r").text(Math.round(protein));
                $("#fattab-r").text(Math.round(fat));
                $("#cartab-r").text(Math.round(carbs));

                const { text, value } = calculateEstimatedWeight(
                    estimatedKgPerWeek,
                    data.units
                );

                $("#estimatedWeightLossText").text(text);
                $("#estimatedWeightLossValue").text(value);
            } catch (e) {
                console.log("Error parsing JSON response");
                console.log("Response: ", response);
            }
        })
        .fail((xhr, status, error) => {
            console.log(xhr.responseText, status, error);
        });
};

// Function to update track color
const updateTrackColor = (input) => {
    const percent = ((input.value - input.min) / (input.max - input.min)) * 100;

    if ($("#track").length > 0) {
        $("#track").css(
            "background",
            `linear-gradient(to right, #45b39d ${percent}%, #f39c12 ${percent}%)`
        );
    }
};

// Function to handle form changes
const handleFormChanges = () => {
    $("form input, form select").change(() => {
        updateDynamicValues();
    });
};

// Function to handle range input
const handleRangeInput = () => {
    $("#range").on("input", function () {
        updateTrackColor(this);
        $("#fatP").val($(this).val());
    });

    $("#fatP").on("input", function () {
        updateTrackColor(this);
        $("#range").val($(this).val());
    });
};

// Function to handle activity level changes
const handleActivityLevelChanges = () => {
    $("#alevel").change(function () {
        if ($(this).val() === "custom") {
            $("#customActivityModal").modal("show");
        }
    });
};

// Function to save activity
const saveActivity = () => {
    $("#saveActivity").click(function () {
        const sittingHours = Number($("#sittingHours").val());
        const littleMovementHours = Number($("#littleMovementHours").val());
        const someMovementHours = Number($("#someMovementHours").val());
        const standingHours = Number($("#standingHours").val());
        const moreMovementHours = Number($("#moreMovementHours").val());
        const strenuousHours = Number($("#strenuousHours").val());
        const veryStrenuousHours = Number($("#veryStrenuousHours").val());

        const totalHours =
            sittingHours +
            littleMovementHours +
            someMovementHours +
            standingHours +
            moreMovementHours +
            strenuousHours +
            veryStrenuousHours;

        if (totalHours > 24) {
            alert("Invalid hours. The total cannot exceed 24.");
            return;
        }

        const sleepHours = 24 - totalHours;

        // Calculate PAL factor
        const palFactor =
            (sittingHours * 1.2 +
                littleMovementHours * 1.375 +
                someMovementHours * 1.55 +
                standingHours * 1.725 +
                moreMovementHours * 1.9 +
                strenuousHours * 2.2 +
                veryStrenuousHours * 2.5 +
                sleepHours * 1) /
            24;

        // Remove existing calculated value option if it exists by searching for options containing "Calculated Value"
        $('#alevel option:contains("Calculated Value")').remove();

        // Add new option with calculated PAL factor
        $("#alevel").append(
            new Option(`Calculated Value (${palFactor.toFixed(2)})`, palFactor)
        );

        // Select the new option
        $("#alevel").val(palFactor);
        $("#customActivityModal").modal("hide");
    });
};

// Function to handle modal hide
const handleModalHide = () => {
    $("#customActivityModal").on("hide.bs.modal", function () {
        if ($("#alevel").val() == "custom") {
            $("#alevel").prop("selectedIndex", 0);
        }
    });
};

// Function to enforce min and max values for input fields
function enforceMinMax(selector, min, max) {
    $(selector).on("input", function () {
        var value = $(this).val();
        if (value < min) $(this).val(min);
        if (value > max) $(this).val(max);
    });
}

// Function to handle units change
const handleUnitsChange = () => {
    const unitsSelect = $("#units");
    const weightUnits = $("#weight ~ .input-group-append .input-group-text");
    const heightUnits = $("#height ~ .input-group-append .input-group-text");

    unitsSelect.on("change", function () {
        const weightInput = $("#weight");
        const heightInput = $("#height");

        if ($(this).val() === "2") {
            // Imperial units selected
            weightUnits.eq(0).hide();
            weightUnits.eq(1).show();
            heightUnits.eq(0).hide();
            heightUnits.eq(1).show();

            // Convert weight from kg to lbs (1 kg is approximately 2.20462 lbs)
            const weightInLbs = Math.round(
                parseFloat(weightInput.val()) * 2.20462
            );
            weightInput.val(weightInLbs);

            // Convert height from cm to inches (1 cm is approximately 0.393701 inches)
            const heightInInches = Math.round(
                parseFloat(heightInput.val()) * 0.393701
            );
            heightInput.val(heightInInches);
        } else {
            // Metric units selected
            weightUnits.eq(0).show();
            weightUnits.eq(1).hide();
            heightUnits.eq(0).show();
            heightUnits.eq(1).hide();

            // Convert weight from lbs to kg
            const weightInKg = Math.round(
                parseFloat(weightInput.val()) / 2.20462
            );
            weightInput.val(weightInKg);

            // Convert height from inches to cm
            const heightInCm = Math.round(
                parseFloat(heightInput.val()) / 0.393701
            );
            heightInput.val(heightInCm);
        }
    });
};

// Call the functions on page load
$(document).ready(() => {
    enforceMinMax("#age", 0, 100);
    enforceMinMax("#weight", 0, 300);
    enforceMinMax("#fatp", 0, 100);

    handleUnitsChange();
    handleRangeInput();
    handleActivityLevelChanges();
    saveActivity();
    handleModalHide();
    handleFormChanges();

    // Trigger input event on page load to update track color
    $("#range").val(50).trigger("input");
    updateDynamicValues();
});
