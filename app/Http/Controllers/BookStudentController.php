<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookStudent\StoreRequest;
use App\Models\Books;
use App\Models\BookStudent;
use Carbon\Carbon;
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
        $status = $request->status;

        // Processing extend requests
        if ($bookStudent->status == BookStudent::STATUS_EXTEND) {
            if ($status == BookStudent::STATUS_REJECTED) {
                $bookStudent->update(['status' => BookStudent::STATUS_BORROWED]);
            } else if ($status = BookStudent::STATUS_APPROVED) {
                $bookStudent->update(['status' => BookStudent::STATUS_BORROWED, 'expired_time' => Carbon::now()->addDays(15)]);
            }

            return 'Cập nhật thành công';
        }

        // reject request
        if ($status == BookStudent::STATUS_REJECTED) {
            $bookStudent->update(['status' => $status]);

            return 'Cập nhật thành công';
        }

        $borrowedQuantity = $bookStudent->number;
        $book = Books::find($bookStudent->book_id);

        if ($borrowedQuantity > $book->total_active) {
            return 'Số lượng sách không đủ';
        }

        DB::beginTransaction();
        try {
            $bookStudent->update(['status' => $status]);
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
        $query = BookStudent::whereIn('status', [BookStudent::STATUS_PENDING, BookStudent::STATUS_EXTEND])
            ->with('book.bookCategory', 'student');

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        return $query->get()->map(function ($bookStudent) {
            $bookStudent['type'] = BookStudent::STATUS_TEXT_ADMIN[$bookStudent['status']];

            return $bookStudent;
        });
    }

    public function extend(BookStudent $bookStudent)
    {
        if ($bookStudent->student_id != auth('student')->user()->id) {
            return redirect()->back()->with([
                'alert' => 'alert-error',
                'global' => 'Bạn không có quyền'
            ]);
        }

        $bookStudent->update(['status' => BookStudent::STATUS_EXTEND]);

        return redirect()->back()->with(['global' => 'Đang chờ phê duyệt']);
    }
}
