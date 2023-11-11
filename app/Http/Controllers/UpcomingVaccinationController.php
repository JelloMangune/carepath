<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Models\Infant;
use App\Models\VaccineDose;
use App\Models\Barangay;
use App\Models\ImmunizationRecord;

use Carbon\Carbon;

class UpcomingVaccinationController extends Controller
{
    public function index()
    {
        Config::set('app.timezone', 'Asia/Manila');

        $currentDate = Carbon::now();

        $upcomingDate = $currentDate->copy()->addWeek();

        $infants = Infant::all();
        
        $vaccineDoses = VaccineDose::all();

        $upcomingVaccinations = [];

        foreach ($infants as $infant) {
            $barangay = Barangay::find($infant->barangay_id);
            foreach ($vaccineDoses as $dose) {
                $vaccineId = $dose->vaccine_id;
                $doseNumber = $dose->dose_number;
                $monthsToTake = $dose->months_to_take;
                $daysToTake = $monthsToTake * 30;
                // Calculate the vaccination date based on birthdate and months_to_take
                $birthDate = Carbon::parse($infant->birth_date);
                $vaccinationDate = $birthDate->copy()->addDays($daysToTake);

                // Check if this vaccination record already exists in ImmunizationRecord
                $existingRecord = ImmunizationRecord::where([
                    'infant_id' => $infant->id,
                    'vaccine_id' => $vaccineId,
                    'dose_number' => $doseNumber,
                ])->first();

                if (($vaccinationDate->isToday() || $vaccinationDate->isBetween($currentDate, $upcomingDate)) && !$existingRecord) {
                    $formattedBirthDate = Carbon::parse($infant->birth_date)->format('F d, Y');
                    $formattedVaccinationDate = $vaccinationDate->format('F d, Y');
                    $upcomingVaccinations[] = [
                        'infant_name' => $infant->name,
                        'infant_tracking_number' => $infant->tracking_number,
                        'birth_date' => $formattedBirthDate,
                        'barangay_name' => $barangay->name, // Include barangay name
                        'vaccine_name' => $dose->vaccine->name, // Include vaccine name
                        'dose_number' => $doseNumber,
                        'vaccination_date' => $formattedVaccinationDate,
                    ];
                }
            }
        }

        // Restore the original timezone (if needed) to avoid affecting other parts of your application
        Config::set('app.timezone', config('app.timezone', 'UTC'));

        return response()->json(['data' => $upcomingVaccinations], 200);
    }

    public function missedVaccinationsLastWeek()
    {
        Config::set('app.timezone', 'Asia/Manila');

        $currentDate = Carbon::now();

        // Calculate the date one week ago
        $lastWeekDate = $currentDate->copy()->subWeek();

        $infants = Infant::all(); // Fetch infants

        $vaccineDoses = VaccineDose::all();

        $missedVaccinations = [];

        foreach ($infants as $infant) {
            $barangay = Barangay::find($infant->barangay_id); // Fetch barangay information

            foreach ($vaccineDoses as $dose) {
                $vaccineId = $dose->vaccine_id;
                $doseNumber = $dose->dose_number;
                $monthsToTake = $dose->months_to_take;
                $daysToTake = $monthsToTake * 30;
                // Calculate the vaccination date based on birthdate and months_to_take
                $birthDate = Carbon::parse($infant->birth_date);
                $vaccinationDate = $birthDate->copy()->addDays($daysToTake);

                // Check if this vaccination record does not exist in ImmunizationRecord
                $existingRecord = ImmunizationRecord::where([
                    'infant_id' => $infant->id,
                    'vaccine_id' => $vaccineId,
                    'dose_number' => $doseNumber,
                ])->first();
                
                if (($vaccinationDate->isSameDay($lastWeekDate) || $vaccinationDate->isBetween($currentDate->yesterday(), $lastWeekDate)) && !$existingRecord) {
                    $formattedBirthDate = Carbon::parse($infant->birth_date)->format('F d, Y');
                    $missedVaccinations[] = [
                        'infant_name' => $infant->name,
                        'infant_tracking_number' => $infant->tracking_number,
                        'birth_date' => $formattedBirthDate,
                        'barangay_name' => $barangay->name, // Include barangay name
                        'vaccine_name' => $dose->vaccine->name, // Include vaccine name
                        'dose_number' => $doseNumber,
                        'vaccination_date' => $vaccinationDate->format('F d, Y'),
                    ];
                }
            }
        }

        // Restore the original timezone (if needed) to avoid affecting other parts of your application
        Config::set('app.timezone', config('app.timezone', 'UTC'));

        return response()->json(['data' => $missedVaccinations], 200);
    }

    public function filteredIndex()
    {
        Config::set('app.timezone', 'Asia/Manila');
    
        $currentDate = Carbon::now();
    
        $upcomingDate = $currentDate->copy()->addWeek();
    
        // Filter infants based on the authenticated user's barangay_id
        $infants = Infant::where('barangay_id', Auth::user()->barangay_id)->get();
        
        $vaccineDoses = VaccineDose::all();
    
        $upcomingVaccinations = [];
    
        foreach ($infants as $infant) {
            $barangay = Barangay::find($infant->barangay_id);
            foreach ($vaccineDoses as $dose) {
                $vaccineId = $dose->vaccine_id;
                $doseNumber = $dose->dose_number;
                $monthsToTake = $dose->months_to_take;
                $daysToTake = $monthsToTake * 30;
                // Calculate the vaccination date based on birthdate and months_to_take
                $birthDate = Carbon::parse($infant->birth_date);
                $vaccinationDate = $birthDate->copy()->addDays($daysToTake);
    
                // Check if this vaccination record already exists in ImmunizationRecord
                $existingRecord = ImmunizationRecord::where([
                    'infant_id' => $infant->id,
                    'vaccine_id' => $vaccineId,
                    'dose_number' => $doseNumber,
                ])->first();
    
                if (($vaccinationDate->isToday() || $vaccinationDate->isBetween($currentDate, $upcomingDate)) && !$existingRecord) {
                    $formattedBirthDate = Carbon::parse($infant->birth_date)->format('F d, Y');
                    $formattedVaccinationDate = $vaccinationDate->format('F d, Y');
                    $upcomingVaccinations[] = [
                        'infant_name' => $infant->name,
                        'birth_date' => $formattedBirthDate,
                        'barangay_name' => $barangay->name, // Include barangay name
                        'vaccine_name' => $dose->vaccine->name, // Include vaccine name
                        'dose_number' => $doseNumber,
                        'vaccination_date' => $formattedVaccinationDate,
                    ];
                }
            }
        }
    
        // Restore the original timezone (if needed) to avoid affecting other parts of your application
        Config::set('app.timezone', config('app.timezone', 'UTC'));
    
        return response()->json(['data' => $upcomingVaccinations, 'current_date' => $currentDate], 200);
    }
    
    public function filteredMissedVaccinations()
    {
        Config::set('app.timezone', 'Asia/Manila');
    
        $currentDate = Carbon::now();
        
        // Calculate the date one week ago
        $lastWeekDate = $currentDate->copy()->subWeek();
    
        // Filter infants based on the authenticated user's barangay_id
        $infants = Infant::where('barangay_id', Auth::user()->barangay_id)->get();
    
        $vaccineDoses = VaccineDose::all();
    
        $missedVaccinations = [];
    
        foreach ($infants as $infant) {
            $barangay = Barangay::find($infant->barangay_id); // Fetch barangay information
    
            foreach ($vaccineDoses as $dose) {
                $vaccineId = $dose->vaccine_id;
                $doseNumber = $dose->dose_number;
                $monthsToTake = $dose->months_to_take;
                $daysToTake = $monthsToTake * 30;
                // Calculate the vaccination date based on birthdate and months_to_take
                $birthDate = Carbon::parse($infant->birth_date);
                $vaccinationDate = $birthDate->copy()->addDays($daysToTake);
    
                // Check if this vaccination record does not exist in ImmunizationRecord
                $existingRecord = ImmunizationRecord::where([
                    'infant_id' => $infant->id,
                    'vaccine_id' => $vaccineId,
                    'dose_number' => $doseNumber,
                ])->first();
                
                if (($vaccinationDate->isSameDay($lastWeekDate) || $vaccinationDate->isBetween($currentDate->yesterday(), $lastWeekDate)) && !$existingRecord) {
                    $formattedBirthDate = Carbon::parse($infant->birth_date)->format('F d, Y');
                    $missedVaccinations[] = [
                        'infant_name' => $infant->name,
                        'birth_date' => $formattedBirthDate,
                        'barangay_name' => $barangay->name, // Include barangay name
                        'vaccine_name' => $dose->vaccine->name, // Include vaccine name
                        'dose_number' => $doseNumber,
                        'vaccination_date' => $vaccinationDate->format('F d, Y'),
                    ];
                }
            }
        }
    
        // Restore the original timezone (if needed) to avoid affecting other parts of your application
        Config::set('app.timezone', config('app.timezone', 'UTC'));
    
        return response()->json(['data' => $missedVaccinations], 200);
    }
    
}
