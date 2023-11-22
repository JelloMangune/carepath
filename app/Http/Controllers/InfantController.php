<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Infant;
use App\Models\Vaccine;
use App\Models\VaccineDose;
use App\Models\ImmunizationRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class InfantController extends Controller
{
    /**
     * Get all infants.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $userBarangayId = $user->barangay_id;

        $query = Infant::query();

        // Check if the authenticated user is not an admin (user_type not equal to 0)
        if ($user->user_type !== 0) {
            if (!is_null($userBarangayId)) {
                // If the user has a barangay_id, filter infants based on it
                $query->where('barangay_id', $userBarangayId);
            } else {
                // If the user has no barangay_id, return no infants (you can adjust this logic)
                return response()->json(['data' => []], 200);
            }
        } else {
            // If the user is an admin, return all infants
            $query->whereHas('barangay', function ($query) {
                $query->where('status', 1);
            });
        }

        $infants = $query->orderBy('barangay_id')->orderBy('birth_date', 'desc')->get();

        return response()->json(['data' => $infants], 200);
    }

    /**
     * Get a single infant by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get the infant by ID
        $infant = Infant::find($id);

        if (!$infant) {
            return response()->json(['error' => 'Infant not found'], 404);
        }

        return response()->json(['data' => $infant], 200);
    }

    /**
     * Store a new infant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Add other validation rules as needed
        ]);

        // Generate a unique tracking number
        $trackingNumber = $this->generateUniqueTrackingNumber();

        // Create the infant with the generated tracking number
        $infant = Infant::create(array_merge($request->all(), ['tracking_number' => $trackingNumber]));

        return response()->json(['message' => 'Infant created successfully', 'data' => $infant], 201);
    }

    /**
     * Update an infant by ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Get the infant by ID
        $infant = Infant::find($id);

        if (!$infant) {
            return response()->json(['data' => null, 'error' => 'Infant not found'], 404);
        }

        // Update the infant's attributes
        $infant->update($request->all());

        return response()->json(['data' => ['message' => 'Infant updated successfully', 'infant' => $infant]], 200);
    }

    /**
     * Delete an infant by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get the infant by ID
        $infant = Infant::find($id);

        if (!$infant) {
            return response()->json(['data' => ['error' => 'Infant not found'], 'message' => null], 404);
        }

        // Delete the infant
        $infant->delete();

        return response()->json(['data' => ['message' => 'Infant deleted successfully'], 'error' => null]);
    }

    /**
     * Generate a unique tracking number.
     *
     * @return string
     */
    private function generateUniqueTrackingNumber()
    {
        $trackingNumber = mt_rand(1000000000, 9999999999); // Generate a 10-digit number

        // Check if the generated tracking number already exists in the database
        while (Infant::where('tracking_number', $trackingNumber)->exists()) {
            $trackingNumber = mt_rand(1000000000, 9999999999);
        }

        return $trackingNumber;
    }

    /**
     * Get filtered infants based on barangay and birth year.
     *
     * @param  int  $barangay_id
     * @param  int|null  $year
     * @return \Illuminate\Http\Response
     */
    public function getFilteredInfants($barangay_id, $year = null)
    {
        // Create a query builder for infants
        $query = Infant::query();

        // If a specific barangay_id is provided, filter infants by barangay
        if ($barangay_id != 0) {
            $query->where('barangay_id', $barangay_id);
        }

        // If a year is provided, add a filter for the birth year
        if (!is_null($year)) {
            // Extract the year part from the birth_date column and compare it
            $query->whereYear('birth_date', '=', $year);
        }

        // Fetch infants based on the filters
        $infants = $query->get();

        return response()->json(['data' => $infants], 200);
    }

    /**
     * View the immunization history of an infant.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewImmunizationHistory($id)
    {
        // Get the infant by ID
        $infant = Infant::find($id);

        if (!$infant) {
            return response()->json(['error' => 'Infant not found'], 404);
        }

        // Get the immunization records for the infant
        $immunizationRecords = $infant->immunizationRecords;

        // Prepare the response data
        $responseData = [
            'Infant name' => $infant->name,
            'Immunization history' => [],
        ];

        // Iterate through immunization records and format the data
        foreach ($immunizationRecords as $record) {
            $administeredByName = $record->administered_by ? $record->administered_by : 'Unknown User';
        
            $formattedRecord = [
                'Id' => $record->id,
                'Immunization Date' => $record->immunization_date,
                'Vaccine Name' => $record->vaccine->name,
                'Vaccination Location' => $record->barangay_id,
                'Vaccine Dose' => 'Dose ' . $record->dose_number,
                'Administered By' => $administeredByName,
                'Remarks' => $record->remarks,
            ];
        
            $responseData['Immunization history'][] = $formattedRecord;
        }
        
        return response()->json(['data' => $responseData], 200);
    }

    /**
     * Get immunization records with details.
     *
     * @param  int|null  $year
     * @return \Illuminate\Http\JsonResponse
     */
    public function getImmunizationRecordsWithDetails($year = null): JsonResponse
    {
        try {
            $user = Auth::user();
            $userType = $user->user_type;
            $userBarangayId = $user->barangay_id;

            $infantsQuery = Infant::query()->with('barangay:id,name');

            if ($year) {
                $infantsQuery->whereYear('birth_date', $year);
            }

            if ($userType === 0) {
                $infants = $infantsQuery->get();
            } elseif ($userType === 1) {
                $infants = $infantsQuery->where('barangay_id', $userBarangayId)->get();
            } else {
                return response()->json(['error' => 'Unauthorized access'], 403);
            }

            $vaccines = Vaccine::all()->pluck('name', 'id')->toArray();
            $vaccineDoses = VaccineDose::all()
                ->groupBy('vaccine_id')
                ->map(function ($vaccineDoses) {
                    return $vaccineDoses->sortBy('dose_number');
                });

            $vaccineSorting = Vaccine::pluck('id')->toArray();

            $result = $infants->map(function ($infant) use ($vaccineDoses, $vaccines, $vaccineSorting) {
                $allVaccinationRecords = [];
                $infantId = $infant->id; // Assuming 'id' is the primary key of the Infant model
                foreach ($vaccineSorting as $vaccineId) {
                    if ($vaccineDoses->has($vaccineId)) {
                        $doses = $vaccineDoses[$vaccineId];
                        foreach ($doses as $dose) {
                            $immunizationDate = $this->getImmunizationDate($infantId, $vaccineId, $dose->dose_number);
                            $allVaccinationRecords[] = [
                                'vaccine_name' => $vaccines[$vaccineId],
                                'vaccine_dose' => $dose->dose_number,
                                'immunization_date' => $immunizationDate,
                            ];
                        }
                    }
                }

                return [
                    'infant' => $infant->toArray(),
                    'immunization_records' => $allVaccinationRecords,
                ];
            });

            return response()->json(['data' => $result], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error in server: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get immunization date for a specific infant, vaccine, and dose.
     *
     * @param  int  $infantId
     * @param  int  $vaccineId
     * @param  int  $doseNumber
     * @return string
     */
    private function getImmunizationDate($infantId, $vaccineId, $doseNumber)
    {
        $immunizationRecord = ImmunizationRecord::where('infant_id', $infantId)
            ->where('vaccine_id', $vaccineId)
            ->where('dose_number', $doseNumber)
            ->first();

        if ($immunizationRecord) {
            return $immunizationRecord->immunization_date ?? " ";
        }

        return " ";
    }

    /**
     * Get infant details with vaccination information.
     *
     * @param  string  $trackingNumber
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfantWithVaccinationDetails($trackingNumber)
    {
        // Get the infant by tracking number
        $infant = Infant::where('tracking_number', $trackingNumber)->first();

        if (!$infant) {
            return response()->json(['error' => 'Infant not found'], 404);
        }

        // Get the immunization records for the infant
        $immunizationRecords = $infant->immunizationRecords;

        // Fetch all vaccines to get their names
        $vaccines = Vaccine::all()->pluck('name', 'id')->toArray();

        // Prepare the response data
        $responseData = [
            'infant' => $infant->toArray(),
            'vaccine_taken' => [],
        ];

        // Iterate through vaccine doses and format the data
        foreach (VaccineDose::all() as $dose) {
            $vaccineId = $dose->vaccine_id;

            // Check if there is an immunization record for this vaccine dose
            $immunizationRecord = $immunizationRecords
                ->where('vaccine_id', $vaccineId)
                ->where('dose_number', $dose->dose_number)
                ->first();

            // Add the vaccine dose data to the response
            $formattedRecord = [
                'vaccine_name' => $vaccines[$vaccineId],
                'vaccine_dose' => $dose->dose_number,
                'immunization_date' => $immunizationRecord ? $immunizationRecord->immunization_date : " ",
                'administered_by' => $immunizationRecord ? $immunizationRecord->administered_by : null,
                'remarks' => $immunizationRecord ? $immunizationRecord->remarks : null,
            ];

            $responseData['vaccine_taken'][] = $formattedRecord;
        }

        return response()->json(['data' => $responseData], 200);
    }
    
    /**
     * Get infants from other barangays (non-admin users only).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOtherBarangayInfants()
    {
        $user = Auth::user();
        $userBarangayId = $user->barangay_id;

        $query = Infant::query()->with('barangay:id,name'); // Include barangay information

        // Check if the authenticated user is not an admin (user_type not equal to 0)
        if ($user->user_type !== 0) {
            if (!is_null($userBarangayId)) {
                // If the user has a barangay_id, filter infants not in the same barangay
                $query->where('barangay_id', '!=', $userBarangayId);
            } else {
                // If the user has no barangay_id, return no infants (you can adjust this logic)
                return response()->json(['data' => []], 200);
            }
        } else {
            // If the user is an admin, return all infants
            $query->whereHas('barangay', function ($query) {
                $query->where('status', 1);
            });
        }

        $infants = $query->orderBy('barangay_id')->orderBy('birth_date', 'desc')->get();

        return response()->json(['data' => $infants], 200);
    }



}
