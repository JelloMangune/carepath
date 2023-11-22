<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vaccine;
use App\Models\Barangay;
use App\Models\Infant;
use App\Models\ImmunizationRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Get the dashboard data.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentYear = Carbon::now()->year;
        $user = Auth::user();
        $userType = $user->user_type;
        $barangayId = $user->barangay_id;
    
        $vaccineCount = Vaccine::count();
        $barangayCount = Barangay::count();
        $partiallyVaccinatedCount = 0;
        $fullyVaccinatedCount = 0;
    
        if ($userType === 1) {
            $recordsByMonthQuery = ImmunizationRecord::whereYear('immunization_date', $currentYear)
                ->where('barangay_id', $barangayId);
    
            $partiallyVaccinatedCount = Infant::where('status', 1)
                ->where('barangay_id', $barangayId)
                ->count();
    
            $fullyVaccinatedCount = Infant::where('status', 2)
                ->where('barangay_id', $barangayId)
                ->count();
        } else {
            $recordsByMonthQuery = ImmunizationRecord::whereYear('immunization_date', $currentYear);
    
            $partiallyVaccinatedCount = Infant::where('status', 1)->count();
            $fullyVaccinatedCount = Infant::where('status', 2)->count();
        }
    
        $recordsByMonth = $recordsByMonthQuery
            ->selectRaw('MONTH(immunization_date) as month, COUNT(*) as records')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    
        // ... (Remaining code to format and structure the data)
    
        $monthNames = [];
        $monthCounts = [];
    
        for ($i = 1; $i <= 12; $i++) {
            $monthCounts[$i] = 0;
            $monthNames[$i] = Carbon::create()->month($i)->format('F');
        }
    
        foreach ($recordsByMonth as $record) {
            $monthCounts[$record->month] = $record->records;
        }
    
        $formattedRecordsByMonth = [];
        foreach ($monthCounts as $month => $count) {
            $formattedRecordsByMonth[$monthNames[$month]] = $count;
        }
    
        $data = [
            'vaccine_count' => $vaccineCount,
            'barangay_count' => $barangayCount,
            'partially_vaccinated_count' => $partiallyVaccinatedCount,
            'fully_vaccinated_count' => $fullyVaccinatedCount,
            'records_by_month' => $formattedRecordsByMonth,
        ];
    
        return response()->json(['data' => $data]);
    }
}
