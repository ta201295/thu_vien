<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Logs;
use App\Models\Books;
use App\Models\Issue;
use App\Models\Branch;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\StudentCategories;
use App\Http\Controllers\HomeController;
use App\Http\Requests\Student\LoginRequest;
use App\Http\Requests\Student\StoreRequest;
use App\Models\BookCategories;
use App\Models\BookStudent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class StudentController extends Controller
{

	public function index()
	{
		$conditions = array(
			'approved'	=> 0,
			'rejected'	=> 0
		);

		$students = Student::join('branches', 'branches.id', '=', 'students.branch')
		->join('student_categories', 'student_categories.cat_id', '=', 'students.category')
		->select('student_id', 'first_name', 'last_name', 'student_categories.category', 'roll_num', 'branches.branch', 'year')
			->where($conditions)
			->orderBy('student_id');

		// $this->filterQuery($students);
		$students = $students->get();
		// dd($students);
        return $students;
	}

	public function StudentByAttribute(Request $request)
	{
		// dd($request->branch );
		$conditions = array(
			'approved'	=> 0,
			'rejected'	=> 0
		);
		if ($request->branch != 0) {
			
			$students = Student::join('branches', 'branches.id', '=', 'students.branch')
			->join('book_categories', 'book_categories.id', '=', 'students.category')
			->select('student_id', 'first_name', 'last_name', 'book_categories.category', 'roll_num', 'branches.branch', 'year')
				->where($conditions)
				->where('students.branch', $request->branch)
				->orderBy('student_id');
			$students = $students->get();
			return $students;
		
		}

		elseif ($request->category != 0) {
			// dd($request->category);
			$students = Student::join('branches', 'branches.id', '=', 'students.branch')
			->join('book_categories', 'book_categories.id', '=', 'students.category')
			->select('student_id', 'first_name', 'last_name', 'book_categories.category', 'roll_num', 'branches.branch', 'year')
				->where($conditions)
				->where('students.category', $request->category)
				->orderBy('student_id');
			$students = $students->get();
			return $students;
		
		}

		elseif ($request->year != 0) {
			// dd($request->year );
			$students = Student::join('branches', 'branches.id', '=', 'students.branch')
			->join('book_categories', 'book_categories.id', '=', 'students.category')
			->select('student_id', 'first_name', 'last_name', 'book_categories.category', 'roll_num', 'branches.branch', 'year')
				->where($conditions)
				->where('students.year', $request->year)
				->orderBy('student_id');
			$students = $students->get();

			return $students;
		
		}
		return "No Result Found";

		
	}


	public function create()
	{
		return view('students.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(StoreRequest $request)
	{
		$student = Student::create($request->validated());

		if ($student) {
			Auth::guard('student')->login($student);

			return redirect()->route('search-book');
		}

		return redirect()->back()->with(['system_error' => 'Lỗi hệ thống, vui lòng thử lại sau!']);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$student = Student::find($id);

		if($student == NULL){
			throw new Exception('Invalid Student ID');
		}

		$booksIssued = BookStudent::where('student_id', $student->id)
			->whereIn('status', [BookStudent::STATUS_BORROWED, BookStudent::STATUS_EXTEND])
			->sum('number');
		$student->books_issued = $booksIssued;

		return $student;
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id){
        $flag = (bool)$request->get('flag');

        $student = Student::findOrFail($id);

		if($flag){
			// if student is approved
	        $student->approved = 1;
		} else {
			// if student is rejected for some reason
			$student->rejected = 1;
		}

        $student->save();

        return "Student's approval/rejection status successfully changed.";
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		// dd($request->all());
		if ($request->category) {
			
			$student = StudentCategories::find($id);
			$student->delete();
			if (!$student) {
				return "Student Category Fail to Delete!.";
			}else {
				return redirect(route('settings'));
			}
		}elseif ($request->branch) {
			
			$branch = Branch::find($id);
			$branch->delete();
			if (!$branch) {
				return "School Branch Fail to Delete!.";
			}else {
				return redirect(route('settings'));
			}
		}
	}


	public function renderStudents(){
		$db_control = new HomeController;
		return view('panel.students')
			->with('branch_list', $db_control->branch_list)
			->with('student_categories_list', BookCategories::all());
	}

	public function renderApprovalStudents(){
		$db_control = new HomeController;
		return view('panel.approval')
			->with('branch_list', $db_control->branch_list)
			->with('book_categories', BookCategories::all());
	}

	public function postRegistration(Request $request){

		$validator = $request->validate([

				'first'			=> 'required|alpha',
				'last'			=> 'required|alpha',
				'rollnumber'	=> 'required|integer',
				'branch'		=> 'required|integer|min:1',
				'year'			=> 'required|integer',
				'email'			=> 'required|email',
				'category'		=> 'required|integer|min:1'
		]);

		if(!$validator) {
			return Redirect::route('student-registration')
				->withErrors($validator)
				->withInput();   // fills the field with the old inputs what were correct

		} else {
			$student = Student::create(array(
				'first_name'	=> $request->get('first'),
				'last_name'		=> $request->get('last'),
				'category'		=> $request->get('category'),
				'roll_num'		=> $request->get('rollnumber'),
				'branch'		=> $request->get('branch'),
				'year'			=> $request->get('year'),
				'email_id'		=> $request->get('email'),
			));

			if($student){
				return Redirect::route('student-registration')
					->with('global', 'Your request has been raised, you will be soon approved!');
			}
		}
	}

	public function Setting()
	{
		$branches = Branch::all();
		$student_category = StudentCategories::all();

		return view('panel.addsettings')
		->with('branches', $branches)
		->with('student_category', $student_category);
	}

	public function StoreSetting(Request $request)
	{
		// dd($request->all());
		if ($request->category) {
			
			$student = StudentCategories::create($request->all());

			if (!$student) {
				return "Student Category Fail to Save!.";
			}else {
				return "Student Category Save Succesfully!.";
				// return back();
			}
		}elseif ($request->branch) {
			
			$branch = Branch::create($request->all());

			if (!$branch) {
				return "School Branch Fail to Save!.";
			}else {
				return "School Branch Save Succesfully!.";
				// return back();
			}
		}

		
	}

	public function login(LoginRequest $request)
	{
		if (Auth::guard('student')->attempt($request->validated())) {
			return redirect()->route('search-book');
        }

		return redirect()->back()
			->withErrors(['password' => 'Mật khẩu không chính xác'])
			->withInput($request->except('password'));
	}

	public function logout()
	{
		Auth::guard('student')->logout();

		return redirect()->route('main');
	}
}
