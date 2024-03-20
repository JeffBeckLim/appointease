<!-- include the header first -->
<?php require_once('assets/header.php') ?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <!-- <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index-2.php?page=home">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Patient Information</li>
                    </ol> -->
                </nav>
                <h2 class="breadcrumb-title">Patient Information</h2>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<!-- get the SQL data if this is not 1st time use -->
<?php

$userId = $_SESSION['id'];

$sql = "SELECT * FROM patient_form WHERE user_id = $userId";
$result = mysqli_query($connect, $sql);

// Check if data exists for the user
if (mysqli_num_rows($result) > 0) {
    $userData = mysqli_fetch_assoc($result);
} else {
    // No data found, initialize empty array
    $userData = array();
}


?>
<div class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2>Patient Information Record</h2>
                    </div>
                    <div class="card-body">
                        <form action="assets/patient-process-form.php" method="post">

                            <div class="form-group">
                                <label>1. Are you taking any prescription medicines?</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="prescription_yes" name="prescription" value="Yes" <?php echo (isset($userData['prescription']) && $userData['prescription'] == 'Yes') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="prescription_yes">Yes. Please list your medicines below</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="prescription_yes_with_list" name="prescription" value="Yes_with_list" <?php echo (isset($userData['prescription']) && $userData['prescription'] == 'Yes_with_list') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="prescription_yes_with_list">Yes. I have brought my own list</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="prescription_no" name="prescription" value="No" <?php echo (isset($userData['prescription']) && $userData['prescription'] == 'No') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="prescription_no">No, I do not take any prescription medicines. (If no, go to question #5.)</label>
                                </div>
                            </div>

                            <div class="form-group" id="medications">
                                <label for="medicationsTable">List of Medications</label>
                                <table class="table" id="medicationsTable">
                                    <thead>
                                        <tr>
                                            <th>Name of Medicine</th>
                                            <th>Amount/Size of Pill</th>
                                            <th>How Many Pills or Doses</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $medication_names = isset($userData['medication_name']) ? explode(', ', $userData['medication_name']) : [''];
                                        $pill_amounts = isset($userData['pill_amount']) ? explode(', ', $userData['pill_amount']) : [''];
                                        $pill_doses = isset($userData['pill_doses']) ? explode(', ', $userData['pill_doses']) : [''];
                                        $rowCount = max(count($medication_names), count($pill_amounts), count($pill_doses));

                                        for ($i = 0; $i < $rowCount; $i++) {
                                            $medication_name_item = isset($medication_names[$i]) ? $medication_names[$i] : '';
                                            $pill_amount_item = isset($pill_amounts[$i]) ? $pill_amounts[$i] : '';
                                            $pill_dose_item = isset($pill_doses[$i]) ? $pill_doses[$i] : '';
                                        ?>
                                            <tr>
                                                <td><input type="text" class="form-control" name="medication_name[]" value="<?php echo htmlspecialchars($medication_name_item); ?>"></td>
                                                <td><input type="text" class="form-control" name="pill_amount[]" value="<?php echo htmlspecialchars($pill_amount_item); ?>"></td>
                                                <td><input type="text" class="form-control" name="pill_doses[]" value="<?php echo htmlspecialchars($pill_dose_item); ?>"></td>
                                                <td><button type="button" class="btn btn-danger" onclick="removeMedicationRow(this)">-</button></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-primary" onclick="addMedicationRow()">+</button>
                            </div>

                            <div class="form-group">
                                <label for="otc_medicines">2. What over-the-counter medicines do you take regularly?</label><br>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="pain_reliever" name="otc_medicines[]" value="Pain Reliever" <?php if (isset($userData['otc_medicines']) && in_array('Pain Reliever', explode(', ', $userData['otc_medicines']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="pain_reliever">Pain reliever (for example: Tylenol, Advil, Motrin, Aleve, aspirin)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="vitamins" name="otc_medicines[]" value="Vitamins" <?php if (isset($userData['otc_medicines']) && in_array('Vitamins', explode(', ', $userData['otc_medicines']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="vitamins">Vitamins</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="antacid" name="otc_medicines[]" value="Antacid" <?php if (isset($userData['otc_medicines']) && in_array('Antacid', explode(', ', $userData['otc_medicines']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="antacid">Antacid (for example: Tums, Prilosec)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="herbal_medicine" name="otc_medicines[]" value="Herbal Medicine" <?php if (isset($userData['otc_medicines']) && in_array('Herbal Medicine', explode(', ', $userData['otc_medicines']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="herbal_medicine">Herbal medicine (please list using format below)</label>
                                    <input type="text" class="form-control" name="herbal_medicine_list" placeholder="ex. Flu Medicine, Antihistamine" value="<?php echo isset($userData['herbal_medicine_list']) ? $userData['herbal_medicine_list'] : ''; ?>">
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="other_medicine" name="otc_medicines[]" value="Other" <?php if (isset($userData['otc_medicines']) && in_array('Other', explode(', ', $userData['otc_medicines']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="other_medicine">Other (please list using format below)</label>
                                    <input type="text" class="form-control" name="other_medicine_list" placeholder="ex. Flu Medicine, Antihistamine" value="<?php echo isset($userData['other_medicine_list']) ? $userData['other_medicine_list'] : ''; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>3. Have you ever had any allergic reaction (bad effects) to a medicine or a shot?</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="allergic_yes" name="allergic_reaction" value="Yes" <?php echo (isset($userData['allergic_reaction']) && $userData['allergic_reaction'] == 'Yes') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="allergic_yes">Yes. (Please write the name of the medicine and the effect you had.)</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="allergic_no" name="allergic_reaction" value="No" <?php echo (isset($userData['allergic_reaction']) && $userData['allergic_reaction'] == 'No') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="allergic_no">No, I am not allergic to any medicines.</label>
                                </div>
                            </div>

                            <div class="form-group" id="allergicReactionDetails">
                                <label for="allergicReactionTable">Allergic Reaction Details</label>
                                <table class="table" id="allergicReactionTable">
                                    <thead>
                                        <tr>
                                            <th>Medicine Name</th>
                                            <th>Allergic Reaction</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Explode comma-separated strings into arrays if the keys are set, otherwise use empty arrays
                                        $medicine_names = isset($userData['allergic_medicine']) ? explode(', ', $userData['allergic_medicine']) : [];
                                        $reaction_descriptions = isset($userData['allergic_reaction_description']) ? explode(', ', $userData['allergic_reaction_description']) : [];

                                        // Check if the arrays are empty, and if so, add one row with blank input fields
                                        if (empty($medicine_names) && empty($reaction_descriptions)) {
                                            $medicine_names = [''];
                                            $reaction_descriptions = [''];
                                        }

                                        $rowCount = max(count($medicine_names), count($reaction_descriptions));

                                        for ($i = 0; $i < $rowCount; $i++) {
                                            // Get values from arrays or use empty strings if not set
                                            $medicine_name = isset($medicine_names[$i]) ? $medicine_names[$i] : '';
                                            $reaction_description = isset($reaction_descriptions[$i]) ? $reaction_descriptions[$i] : '';
                                        ?>
                                            <tr>
                                                <td><input type="text" class="form-control" name="allergic_medicine[]" value="<?php echo $medicine_name; ?>"></td>
                                                <td><input type="text" class="form-control" name="allergic_reaction_description[]" value="<?php echo $reaction_description; ?>"></td>
                                                <td><button type="button" class="btn btn-danger" onclick="removeAllergicReactionRow(this)">-</button></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-primary" onclick="addAllergicReactionRow()">+</button>
                            </div>

                            <div class="form-group">
                                <label>4. Do you get an allergic reaction (bad effect) from any of the following? (Check all that apply)</label><br>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="latex" name="allergy_triggers[]" value="latex (rubber gloves)" <?php echo isset($userData['allergy_triggers']) && in_array('latex (rubber gloves)', explode(', ', $userData['allergy_triggers'])) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="latex">latex (rubber gloves)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="eggs" name="allergy_triggers[]" value="eggs" <?php echo isset($userData['allergy_triggers']) && in_array('eggs', explode(', ', $userData['allergy_triggers'])) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="eggs">eggs</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="shellfish" name="allergy_triggers[]" value="shellfish" <?php echo isset($userData['allergy_triggers']) && in_array('shellfish', explode(', ', $userData['allergy_triggers'])) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="shellfish">shellfish</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="other_allergy" name="allergy_triggers[]" value="Other" <?php echo isset($userData['allergy_triggers']) && in_array('Other', explode(', ', $userData['allergy_triggers'])) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="other_allergy">Other (please describe using format below)</label>
                                    <input type="text" class="form-control" name="other_allergy_triggers" placeholder="ex. Fur, Alcohol" value="<?php echo isset($userData['other_allergy_triggers']) ? $userData['other_allergy_triggers'] : ''; ?>">
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="no_allergies" name="allergy_triggers[]" value="No Allergies" <?php echo isset($userData['allergy_triggers']) && in_array('No Allergies', explode(', ', $userData['allergy_triggers'])) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="no_allergies">No—I have no allergies that I know of.</label>
                                </div>
                            </div>

                            <hr>
                            <div class="form-group">
                                <label>
                                    <h3>For Women Only</h3>
                                </label>
                            </div>

                            <div class="form-group">
                                <label>5. Have you ever been pregnant?</label><br>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="pregnant_yes" name="pregnant" value="Yes" <?php echo isset($userData['pregnant']) && $userData['pregnant'] === 'Yes' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="pregnant_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="pregnant_no" name="pregnant" value="No" <?php echo isset($userData['pregnant']) && $userData['pregnant'] === 'No' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="pregnant_no">No</label>
                                </div>
                                <div>
                                    <label>How many times?</label>
                                    <input type="number" class="form-control" name="pregnancy_times" value="<?php echo isset($userData['pregnancy_times']) ? $userData['pregnancy_times'] : ''; ?>">
                                </div>
                                <div>
                                    <label>How many children have you given birth to?</label>
                                    <input type="number" class="form-control" name="children_birthed" value="<?php echo isset($userData['children_birthed']) ? $userData['children_birthed'] : ''; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>6. Have you had a PAP smear?</label><br>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="pap_smear_yes" name="pap_smear" value="Yes" <?php echo isset($userData['pap_smear']) && $userData['pap_smear'] === 'Yes' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="pap_smear_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="pap_smear_no" name="pap_smear" value="No" <?php echo isset($userData['pap_smear']) && $userData['pap_smear'] === 'No' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="pap_smear_no">No</label>
                                </div>
                                <div>
                                    <label>Date of last one</label>
                                    <input type="date" class="form-control" name="last_pap_smear_date" value="<?php echo isset($userData['last_pap_smear_date']) ? $userData['last_pap_smear_date'] : ''; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>7. Have you ever had a PAP smear that was not normal?</label><br>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="abnormal_pap_yes" name="abnormal_pap" value="Yes" <?php echo isset($userData['abnormal_pap']) && $userData['abnormal_pap'] === 'Yes' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="abnormal_pap_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="abnormal_pap_no" name="abnormal_pap" value="No" <?php echo isset($userData['abnormal_pap']) && $userData['abnormal_pap'] === 'No' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="abnormal_pap_no">No</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>8. Have you had a mammogram (breast x-ray)?</label><br>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="mammogram_yes" name="mammogram" value="Yes" <?php echo isset($userData['mammogram']) && $userData['mammogram'] === 'Yes' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="mammogram_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="mammogram_no" name="mammogram" value="No" <?php echo isset($userData['mammogram']) && $userData['mammogram'] === 'No' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="mammogram_no">No</label>
                                </div>
                                <div>
                                    <label>Date of last one</label>
                                    <input type="date" class="form-control" name="last_mammogram_date" value="<?php echo isset($userData['last_mammogram_date']) ? $userData['last_mammogram_date'] : ''; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <hr>
                                <label>
                                    <h3>Family History</h3>
                                </label><br>
                                <label>What medical problems do people in your family have?</label><br>

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Family Member</th>
                                            <th>Medical Problems (use format below)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Mother:</td>
                                            <td><input type="text" class="form-control" name="mother_medical_problems" placeholder="ex. Cancer, Diabetes, High blood" value="<?php echo isset($userData['mother_medical_problems']) ? $userData['mother_medical_problems'] : ''; ?>"></td>
                                        </tr>
                                        <tr>
                                            <td>Father:</td>
                                            <td><input type="text" class="form-control" name="father_medical_problems" placeholder="ex. Cancer, Diabetes, High blood" value="<?php echo isset($userData['father_medical_problems']) ? $userData['father_medical_problems'] : ''; ?>"></td>
                                        </tr>
                                        <tr>
                                            <td>Sisters:</td>
                                            <td><input type="text" class="form-control" name="sisters_medical_problems" placeholder="ex. Cancer, Diabetes, High blood" value="<?php echo isset($userData['sisters_medical_problems']) ? $userData['sisters_medical_problems'] : ''; ?>"></td>
                                        </tr>
                                        <tr>
                                            <td>Brothers:</td>
                                            <td><input type="text" class="form-control" name="brothers_medical_problems" placeholder="ex. Cancer, Diabetes, High blood" value="<?php echo isset($userData['brothers_medical_problems']) ? $userData['brothers_medical_problems'] : ''; ?>"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group">
                                <hr>
                                <label>
                                    <h3>History of Medical Conditions </h3>
                                </label><br>
                                <label>Have you ever had any of the following conditions? (Check all that apply)</label><br>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="anemia" name="medical_conditions[]" value="Anemia" <?php if (isset($userData['medical_conditions']) && in_array('Anemia', explode(', ', $userData['medical_conditions']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="anemia">Anemia (low iron blood)</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="asthma" name="medical_conditions[]" value="Asthma" <?php if (isset($userData['medical_conditions']) && in_array('Asthma', explode(', ', $userData['medical_conditions']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="asthma">Asthma (wheezing)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="diabetes" name="medical_conditions[]" value="Diabetes" <?php if (isset($userData['medical_conditions']) && in_array('Diabetes', explode(', ', $userData['medical_conditions']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="diabetes">Diabetes (sugar)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="heart-trouble" name="medical_conditions[]" value="Heart Trouble" <?php if (isset($userData['medical_conditions']) && in_array('Heart Trouble', explode(', ', $userData['medical_conditions']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="heart-troubl">Heart Trouble </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="hemorrhoids" name="medical_conditions[]" value="Hemorrhoids" <?php if (isset($userData['medical_conditions']) && in_array('Hemorrhoids', explode(', ', $userData['medical_conditions']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="hemorrhoids">Hemorrhoids (piles)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="cancer" name="medical_conditions[]" value="Cancer" <?php if (isset($userData['medical_conditions']) && in_array('Cancer', explode(', ', $userData['medical_conditions']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="cancer">Cancer</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="hepatitis" name="medical_conditions[]" value="Hepatitis" <?php if (isset($userData['medical_conditions']) && in_array('Hepatitis', explode(', ', $userData['medical_conditions']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="hepatitis">Hepatitis (yellow jaundice)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="tuberculosis" name="medical_conditions[]" value="Tuberculosis" <?php if (isset($userData['medical_conditions']) && in_array('Tuberculosis', explode(', ', $userData['medical_conditions']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="tuberculosis">Tuberculosis (TB)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="liver-trouble" name="medical_conditions[]" value="Liver Trouble" <?php if (isset($userData['medical_conditions']) && in_array('Liver Trouble', explode(', ', $userData['medical_conditions']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="liver-trouble">Liver Trouble </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="pneumonia" name="medical_conditions[]" value="Pneumonia" <?php if (isset($userData['medical_conditions']) && in_array('Pneumonia', explode(', ', $userData['medical_conditions']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="pneumonia">Pneumonia</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="rheumatic-fever" name="medical_conditions[]" value="Rheumatic Fever" <?php if (isset($userData['medical_conditions']) && in_array('Rheumatic Fever', explode(', ', $userData['medical_conditions']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="rheumatic-fever">Rheumatic Fever </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="ulcer" name="medical_conditions[]" value="Ulcers" <?php if (isset($userData['medical_conditions']) && in_array('Ulcer', explode(', ', $userData['medical_conditions']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="ulcer">Ulcers</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="stroke" name="medical_conditions[]" value="Stroke" <?php if (isset($userData['medical_conditions']) && in_array('Stroke', explode(', ', $userData['medical_conditions']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="stroke">Stroke</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="high-blood-pressure" name="medical_conditions[]" value="High Blood Pressure" <?php if (isset($userData['medical_conditions']) && in_array('High Blood Pressure', explode(', ', $userData['medical_conditions']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="high-blood-pressure">High Blood Pressure</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="skin-problems" name="medical_conditions[]" value="Skin problems" <?php if (isset($userData['medical_conditions']) && in_array('Skin problems', explode(', ', $userData['medical_conditions']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="skin-problems">Skin problems</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="depression" name="medical_conditions[]" value="Depression" <?php if (isset($userData['medical_conditions']) && in_array('Depression', explode(', ', $userData['medical_conditions']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="depression">Depression (feeling down or blue)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="epilepsy" name="medical_conditions[]" value="Epilepsy" <?php if (isset($userData['medical_conditions']) && in_array('Epilepsy', explode(', ', $userData['medical_conditions']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="epilepsy">Epilepsy (fits, seizures)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="anxiety" name="medical_conditions[]" value="Anxiety" <?php if (isset($userData['medical_conditions']) && in_array('Anxiety', explode(', ', $userData['medical_conditions']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="anxiety">Anxiety (nerves, panic attacks) </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="std" name="medical_conditions[]" value="STD" <?php if (isset($userData['medical_conditions']) && in_array('STD', explode(', ', $userData['medical_conditions']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="std">STD (syphilis, gonorrhea, chlamydia, HIV)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="other" name="medical_conditions[]" value="Other" <?php if (isset($userData['medical_conditions']) && in_array('Other', explode(', ', $userData['medical_conditions']))) echo 'checked'; ?>>
                                    <label class="form-check-label" for="other">Other</label>
                                    <input type="text" class="form-control" id="other_condition" name="other_condition" placeholder="Please specify" value="<?php echo isset($userData['other_condition']) ? $userData['other_condition'] : ''; ?>">
                                </div>
                            </div>

                            <input type="hidden" name="session_id" value="<?php echo $_SESSION['id']; ?>">

                            <div class="submit-section submit-btn-bottom">
                                <button type="submit" class="btn btn-primary" name="save-information">Save Information</button>
                            </div>

                        </form>

                        <script>
                            // add another row for medication table
                            function addMedicationRow() {
                                var table = document.getElementById("medicationsTable");
                                var newRow = table.insertRow(-1); // Insert at the end of the table
                                var cell1 = newRow.insertCell(0);
                                var cell2 = newRow.insertCell(1);
                                var cell3 = newRow.insertCell(2);
                                cell1.innerHTML = '<input type="text" class="form-control" name="medication_name[]">';
                                cell2.innerHTML = '<input type="text" class="form-control" name="pill_amount[]">';
                                cell3.innerHTML = '<input type="text" class="form-control" name="pill_doses[]">';
                                var removeBtnCell = newRow.insertCell(3);
                                removeBtnCell.innerHTML = '<button type="button" class="btn btn-danger" onclick="removeMedicationRow(this)">-</button>';
                            }

                            //remove row from medication table
                            function removeMedicationRow(button) {
                                var row = button.parentNode.parentNode;
                                row.parentNode.removeChild(row);
                            }

                            // add another row for allergic reaction table
                            function addAllergicReactionRow() {
                                var table = document.getElementById("allergicReactionTable");
                                var newRow = table.insertRow(-1);
                                var cell1 = newRow.insertCell(0);
                                var cell2 = newRow.insertCell(1);
                                cell1.innerHTML = '<input type="text" class="form-control" name="allergic_medicine[]">';
                                cell2.innerHTML = '<input type="text" class="form-control" name="allergic_reaction_description[]">';
                                var removeBtnCell = newRow.insertCell(2);
                                removeBtnCell.innerHTML = '<button type="button" class="btn btn-danger" onclick="removeAllergicReactionRow(this)">-</button>';
                            }

                            //remove row from allergic reaction table
                            function removeAllergicReactionRow(button) {
                                var row = button.parentNode.parentNode;
                                row.parentNode.removeChild(row);
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Page Content -->

<!-- include footer -->
<?php require_once('assets/footer.php') ?>