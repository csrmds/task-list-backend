<?php

namespace App\Services;

use App\Models\Task;


class TaskService {
    
    public function removeGoogleCalendarId(string $googleEventId): bool {
        logger("TaskService removeGoogleCalendar: ", [$googleEventId]);

        $task= Task::where('google_calendar_id', $googleEventId)->first();

        if ($task) {
            $task->google_calendar_id= null;
            $task->google_calendar_link= null;

            return $task->save();
        }

        return false;
    }

    public function insertGoogleCalendarId($googleData): bool {
        logger("TaskService insertGoogleCalendarId: ", [$googleData]);

        $task= Task::where('id', $googleData['id'])->first();

        if ($task && $task->google_calendar_id==null) {
            $task->google_calendar_id= $googleData['google_calendar_id'];
            $task->google_calendar_link= $googleData['google_calendar_link'];

            return $task->save();
        }

        return false;
    }
}





