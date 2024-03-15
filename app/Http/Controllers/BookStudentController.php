<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookStudent\StoreRequest;
use App\Models\Books;
use App\Models\BookStudent;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookStudents = BookStudent::where('student_id', auth('student')->user()->id)
            ->orderByDesc('id')
            ->with('book')
            ->paginate();

        return view('students.borrowed-list', [
            'bookStudents' => $bookStudents
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bookActive = Books::where('total_active', '>', 0)->get();

        return view('students.registration', [
            'books' => $bookActive
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $book = Books::find($data['book_id']);

        if (!$book) {
            return redirect()->back()->withErrors(['book_id' => 'Sách không tồn tại']);
        }

        $data['category_id'] = $book->category_id;
        $data['student_id'] = auth('student')->user()->id;

        BookStudent::create($data);

        return redirect()->route('book-student.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $bookStudent = BookStudent::find($id);

        // reject request
        if ($request->status == BookStudent::STATUS_REJECTED) {
            $bookStudent->update(['status' => $request->status]);

            return 'Cập nhật thành công';
        }

        $borrowedQuantity = $bookStudent->number;
        $book = Books::find($bookStudent->book_id);

        if ($borrowedQuantity > $book->total_active) {
            return 'Số lượng sách không đủ';
        }

        DB::beginTransaction();
        try {
            $bookStudent->update(['status' => $request->status]);
            $totalActive = $book->total_active - $borrowedQuantity;
            $book->update(['total_active' => $totalActive]);
            DB::commit();

            return 'Cập nhật thành công';
        } catch (Exception $e) {
            Log::error("BookStudentController@update:id-$bookStudent " . $e->getMessage());
            DB::rollBack();

            return 'Đã có lỗi xảy ra, vui lòng thử lại sau!';
        }        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function listPending(Request $request)
    {
        $query = BookStudent::where('status', BookStudent::STATUS_PENDING)
            ->with('book.bookCategory', 'student');

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        return $query->get();
    }
}
