<?php
session_start(); 

if (isset($_POST['save-information']) && $_SERVER["REQUEST_METHOD"] == "POST") {

    require_once('database-connection.php');

    $connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check the database connection
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve form data
    $user_id = isset($_POST['session_id']) ? $_POST['session_id'] : '';

    // Question 1 
    $health_description = isset($_POST['health_description']) ? $_POST['health_description'] : '';

    // Question 2
    $prescription = isset($_POST['prescription']) ? $_POST['prescription'] : '';
    $medication_names = isset($_POST['medication_name']) ? implode(", ", $_POST['medication_name']) : '';
    $pill_amounts = isset($_POST['pill_amount']) ? implode(", ", $_POST['pill_amount']) : '';
    $pill_doses = isset($_POST['pill_doses']) ? implode(", ", $_POST['pill_doses']) : '';

    // Question 3
    $otc_medicines = isset($_POST['otc_medicines']) ? implode(", ", $_POST['otc_medicines']) : '';
    $herbal_medicine_list = isset($_POST['herbal_medicine_list']) ? $_POST['herbal_medicine_list'] : '';
    $other_medicine_list = isset($_POST['other_medicine_list']) ? $_POST['other_medicine_list'] : '';

    // Question 4
    $allergic_reaction = isset($_POST['allergic_reaction']) ? $_POST['allergic_reaction'] : '';
    $allergic_medicine = isset($_POST['allergic_medicine']) ? implode(", ", $_POST['allergic_medicine']) : '';
    $allergic_reaction_description = isset($_POST['allergic_reaction_description']) ? implode(", ", $_POST['allergic_reaction_description']) : '';

    // Question 5
    $allergy_triggers = isset($_POST['allergy_triggers']) ? implode(", ", $_POST['allergy_triggers']) : '';
    $other_allergic_triggers = isset($_POST['other_allergy_triggers']) ? $_POST['other_allergy_triggers'] : '';

    // Question 6
    $pregnant = isset($_POST['pregnant']) ? $_POST['pregnant'] : '';
    $pregnancy_times = isset($_POST['pregnancy_times']) ? $_POST['pregnancy_times'] : '';
    $children_birthed = isset($_POST['children_birthed']) ? $_POST['children_birthed'] : '';

    // Question 7
    $pap_smear = isset($_POST['pap_smear']) ? $_POST['pap_smear'] : '';
    $last_pap_smear_date = isset($_POST['last_pap_smear_date']) ? $_POST['last_pap_smear_date'] : '';

    // Question 8
    $abnormal_pap = isset($_POST['abnormal_pap']) ? $_POST['abnormal_pap'] : '';

    // Question 9
    $mammogram = isset($_POST['mammogram']) ? $_POST['mammogram'] : '';
    $last_mammogram_date = isset($_POST['last_mammogram_date']) ? $_POST['last_mammogram_date'] : '';

    // Family History
    $mother_medical_problems = isset($_POST['mother_medical_problems']) ? $_POST['mother_medical_problems'] : '';
    $father_medical_problems = isset($_POST['father_medical_problems']) ? $_POST['father_medical_problems'] : '';
    $sisters_medical_problems = isset($_POST['sisters_medical_problems']) ? $_POST['sisters_medical_problems'] : '';
    $brothers_medical_problems = isset($_POST['brothers_medical_problems']) ? $_POST['brothers_medical_problems'] : '';

    // History of Medical Conditions
    $medical_conditions = isset($_POST['medical_conditions']) ? implode(", ", $_POST['medical_conditions']) : '';
    $other_condition = isset($_POST['other_condition']) ? $_POST['other_condition'] : '';

    // Check if the user already exists in the patient_form table
    $sql_check = "SELECT COUNT(*) AS user_count FROM patient_form WHERE user_id = ?";
    $stmt_check = mysqli_prepare($connect, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "i", $user_id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_bind_result($stmt_check, $user_count);
    mysqli_stmt_fetch($stmt_check);
    mysqli_stmt_close($stmt_check);

    // Decide whether to insert or update data
    if ($_SESSION["isFirstUse"] || $user_count == 0) {
        // First use or user_id not found: insert data into the database
        $sql = "INSERT INTO patient_form (user_id, health_description, prescription, medication_name, pill_amount, pill_doses, otc_medicines, herbal_medicine_list, other_medicine_list, allergic_reaction, allergic_medicine, allergic_reaction_description, allergy_triggers, other_allergy_triggers, pregnant, pregnancy_times, children_birthed, pap_smear, last_pap_smear_date, abnormal_pap, mammogram, last_mammogram_date, mother_medical_problems, father_medical_problems, sisters_medical_problems, brothers_medical_problems, medical_conditions, other_condition)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, "isssssssssssssssssssssssssss", $user_id, $health_description, $prescription, $medication_names, $pill_amounts, $pill_doses, $otc_medicines, $herbal_medicine_list, $other_medicine_list, $allergic_reaction , $allergic_medicine, $allergic_reaction_description, $allergy_triggers, $other_allergic_triggers, $pregnant, $pregnancy_times, $children_birthed, $pap_smear, $last_pap_smear_date, $abnormal_pap, $mammogram, $last_mammogram_date, $mother_medical_problems, $father_medical_problems, $sisters_medical_problems, $brothers_medical_problems, $medical_conditions, $other_condition);
    } else {
        // Not the first use and user_id found: update data in the database
        $sql = "UPDATE patient_form SET health_description = ?, prescription = ?, medication_name = ?, pill_amount = ?, pill_doses = ?, otc_medicines = ?, herbal_medicine_list = ?, other_medicine_list = ?, allergic_reaction = ?, allergic_medicine = ?, allergic_reaction_description = ?, allergy_triggers = ?, other_allergy_triggers = ?, pregnant = ?, pregnancy_times = ?, children_birthed = ?, pap_smear = ?, last_pap_smear_date = ?, abnormal_pap = ?, mammogram = ?, last_mammogram_date = ?, mother_medical_problems = ?, father_medical_problems = ?, sisters_medical_problems = ?, brothers_medical_problems = ?, medical_conditions = ?, other_condition = ? WHERE user_id = ?";

        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, "sssssssssssssssssssssssssssi", $health_description, $prescription, $medication_names, $pill_amounts, $pill_doses, $otc_medicines, $herbal_medicine_list, $other_medicine_list, $allergic_reaction , $allergic_medicine, $allergic_reaction_description, $allergy_triggers, $other_allergic_triggers, $pregnant, $pregnancy_times, $children_birthed, $pap_smear, $last_pap_smear_date, $abnormal_pap, $mammogram, $last_mammogram_date, $mother_medical_problems, $father_medical_problems, $sisters_medical_problems, $brothers_medical_problems, $medical_conditions, $other_condition, $user_id);
    }

    // Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Close statement 
        mysqli_stmt_close($stmt);

        $_SESSION["isFirstUse"] = false;
        
        echo '<script>alert("Patient information recorded");</script>';
        
        // Redirect to same page
        header("refresh:0.1; url=../patient-form.php?page=patients&sub=patientinfo");
        exit();
    } else {
        // If the query failed, check if there are unidentified fields
        if (mysqli_errno($connect) == 1054) {
            echo '<script>alert("Some fields in the form are unidentified.");</script>';
        } else {
            echo "Error: " . mysqli_error($connect);
        }
    }
}
?>
