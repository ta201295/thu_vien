<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Books;
use App\Models\BookStudent;
use App\Models\Issue;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;

class LogController extends Controller
{
    public function index()
	{

		$logs = Logs::select('id','book_issue_id','student_id','issued_at')
			->where('return_time', '=', 0)
			->orderBy('issued_at', 'DESC');

			// dd($logs);
		
		$logs = $logs->get();

		for($i=0; $i<count($logs); $i++){
	        
	        $issue_id = $logs[$i]['book_issue_id'];
	        $student_id = $logs[$i]['student_id'];
	        
	        // to get the name of the book from book issue id
	        $issue = Issue::find($issue_id);
	        $book_id = $issue->book_id;
	        $book = Books::find($book_id);
			$logs[$i]['book_name'] = $book->title;

			// to get the name of the student from student id
			$student = Student::find($student_id);
			$logs[$i]['student_name'] = $student->first_name . ' ' . $student->last_name;

			// change issue date and return date in human readable format
			$logs[$i]['issued_at'] = date('d-M', strtotime($logs[$i]['issued_at']));
			if ($issue->return_time == 0) {
				$logs[$i]['return_time'] =  '<p class="color:red">Pending</p>';
			}else {
				$logs[$i]['return_time'] = date('d-M', strtotime($logs[$i]['return_time']));
			}

		}

        return $logs;
	}

	public function create()
	{
		//
	}

	public function store(Request $request)
	{
		DB::beginTransaction();
        try {
			$bookStudentId = $request->book_student_id;
			$bookStudent = BookStudent::find($bookStudentId);
            $bookStudent->update([
				'status' => BookStudent::STATUS_BORROWED,
				'expired_time' => Carbon::now()->addDays(15)
			]);

			$data = [
				'book_student_id' => $bookStudent->id,
				'issue_by' => Auth::id()
			];
            Logs::create($data);
            DB::commit();

			return redirect()->route('issue-return')->with(['global' => 'Phát hành sách thành công']);
        } catch (Exception $e) {
            Log::error("LogController@store:bookStudentId-$bookStudentId " . $e->getMessage());
            DB::rollBack();

			return redirect()->route('issue-return')->with([
				'alert' => 'alert-error',
				'global' => 'Đã có lỗi xảy ra, vui lòng thử lại sau!'
			]);
        }
	}

	public function show($id)
	{
		//
	}

	public function edit($id)
	{
		$issueID = $id;

		$conditions = array(
			'book_issue_id'	=> $issueID,
			'return_time'	=> 0
		);

		$log = Logs::where($conditions);

		if(!$log->count()){
			return 'Invalid Book ID entered or book already returned';
		} else {
		
			$log = Logs::where($conditions)
				->firstOrFail();


			$log_id = $log['id'];
			$student_id = $log['student_id'];
			$issue_id = $log['book_issue_id'];


			DB::transaction( function() use($log_id, $student_id, $issue_id) {
				// change log status by changing return time
				$log_change = Logs::find($log_id);
				$log_change->return_time = date('Y-m-d H:i');
				$log_change->save();

				// decrease student book issue counter
				$student = Student::find($student_id);
				$student->books_issued = $student->books_issued - 1;
				$student->save();

				// change issue availability status
				$issue = Issue::find($issue_id);
				$issue->available_status = 1;
				$issue->save();
				
			});

			return 'Successfully returned';
			
		}
	}

	public function update($id)
	{
		//
	}

	public function destroy($id)
	{
		//
	}

    public function renderLogs() {
        return view('panel.logs');
    }

    public function renderIssueReturn()
	{
		$bookStudents = BookStudent::whereIn('status', [BookStudent::STATUS_APPROVED, BookStudent::STATUS_BORROWED])
			->with('book', 'student')->get();
		$approvedBooks = $bookStudents->where('status', BookStudent::STATUS_APPROVED);
		$borrowedBooks = $bookStudents->where('status', BookStudent::STATUS_BORROWED);
		
        return view('panel.issue-return', [
            'approvedBooks' => $approvedBooks,
			'borrowedBooks' => $borrowedBooks
        ]);
    }
}
