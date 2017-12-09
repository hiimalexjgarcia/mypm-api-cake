<?php
use Migrations\AbstractSeed;

/**
 * Projects seed.
 */
class ProjectsSeed extends AbstractSeed
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
                'title' => 'Default Project',
                'description' => NULL,
                'created' => '2017-12-09 02:51:26',
                'modified' => '2017-12-09 02:51:26',
            ],
        ];

        $table = $this->table('projects');
        $table->insert($data)->save();
    }
}
