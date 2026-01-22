<?php
// database/seeders/SubjectSeeder.php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Department;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        // Get departments by code to assign correct IDs
        $languages = Department::where('code', 'LANG')->first();
        $mathematics = Department::where('code', 'MATH')->first();
        $sciences = Department::where('code', 'SCI')->first();
        $humanities = Department::where('code', 'HUM')->first();
        $businessIt = Department::where('code', 'BUSIT')->first();
        $creativeArts = Department::where('code', 'ARTS')->first();
        $technical = Department::where('code', 'TECH')->first();

        $subjects = [
            // Languages Department
            ['name' => 'English Language', 'department_id' => $languages->id, 'code' => 'ENG', 'description' => 'English Language and Literature'],
            ['name' => 'English Literature', 'department_id' => $languages->id, 'code' => 'ENG_LIT', 'description' => 'English Literature Studies'],
            ['name' => 'Kiswahili', 'department_id' => $languages->id, 'code' => 'KIS', 'description' => 'Kiswahili Language'],
            ['name' => 'French', 'department_id' => $languages->id, 'code' => 'FRE', 'description' => 'French Language'],
            ['name' => 'German', 'department_id' => $languages->id, 'code' => 'GER', 'description' => 'German Language'],
            ['name' => 'Arabic', 'department_id' => $languages->id, 'code' => 'ARAB', 'description' => 'Arabic Language'],
            ['name' => 'Chinese', 'department_id' => $languages->id, 'code' => 'CHI', 'description' => 'Chinese Mandarin'],
            
            // Mathematics Department
            ['name' => 'Mathematics', 'department_id' => $mathematics->id, 'code' => 'MATH', 'description' => 'Core Mathematics'],
            ['name' => 'Additional Mathematics', 'department_id' => $mathematics->id, 'code' => 'ADD_MATH', 'description' => 'Advanced Mathematics'],
            ['name' => 'Statistics', 'department_id' => $mathematics->id, 'code' => 'STAT', 'description' => 'Statistics and Probability'],
            ['name' => 'Pure Mathematics', 'department_id' => $mathematics->id, 'code' => 'PURE_MATH', 'description' => 'Pure Mathematics'],
            ['name' => 'Applied Mathematics', 'department_id' => $mathematics->id, 'code' => 'APP_MATH', 'description' => 'Applied Mathematics'],
            
            // Sciences Department
            ['name' => 'Physics', 'department_id' => $sciences->id, 'code' => 'PHY', 'description' => 'Physics'],
            ['name' => 'Chemistry', 'department_id' => $sciences->id, 'code' => 'CHEM', 'description' => 'Chemistry'],
            ['name' => 'Biology', 'department_id' => $sciences->id, 'code' => 'BIO', 'description' => 'Biology'],
            ['name' => 'Human Biology', 'department_id' => $sciences->id, 'code' => 'HUM_BIO', 'description' => 'Human Biology'],
            ['name' => 'General Science', 'department_id' => $sciences->id, 'code' => 'GEN_SCI', 'description' => 'General Science'],
            ['name' => 'Environmental Science', 'department_id' => $sciences->id, 'code' => 'ENV_SCI', 'description' => 'Environmental Science'],
            ['name' => 'Computer Science', 'department_id' => $sciences->id, 'code' => 'COMP_SCI', 'description' => 'Computer Science'],
            
            // Humanities Department
            ['name' => 'History', 'department_id' => $humanities->id, 'code' => 'HIST', 'description' => 'History'],
            ['name' => 'Geography', 'department_id' => $humanities->id, 'code' => 'GEO', 'description' => 'Geography'],
            ['name' => 'Civics', 'department_id' => $humanities->id, 'code' => 'CIV', 'description' => 'Civics'],
            ['name' => 'Social Studies', 'department_id' => $humanities->id, 'code' => 'SOC_STUD', 'description' => 'Social Studies'],
            ['name' => 'Religious Education', 'department_id' => $humanities->id, 'code' => 'RE', 'description' => 'Religious Education'],
            ['name' => 'Philosophy', 'department_id' => $humanities->id, 'code' => 'PHIL', 'description' => 'Philosophy'],
            ['name' => 'Psychology', 'department_id' => $humanities->id, 'code' => 'PSYCH', 'description' => 'Psychology'],
            ['name' => 'Sociology', 'department_id' => $humanities->id, 'code' => 'SOCIO', 'description' => 'Sociology'],
            
            // Business & IT Department
            ['name' => 'Business Studies', 'department_id' => $businessIt->id, 'code' => 'BUS', 'description' => 'Business Studies'],
            ['name' => 'Commerce', 'department_id' => $businessIt->id, 'code' => 'COMM', 'description' => 'Commerce'],
            ['name' => 'Accounting', 'department_id' => $businessIt->id, 'code' => 'ACC', 'description' => 'Accounting'],
            ['name' => 'Economics', 'department_id' => $businessIt->id, 'code' => 'ECON', 'description' => 'Economics'],
            ['name' => 'Entrepreneurship', 'department_id' => $businessIt->id, 'code' => 'ENTRE', 'description' => 'Entrepreneurship'],
            ['name' => 'Computer Studies', 'department_id' => $businessIt->id, 'code' => 'COMP', 'description' => 'Computer Studies'],
            ['name' => 'Information Technology', 'department_id' => $businessIt->id, 'code' => 'IT', 'description' => 'Information Technology'],
            ['name' => 'Book Keeping', 'department_id' => $businessIt->id, 'code' => 'BKEEP', 'description' => 'Book Keeping'],
            
            // Creative Arts Department
            ['name' => 'Art and Design', 'department_id' => $creativeArts->id, 'code' => 'ART', 'description' => 'Art and Design'],
            ['name' => 'Music', 'department_id' => $creativeArts->id, 'code' => 'MUSIC', 'description' => 'Music'],
            ['name' => 'Drama', 'department_id' => $creativeArts->id, 'code' => 'DRAMA', 'description' => 'Drama and Theatre Arts'],
            ['name' => 'Physical Education', 'department_id' => $creativeArts->id, 'code' => 'PE', 'description' => 'Physical Education'],
            ['name' => 'Dance', 'department_id' => $creativeArts->id, 'code' => 'DANCE', 'description' => 'Dance'],
            ['name' => 'Film Studies', 'department_id' => $creativeArts->id, 'code' => 'FILM', 'description' => 'Film Studies'],
            
            // Technical & Vocational Department
            ['name' => 'Home Science', 'department_id' => $technical->id, 'code' => 'HOME_SCI', 'description' => 'Home Science'],
            ['name' => 'Agriculture', 'department_id' => $technical->id, 'code' => 'AGRIC', 'description' => 'Agriculture'],
            ['name' => 'Technical Drawing', 'department_id' => $technical->id, 'code' => 'TECH_DRAW', 'description' => 'Technical Drawing'],
            ['name' => 'Woodwork', 'department_id' => $technical->id, 'code' => 'WOOD', 'description' => 'Woodwork'],
            ['name' => 'Metalwork', 'department_id' => $technical->id, 'code' => 'METAL', 'description' => 'Metalwork'],
            ['name' => 'Electrical Technology', 'department_id' => $technical->id, 'code' => 'ELEC_TECH', 'description' => 'Electrical Technology'],
            ['name' => 'Mechanical Technology', 'department_id' => $technical->id, 'code' => 'MECH_TECH', 'description' => 'Mechanical Technology'],
            ['name' => 'Automotive Technology', 'department_id' => $technical->id, 'code' => 'AUTO_TECH', 'description' => 'Automotive Technology'],
            ['name' => 'Building Construction', 'department_id' => $technical->id, 'code' => 'BUILD_CON', 'description' => 'Building Construction'],
            ['name' => 'Food and Nutrition', 'department_id' => $technical->id, 'code' => 'FOOD_NUT', 'description' => 'Food and Nutrition'],
            ['name' => 'Fashion and Fabrics', 'department_id' => $technical->id, 'code' => 'FASHION', 'description' => 'Fashion and Fabrics'],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(
                ['name' => $subject['name']],
                $subject
            );
        }
    }
}