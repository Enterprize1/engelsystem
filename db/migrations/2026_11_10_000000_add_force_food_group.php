<?php

declare(strict_types=1);

namespace Engelsystem\Migrations;

use Engelsystem\Database\Migration\Migration;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Builder as SchemaBuilder;

class AddForceFoodGroup extends Migration
{
    protected int $forceFoodManagerId = 65;
    protected Connection $db;

    public function __construct(SchemaBuilder $schema)
    {
        parent::__construct($schema);
        $this->db = $this->schema->getConnection();
    }

    /**
     * Run the migration
     */
    public function up(): void
    {
        $this->db->table('groups')->insertOrIgnore([
            'id' => $this->forceFoodManagerId,
            'name' => 'Force Food Manager',
        ]);

        $privilegeIds = $this->db->table('privileges')
            ->whereIn('name', ['user.ff.edit'])
            ->pluck('id');

        $this->db->table('group_privileges')->insertOrIgnore(
            $privilegeIds->map(fn(int $id) => [
                'group_id' => $this->forceFoodManagerId,
                'privilege_id' => $id,
            ])->all()
        );
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        $this->db->table('groups')
            ->where('id', $this->forceFoodManagerId)
            ->delete();
    }
}
