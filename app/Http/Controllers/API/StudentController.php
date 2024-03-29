<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\StoreStudentRequest;
use App\Http\Requests\API\UpdateStudentRequest;
use App\Http\Resources\StudentCollection;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return new StudentCollection(
            Student::with('user', 'class', 'tuition', 'user.role', 'class.major')
                ->search($request->search)->latest()->paginate($request->per_page)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\API\StoreStudentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentRequest $request)
    {
        $data = $request->validated();

        $user = User::create(array_merge(
            Arr::only($data, ['name', 'username']),
            ['role_id' => 3, 'password' => bcrypt($data['password'])]
        ));
        $student = $user->student()->create(Arr::except($data, ['name', 'username', 'password', 'password_confirmation']));

        return (new StudentResource($student->load('user', 'class', 'tuition', 'user.role')))
            ->additional(['message' => "Student has been Submitted successfully"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        return new StudentResource($student->load('user', 'class', 'tuition', 'user.role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\API\UpdateStudentRequest  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $data = $request->validated();

        $student->user()->update(Arr::only($data, ['name', 'username']));
        $student->update(Arr::except($data, ['name', 'username']));

        return (new StudentResource($student->load('user', 'class', 'tuition', 'user.role')))
            ->additional(['message' => "Student updated successfully"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->user()->delete();

        return response(['message' => 'Student deleted successfully']);
    }
}
