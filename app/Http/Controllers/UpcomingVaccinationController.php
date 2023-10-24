<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Models\Infant;
use App\Models\VaccineDose;
use Carbon\Carbon;

class UpcomingVaccinationController extends Controller
{
    public function index()
    {
        Config::set('app.timezone', 'Asia/Manila');
        // Get the current date
        $currentDate = Carbon::now();

        // Calculate the date one week from today
        $upcomingDate = $currentDate->copy()->addWeek();

        // Get all infants
        $infants = Infant::all();
        
        // Get the vaccine doses
        $vaccineDoses = VaccineDose::all();

        $upcomingVaccinations = [];

        foreach ($infants as $infant) {
            foreach ($vaccineDoses as $dose) {
                $vaccineId = $dose->vaccine_id;
                $monthsToTake = $dose->months_to_take;
                $daysToTake = $monthsToTake * 30;
                // Calculate the vaccination date based on birthdate and months_to_take
                $birthDate = Carbon::parse($infant->birth_date);
                $vaccinationDate = $birthDate->addDays($daysToTake);

                if ($vaccinationDate->isBetween($currentDate, $upcomingDate)) {
                    // This infant has an upcoming vaccination within the next week
                    $upcomingVaccinations[] = [
                        'infant_name' => $infant->name,
                        'vaccine_id' => $vaccineId,
                        'dose_number' => $dose->dose_number,
                        'vaccination_date' => $vaccinationDate,
                    ];
                }
            }
        }

        Config::set('app.timezone', 'UTC');
        return response()->json(['data' => $upcomingVaccinations], 200);
    }
}
