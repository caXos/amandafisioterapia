<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Inertia\Inertia;
// use App\Models\Pacient;
// use App\Models\Activity;
// use App\Models\Apparatus;
// use App\Models\Appointment;
use App\Models\User;
// use App\Models\PacientPlan;
// use App\Models\Plan;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedules = Schedule::orderBy('date', 'asc')->where('active', true)->get();
        foreach ($schedules as $schedule) {
            foreach ($schedule->validAppointments as $appointment) {
                $pacient = Pacient::find($appointment->pacient_id);
                $activity = Activity::find($appointment->activity_id);
                $apparatus = Apparatus::find($appointment->apparatus_id);
                $professional = User::find($appointment->professional_id);
                $appointment->pacient_name = $pacient->name;
                $appointment->activity_name = $activity->name;
                $appointment->aparatus_name = $apparatus->name;
                $appointment->professional_name = $professional->name;
            }
        }
        return Inertia::render('Schedule/Schedule', ['schedules' => $schedules]);
    }

    /**
     * Display a listing of the inactive resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function history()
    {
        $schedules = Schedule::orderBy('date', 'desc')->where('active', false)->get();
        foreach ($schedules as $schedule) {
            foreach ($schedule->inactiveAppointments as $appointment) {
                $pacient = Pacient::find($appointment->pacient_id);
                $activity = Activity::find($appointment->activity_id);
                $apparatus = Apparatus::find($appointment->apparatus_id);
                $professional = User::find($appointment->professional_id);
                $appointment->pacient_name = $pacient->name;
                $appointment->activity_name = $activity->name;
                $appointment->aparatus_name = $apparatus->name;
                $appointment->professional_name = $professional->name;
            }
        }
        return Inertia::render('Schedule/History', ['schedules' => $schedules]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pacients = Pacient::all('id', 'name');
        $activities = Activity::all('id', 'name', 'usesAparatus');
        $apparati = Apparatus::all('id', 'name');
        $professionals = User::all('id', 'name');
        $otherSchedules = Schedule::where('active',true)->get();
        foreach ($otherSchedules as $schedule) {
            $schedule->validAppointments;
        }
        $pacient_plans = PacientPlan::all();
        foreach ($pacient_plans as $pacient_plan) {
            //HARDcoded
          if ($pacient_plan->id <= 25) $pacient_plan->activity = 1;
          else $pacient_plan->activity = 2;
        }
        return Inertia::render('Schedule/ScheduleForm', [
          'pacients' => $pacients,
          'activities' => $activities,
          'apparati' => $apparati,
          'professionals' => $professionals,
          'otherSchedules' => $otherSchedules,
          'pacient_plans' => $pacient_plans,
        ]);
    }

    /**
     * Checks if it is how many appointments can be created
     */
    public static function prepareToCreateSchedules(StorePacienteRequest $request)
    {
        $datesAndTimesParadigms = array('dates' => array(), 'times' => array());
        $datesArray = []; //Days of the week
        $timesArray = []; //Times of the day
        foreach ($request->dates as $dateInt) {
            array_push($datesArray, intval($dateInt));
        }
        foreach ($request->times as $timesString) {
            array_push($timesArray, $timesString);
        }
        $datesAndTimesParadigms['dates'][0] = date_create($request->inicio);
        $index = 0;
        for ($i = 0; $i < 7; $i++) {
            $index = array_search(intval($datesAndTimesParadigms['dates'][0]->format('N')), $datesArray);
            if ($index !== false) {
                $datesAndTimesParadigms['times'][0] = $timesArray[$index];
            } else {
                date_add($datesAndTimesParadigms['dates'][0], date_interval_create_from_date_string("1 day"));
            }
        }
        $datesArray[$index] = -1;
        $timesArray[$index] = -1;

        $index = 0;
        for ($i = 0; $i < sizeof($datesArray)-1; $i++) {
            $datesAndTimesParadigms['dates'][$i + 1] = date_create($datesAndTimesParadigms['dates'][$i]->format('Y-m-d'));
            for ($d = 0; $d < 7; $d++) {
                $index = array_search(intval($datesAndTimesParadigms['dates'][$i + 1]->format('N')), $datesArray);
                if ($index !== false) {
                    $datesAndTimesParadigms['times'][$i + 1] = $timesArray[$index];
                    $datesArray[$index] = -1;
                    $timesArray[$index] = -1;
                    break 1;
                } else {
                    date_add($datesAndTimesParadigms['dates'][$i + 1], date_interval_create_from_date_string("1 day"));
                }
            }
        }
        $appointmentsToBook = array();
        $usedSlots = array();
        for ($i = 0; $i < (floor( intval($request->appointmentsQtty)/sizeof($datesArray) ) + (intval($request->appointmentsQtty) % sizeof($datesArray) ) ); $i++) {
            $weeks = '';
            if ($i == 1) $weeks = "1 week";
            else $weeks = $i." weeks";
            for ($j =0; $j < sizeof($datesArray); $j++) {
                $appointmentCandidate = array(
                    'date'=>date_add(
                        date_create($datesAndTimesParadigms['dates'][$j]->format('Y-m-d')),
                        date_interval_create_from_date_string($weeks)
                    ),
                    'time'=>$datesAndTimesParadigms['times'][$j].":00",
                );
                $schedule = Schedule::where('date', $appointmentCandidate['date']->format('Y-m-d'))->where('time', $appointmentCandidate['time'])->where('active','true')->limit(1)->get();
                if (sizeof($schedule) > 0 ) {
                    if ($schedule[0]->used_slots < $schedule[0]->slots) {
                        array_push($appointmentsToBook, $appointmentCandidate);
                        // dump('Edit existing schedule');
                    }
                    else {
                        array_push($usedSlots, $appointmentCandidate);
                        // dump('Schedule full!');
                    }
                } else {
                    array_push($appointmentsToBook, $appointmentCandidate);
                    // dump('Criar novo Crompromisso');
                    // $schedule->date = $appointmentCandidate['date']->format('Y-m-d');
                    // $schedule->time = $appointmentCandidate['time'];
                    // $schedule->professional_id = intval($request->fisio);
                    // $schedule->pacient_id = $pacient_id;
                    // $request->plan <= 25 ? $schedule->activity_id = 1 : $schedule->activity_id = 2; //HARDcoded
                    // $aparelhos = Aparelho::all()->count();//HARDcoded
                    // $schedule->activity_id == 1 ? $schedule->apparatus_id = rand(2, $aparelhos) : $schedule->apparatus_id = 1;//HARDcoded
                    // ScheduleController::criarSchedule($schedule);
                }
            }
        }
        $result = array($appointmentsToBook, $usedSlots);
        return $result;
    }

    /**
     * Effectively creates possible schedules
     */
    public static function createSchedules(StorePacienteRequest $request)
    {
        $apparatusCounter = 2;
        $datesAndTimesParadigms = array('dates' => array(), 'times' => array());
        $datesArray = []; //Days of the week
        $timesArray = []; //Times of the day
        foreach ($request->dates as $dateInt) {
            array_push($datesArray, intval($dateInt));
        }
        foreach ($request->times as $timesString) {
            array_push($timesArray, $timesString);
        }
        $datesAndTimesParadigms['dates'][0] = date_create($request->inicio);
        $index = 0;
        for ($i = 0; $i < 7; $i++) {
            $index = array_search(intval($datesAndTimesParadigms['dates'][0]->format('N')), $datesArray);
            if ($index !== false) {
                $datesAndTimesParadigms['times'][0] = $timesArray[$index];
            } else {
                date_add($datesAndTimesParadigms['dates'][0], date_interval_create_from_date_string("1 day"));
            }
        }
        $datesArray[$index] = -1;
        $timesArray[$index] = -1;

        $index = 0;
        for ($i = 0; $i < sizeof($datesArray)-1; $i++) {
            $datesAndTimesParadigms['dates'][$i + 1] = date_create($datesAndTimesParadigms['dates'][$i]->format('Y-m-d'));
            for ($d = 0; $d < 7; $d++) {
                $index = array_search(intval($datesAndTimesParadigms['dates'][$i + 1]->format('N')), $datesArray);
                if ($index !== false) {
                    $datesAndTimesParadigms['times'][$i + 1] = $timesArray[$index];
                    $datesArray[$index] = -1;
                    $timesArray[$index] = -1;
                    break 1;
                } else {
                    date_add($datesAndTimesParadigms['dates'][$i + 1], date_interval_create_from_date_string("1 day"));
                }
            }
        }
        $appointmentsToBook = array();
        $usedSlots = array();
        for ($i = 0; $i < (floor( intval($request->appointmentsQtty)/sizeof($datesArray) ) + (intval($request->appointmentsQtty) % sizeof($datesArray) ) ); $i++) {
            $weeks = '';
            if ($i == 1) $weeks = "1 week";
            else $weeks = $i." weeks";
            for ($j =0; $j < sizeof($datesArray); $j++) {
                $appointmentCandidate = array(
                    'date'=>date_add(
                        date_create($datesAndTimesParadigms['dates'][$j]->format('Y-m-d')),
                        date_interval_create_from_date_string($weeks)
                    ),
                    'time'=>$datesAndTimesParadigms['times'][$j].":00",
                );
                $schedule = Schedule::where('date', $appointmentCandidate['date']->format('Y-m-d'))->where('time', $appointmentCandidate['time'])->where('active','true')->limit(1)->get();
                if (sizeof($schedule) > 0 ) {
                    if ($schedule[0]->used_slots < $schedule[0]->slots) {
                        ScheduleController::editSchedule($schedule[0], $request->professional, $pacient_id, $request->plan);
                    }
                } else {
                    $schedule->date = $candidatoAatendimento['date']->format('Y-m-d');
                    $schedule->time = $candidatoAatendimento['time'];
                    $schedule->professional_id = intval($request->fisio);
                    $schedule->pacient_id = $pacient_id;
                    $request->plan <= 25 ? $schedule->activity_id = 1 : $schedule->activity_id = 2; //HARDcoded
                    $aparelhos = Apparatus::all()->count();//HARDcoded
                    //$schedule->activity_id == 1 ? $schedule->apparatus_id = rand(2, $aparelhos) : $schedule->apparatus_id = 1;//HARDcoded
                    if ($schedule->activity_id == 1) {
                        $schedule->apparatus_id = $apparatusCounter;
                        ($apparatusCounter + 1) <= $aparelhos ? $apparatusCounter++ : $apparatusCounter = 2;
                    } else {
                        $schedule->apparatus_id = 1;
                    }
                    ScheduleController::createSchedule($schedule);
                }
            }
        }
    }

    /**
     * Creates one single schedule
     */
    public static function createSchedule($scheduleToBook)
    {
        $schedule = new Schedule([
            'user_id' => $scheduleToBook->professional_id,
            'date' => $scheduleToBook->date,
            'time' => $scheduleToBook->time,
            'slots' => 3,
            'used_slots' => 1,
            'active' => true,
        ]);//HARDcoded slots
        $schedule->save();
        $newAppointment = new Appointment([
            'schedule_id' => $schedule->id,
            'pacient_id' => $scheduleToBook->pacient_id,
            'activity_id' => $scheduleToBook->activity_id,
            'apparatus_id' => $scheduleToBook->apparatus_id,
            'professional_id' => $scheduleToBook->professional_id,
            'done' => false,
            'active' => true
        ]);
        $newAppointment->save();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreScheduleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreScheduleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateScheduleRequest  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        //
    }
}
