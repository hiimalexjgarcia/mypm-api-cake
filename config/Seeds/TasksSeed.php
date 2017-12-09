<?php
use Migrations\AbstractSeed;

/**
 * Tasks seed.
 */
class TasksSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => '1',
                'title' => 'Do something.',
                'description' => 'Do something. Anything.',
                'complete' => '0',
                'project_id' => '1',
                'created' => '2017-12-09 06:11:54',
                'modified' => '2017-12-09 06:11:54',
            ],
        ];

        $table = $this->table('tasks');
        $table->insert($data)->save();
    }
}
