<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(ConfigTableSeeder::class);
        $this->call(CategoryUserTableSeeder::class);
            //$this->call(CoursesTableSeeder::class);
            //$this->call(CourseGroupsTableSeeder::class);
            //$this->call(CalendarCourseTableSeeder::class);    
        $this->call(ContractsTableSeeder::class);
        $this->call(ReductionsTableSeeder::class);
        $this->call(TeleworkingTableSeeder::class);
                    //$this->call(UserHolidaysTableSeeder::class);
                    //$this->call(CalendarHolidaysTableSeeder::class);
            //$this->call(AttendeesTableSeeder::class);
        $this->call(ProjectsTableSeeder::class);  
        $this->call(GroupProjectTableSeeder::class); 
        $this->call(WorkingReportTableSeeder::class); 
    }
}
