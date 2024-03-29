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
		$logs = Logs::with('bookStudent.book', 'bookStudent.student')
			->orderBy('created_at', 'DESC')->get();

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
		$bookStudents = BookStudent::whereIn('status', [BookStudent::STATUS_APPROVED, BookStudent::STATUS_BORROWED, BookStudent::STATUS_EXTEND])
			->with('book', 'student')->get();
		$approvedBooks = $bookStudents->where('status', BookStudent::STATUS_APPROVED);
		$borrowedBooks = $bookStudents->whereIn('status', [BookStudent::STATUS_BORROWED, BookStudent::STATUS_EXTEND]);
		
        return view('panel.issue-return', [
            'approvedBooks' => $approvedBooks,
			'borrowedBooks' => $borrowedBooks
        ]);
    }

	public function return(Request $request)
	{
		DB::beginTransaction();
        try {
			$bookStudentId = $request->book_student_id;
			$bookStudent = BookStudent::find($bookStudentId);
            $bookStudent->update(['status' => BookStudent::STATUS_COMPLETED]);

			$book = Books::find($bookStudent->book_id);
			$totalActive = $book->total_active + $bookStudent->number;
            $book->update(['total_active' => $totalActive]);

            Logs::where('book_student_id', $bookStudent->id)->update(['return_time' => now()]);
            DB::commit();

			return redirect()->route('issue-return')->with(['global' => 'Trả sách thành công']);
        } catch (Exception $e) {
            Log::error("LogController@store:bookStudentId-$bookStudentId " . $e->getMessage());
            DB::rollBack();

			return redirect()->route('issue-return')->with([
				'alert' => 'alert-error',
				'global' => 'Đã có lỗi xảy ra, vui lòng thử lại sau!'
			]);
        }
	}
}
