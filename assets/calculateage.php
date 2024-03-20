<?php
// calculate the age by finding the difference between the two dates
function calculateAge($dob)
{
    // Create DateTime objects for the date of birth and the current date
    $dob = new DateTime($dob);
    $currentDate = new DateTime();

    // Calculate the interval between the two dates
    $interval = $dob->diff($currentDate);

    // Return the difference in years
    return $interval->y;
}
