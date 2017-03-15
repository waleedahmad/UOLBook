<?php

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Course::truncate();

        $courses = [
            [
                'name'  => 'Introduction  to Computing',
                'code'  =>  'CS1010',
            ],
            [
                'name'  => 'Programming Fundamental',
                'code'  =>  'CS1012',
            ],
            [
                'name'  => 'Calculus and Analytical Geometry',
                'code'  =>  'MA1133',
            ],
            [
                'name'  => 'Functional English',
                'code'  =>  'ENG1012',
            ],
            [
                'name'  => 'Islamic and Pakistan Studies',
                'code'  =>  'SS2233',
            ],
            [
                'name'  => 'Digital Logic and Design',
                'code'  =>  'ECE1215',
            ],
            [
                'name'  => 'Communication Skills',
                'code'  =>  'ENG1025',
            ],
            [
                'name'  => 'Principle of Marketing',
                'code'  =>  'SS2332',
            ],
            [
                'name'  => 'Probability and  Statistics',
                'code'  =>  'MA3334',
            ],
            [
                'name'  => 'Discrete Structures',
                'code'  =>  'CS1114',
            ],
            [
                'name'  => 'Object Oriented Programming',
                'code'  =>  'CS2135',
            ],
            [
                'name'  => 'Multivariable Calculus',
                'code'  =>  'MA2114',
            ],
            [
                'name'  => 'Professional Practices',
                'code'  =>  'CS3327',
            ],
            [
                'name'  => 'Introduction to Database Systems',
                'code'  =>  'CS1117',
            ],
            [
                'name'  => 'Data Structures and Algorithms',
                'code'  =>  'CS2112',
            ],
            [
                'name'  => 'Computer Organization and Assembly Language',
                'code'  =>  'CS2164',
            ],
            [
                'name'  => 'Human Resource Management',
                'code'  =>  'SS4333',
            ],
            [
                'name'  => 'Computer Communication and Networks',
                'code'  =>  'ECE4353',
            ],
            [
                'name'  => 'Operating Systems',
                'code'  =>  'CS3534',
            ],
            [
                'name'  => 'Theory of Automata and Formal Languages',
                'code'  =>  'CS3435',
            ],
            [
                'name'  => 'Introduction to Software Engineering',
                'code'  =>  'CS4347',
            ],
            [
                'name'  => 'Organizational Behavior',
                'code'  =>  'CS4330',
            ],
            [
                'name'  => 'Differential Equations',
                'code'  =>  'CS2334',
            ],
            [
                'name'  => 'Web Engineering',
                'code'  =>  'CS428',
            ],
            [
                'name'  => 'Numerical Computing',
                'code'  =>  'CS3146',
            ],
            [
                'name'  => 'Java Software Development Paradigm',
                'code'  =>  'CS4110',
            ],
            [
                'name'  => 'Compiler Construction',
                'code'  =>  'CS4435',
            ],
            [
                'name'  => 'Computer Architecture',
                'code'  =>  'ECE3232',
            ],
            [
                'name'  => 'Human Computer Interaction',
                'code'  =>  'SE2224',
            ],
            [
                'name'  => 'Wireless Networks',
                'code'  =>  'CS4445',
            ],
            [
                'name'  => 'Artificial Intelligence',
                'code'  =>  'CS4710',
            ],
            [
                'name'  => 'Mobile Application Development',
                'code'  =>  'CS4555',
            ],
            [
                'name'  => 'Advanced Software Engineering',
                'code'  =>  'CS4356',
            ],
            [
                'name'  => 'Linear Algebra',
                'code'  =>  'MA2320',
            ],
            [
                'name'  => 'Software Project Management',
                'code'  =>  'CS5310',
            ],
            [
                'name'  => 'Software Testing and Implementation',
                'code'  =>  'CS4349',
            ],
        ];

        foreach($courses as $course){
            $cor = new Course();
            $cor->name = $course['name'];
            $cor->code = $course['code'];
            $cor->save();
        }
    }
}
