<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class wf_notification extends Model
{
    use HasFactory;

    protected $connection = 'oracle';

    public static function getWorklistNotifications($employeeID)
    {
        $query = DB::connection('oracle')
                ->table('WF_NOTIFICATIONS wn')
                ->join('WF_ITEM_TYPES_TL witl', 'wn.MESSAGE_TYPE', '=', 'witl.name')
                ->join('FND_USER fu', 'wn.RECIPIENT_ROLE', '=', 'fu.USER_NAME')
                ->join('per_all_people_f paf2', 'fu.employee_id', '=', 'paf2.PERSON_ID')
                ->select('wn.notification_id','wn.FROM_USER','witl.display_name TYPE_TRX','wn.subject','wn.due_date','fu.USER_NAME','paf2.PERSON_ID APPROVER_PERSONID','PAF2.FULL_NAME APPROVER_FULLNAME','wn.begin_date','WN.STATUS','WN.MESSAGE_NAME')
                ->where('WN.STATUS', '<>', 'CLOSED')
                ->whereRaw('(SYSDATE BETWEEN paf2.effective_start_date AND paf2.effective_end_date)')
                ->where('paf2.PERSON_ID',$employeeID)
                ->orderByRaw('case when wn.due_date is null then wn.begin_date else wn.due_date end DESC');

        $data = $query->get();
        return $data;
    }
}
