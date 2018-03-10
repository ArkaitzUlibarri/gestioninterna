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

        //region CONFIG
        $this->call(AbsencesTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(ContractTypesTableSeeder::class);
        $this->call(BankHolidaysCodesTableSeeder::class);
        $this->call(BankHolidaysTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        //$this->call(PlanesTableSeeder::class);//Cursos
        //endregion

        //$this->call(CategoryUserTableSeeder::class);
                //$this->call(CoursesTableSeeder::class);
                //$this->call(CourseGroupsTableSeeder::class);
                //$this->call(CalendarCourseTableSeeder::class);    
        //$this->call(ContractsTableSeeder::class);
        //$this->call(ReductionsTableSeeder::class);
        //$this->call(TeleworkingTableSeeder::class);
                        //$this->call(UserHolidaysTableSeeder::class);
                        //$this->call(CalendarHolidaysTableSeeder::class);
                //$this->call(AttendeesTableSeeder::class);
        //$this->call(ProjectsTableSeeder::class);  
        //$this->call(GroupProjectTableSeeder::class); 
        //$this->call(GroupUserTableSeeder::class);
        //$this->call(WorkingReportTableSeeder::class); 
    }
}
